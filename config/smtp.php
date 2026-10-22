<?php
/**
 * Moneyguru Financial Services - SMTP Mailer Class
 * Zero external dependencies: Uses native PHP stream sockets (stream_socket_client)
 * Compatible with Gmail, Outlook, Brevo, SendGrid, Amazon SES, and custom cPanel webmail.
 */

require_once __DIR__ . '/db.php';

class SimpleSMTPMailer {
    private $host;
    private $port;
    private $encryption;
    private $username;
    private $password;
    private $fromEmail;
    private $fromName;
    private $timeout = 15;
    private $socket = null;
    private $log = [];

    public function __construct(array $config = []) {
        $settings = $this->loadSettings();

        $this->host       = $config['host']       ?? ($settings['smtp_host'] ?? 'smtp.gmail.com');
        $this->port       = intval($config['port'] ?? ($settings['smtp_port'] ?? 587));
        $this->encryption = strtolower($config['encryption'] ?? ($settings['smtp_encryption'] ?? 'tls'));
        $this->username   = $config['username']   ?? ($settings['smtp_user'] ?? '');
        $this->password   = $config['password']   ?? ($settings['smtp_pass'] ?? '');
        $this->fromEmail  = $config['from_email'] ?? ($settings['smtp_from_email'] ?? ($settings['contact_email'] ?? 'noreply@moneyguru.co.in'));
        $this->fromName   = $config['from_name']  ?? ($settings['smtp_from_name'] ?? ($settings['site_name'] ?? 'Moneyguru Financial Services'));
    }

    private function loadSettings(): array {
        $pdo = getDBConnection();
        $settings = [];
        if ($pdo) {
            try {
                $stmt = $pdo->query("SELECT `setting_key`, `setting_value` FROM `site_settings` WHERE `setting_key` LIKE 'smtp_%' OR `setting_key` IN ('contact_email', 'site_name')");
                foreach ($stmt->fetchAll() as $row) {
                    $settings[$row['setting_key']] = $row['setting_value'];
                }
            } catch (Exception $e) {
                // Ignore DB error, use defaults
            }
        }
        return $settings;
    }

    private function recordLog(string $msg) {
        $this->log[] = date('H:i:s') . ' ' . $msg;
    }

    public function getLog(): array {
        return $this->log;
    }

    public function getLogString(): string {
        return implode("\n", $this->log);
    }

    /**
     * Send an email via SMTP
     */
    public function send(string $toEmail, string $subject, string $htmlBody, string $replyTo = '', string $toName = ''): array {
        $this->log = [];
        $toEmail = trim($toEmail);
        if (!filter_var($toEmail, FILTER_VALIDATE_EMAIL)) {
            return ['success' => false, 'error' => "Invalid recipient email address: $toEmail", 'log' => ''];
        }

        try {
            $this->connect();
            $this->authenticate();
            $this->deliver($toEmail, $subject, $htmlBody, $replyTo, $toName);
            $this->disconnect();

            return [
                'success' => true,
                'message' => 'Email sent successfully via SMTP.',
                'log'     => $this->getLogString()
            ];
        } catch (Exception $e) {
            $this->disconnect();
            $this->recordLog("ERROR: " . $e->getMessage());
            return [
                'success' => false,
                'error'   => $e->getMessage(),
                'log'     => $this->getLogString()
            ];
        }
    }

    private function connect() {
        $remote = ($this->encryption === 'ssl' ? 'ssl://' : 'tcp://') . $this->host . ':' . $this->port;
        $this->recordLog("Connecting to socket $remote (Timeout {$this->timeout}s)...");

        $context = stream_context_create([
            'ssl' => [
                'verify_peer'       => false,
                'verify_peer_name'  => false,
                'allow_self_signed' => true,
            ]
        ]);

        $errno = 0;
        $errstr = '';
        $this->socket = @stream_socket_client($remote, $errno, $errstr, $this->timeout, STREAM_CLIENT_CONNECT, $context);

        if (!$this->socket) {
            throw new Exception("Socket connection failed to {$this->host}:{$this->port} ($errno: $errstr)");
        }

        stream_set_timeout($this->socket, $this->timeout);
        $greeting = $this->readResponse();
        $this->recordLog("Server Greeting: $greeting");

        if (substr($greeting, 0, 3) !== '220') {
            throw new Exception("Unexpected server response on connect: $greeting");
        }

        // Send EHLO
        $clientHost = gethostname() ?: 'localhost';
        $ehlo = $this->sendCommand("EHLO $clientHost");

        // If STARTTLS requested and supported
        if ($this->encryption === 'tls') {
            $this->recordLog("Starting TLS negotiation...");
            $tlsRes = $this->sendCommand("STARTTLS");
            if (substr($tlsRes, 0, 3) !== '220') {
                throw new Exception("STARTTLS failed: $tlsRes");
            }

            $cryptoMethod = STREAM_CRYPTO_METHOD_TLS_CLIENT;
            if (defined('STREAM_CRYPTO_METHOD_TLSv1_2_CLIENT')) {
                $cryptoMethod |= STREAM_CRYPTO_METHOD_TLSv1_2_CLIENT;
            }
            if (defined('STREAM_CRYPTO_METHOD_TLSv1_3_CLIENT')) {
                $cryptoMethod |= STREAM_CRYPTO_METHOD_TLSv1_3_CLIENT;
            }

            $secure = @stream_socket_enable_crypto($this->socket, true, $cryptoMethod);
            if (!$secure) {
                throw new Exception("Failed to establish secure TLS encryption with {$this->host}.");
            }
            $this->recordLog("TLS encryption established successfully.");

            // Re-send EHLO after TLS
            $this->sendCommand("EHLO $clientHost");
        }
    }

    private function authenticate() {
        if (empty($this->username) && empty($this->password)) {
            $this->recordLog("No authentication credentials provided. Skipping AUTH.");
            return;
        }

        $this->recordLog("Attempting AUTH LOGIN for user: {$this->username}...");
        $authRes = $this->sendCommand("AUTH LOGIN");
        if (substr($authRes, 0, 3) !== '334') {
            throw new Exception("AUTH LOGIN rejected: $authRes");
        }

        $userRes = $this->sendCommand(base64_encode($this->username));
        if (substr($userRes, 0, 3) !== '334') {
            throw new Exception("Username rejected by SMTP server: $userRes");
        }

        $passRes = $this->sendCommand(base64_encode($this->password));
        if (substr($passRes, 0, 3) !== '235') {
            throw new Exception("Password rejected by SMTP server: $passRes");
        }

        $this->recordLog("SMTP Authentication succeeded (235).");
    }

    private function deliver(string $toEmail, string $subject, string $htmlBody, string $replyTo = '', string $toName = '') {
        $fromRes = $this->sendCommand("MAIL FROM:<" . $this->fromEmail . ">");
        if (substr($fromRes, 0, 3) !== '250') {
            throw new Exception("MAIL FROM failed: $fromRes");
        }

        $rcptRes = $this->sendCommand("RCPT TO:<$toEmail>");
        if (substr($rcptRes, 0, 3) !== '250' && substr($rcptRes, 0, 3) !== '251') {
            throw new Exception("RCPT TO failed for <$toEmail>: $rcptRes");
        }

        $dataRes = $this->sendCommand("DATA");
        if (substr($dataRes, 0, 3) !== '354') {
            throw new Exception("DATA command rejected: $dataRes");
        }

        // Build RFC 822 Email Headers and Body
        $encodedSubject = '=?UTF-8?B?' . base64_encode($subject) . '?=';
        $encodedFromName = '=?UTF-8?B?' . base64_encode($this->fromName) . '?=';
        $toHeader = !empty($toName) ? '=?UTF-8?B?' . base64_encode($toName) . "?= <$toEmail>" : "<$toEmail>";
        $replyToHeader = !empty($replyTo) ? $replyTo : $this->fromEmail;

        $msgId = '<' . md5(uniqid(microtime(true), true)) . '@' . ($this->host ?: 'moneyguru.co.in') . '>';

        $headers = [];
        $headers[] = "Date: " . date('r');
        $headers[] = "From: {$encodedFromName} <{$this->fromEmail}>";
        $headers[] = "To: {$toHeader}";
        $headers[] = "Reply-To: <{$replyToHeader}>";
        $headers[] = "Subject: {$encodedSubject}";
        $headers[] = "Message-ID: {$msgId}";
        $headers[] = "X-Mailer: Moneyguru-SMTP-Mailer/1.0";
        $headers[] = "MIME-Version: 1.0";
        $headers[] = "Content-Type: text/html; charset=UTF-8";
        $headers[] = "Content-Transfer-Encoding: base64";

        $fullPayload = implode("\r\n", $headers) . "\r\n\r\n" . chunk_split(base64_encode($htmlBody)) . "\r\n.";

        $this->recordLog("Sending email body (" . strlen($htmlBody) . " bytes HTML)...");
        fwrite($this->socket, $fullPayload . "\r\n");

        $finishRes = $this->readResponse();
        $this->recordLog("DATA response: $finishRes");
        if (substr($finishRes, 0, 3) !== '250') {
            throw new Exception("Failed to send email body: $finishRes");
        }
    }

    private function disconnect() {
        if ($this->socket) {
            try {
                @fwrite($this->socket, "QUIT\r\n");
                @fclose($this->socket);
            } catch (Exception $e) {
                // ignore
            }
            $this->socket = null;
        }
    }

    private function sendCommand(string $cmd): string {
        if (!$this->socket) {
            throw new Exception("Cannot send command, socket is closed.");
        }
        // Mask passwords in log
        if (strpos($cmd, 'AUTH') === false && !base64_decode($cmd, true) && strlen($cmd) < 80) {
            $this->recordLog(">>> $cmd");
        } else {
            $this->recordLog(">>> [COMMAND SENT]");
        }

        fwrite($this->socket, $cmd . "\r\n");
        $res = $this->readResponse();
        $this->recordLog("<<< $res");
        return $res;
    }

    private function readResponse(): string {
        $response = '';
        while ($line = fgets($this->socket, 515)) {
            $response .= $line;
            // Response format: code<space>text signifies final line of multi-line response
            if (isset($line[3]) && $line[3] === ' ') {
                break;
            }
        }
        return trim($response);
    }
}

/**
 * Global Helper to send an application email
 */
function sendAppEmail(string $toEmail, string $subject, string $htmlBody, string $replyTo = '', string $toName = ''): array {
    $pdo = getDBConnection();
    $smtpEnabled = '1';

    if ($pdo) {
        try {
            $stmt = $pdo->prepare("SELECT `setting_value` FROM `site_settings` WHERE `setting_key` = 'smtp_enabled' LIMIT 1");
            $stmt->execute();
            $val = $stmt->fetchColumn();
            if ($val !== false) {
                $smtpEnabled = $val;
            }
        } catch (Exception $e) {
            // fallback
        }
    }

    // If SMTP explicitly disabled, try native mail() fallback
    if ($smtpEnabled === '0') {
        $headers  = "MIME-Version: 1.0\r\n";
        $headers .= "Content-Type: text/html; charset=UTF-8\r\n";
        $headers .= "From: Moneyguru Financial Services <contact@moneyguru.co.in>\r\n";
        if (!empty($replyTo)) {
            $headers .= "Reply-To: <$replyTo>\r\n";
        }
        $ok = @mail($toEmail, $subject, $htmlBody, $headers);
        return [
            'success' => $ok,
            'message' => $ok ? 'Sent via native PHP mail()' : 'Failed via PHP mail()',
            'log'     => 'SMTP is disabled; used PHP mail().'
        ];
    }

    $mailer = new SimpleSMTPMailer();
    return $mailer->send($toEmail, $subject, $htmlBody, $replyTo, $toName);
}
