<?php
/**
 * Moneyguru Financial Services
 * Main Application Entry Point
 * Built with PHP 8.2, MySQL (XAMPP), and Tailwind CSS
 */

require_once __DIR__ . '/includes/helpers.php';
require_once __DIR__ . '/includes/header.php';
require_once __DIR__ . '/includes/navbar.php';
?>

<main>
    <!-- 1. Hero Section with Interactive Mockup -->
    <?php require_once __DIR__ . '/includes/hero.php'; ?>

    <!-- 2. Live Market Ticker -->
    <?php require_once __DIR__ . '/includes/ticker.php'; ?>

    <!-- 3. Core Benefits & Visual Highlights -->
    <?php require_once __DIR__ . '/includes/overview.php'; ?>

    <!-- 4. About Founder (Saswata Roy, AMFI ARN- 136048) -->
    <?php require_once __DIR__ . '/includes/about_founder.php'; ?>

    <!-- 5. Stats Metrics Bar -->
    <?php require_once __DIR__ . '/includes/stats.php'; ?>

    <!-- 6. 7 Core Financial Services (Invest, Protect, Borrow) -->
    <?php require_once __DIR__ . '/includes/services.php'; ?>

    <!-- 7. Interactive Financial Calculators (SIP & Loan EMI) -->
    <?php require_once __DIR__ . '/includes/calculators.php'; ?>

    <!-- 8. Why Choose Moneyguru (Smarter Decisions) -->
    <?php require_once __DIR__ . '/includes/why_us.php'; ?>

    <!-- 9. 4-Step Beginner Journey -->
    <?php require_once __DIR__ . '/includes/process.php'; ?>

    <!-- 10. Educational Knowledge Articles & Guides -->
    <?php require_once __DIR__ . '/includes/articles.php'; ?>

    <!-- 11. Partner With Moneyguru Banner -->
    <?php require_once __DIR__ . '/includes/partner_banner.php'; ?>

    <!-- 12. Consultation Booking & Direct Contact Section -->
    <?php require_once __DIR__ . '/includes/contact.php'; ?>

    <!-- 13. Interactive FAQ Accordion with Search -->
    <?php require_once __DIR__ . '/includes/faqs.php'; ?>

    <!-- 14. High-Converting Final CTA Banner -->
    <?php require_once __DIR__ . '/includes/final_cta.php'; ?>
</main>

<!-- Footer, Modals, and Interactive JavaScript -->
<?php require_once __DIR__ . '/includes/footer.php'; ?>
