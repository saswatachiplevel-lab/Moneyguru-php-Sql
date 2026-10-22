<?php
/**
 * Moneyguru Admin - Profile Management
 * Allows admin to change full name, admin email (used for login), and admin password.
 */
require_once __DIR__ . '/auth_check.php';
require_once __DIR__ . '/../config/db.php';
require_once __DIR__ . '/../includes/helpers.php';

$pdo = getDBConnection();
$alert = '';
$error = '';

$adminId = $_SESSION['admin_id'] ?? 1;

// Handle Profile & Photo Updates
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    if (!verifyCsrfToken($_POST['csrf_token'] ?? '')) {
        $error = "Security token expired or invalid. Please reload the page.";
    } else {
        $action = $_POST['action'] ?? '';

        if ($action === 'update_photo') {
            handleAdvisorPhotoUpload($pdo, $alert, $error);
        } elseif ($action === 'reset_photo') {
            handleAdvisorPhotoReset($pdo, $alert, $error);
        } elseif ($action === 'update_profile') {
            $fullName = trim($_POST['full_name'] ?? '');
            $email = trim($_POST['email'] ?? '');

            if (empty($fullName)) {
                $error = "Full Name cannot be empty.";
            } elseif (empty($email) || !filter_var($email, FILTER_VALIDATE_EMAIL)) {
                $error = "Please provide a valid email address.";
            } else {
                // Check if email already used by another admin
                $checkStmt = $pdo->prepare("SELECT `id` FROM `admins` WHERE LOWER(`email`) = LOWER(:email) AND `id` != :id LIMIT 1");
                $checkStmt->execute([':email' => $email, ':id' => $adminId]);
                if ($checkStmt->fetch()) {
                    $error = "The email address '$email' is already registered to another administrator.";
                } else {
                    $upd = $pdo->prepare("UPDATE `admins` SET `full_name` = :fn, `email` = :email WHERE `id` = :id");
                    $upd->execute([
                        ':fn'    => $fullName,
                        ':email' => $email,
                        ':id'    => $adminId
                    ]);

                    // Update active session values
                    $_SESSION['admin_name'] = $fullName;
                    $_SESSION['admin_email'] = $email;

                    $alert = "Admin profile updated successfully! You can now log in using: " . htmlspecialchars($email);
                }
            }
        } elseif ($action === 'change_password') {
            $currentPass = trim($_POST['current_password'] ?? '');
            $newPass     = trim($_POST['new_password'] ?? '');
            $confirmPass = trim($_POST['confirm_password'] ?? '');

            if (empty($currentPass) || empty($newPass) || empty($confirmPass)) {
                $error = "Please fill in all password fields.";
            } elseif (strlen($newPass) < 6) {
                $error = "New password must be at least 6 characters long.";
            } elseif ($newPass !== $confirmPass) {
                $error = "New password and confirmation password do not match.";
            } else {
                // Verify current password
                $pwdStmt = $pdo->prepare("SELECT `password_hash` FROM `admins` WHERE `id` = :id LIMIT 1");
                $pwdStmt->execute([':id' => $adminId]);
                $currentHash = $pwdStmt->fetchColumn();

                if ($currentHash && password_verify($currentPass, $currentHash)) {
                    $newHash = password_hash($newPass, PASSWORD_BCRYPT);
                    $upd = $pdo->prepare("UPDATE `admins` SET `password_hash` = :hash WHERE `id` = :id");
                    $upd->execute([':hash' => $newHash, ':id' => $adminId]);
                    $alert = "Admin password updated successfully! Please keep your new password safe.";
                } else {
                    $error = "Current password is incorrect.";
                }
            }
        }
    }
}

// Fetch current admin information
$stmt = $pdo->prepare("SELECT * FROM `admins` WHERE `id` = :id LIMIT 1");
$stmt->execute([':id' => $adminId]);
$admin = $stmt->fetch();
if (!$admin) {
    die("Administrator account not found.");
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Admin Profile | Moneyguru Admin</title>
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
                        <span class="text-[10px] font-bold bg-[#C9F24A] text-[#071D29] px-2 py-0.5 rounded-full">Profile</span>
                    </h1>
                    <p class="text-[10px] text-white/60"><?= htmlspecialchars($admin['full_name']) ?> &bull; ARN- 136048</p>
                </div>
            </div>

            <nav class="hidden md:flex items-center gap-1 text-xs font-semibold">
                <a href="index.php" class="text-white/70 hover:text-white px-3.5 py-1.5 rounded-lg hover:bg-white/5 transition-colors">Dashboard</a>
                <a href="consultations.php" class="text-white/70 hover:text-white px-3.5 py-1.5 rounded-lg hover:bg-white/5 transition-colors">All Consultations</a>
                <a href="partners.php" class="text-white/70 hover:text-white px-3.5 py-1.5 rounded-lg hover:bg-white/5 transition-colors">Partnerships</a>
                <a href="profile.php" class="bg-white/10 text-[#C9F24A] px-3.5 py-1.5 rounded-lg">Profile</a>
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
    <main class="flex-1 max-w-4xl mx-auto w-full p-6 sm:p-8 space-y-8">
        
        <!-- Header -->
        <div>
            <h2 class="text-2xl font-black text-[#092532]">Admin Profile &amp; Security</h2>
            <p class="text-xs text-[#607078] mt-0.5">Manage your administrator account details, login email address, and security credentials.</p>
        </div>

        <?php if (!empty($alert)): ?>
        <div class="p-4 rounded-2xl bg-emerald-50 border border-emerald-200 text-xs text-emerald-800 flex items-center gap-3 shadow-sm">
            <i data-lucide="check-circle" class="w-5 h-5 text-emerald-600 shrink-0"></i>
            <div>
                <strong class="font-bold">Success:</strong> <?= htmlspecialchars($alert) ?>
            </div>
        </div>
        <?php endif; ?>

        <?php if (!empty($error)): ?>
        <div class="p-4 rounded-2xl bg-red-50 border border-red-200 text-xs text-red-800 flex items-center gap-3 shadow-sm">
            <i data-lucide="alert-circle" class="w-5 h-5 text-red-600 shrink-0"></i>
            <div>
                <strong class="font-bold">Action Needed:</strong> <?= htmlspecialchars($error) ?>
            </div>
        </div>
        <?php endif; ?>

        <!-- Profile Overview Card -->
        <div class="bg-[#071D29] text-white p-6 sm:p-8 rounded-3xl border border-white/10 shadow-lg relative overflow-hidden">
            <div class="absolute top-0 right-0 w-64 h-64 bg-[#C9F24A]/10 rounded-full blur-3xl pointer-events-none"></div>

            <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-6 relative z-10">
                <div class="flex items-center gap-4 sm:gap-5">
                    <div class="relative group shrink-0">
                        <img src="<?= htmlspecialchars(getFounderPhotoSrc('../')) ?>" 
                             onerror="this.onerror=null; this.src='../assets/img/saswata-roy.svg';" 
                             alt="<?= htmlspecialchars($admin['full_name']) ?>" 
                             class="w-20 h-20 sm:w-24 sm:h-24 rounded-2xl object-cover object-top border-2 border-[#C9F24A]/60 shadow-xl bg-gray-900 transition-transform duration-300 group-hover:scale-105">
                        <a href="#photo-section" class="absolute -bottom-1.5 -right-1.5 bg-[#C9F24A] hover:bg-[#D9F77A] text-[#071D29] p-1.5 rounded-lg shadow-md transition-all flex items-center justify-center border border-[#071D29]" title="Change portrait photo">
                            <i data-lucide="camera" class="w-3.5 h-3.5"></i>
                        </a>
                    </div>
                    <div>
                        <div class="flex items-center gap-2 flex-wrap">
                            <h3 class="text-xl sm:text-2xl font-extrabold text-white tracking-tight"><?= htmlspecialchars($admin['full_name']) ?></h3>
                            <span class="text-[10px] uppercase font-bold bg-[#C9F24A] text-[#071D29] px-2.5 py-0.5 rounded-full">
                                <?= htmlspecialchars(strtoupper($admin['role'] ?? 'ADMIN')) ?>
                            </span>
                        </div>
                        <div class="flex flex-wrap items-center gap-y-1 gap-x-4 mt-1.5 text-xs text-white/70">
                            <span class="flex items-center gap-1.5">
                                <i data-lucide="mail" class="w-3.5 h-3.5 text-[#C9F24A]"></i>
                                <span class="font-mono text-white/90"><?= htmlspecialchars($admin['email']) ?></span>
                            </span>
                            <span class="flex items-center gap-1.5">
                                <i data-lucide="shield" class="w-3.5 h-3.5 text-[#C9F24A]"></i>
                                <span>AMFI ARN- 136048</span>
                            </span>
                            <span class="flex items-center gap-1.5 text-[#C9F24A] font-semibold">
                                <i data-lucide="sparkles" class="w-3.5 h-3.5"></i>
                                <span>Official Advisor Portal</span>
                            </span>
                        </div>
                    </div>
                </div>

                <div class="flex sm:flex-col items-center sm:items-end justify-between gap-3">
                    <div class="bg-white/5 border border-white/10 rounded-2xl p-3 px-4 text-xs">
                        <span class="text-white/60 block text-[10px] uppercase tracking-wider">Account ID:</span>
                        <span class="font-bold text-[#C9F24A] font-mono">#ADM-<?= str_pad($admin['id'], 3, '0', STR_PAD_LEFT) ?></span>
                    </div>
                    <a href="#photo-section" class="inline-flex items-center gap-1.5 text-xs font-bold bg-[#C9F24A] hover:bg-[#D9F77A] text-[#071D29] px-4 py-2 rounded-xl transition-all shadow-md">
                        <i data-lucide="camera" class="w-3.5 h-3.5"></i>
                        <span>Update Photo</span>
                    </a>
                </div>
            </div>
        </div>

        <!-- Advisor Portrait Photo Management Section -->
        <div id="photo-section" class="bg-white p-6 sm:p-8 rounded-3xl border border-gray-200/90 shadow-sm scroll-mt-20">
            <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-4 pb-6 border-b border-gray-100">
                <div class="flex items-start gap-3">
                    <div class="p-2.5 rounded-2xl bg-[#071D29] text-[#C9F24A] shrink-0 shadow-sm">
                        <i data-lucide="camera" class="w-5 h-5"></i>
                    </div>
                    <div>
                        <div class="flex items-center gap-2">
                            <h3 class="text-lg font-bold text-[#092532]">Advisor Portrait Photo</h3>
                            <span class="inline-flex items-center gap-1 text-[11px] font-bold px-2.5 py-0.5 rounded-full bg-emerald-50 text-emerald-700 border border-emerald-200">
                                <span class="w-1.5 h-1.5 rounded-full bg-emerald-500"></span>
                                Live on Frontend
                            </span>
                        </div>
                        <p class="text-xs text-[#607078] mt-1">
                            This photograph represents you across the public website (About Saswata Roy section &amp; direct consultation booking cards).
                        </p>
                    </div>
                </div>
                <div>
                    <a href="../index.php#about" target="_blank" class="inline-flex items-center gap-1.5 text-xs font-bold text-[#071D29] hover:text-[#123B43] bg-gray-100 hover:bg-gray-200/80 px-3.5 py-2 rounded-xl transition-all border border-gray-200 shadow-sm">
                        <i data-lucide="external-link" class="w-3.5 h-3.5"></i>
                        <span>View on Website</span>
                    </a>
                </div>
            </div>

            <div class="pt-6 grid grid-cols-1 lg:grid-cols-12 gap-8 items-start">
                
                <!-- Current Active Photo Column -->
                <div class="lg:col-span-4 flex flex-col items-center text-center p-5 rounded-2xl bg-[#F7F8F5] border border-gray-200">
                    <span class="text-[11px] font-bold uppercase tracking-wider text-gray-500 mb-3 flex items-center gap-1.5">
                        <i data-lucide="image" class="w-3.5 h-3.5"></i>
                        <span>Currently Active Photo</span>
                    </span>
                    
                    <div class="relative w-44 h-56 sm:w-48 sm:h-64 rounded-2xl overflow-hidden shadow-lg border-2 border-white bg-gray-900 group">
                        <img id="adminPhotoCurrentPreview" 
                             src="<?= htmlspecialchars(getFounderPhotoSrc('../')) ?>" 
                             onerror="this.onerror=null; this.src='../assets/img/saswata-roy.svg';" 
                             alt="Saswata Roy Portrait" 
                             class="w-full h-full object-cover object-top transition-transform duration-500 group-hover:scale-105">
                        <div class="absolute inset-0 bg-gradient-to-t from-[#071D29]/80 via-transparent to-transparent flex items-end justify-center p-3">
                            <span class="text-xs text-[#C9F24A] font-extrabold tracking-wide">SASWATA ROY</span>
                        </div>
                    </div>

                    <div class="mt-4 text-[11px] text-gray-500 space-y-1">
                        <p class="font-bold text-gray-700">AMFI ARN- 136048</p>
                        <p class="text-[10px] text-gray-400 font-mono">File: <?= htmlspecialchars(basename(getSiteSettings()['founder_photo'] ?? 'about-portrait.jpg')) ?></p>
                    </div>

                    <!-- Reset to Default Button -->
                    <form method="POST" action="profile.php" class="mt-4 w-full" onsubmit="return confirm('Revert to the original default portrait photo?');">
                        <?= csrfInput() ?>
                        <input type="hidden" name="action" value="reset_photo">
                        <button type="submit" class="w-full py-2 px-3 rounded-xl border border-gray-300 hover:border-gray-400 text-gray-700 hover:text-gray-900 bg-white hover:bg-gray-50 text-xs font-semibold transition-all flex items-center justify-center gap-1.5 shadow-sm">
                            <i data-lucide="rotate-ccw" class="w-3.5 h-3.5 text-gray-500"></i>
                            <span>Reset to Default Photo</span>
                        </button>
                    </form>
                </div>

                <!-- Upload New Photo Column -->
                <div class="lg:col-span-8 space-y-5">
                    <form method="POST" action="profile.php" enctype="multipart/form-data" class="space-y-5" id="adminPhotoForm">
                        <?= csrfInput() ?>
                        <input type="hidden" name="action" value="update_photo">

                        <!-- Interactive Dropzone -->
                        <div id="dropZone" 
                             onclick="document.getElementById('photoFileInput').click()" 
                             class="border-2 border-dashed border-gray-300 hover:border-[#071D29] bg-gray-50/70 hover:bg-white rounded-2xl p-6 sm:p-8 text-center cursor-pointer transition-all group relative">
                            
                            <input type="file" 
                                   id="photoFileInput" 
                                   name="founder_photo" 
                                   accept="image/jpeg,image/png,image/webp" 
                                   class="hidden" 
                                   onchange="handleAdminPhotoSelect(event)" 
                                   required>

                            <div id="dropZonePrompt">
                                <div class="w-12 h-12 rounded-2xl bg-[#071D29]/5 text-[#071D29] group-hover:bg-[#071D29] group-hover:text-[#C9F24A] flex items-center justify-center mx-auto mb-3 transition-colors shadow-sm">
                                    <i data-lucide="upload-cloud" class="w-6 h-6"></i>
                                </div>

                                <p class="text-sm font-bold text-[#092532] mb-1">
                                    <span class="text-[#071D29] underline decoration-[#C9F24A] decoration-2">Click to choose a photo</span> or drag &amp; drop here
                                </p>
                                <p class="text-xs text-[#607078]">
                                    Supports JPG, PNG, or WebP &bull; Maximum file size: 10MB
                                </p>
                            </div>

                            <!-- Live Selected File Preview (hidden until chosen) -->
                            <div id="newPhotoPreviewContainer" class="hidden text-left p-3 rounded-xl bg-emerald-50/90 border border-emerald-300 flex items-center gap-4">
                                <img id="newPhotoPreviewImg" src="" alt="Selected Photo" class="w-14 h-16 rounded-xl object-cover object-top border border-emerald-500 shadow-sm shrink-0 bg-white">
                                <div class="flex-1 min-w-0">
                                    <div class="flex items-center gap-1.5 text-xs font-bold text-emerald-900">
                                        <i data-lucide="check-circle" class="w-4 h-4 text-emerald-600"></i>
                                        <span>New Photo Selected</span>
                                    </div>
                                    <p id="newPhotoFileName" class="text-xs text-emerald-800 font-mono truncate mt-0.5">filename.jpg</p>
                                    <p id="newPhotoFileSize" class="text-[11px] text-emerald-600 mt-0.5">0 KB</p>
                                </div>
                                <button type="button" onclick="cancelPhotoSelection(event)" class="text-xs font-bold text-rose-600 hover:text-rose-800 px-3 py-1.5 rounded-lg hover:bg-rose-100/70 transition-colors shrink-0">
                                    Change
                                </button>
                            </div>
                        </div>

                        <!-- Recommendations & Tips -->
                        <div class="grid grid-cols-1 sm:grid-cols-2 gap-3 text-[11px] text-[#607078]">
                            <div class="flex items-start gap-2 p-3 rounded-xl bg-[#F7F8F5] border border-gray-200">
                                <i data-lucide="check" class="w-3.5 h-3.5 text-emerald-600 shrink-0 mt-0.5"></i>
                                <span><strong>Optimal Ratio:</strong> Vertical portrait orientation (3:4 or 4:5 ratio) looks crisp and professional.</span>
                            </div>
                            <div class="flex items-start gap-2 p-3 rounded-xl bg-[#F7F8F5] border border-gray-200">
                                <i data-lucide="check" class="w-3.5 h-3.5 text-emerald-600 shrink-0 mt-0.5"></i>
                                <span><strong>Instant Sync:</strong> Updates the public website immediately with automatic cache-busting.</span>
                            </div>
                        </div>

                        <!-- Action Submit Button -->
                        <div class="pt-2 flex flex-wrap items-center gap-3">
                            <button type="submit" id="savePhotoBtn" class="bg-[#071D29] hover:bg-[#0B2733] text-[#C9F24A] font-bold text-xs sm:text-sm px-6 py-3 rounded-xl shadow-md transition-all flex items-center justify-center gap-2">
                                <i data-lucide="camera" class="w-4 h-4"></i>
                                <span>Upload &amp; Save New Photo</span>
                            </button>
                            <span class="text-xs text-gray-500">Takes effect immediately</span>
                        </div>
                    </form>
                </div>

            </div>
        </div>

        <div class="grid grid-cols-1 md:grid-cols-2 gap-8">
            
            <!-- Form 1: Account Information (Name & Email) -->
            <div class="bg-white p-6 sm:p-8 rounded-3xl border border-gray-200/90 shadow-sm flex flex-col justify-between">
                <div>
                    <div class="flex items-center gap-2.5 mb-2">
                        <div class="p-2 rounded-xl bg-[#071D29]/5 text-[#071D29]">
                            <i data-lucide="user" class="w-5 h-5"></i>
                        </div>
                        <h3 class="text-lg font-bold text-[#092532]">Profile Information</h3>
                    </div>
                    <p class="text-xs text-[#607078] mb-6">
                        Update your administrator name and primary email address. This email is strictly used for authentication.
                    </p>

                    <form method="POST" action="profile.php" class="space-y-4">
                        <?= csrfInput() ?>
                        <input type="hidden" name="action" value="update_profile">

                        <div>
                            <label class="block text-xs font-bold text-[#092532] uppercase mb-1.5">Full Name</label>
                            <div class="relative">
                                <i data-lucide="user" class="w-4 h-4 text-gray-400 absolute left-3.5 top-1/2 -translate-y-1/2"></i>
                                <input type="text" name="full_name" required value="<?= htmlspecialchars($admin['full_name']) ?>" class="w-full pl-10 pr-4 py-2.5 rounded-xl bg-gray-50 border border-gray-200 text-sm focus:outline-none focus:ring-2 focus:ring-[#071D29]">
                            </div>
                        </div>

                        <div>
                            <label class="block text-xs font-bold text-[#092532] uppercase mb-1.5">
                                Login Email Address
                                <span class="text-rose-500">*</span>
                            </label>
                            <div class="relative">
                                <i data-lucide="mail" class="w-4 h-4 text-gray-400 absolute left-3.5 top-1/2 -translate-y-1/2"></i>
                                <input type="email" name="email" required value="<?= htmlspecialchars($admin['email']) ?>" class="w-full pl-10 pr-4 py-2.5 rounded-xl bg-gray-50 border border-gray-200 text-sm focus:outline-none focus:ring-2 focus:ring-[#071D29]">
                            </div>
                            <p class="text-[11px] text-gray-500 mt-1 flex items-center gap-1">
                                <i data-lucide="info" class="w-3 h-3 text-[#071D29]"></i>
                                <span>You will use this email address to log in to the admin panel.</span>
                            </p>
                        </div>

                        <div class="pt-4">
                            <button type="submit" class="w-full bg-[#071D29] hover:bg-[#0B2733] text-[#C9F24A] font-bold text-xs py-3 rounded-xl shadow-md transition-all flex items-center justify-center gap-2">
                                <i data-lucide="save" class="w-4 h-4"></i>
                                <span>Save Profile Changes</span>
                            </button>
                        </div>
                    </form>
                </div>
            </div>

            <!-- Form 2: Change Password -->
            <div class="bg-white p-6 sm:p-8 rounded-3xl border border-gray-200/90 shadow-sm flex flex-col justify-between">
                <div>
                    <div class="flex items-center gap-2.5 mb-2">
                        <div class="p-2 rounded-xl bg-[#071D29]/5 text-[#071D29]">
                            <i data-lucide="lock" class="w-5 h-5"></i>
                        </div>
                        <h3 class="text-lg font-bold text-[#092532]">Change Password</h3>
                    </div>
                    <p class="text-xs text-[#607078] mb-6">
                        Ensure your account is using a long, random password to keep access secure.
                    </p>

                    <form method="POST" action="profile.php" class="space-y-4">
                        <?= csrfInput() ?>
                        <input type="hidden" name="action" value="change_password">

                        <div>
                            <label class="block text-xs font-bold text-[#092532] uppercase mb-1.5">Current Password</label>
                            <div class="relative">
                                <i data-lucide="key" class="w-4 h-4 text-gray-400 absolute left-3.5 top-1/2 -translate-y-1/2"></i>
                                <input type="password" id="current_password" name="current_password" required placeholder="Enter current password" class="w-full pl-10 pr-10 py-2.5 rounded-xl bg-gray-50 border border-gray-200 text-sm focus:outline-none focus:ring-2 focus:ring-[#071D29]">
                                <button type="button" onclick="togglePasswordVisibility('current_password', this)" class="absolute right-3.5 top-1/2 -translate-y-1/2 text-gray-400 hover:text-gray-600 focus:outline-none" aria-label="Toggle password">
                                    <i data-lucide="eye" class="w-4 h-4"></i>
                                </button>
                            </div>
                        </div>

                        <div>
                            <label class="block text-xs font-bold text-[#092532] uppercase mb-1.5">New Password (Min 6 chars)</label>
                            <div class="relative">
                                <i data-lucide="lock" class="w-4 h-4 text-gray-400 absolute left-3.5 top-1/2 -translate-y-1/2"></i>
                                <input type="password" id="new_password" name="new_password" required minlength="6" placeholder="Minimum 6 characters" class="w-full pl-10 pr-10 py-2.5 rounded-xl bg-gray-50 border border-gray-200 text-sm focus:outline-none focus:ring-2 focus:ring-[#071D29]">
                                <button type="button" onclick="togglePasswordVisibility('new_password', this)" class="absolute right-3.5 top-1/2 -translate-y-1/2 text-gray-400 hover:text-gray-600 focus:outline-none" aria-label="Toggle password">
                                    <i data-lucide="eye" class="w-4 h-4"></i>
                                </button>
                            </div>
                        </div>

                        <div>
                            <label class="block text-xs font-bold text-[#092532] uppercase mb-1.5">Confirm New Password</label>
                            <div class="relative">
                                <i data-lucide="check" class="w-4 h-4 text-gray-400 absolute left-3.5 top-1/2 -translate-y-1/2"></i>
                                <input type="password" id="confirm_password" name="confirm_password" required minlength="6" placeholder="Re-type new password" class="w-full pl-10 pr-10 py-2.5 rounded-xl bg-gray-50 border border-gray-200 text-sm focus:outline-none focus:ring-2 focus:ring-[#071D29]">
                                <button type="button" onclick="togglePasswordVisibility('confirm_password', this)" class="absolute right-3.5 top-1/2 -translate-y-1/2 text-gray-400 hover:text-gray-600 focus:outline-none" aria-label="Toggle password">
                                    <i data-lucide="eye" class="w-4 h-4"></i>
                                </button>
                            </div>
                        </div>

                        <div class="pt-4">
                            <button type="submit" class="w-full bg-gray-800 hover:bg-black text-white font-bold text-xs py-3 rounded-xl shadow-md transition-all flex items-center justify-center gap-2">
                                <i data-lucide="shield-check" class="w-4 h-4 text-[#C9F24A]"></i>
                                <span>Update Password</span>
                            </button>
                        </div>
                    </form>
                </div>
            </div>

        </div>

    </main>

    <script>
        lucide.createIcons();
        function togglePasswordVisibility(fieldId, btn) {
            const field = document.getElementById(fieldId);
            if (!field) return;
            const isPassword = field.type === 'password';
            field.type = isPassword ? 'text' : 'password';
            btn.innerHTML = isPassword ? '<i data-lucide="eye-off" class="w-4 h-4"></i>' : '<i data-lucide="eye" class="w-4 h-4"></i>';
            lucide.createIcons();
        }

        // Photo upload preview & dropzone interactions
        function handleAdminPhotoSelect(event) {
            const file = event.target.files && event.target.files[0];
            if (!file) return;

            const validTypes = ['image/jpeg', 'image/png', 'image/webp'];
            if (!validTypes.includes(file.type)) {
                alert('Please select a valid JPG, PNG, or WebP image.');
                event.target.value = '';
                return;
            }

            if (file.size > 10 * 1024 * 1024) {
                alert('File size exceeds the 10MB limit. Please choose a smaller photo.');
                event.target.value = '';
                return;
            }

            const reader = new FileReader();
            reader.onload = function(e) {
                const previewImg = document.getElementById('newPhotoPreviewImg');
                const previewContainer = document.getElementById('newPhotoPreviewContainer');
                const dropZonePrompt = document.getElementById('dropZonePrompt');
                const fileName = document.getElementById('newPhotoFileName');
                const fileSize = document.getElementById('newPhotoFileSize');

                if (previewImg) previewImg.src = e.target.result;
                if (fileName) fileName.textContent = file.name;
                if (fileSize) {
                    const mb = (file.size / (1024 * 1024)).toFixed(2);
                    const kb = (file.size / 1024).toFixed(0);
                    fileSize.textContent = mb > 0.5 ? mb + ' MB' : kb + ' KB';
                }
                if (previewContainer) previewContainer.classList.remove('hidden');
                if (dropZonePrompt) dropZonePrompt.classList.add('hidden');
                lucide.createIcons();
            };
            reader.readAsDataURL(file);
        }

        function cancelPhotoSelection(event) {
            if (event) {
                event.stopPropagation();
            }
            const fileInput = document.getElementById('photoFileInput');
            const previewContainer = document.getElementById('newPhotoPreviewContainer');
            const dropZonePrompt = document.getElementById('dropZonePrompt');

            if (fileInput) fileInput.value = '';
            if (previewContainer) previewContainer.classList.add('hidden');
            if (dropZonePrompt) dropZonePrompt.classList.remove('hidden');
        }

        // Setup Drag & Drop listeners
        document.addEventListener('DOMContentLoaded', function() {
            const dropZone = document.getElementById('dropZone');
            const fileInput = document.getElementById('photoFileInput');

            if (dropZone && fileInput) {
                ['dragenter', 'dragover'].forEach(eventName => {
                    dropZone.addEventListener(eventName, function(e) {
                        e.preventDefault();
                        e.stopPropagation();
                        dropZone.classList.add('border-[#071D29]', 'bg-gray-100');
                    }, false);
                });

                ['dragleave', 'drop'].forEach(eventName => {
                    dropZone.addEventListener(eventName, function(e) {
                        e.preventDefault();
                        e.stopPropagation();
                        dropZone.classList.remove('border-[#071D29]', 'bg-gray-100');
                    }, false);
                });

                dropZone.addEventListener('drop', function(e) {
                    const dt = e.dataTransfer;
                    const files = dt && dt.files;
                    if (files && files.length > 0) {
                        fileInput.files = files;
                        handleAdminPhotoSelect({ target: { files: files } });
                    }
                }, false);
            }
        });
    </script>
</body>
</html>
