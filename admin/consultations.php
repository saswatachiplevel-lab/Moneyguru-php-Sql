<?php
/**
 * Moneyguru Admin - Consultations Management
 */
require_once __DIR__ . '/auth_check.php';
require_once __DIR__ . '/../config/db.php';

$pdo = getDBConnection();

// Export CSV handler
if (isset($_GET['export']) && $_GET['export'] === 'csv') {
    header('Content-Type: text/csv; charset=utf-8');
    header('Content-Disposition: attachment; filename=moneyguru_consultations_' . date('Y-m-d') . '.csv');
    
    $output = fopen('php://output', 'w');
    fputcsv($output, ['ID', 'Reference', 'Client Name', 'Email', 'Phone', 'Service', 'Preferred Contact', 'Preferred Date', 'Status', 'Message', 'Created At']);
    
    $rows = $pdo->query("SELECT * FROM `consultations` ORDER BY `id` DESC")->fetchAll();
    foreach ($rows as $r) {
        fputcsv($output, [
            $r['id'],
            'MG-' . str_pad($r['id'], 5, '0', STR_PAD_LEFT),
            $r['name'],
            $r['email'],
            $r['phone'],
            $r['service'],
            $r['preferred_contact'],
            $r['preferred_date'],
            $r['status'],
            $r['message'],
            $r['created_at']
        ]);
    }
    fclose($output);
    exit;
}

// Handle Status or Note update
$alert = '';
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    if (!verifyCsrfToken($_POST['csrf_token'] ?? '')) {
        $alert = "Security token validation failed. Please refresh the page.";
    } else {
        if (isset($_POST['action']) && $_POST['action'] === 'update_row') {
            $id = intval($_POST['id']);
            $status = trim($_POST['status']);
            $notes = trim($_POST['notes'] ?? '');
            $stmt = $pdo->prepare("UPDATE `consultations` SET `status` = :s, `notes` = :n WHERE `id` = :id");
            $stmt->execute([':s' => $status, ':n' => $notes, ':id' => $id]);
            $alert = "Updated Consultation #MG-" . str_pad($id, 5, '0', STR_PAD_LEFT);
        } elseif (isset($_POST['action']) && $_POST['action'] === 'delete_row') {
            $id = intval($_POST['id']);
            $stmt = $pdo->prepare("DELETE FROM `consultations` WHERE `id` = :id");
            $stmt->execute([':id' => $id]);
            $alert = "Deleted Consultation record #MG-" . str_pad($id, 5, '0', STR_PAD_LEFT);
        }
    }
}

// Filtering & Search
$statusFilter = trim($_GET['status'] ?? '');
$searchQuery = trim($_GET['q'] ?? '');

$sql = "SELECT * FROM `consultations` WHERE 1=1";
$params = [];

if (!empty($statusFilter)) {
    $sql .= " AND `status` = :status";
    $params[':status'] = $statusFilter;
}

if (!empty($searchQuery)) {
    $sql .= " AND (`name` LIKE :q OR `email` LIKE :q OR `phone` LIKE :q OR `service` LIKE :q)";
    $params[':q'] = "%$searchQuery%";
}

$sql .= " ORDER BY `id` DESC";
$stmt = $pdo->prepare($sql);
$stmt->execute($params);
$consultations = $stmt->fetchAll();
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>All Consultations | Moneyguru Admin</title>
    <link rel="icon" type="image/svg+xml" href="../assets/img/logo.svg">
    <link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@400;500;600;700;800&display=swap" rel="stylesheet">
    <script src="https://cdn.tailwindcss.com"></script>
    <script src="https://unpkg.com/lucide@latest"></script>
    <style>
        body { font-family: 'Plus Jakarta Sans', sans-serif; background-color: #F7F8F5; }
    </style>
</head>
<body class="min-h-screen text-[#092532] flex flex-col">
    
    <!-- Top Admin Bar -->
    <header class="bg-[#071D29] text-white px-6 py-3.5 border-b border-white/10 sticky top-0 z-40">
        <div class="max-w-7xl mx-auto flex items-center justify-between">
            <div class="flex items-center gap-3">
                <img src="../assets/img/logo.svg" alt="Moneyguru" class="w-9 h-9 rounded-full object-contain">
                <div>
                    <h1 class="font-extrabold text-base tracking-tight text-white flex items-center gap-1.5">
                        <span>Moneyguru Admin</span>
                        <span class="text-[10px] font-bold bg-[#C9F24A] text-[#071D29] px-2 py-0.5 rounded-full">Consultations</span>
                    </h1>
                    <p class="text-[10px] text-white/60">Saswata Roy &bull; ARN- 136048</p>
                </div>
            </div>

            <nav class="hidden md:flex items-center gap-1 text-xs font-semibold">
                <a href="index.php" class="text-white/70 hover:text-white px-3.5 py-1.5 rounded-lg hover:bg-white/5 transition-colors">Dashboard</a>
                <a href="consultations.php" class="bg-white/10 text-[#C9F24A] px-3.5 py-1.5 rounded-lg">All Consultations</a>
                <a href="partners.php" class="text-white/70 hover:text-white px-3.5 py-1.5 rounded-lg hover:bg-white/5 transition-colors">Partnerships</a>
                <a href="profile.php" class="text-white/70 hover:text-white px-3.5 py-1.5 rounded-lg hover:bg-white/5 transition-colors">Profile</a>
                <a href="settings.php" class="text-white/70 hover:text-white px-3.5 py-1.5 rounded-lg hover:bg-white/5 transition-colors">Settings</a>
            </nav>

            <div class="flex items-center gap-3">
                <a href="logout.php" class="flex items-center gap-1.5 text-xs text-rose-300 hover:text-rose-200 bg-rose-500/10 px-3 py-1.5 rounded-lg border border-rose-500/20">
                    <i data-lucide="log-out" class="w-3.5 h-3.5"></i>
                    <span>Logout</span>
                </a>
            </div>
        </div>
    </header>

    <main class="flex-1 max-w-7xl mx-auto w-full p-6 sm:p-8 space-y-6">
        
        <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-4">
            <div>
                <h2 class="text-2xl font-black text-[#092532]">Manage Consultations</h2>
                <p class="text-xs text-[#607078] mt-0.5">Total <?= count($consultations) ?> records found.</p>
            </div>

            <div class="flex items-center gap-3">
                <a href="consultations.php?export=csv" class="flex items-center gap-1.5 text-xs font-bold text-[#071D29] bg-[#C9F24A] hover:bg-[#D9F77A] px-4 py-2 rounded-xl transition-all shadow-sm">
                    <i data-lucide="download" class="w-4 h-4"></i>
                    <span>Export CSV</span>
                </a>
            </div>
        </div>

        <?php if (!empty($alert)): ?>
        <div class="p-3.5 rounded-xl bg-emerald-50 border border-emerald-200 text-xs text-emerald-800 flex items-center gap-2">
            <i data-lucide="check-circle" class="w-4 h-4 text-emerald-600"></i>
            <span><?= htmlspecialchars($alert) ?></span>
        </div>
        <?php endif; ?>

        <!-- Search & Filter Bar -->
        <div class="bg-white p-4 rounded-2xl border border-gray-200 shadow-sm flex flex-col sm:flex-row gap-3">
            <form method="GET" action="consultations.php" class="flex-1 flex gap-2">
                <input type="text" name="q" value="<?= htmlspecialchars($searchQuery) ?>" placeholder="Search by client name, email, phone, or service..." class="flex-1 px-4 py-2 rounded-xl bg-gray-50 border border-gray-200 text-xs sm:text-sm focus:outline-none focus:ring-2 focus:ring-[#071D29]">
                <button type="submit" class="bg-[#071D29] text-[#C9F24A] px-5 py-2 rounded-xl font-bold text-xs hover:bg-[#0B2733] transition-colors">
                    Search
                </button>
            </form>

            <div class="flex items-center gap-2 overflow-x-auto text-xs">
                <a href="consultations.php" class="px-3 py-1.5 rounded-lg border <?= empty($statusFilter) ? 'bg-[#071D29] text-white border-[#071D29]' : 'bg-gray-50 text-gray-700' ?>">All</a>
                <a href="consultations.php?status=Pending" class="px-3 py-1.5 rounded-lg border <?= $statusFilter === 'Pending' ? 'bg-amber-600 text-white' : 'bg-amber-50 text-amber-800 border-amber-200' ?>">Pending</a>
                <a href="consultations.php?status=Scheduled" class="px-3 py-1.5 rounded-lg border <?= $statusFilter === 'Scheduled' ? 'bg-blue-600 text-white' : 'bg-blue-50 text-blue-800 border-blue-200' ?>">Scheduled</a>
                <a href="consultations.php?status=Completed" class="px-3 py-1.5 rounded-lg border <?= $statusFilter === 'Completed' ? 'bg-emerald-600 text-white' : 'bg-emerald-50 text-emerald-800 border-emerald-200' ?>">Completed</a>
            </div>
        </div>

        <!-- Table -->
        <div class="bg-white rounded-3xl border border-gray-200/90 shadow-sm overflow-hidden">
            <div class="overflow-x-auto">
                <table class="w-full text-left text-xs">
                    <thead class="bg-gray-50 border-b border-gray-200 text-[#607078] uppercase text-[10px] tracking-wider font-extrabold">
                        <tr>
                            <th class="p-4 pl-6">Ref &amp; Date</th>
                            <th class="p-4">Client</th>
                            <th class="p-4">Service</th>
                            <th class="p-4">Contact Mode</th>
                            <th class="p-4">Message</th>
                            <th class="p-4">Status &amp; Notes</th>
                            <th class="p-4 pr-6 text-right">Actions</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-gray-100">
                        <?php if (empty($consultations)): ?>
                        <tr><td colspan="7" class="p-8 text-center text-gray-500">No records found matching criteria.</td></tr>
                        <?php else: ?>
                        <?php foreach ($consultations as $c): ?>
                        <tr class="hover:bg-gray-50/70">
                            <td class="p-4 pl-6 font-mono text-gray-500">
                                <div class="font-bold text-[#071D29]">#MG-<?= str_pad($c['id'], 5, '0', STR_PAD_LEFT) ?></div>
                                <div class="text-[10px] text-gray-400 mt-0.5"><?= date('d M Y, H:i', strtotime($c['created_at'])) ?></div>
                            </td>
                            <td class="p-4">
                                <div class="font-bold text-sm text-[#092532]"><?= htmlspecialchars($c['name']) ?></div>
                                <div class="text-[11px] text-gray-500">
                                    <a href="tel:<?= htmlspecialchars($c['phone']) ?>" class="hover:underline font-mono"><?= htmlspecialchars($c['phone']) ?></a><br>
                                    <a href="mailto:<?= htmlspecialchars($c['email']) ?>" class="hover:underline"><?= htmlspecialchars($c['email']) ?></a>
                                </div>
                            </td>
                            <td class="p-4 font-semibold text-[#071D29]"><?= htmlspecialchars($c['service']) ?></td>
                            <td class="p-4">
                                <?php if ($c['preferred_contact'] === 'WhatsApp'): ?>
                                <a href="https://wa.me/<?= preg_replace('/[^0-9]/', '', $c['phone']) ?>?text=Hi%20<?= urlencode($c['name']) ?>,%20this%20is%20Saswata%20Roy%20from%20Moneyguru%20Financial%20Services." target="_blank" class="inline-flex items-center gap-1 text-emerald-700 bg-emerald-50 px-2 py-0.5 rounded text-[11px] font-semibold border border-emerald-200">
                                    <i data-lucide="message-circle" class="w-3 h-3"></i>
                                    <span>WhatsApp</span>
                                </a>
                                <?php else: ?>
                                <span class="text-gray-700 font-medium"><?= htmlspecialchars($c['preferred_contact']) ?></span>
                                <?php endif; ?>
                            </td>
                            <td class="p-4 max-w-xs text-[#607078] leading-relaxed">
                                <?= nl2br(htmlspecialchars($c['message'] ?: 'N/A')) ?>
                            </td>
                            <td class="p-4">
                                <form method="POST" action="consultations.php" class="space-y-1.5">
                                    <?= csrfInput() ?>
                                    <input type="hidden" name="action" value="update_row">
                                    <input type="hidden" name="id" value="<?= $c['id'] ?>">
                                    <select name="status" class="w-full text-xs font-semibold px-2 py-1 rounded border border-gray-200 bg-white">
                                        <option value="Pending" <?= $c['status'] === 'Pending' ? 'selected' : '' ?>>Pending</option>
                                        <option value="Contacted" <?= $c['status'] === 'Contacted' ? 'selected' : '' ?>>Contacted</option>
                                        <option value="Scheduled" <?= $c['status'] === 'Scheduled' ? 'selected' : '' ?>>Scheduled</option>
                                        <option value="Completed" <?= $c['status'] === 'Completed' ? 'selected' : '' ?>>Completed</option>
                                        <option value="Cancelled" <?= $c['status'] === 'Cancelled' ? 'selected' : '' ?>>Cancelled</option>
                                    </select>
                                    <input type="text" name="notes" value="<?= htmlspecialchars($c['notes'] ?? '') ?>" placeholder="Add advisor note..." class="w-full text-[11px] px-2 py-1 rounded border border-gray-200 bg-gray-50">
                                    <button type="submit" class="text-[10px] bg-[#071D29] text-[#C9F24A] px-2 py-0.5 rounded font-bold">Save</button>
                                </form>
                            </td>
                            <td class="p-4 pr-6 text-right">
                                <form method="POST" action="consultations.php" onsubmit="return confirm('Delete this consultation record?');">
                                    <?= csrfInput() ?>
                                    <input type="hidden" name="action" value="delete_row">
                                    <input type="hidden" name="id" value="<?= $c['id'] ?>">
                                    <button type="submit" class="text-rose-600 hover:text-rose-800 p-1" title="Delete Record">
                                        <i data-lucide="trash-2" class="w-4 h-4"></i>
                                    </button>
                                </form>
                            </td>
                        </tr>
                        <?php endforeach; ?>
                        <?php endif; ?>
                    </tbody>
                </table>
            </div>
        </div>

    </main>

    <script>
        lucide.createIcons();
    </script>
</body>
</html>
