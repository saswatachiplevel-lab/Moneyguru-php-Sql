<?php
/**
 * Moneyguru Admin Dashboard
 */
require_once __DIR__ . '/auth_check.php';
require_once __DIR__ . '/../config/db.php';
require_once __DIR__ . '/../includes/helpers.php';

$pdo = getDBConnection();

// Handle quick status update
$statusMsg = '';
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    if (verifyCsrfToken($_POST['csrf_token'] ?? '') && isset($_POST['action']) && $_POST['action'] === 'update_consultation_status') {
        $cid = intval($_POST['consultation_id'] ?? 0);
        $newStatus = trim($_POST['status'] ?? '');
        $validStatuses = ['Pending', 'Contacted', 'Scheduled', 'Completed', 'Cancelled'];
        
        if ($cid > 0 && in_array($newStatus, $validStatuses)) {
            $stmt = $pdo->prepare("UPDATE `consultations` SET `status` = :s WHERE `id` = :id");
            $stmt->execute([':s' => $newStatus, ':id' => $cid]);
            $statusMsg = "Consultation #MG-" . str_pad($cid, 5, '0', STR_PAD_LEFT) . " status updated to $newStatus.";
        }
    }
}

// Stats
$totalConsultations = $pdo->query("SELECT COUNT(*) FROM `consultations`")->fetchColumn();
$pendingConsultations = $pdo->query("SELECT COUNT(*) FROM `consultations` WHERE `status` = 'Pending'")->fetchColumn();
$scheduledConsultations = $pdo->query("SELECT COUNT(*) FROM `consultations` WHERE `status` = 'Scheduled'")->fetchColumn();
$completedConsultations = $pdo->query("SELECT COUNT(*) FROM `consultations` WHERE `status` = 'Completed'")->fetchColumn();
$totalPartners = $pdo->query("SELECT COUNT(*) FROM `partner_inquiries`")->fetchColumn();

// Recent Consultations
$recentConsultations = $pdo->query("SELECT * FROM `consultations` ORDER BY `id` DESC LIMIT 10")->fetchAll();

// Recent Partners
$recentPartners = $pdo->query("SELECT * FROM `partner_inquiries` ORDER BY `id` DESC LIMIT 5")->fetchAll();
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Advisor Dashboard | Moneyguru Admin</title>
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
                        <span class="text-[10px] font-bold bg-[#C9F24A] text-[#071D29] px-2 py-0.5 rounded-full">Advisor Hub</span>
                    </h1>
                    <p class="text-[10px] text-white/60">Saswata Roy &bull; ARN- 136048</p>
                </div>
            </div>

            <nav class="hidden md:flex items-center gap-1 text-xs font-semibold">
                <a href="index.php" class="bg-white/10 text-[#C9F24A] px-3.5 py-1.5 rounded-lg">Dashboard</a>
                <a href="consultations.php" class="text-white/70 hover:text-white px-3.5 py-1.5 rounded-lg hover:bg-white/5 transition-colors">All Consultations</a>
                <a href="partners.php" class="text-white/70 hover:text-white px-3.5 py-1.5 rounded-lg hover:bg-white/5 transition-colors">Partnerships</a>
                <a href="profile.php" class="text-white/70 hover:text-white px-3.5 py-1.5 rounded-lg hover:bg-white/5 transition-colors">Profile</a>
                <a href="settings.php" class="text-white/70 hover:text-white px-3.5 py-1.5 rounded-lg hover:bg-white/5 transition-colors">Settings</a>
            </nav>

            <div class="flex items-center gap-3">
                <a href="../index.php" target="_blank" class="hidden sm:flex items-center gap-1.5 text-xs text-white/80 hover:text-white bg-white/5 px-3 py-1.5 rounded-lg border border-white/10">
                    <i data-lucide="external-link" class="w-3.5 h-3.5"></i>
                    <span>View Website</span>
                </a>
                <a href="logout.php" class="flex items-center gap-1.5 text-xs text-rose-300 hover:text-rose-200 bg-rose-500/10 px-3 py-1.5 rounded-lg border border-rose-500/20">
                    <i data-lucide="log-out" class="w-3.5 h-3.5"></i>
                    <span>Logout</span>
                </a>
            </div>
        </div>
    </header>

    <!-- Main Container -->
    <main class="flex-1 max-w-7xl mx-auto w-full p-6 sm:p-8 space-y-8">
        
        <!-- Welcome Banner -->
        <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-4 bg-white p-6 rounded-3xl border border-gray-200/90 shadow-sm">
            <div class="flex items-center gap-4">
                <div class="relative group shrink-0">
                    <img src="<?= htmlspecialchars(getFounderPhotoSrc('../')) ?>" 
                         onerror="this.onerror=null; this.src='../assets/img/saswata-roy.svg';" 
                         alt="Advisor Photo" 
                         class="w-14 h-14 sm:w-16 sm:h-16 rounded-2xl object-cover object-top border-2 border-[#C9F24A]/70 shadow-md bg-gray-900 transition-transform duration-300 group-hover:scale-105">
                    <a href="profile.php#photo-section" class="absolute -bottom-1 -right-1 bg-[#071D29] hover:bg-[#123B43] text-[#C9F24A] p-1 rounded-lg border border-[#C9F24A]/40 shadow transition-all" title="Update portrait photo">
                        <i data-lucide="camera" class="w-3 h-3"></i>
                    </a>
                </div>
                <div>
                    <h2 class="text-xl sm:text-2xl font-black text-[#092532]">Welcome, <?= htmlspecialchars($_SESSION['admin_name'] ?? 'Saswata') ?>!</h2>
                    <p class="text-xs text-[#607078] mt-1">Here is a real-time summary of incoming client consultations and institutional proposals.</p>
                </div>
            </div>
            <div class="flex items-center gap-2 flex-wrap">
                <a href="profile.php#photo-section" class="flex items-center gap-1.5 text-xs font-bold text-[#071D29] bg-[#F7F8F5] hover:bg-gray-100 px-3.5 py-2.5 rounded-xl transition-all border border-gray-200 shadow-sm">
                    <i data-lucide="camera" class="w-3.5 h-3.5 text-[#071D29]"></i>
                    <span>Update Photo</span>
                </a>
                <a href="consultations.php?export=csv" class="flex items-center gap-1.5 text-xs font-bold text-[#071D29] bg-[#C9F24A] hover:bg-[#D9F77A] px-4 py-2.5 rounded-xl transition-all shadow-sm">
                    <i data-lucide="download" class="w-4 h-4"></i>
                    <span>Export CSV</span>
                </a>
            </div>
        </div>

        <?php if (!empty($statusMsg)): ?>
        <div class="p-3.5 rounded-xl bg-emerald-50 border border-emerald-200 text-xs text-emerald-800 flex items-center gap-2">
            <i data-lucide="check-circle" class="w-4 h-4 text-emerald-600"></i>
            <span><?= htmlspecialchars($statusMsg) ?></span>
        </div>
        <?php endif; ?>

        <!-- KPI Cards Grid -->
        <div class="grid grid-cols-2 lg:grid-cols-5 gap-4">
            <div class="bg-white p-5 rounded-2xl border border-gray-200/80 shadow-sm">
                <div class="flex items-center justify-between text-xs text-[#607078] font-bold uppercase mb-2">
                    <span>Total Leads</span>
                    <i data-lucide="users" class="w-4 h-4 text-[#071D29]"></i>
                </div>
                <div class="text-3xl font-black text-[#092532]"><?= $totalConsultations ?></div>
                <span class="text-[11px] text-gray-500 mt-1 block">Lifetime inquiries</span>
            </div>

            <div class="bg-white p-5 rounded-2xl border border-amber-200 shadow-sm bg-amber-50/40">
                <div class="flex items-center justify-between text-xs text-amber-800 font-bold uppercase mb-2">
                    <span>Pending Action</span>
                    <i data-lucide="clock" class="w-4 h-4 text-amber-600"></i>
                </div>
                <div class="text-3xl font-black text-amber-900"><?= $pendingConsultations ?></div>
                <span class="text-[11px] text-amber-700 mt-1 block">Awaiting advisor callback</span>
            </div>

            <div class="bg-white p-5 rounded-2xl border border-blue-200 shadow-sm bg-blue-50/40">
                <div class="flex items-center justify-between text-xs text-blue-800 font-bold uppercase mb-2">
                    <span>Scheduled</span>
                    <i data-lucide="calendar" class="w-4 h-4 text-blue-600"></i>
                </div>
                <div class="text-3xl font-black text-blue-900"><?= $scheduledConsultations ?></div>
                <span class="text-[11px] text-blue-700 mt-1 block">Upcoming discussions</span>
            </div>

            <div class="bg-white p-5 rounded-2xl border border-emerald-200 shadow-sm bg-emerald-50/40">
                <div class="flex items-center justify-between text-xs text-emerald-800 font-bold uppercase mb-2">
                    <span>Completed</span>
                    <i data-lucide="check-circle" class="w-4 h-4 text-emerald-600"></i>
                </div>
                <div class="text-3xl font-black text-emerald-900"><?= $completedConsultations ?></div>
                <span class="text-[11px] text-emerald-700 mt-1 block">Advised &amp; onboarded</span>
            </div>

            <div class="bg-white p-5 rounded-2xl border border-purple-200 shadow-sm bg-purple-50/40 col-span-2 lg:col-span-1">
                <div class="flex items-center justify-between text-xs text-purple-800 font-bold uppercase mb-2">
                    <span>Partnerships</span>
                    <i data-lucide="handshake" class="w-4 h-4 text-purple-600"></i>
                </div>
                <div class="text-3xl font-black text-purple-900"><?= $totalPartners ?></div>
                <span class="text-[11px] text-purple-700 mt-1 block">Corporate &amp; affiliates</span>
            </div>
        </div>

        <!-- Recent Consultations Table -->
        <div class="bg-white rounded-3xl border border-gray-200/90 shadow-sm overflow-hidden">
            <div class="p-6 border-b border-gray-100 flex flex-col sm:flex-row sm:items-center justify-between gap-3">
                <div>
                    <h3 class="text-lg font-bold text-[#092532]">Recent Consultation Bookings</h3>
                    <p class="text-xs text-[#607078] mt-0.5">Showing the latest 10 client inquiries submitted from web &amp; mobile.</p>
                </div>
                <a href="consultations.php" class="text-xs font-bold text-[#071D29] hover:underline flex items-center gap-1">
                    <span>View All Inquiries</span>
                    <i data-lucide="arrow-right" class="w-3.5 h-3.5"></i>
                </a>
            </div>

            <div class="overflow-x-auto">
                <table class="w-full text-left text-xs">
                    <thead class="bg-gray-50 border-b border-gray-200 text-[#607078] uppercase text-[10px] tracking-wider font-extrabold">
                        <tr>
                            <th class="p-4 pl-6">Client Info</th>
                            <th class="p-4">Service</th>
                            <th class="p-4">Contact Mode</th>
                            <th class="p-4">Message / Goal</th>
                            <th class="p-4">Status &amp; Action</th>
                            <th class="p-4 pr-6 text-right">Date</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-gray-100">
                        <?php if (empty($recentConsultations)): ?>
                        <tr>
                            <td colspan="6" class="p-8 text-center text-gray-500">
                                No consultation requests recorded yet.
                            </td>
                        </tr>
                        <?php else: ?>
                        <?php foreach ($recentConsultations as $c): 
                            $status = $c['status'];
                            $badgeColor = 'bg-amber-100 text-amber-800 border-amber-200';
                            if ($status === 'Scheduled') $badgeColor = 'bg-blue-100 text-blue-800 border-blue-200';
                            if ($status === 'Completed') $badgeColor = 'bg-emerald-100 text-emerald-800 border-emerald-200';
                            if ($status === 'Contacted') $badgeColor = 'bg-purple-100 text-purple-800 border-purple-200';
                            if ($status === 'Cancelled') $badgeColor = 'bg-gray-100 text-gray-700 border-gray-200';
                        ?>
                        <tr class="hover:bg-gray-50/70 transition-colors">
                            <td class="p-4 pl-6">
                                <div class="font-bold text-sm text-[#092532]"><?= htmlspecialchars($c['name']) ?></div>
                                <div class="text-[11px] text-gray-500 mt-0.5">
                                    <a href="tel:<?= htmlspecialchars($c['phone']) ?>" class="hover:underline font-mono"><?= htmlspecialchars($c['phone']) ?></a> &bull; 
                                    <a href="mailto:<?= htmlspecialchars($c['email']) ?>" class="hover:underline"><?= htmlspecialchars($c['email']) ?></a>
                                </div>
                            </td>
                            <td class="p-4">
                                <span class="font-semibold text-[#071D29] bg-gray-100 px-2 py-0.5 rounded text-[11px]">
                                    <?= htmlspecialchars($c['service']) ?>
                                </span>
                            </td>
                            <td class="p-4">
                                <div class="flex items-center gap-1.5">
                                    <?php if ($c['preferred_contact'] === 'WhatsApp'): ?>
                                    <a href="https://wa.me/<?= preg_replace('/[^0-9]/', '', $c['phone']) ?>?text=Hi%20<?= urlencode($c['name']) ?>,%20this%20is%20Saswata%20Roy%20from%20Moneyguru%20Financial%20Services." target="_blank" class="inline-flex items-center gap-1 text-emerald-700 bg-emerald-50 px-2 py-0.5 rounded text-[11px] font-semibold border border-emerald-200">
                                        <i data-lucide="message-circle" class="w-3 h-3"></i>
                                        <span>WhatsApp</span>
                                    </a>
                                    <?php else: ?>
                                    <span class="text-gray-700 font-medium"><?= htmlspecialchars($c['preferred_contact']) ?></span>
                                    <?php endif; ?>
                                </div>
                            </td>
                            <td class="p-4 max-w-xs truncate text-[#607078]" title="<?= htmlspecialchars($c['message'] ?? '') ?>">
                                <?= htmlspecialchars($c['message'] ?: 'None provided.') ?>
                            </td>
                            <td class="p-4">
                                <form method="POST" action="index.php" class="inline-flex items-center gap-1.5">
                                    <?= csrfInput() ?>
                                    <input type="hidden" name="action" value="update_consultation_status">
                                    <input type="hidden" name="consultation_id" value="<?= $c['id'] ?>">
                                    <select name="status" onchange="this.form.submit()" class="text-xs font-semibold px-2 py-1 rounded-lg border <?= $badgeColor ?> focus:outline-none cursor-pointer">
                                        <option value="Pending" <?= $status === 'Pending' ? 'selected' : '' ?>>Pending</option>
                                        <option value="Contacted" <?= $status === 'Contacted' ? 'selected' : '' ?>>Contacted</option>
                                        <option value="Scheduled" <?= $status === 'Scheduled' ? 'selected' : '' ?>>Scheduled</option>
                                        <option value="Completed" <?= $status === 'Completed' ? 'selected' : '' ?>>Completed</option>
                                        <option value="Cancelled" <?= $status === 'Cancelled' ? 'selected' : '' ?>>Cancelled</option>
                                    </select>
                                </form>
                            </td>
                            <td class="p-4 pr-6 text-right text-gray-500 font-mono text-[11px]">
                                <?= date('d M Y', strtotime($c['created_at'])) ?>
                            </td>
                        </tr>
                        <?php endforeach; ?>
                        <?php endif; ?>
                    </tbody>
                </table>
            </div>
        </div>

        <!-- Recent Partner Inquiries Table -->
        <div class="bg-white rounded-3xl border border-gray-200/90 shadow-sm overflow-hidden">
            <div class="p-6 border-b border-gray-100 flex items-center justify-between">
                <div>
                    <h3 class="text-lg font-bold text-[#092532]">Recent Partnership Proposals</h3>
                    <p class="text-xs text-[#607078] mt-0.5">Corporate wellness, employee seminars, and institutional referral inquiries.</p>
                </div>
                <a href="partners.php" class="text-xs font-bold text-[#071D29] hover:underline flex items-center gap-1">
                    <span>All Partners</span>
                    <i data-lucide="arrow-right" class="w-3.5 h-3.5"></i>
                </a>
            </div>

            <div class="overflow-x-auto">
                <table class="w-full text-left text-xs">
                    <thead class="bg-gray-50 border-b border-gray-200 text-[#607078] uppercase text-[10px] tracking-wider font-extrabold">
                        <tr>
                            <th class="p-4 pl-6">Partner Name</th>
                            <th class="p-4">Organization</th>
                            <th class="p-4">Category</th>
                            <th class="p-4">Contact</th>
                            <th class="p-4">Proposal</th>
                            <th class="p-4 pr-6 text-right">Date</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-gray-100">
                        <?php if (empty($recentPartners)): ?>
                        <tr>
                            <td colspan="6" class="p-6 text-center text-gray-500">No partner proposals recorded yet.</td>
                        </tr>
                        <?php else: ?>
                        <?php foreach ($recentPartners as $p): ?>
                        <tr class="hover:bg-gray-50/70 transition-colors">
                            <td class="p-4 pl-6 font-bold text-[#092532]"><?= htmlspecialchars($p['full_name']) ?></td>
                            <td class="p-4 font-semibold text-gray-700"><?= htmlspecialchars($p['organization']) ?></td>
                            <td class="p-4">
                                <span class="bg-purple-50 text-purple-800 border border-purple-200 px-2 py-0.5 rounded text-[11px] font-semibold">
                                    <?= htmlspecialchars($p['partnership_type']) ?>
                                </span>
                            </td>
                            <td class="p-4 text-[11px] text-gray-600">
                                <div><?= htmlspecialchars($p['phone']) ?></div>
                                <div class="text-gray-400"><?= htmlspecialchars($p['email']) ?></div>
                            </td>
                            <td class="p-4 max-w-xs truncate text-[#607078]" title="<?= htmlspecialchars($p['message'] ?? '') ?>">
                                <?= htmlspecialchars($p['message'] ?: 'N/A') ?>
                            </td>
                            <td class="p-4 pr-6 text-right text-gray-500 font-mono text-[11px]">
                                <?= date('d M Y', strtotime($p['created_at'])) ?>
                            </td>
                        </tr>
                        <?php endforeach; ?>
                        <?php endif; ?>
                    </tbody>
                </table>
            </div>
        </div>

    </main>

    <!-- Footer -->
    <footer class="bg-white border-t border-gray-200 py-4 px-6 text-center text-xs text-gray-500">
        &copy; <?= date('Y') ?> Moneyguru Financial Services &bull; Saswata Roy (ARN- 136048) &bull; XAMPP Localhost Development
    </footer>

    <script>
        lucide.createIcons();
    </script>
</body>
</html>
