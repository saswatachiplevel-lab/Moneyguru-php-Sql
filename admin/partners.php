<?php
/**
 * Moneyguru Admin - Partner Inquiries Management
 */
require_once __DIR__ . '/auth_check.php';
require_once __DIR__ . '/../config/db.php';

$pdo = getDBConnection();

$alert = '';
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    if (!verifyCsrfToken($_POST['csrf_token'] ?? '')) {
        $alert = "Security token validation failed. Please refresh the page.";
    } else {
        if (isset($_POST['action']) && $_POST['action'] === 'update_partner_status') {
            $id = intval($_POST['id']);
            $status = trim($_POST['status']);
            $stmt = $pdo->prepare("UPDATE `partner_inquiries` SET `status` = :s WHERE `id` = :id");
            $stmt->execute([':s' => $status, ':id' => $id]);
            $alert = "Updated partner proposal #$id status to $status.";
        } elseif (isset($_POST['action']) && $_POST['action'] === 'delete_partner') {
            $id = intval($_POST['id']);
            $stmt = $pdo->prepare("DELETE FROM `partner_inquiries` WHERE `id` = :id");
            $stmt->execute([':id' => $id]);
            $alert = "Deleted partner proposal #$id.";
        }
    }
}

$partners = $pdo->query("SELECT * FROM `partner_inquiries` ORDER BY `id` DESC")->fetchAll();
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Partnership Proposals | Moneyguru Admin</title>
    <link rel="icon" type="image/svg+xml" href="../assets/img/logo.svg">
    <link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@400;500;600;700;800&display=swap" rel="stylesheet">
    <script src="https://cdn.tailwindcss.com"></script>
    <script src="https://unpkg.com/lucide@latest"></script>
    <style>
        body { font-family: 'Plus Jakarta Sans', sans-serif; background-color: #F7F8F5; }
    </style>
</head>
<body class="min-h-screen text-[#092532] flex flex-col">
    
    <header class="bg-[#071D29] text-white px-6 py-3.5 border-b border-white/10 sticky top-0 z-40">
        <div class="max-w-7xl mx-auto flex items-center justify-between">
            <div class="flex items-center gap-3">
                <img src="../assets/img/logo.svg" alt="Moneyguru" class="w-9 h-9 rounded-full object-contain">
                <div>
                    <h1 class="font-extrabold text-base tracking-tight text-white flex items-center gap-1.5">
                        <span>Moneyguru Admin</span>
                        <span class="text-[10px] font-bold bg-[#C9F24A] text-[#071D29] px-2 py-0.5 rounded-full">Partnerships</span>
                    </h1>
                    <p class="text-[10px] text-white/60">Saswata Roy &bull; ARN- 136048</p>
                </div>
            </div>

            <nav class="hidden md:flex items-center gap-1 text-xs font-semibold">
                <a href="index.php" class="text-white/70 hover:text-white px-3.5 py-1.5 rounded-lg hover:bg-white/5 transition-colors">Dashboard</a>
                <a href="consultations.php" class="text-white/70 hover:text-white px-3.5 py-1.5 rounded-lg hover:bg-white/5 transition-colors">All Consultations</a>
                <a href="partners.php" class="bg-white/10 text-[#C9F24A] px-3.5 py-1.5 rounded-lg">Partnerships</a>
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
        <div>
            <h2 class="text-2xl font-black text-[#092532]">Institutional &amp; Affiliate Proposals</h2>
            <p class="text-xs text-[#607078] mt-0.5">Total <?= count($partners) ?> proposals submitted.</p>
        </div>

        <?php if (!empty($alert)): ?>
        <div class="p-3.5 rounded-xl bg-emerald-50 border border-emerald-200 text-xs text-emerald-800 flex items-center gap-2">
            <i data-lucide="check-circle" class="w-4 h-4 text-emerald-600"></i>
            <span><?= htmlspecialchars($alert) ?></span>
        </div>
        <?php endif; ?>

        <div class="bg-white rounded-3xl border border-gray-200/90 shadow-sm overflow-hidden">
            <div class="overflow-x-auto">
                <table class="w-full text-left text-xs">
                    <thead class="bg-gray-50 border-b border-gray-200 text-[#607078] uppercase text-[10px] tracking-wider font-extrabold">
                        <tr>
                            <th class="p-4 pl-6">Partner</th>
                            <th class="p-4">Organization</th>
                            <th class="p-4">Category</th>
                            <th class="p-4">Contact</th>
                            <th class="p-4">Proposal Text</th>
                            <th class="p-4">Status</th>
                            <th class="p-4 pr-6 text-right">Actions</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-gray-100">
                        <?php if (empty($partners)): ?>
                        <tr><td colspan="7" class="p-8 text-center text-gray-500">No partner proposals recorded yet.</td></tr>
                        <?php else: ?>
                        <?php foreach ($partners as $p): ?>
                        <tr class="hover:bg-gray-50/70">
                            <td class="p-4 pl-6 font-bold text-sm text-[#092532]"><?= htmlspecialchars($p['full_name']) ?></td>
                            <td class="p-4 font-semibold text-gray-700"><?= htmlspecialchars($p['organization']) ?></td>
                            <td class="p-4">
                                <span class="bg-purple-100 text-purple-800 border border-purple-200 px-2.5 py-1 rounded-lg text-[11px] font-semibold">
                                    <?= htmlspecialchars($p['partnership_type']) ?>
                                </span>
                            </td>
                            <td class="p-4 text-[11px]">
                                <div><a href="tel:<?= htmlspecialchars($p['phone']) ?>" class="font-mono text-[#071D29] hover:underline"><?= htmlspecialchars($p['phone']) ?></a></div>
                                <div><a href="mailto:<?= htmlspecialchars($p['email']) ?>" class="text-gray-500 hover:underline"><?= htmlspecialchars($p['email']) ?></a></div>
                            </td>
                            <td class="p-4 max-w-sm text-[#607078] leading-relaxed">
                                <?= nl2br(htmlspecialchars($p['message'] ?: 'N/A')) ?>
                            </td>
                            <td class="p-4">
                                <form method="POST" action="partners.php" class="inline-block">
                                    <?= csrfInput() ?>
                                    <input type="hidden" name="action" value="update_partner_status">
                                    <input type="hidden" name="id" value="<?= $p['id'] ?>">
                                    <select name="status" onchange="this.form.submit()" class="text-xs font-semibold px-2 py-1 rounded border border-gray-200 bg-white cursor-pointer">
                                        <option value="New" <?= $p['status'] === 'New' ? 'selected' : '' ?>>New</option>
                                        <option value="Reviewing" <?= $p['status'] === 'Reviewing' ? 'selected' : '' ?>>Reviewing</option>
                                        <option value="Approved" <?= $p['status'] === 'Approved' ? 'selected' : '' ?>>Approved</option>
                                        <option value="Rejected" <?= $p['status'] === 'Rejected' ? 'selected' : '' ?>>Rejected</option>
                                    </select>
                                </form>
                            </td>
                            <td class="p-4 pr-6 text-right">
                                <form method="POST" action="partners.php" onsubmit="return confirm('Delete this proposal?');">
                                    <?= csrfInput() ?>
                                    <input type="hidden" name="action" value="delete_partner">
                                    <input type="hidden" name="id" value="<?= $p['id'] ?>">
                                    <button type="submit" class="text-rose-600 hover:text-rose-800 p-1" title="Delete">
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
