<?php
$faqsList = getAllFaqs();
?>

<section id="faq" class="py-20 lg:py-28 bg-[#F7F8F5] relative">
    <div class="max-w-4xl mx-auto px-4 sm:px-6 lg:px-8">
        
        <!-- Header -->
        <div class="text-center max-w-2xl mx-auto mb-10">
            <div class="inline-flex items-center gap-1.5 px-3 py-1 rounded-full bg-[#C9F24A]/25 border border-[#C9F24A]/50 w-fit mb-3">
                <i data-lucide="help-circle" class="w-3.5 h-3.5 text-[#071D29]"></i>
                <span class="text-[11px] font-extrabold uppercase tracking-wider text-[#071D29]">FREQUENTLY ASKED QUESTIONS</span>
            </div>
            <h2 class="text-3xl sm:text-4xl font-extrabold text-[#092532] tracking-tight">
                Got Questions? We Have Simple Answers
            </h2>
            <p class="mt-3 text-sm sm:text-base text-[#607078]">
                Clear explanations for the most common questions about SIPs, insurance coverage, and loan math.
            </p>
        </div>

        <!-- Search & Filter Controls -->
        <div class="space-y-4 mb-8">
            <!-- Search Bar -->
            <div class="relative">
                <i data-lucide="search" class="w-4 h-4 text-gray-400 absolute left-4 top-1/2 -translate-y-1/2 pointer-events-none"></i>
                <input type="text" id="faqSearchInput" oninput="filterFaqs()" placeholder="Search any question (e.g. SIP, 80C, Term Insurance, CIBIL, EMI)..." class="w-full pl-11 pr-4 py-3 rounded-2xl bg-white border border-gray-200 text-xs sm:text-sm focus:outline-none focus:ring-2 focus:ring-[#071D29] shadow-sm">
            </div>

            <!-- Category Pills -->
            <div class="flex flex-wrap items-center justify-center gap-2">
                <button type="button" onclick="selectFaqCategory('all')" id="faqCatAll" class="faq-cat-btn px-4 py-1.5 rounded-full text-xs font-bold transition-all bg-[#071D29] text-white shadow-sm">
                    All (<?= count($faqsList) ?>)
                </button>
                <button type="button" onclick="selectFaqCategory('mutual-funds')" id="faqCatMf" class="faq-cat-btn px-4 py-1.5 rounded-full text-xs font-bold transition-all bg-white text-[#607078] hover:text-[#092532] border border-gray-200">
                    Mutual Funds &amp; SIP (4)
                </button>
                <button type="button" onclick="selectFaqCategory('insurance')" id="faqCatIns" class="faq-cat-btn px-4 py-1.5 rounded-full text-xs font-bold transition-all bg-white text-[#607078] hover:text-[#092532] border border-gray-200">
                    Insurance (3)
                </button>
                <button type="button" onclick="selectFaqCategory('loans')" id="faqCatLoans" class="faq-cat-btn px-4 py-1.5 rounded-full text-xs font-bold transition-all bg-white text-[#607078] hover:text-[#092532] border border-gray-200">
                    Loans &amp; Debt (3)
                </button>
            </div>
        </div>

        <!-- FAQ Accordion List -->
        <div id="faqAccordionList" class="space-y-3.5">
            <?php foreach ($faqsList as $index => $faq): ?>
            <div class="faq-item bg-white rounded-2xl border border-gray-200/90 shadow-sm overflow-hidden transition-all duration-200" data-category="<?= htmlspecialchars($faq['category']) ?>" data-keywords="<?= htmlspecialchars(strtolower($faq['question'] . ' ' . $faq['answer'])) ?>">
                
                <button type="button" onclick="toggleFaq(<?= $index ?>)" class="w-full p-5 text-left flex items-start justify-between gap-4 focus:outline-none">
                    <div>
                        <div class="flex items-center gap-2 mb-1.5">
                            <span class="text-[10px] font-bold text-[#123B43] bg-[#123B43]/10 px-2 py-0.5 rounded-md">
                                <?= htmlspecialchars($faq['categoryLabel']) ?>
                            </span>
                            <?php if (!empty($faq['highlightTag'])): ?>
                            <span class="text-[10px] font-bold text-[#071D29] bg-[#C9F24A] px-2 py-0.5 rounded-md">
                                <?= htmlspecialchars($faq['highlightTag']) ?>
                            </span>
                            <?php endif; ?>
                        </div>
                        <h3 class="text-sm sm:text-base font-bold text-[#092532] leading-snug">
                            <?= htmlspecialchars($faq['question']) ?>
                        </h3>
                    </div>
                    <span id="faqChevron-<?= $index ?>" class="w-6 h-6 rounded-full bg-gray-100 text-gray-500 flex items-center justify-center shrink-0 mt-1 transition-transform duration-200">
                        <i data-lucide="chevron-down" class="w-4 h-4"></i>
                    </span>
                </button>

                <div id="faqAnswer-<?= $index ?>" class="hidden px-5 pb-5 pt-0 text-xs sm:text-sm text-[#607078] leading-relaxed border-t border-gray-100 mt-2">
                    <?= nl2br(htmlspecialchars($faq['answer'])) ?>
                </div>

            </div>
            <?php endforeach; ?>
        </div>

        <!-- No Results Fallback -->
        <div id="faqNoResults" class="hidden p-8 text-center bg-white rounded-2xl border border-dashed border-gray-300">
            <i data-lucide="search-x" class="w-8 h-8 text-gray-400 mx-auto mb-2"></i>
            <p class="text-sm font-bold text-[#092532]">No matching questions found</p>
            <p class="text-xs text-[#607078] mt-1">Try another keyword or reach out directly to our advisors.</p>
            <button type="button" onclick="openConsultationModal()" class="mt-4 text-xs font-bold text-[#071D29] bg-[#C9F24A] px-4 py-2 rounded-full shadow-sm">
                Ask Us Directly
            </button>
        </div>

        <!-- Bottom Prompt -->
        <div class="mt-10 text-center">
            <p class="text-xs text-[#607078]">Have a personalized scenario that isn't answered here?</p>
            <button type="button" onclick="openConsultationModal()" class="mt-2 text-xs font-bold text-[#071D29] hover:underline">
                Schedule a 1-on-1 Consultation Session &rarr;
            </button>
        </div>

    </div>
</section>
