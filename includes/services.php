<?php
$servicesList = getAllServices();
?>

<section id="services" class="py-20 lg:py-28 bg-[#F7F8F5] relative">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        
        <!-- Section Header -->
        <div class="text-center max-w-3xl mx-auto mb-12 sm:mb-16">
            <div class="inline-flex items-center gap-1.5 px-3 py-1 rounded-full bg-[#C9F24A]/25 border border-[#C9F24A]/50 w-fit mb-3">
                <span class="w-1.5 h-1.5 rounded-full bg-[#071D29]"></span>
                <span class="text-[11px] font-extrabold uppercase tracking-wider text-[#071D29]">OUR FINANCIAL HORIZONS</span>
            </div>
            <h2 class="text-3xl sm:text-4xl lg:text-5xl font-black text-[#092532] tracking-tight">
                Explore Our 7 Core Financial Services
            </h2>
            <p class="mt-4 text-base sm:text-lg text-[#607078] leading-relaxed">
                From wealth accumulation to income protection and responsible borrowing, explore clear guidance for every step of your financial roadmap.
            </p>

            <!-- Filter Category Tabs -->
            <div class="mt-8 inline-flex flex-wrap items-center justify-center gap-2 p-1.5 rounded-full bg-white border border-gray-200/80 shadow-sm">
                <button type="button" onclick="filterServices('all')" id="svcFilterAll" class="svc-filter-btn px-5 py-2 rounded-full text-xs sm:text-sm font-bold transition-all bg-[#071D29] text-white shadow-md">
                    All Services (7)
                </button>
                <button type="button" onclick="filterServices('invest')" id="svcFilterInvest" class="svc-filter-btn px-5 py-2 rounded-full text-xs sm:text-sm font-bold transition-all text-[#607078] hover:text-[#092532]">
                    Invest (3)
                </button>
                <button type="button" onclick="filterServices('protect')" id="svcFilterProtect" class="svc-filter-btn px-5 py-2 rounded-full text-xs sm:text-sm font-bold transition-all text-[#607078] hover:text-[#092532]">
                    Protect (2)
                </button>
                <button type="button" onclick="filterServices('borrow')" id="svcFilterBorrow" class="svc-filter-btn px-5 py-2 rounded-full text-xs sm:text-sm font-bold transition-all text-[#607078] hover:text-[#092532]">
                    Borrow (2)
                </button>
            </div>
        </div>

        <!-- Services Grid -->
        <div id="servicesGrid" class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6 sm:gap-8">
            <?php foreach ($servicesList as $svc): 
                // Determine group tag for filtering
                $group = 'invest';
                if ($svc['id'] === 'life-insurance' || $svc['id'] === 'health-insurance') {
                    $group = 'protect';
                } elseif ($svc['id'] === 'loans' || $svc['id'] === 'credit-cards') {
                    $group = 'borrow';
                }
            ?>
            <div class="service-card group bg-white rounded-3xl overflow-hidden border border-gray-200/80 hover:border-[#071D29]/30 shadow-sm hover:shadow-2xl transition-all duration-300 flex flex-col justify-between" data-group="<?= $group ?>" data-id="<?= htmlspecialchars($svc['id']) ?>">
                <div>
                    <!-- Image with overlay tags -->
                    <div class="relative h-52 sm:h-56 overflow-hidden bg-gray-100">
                        <img src="<?= htmlspecialchars($svc['imageUrl']) ?>" alt="<?= htmlspecialchars($svc['title']) ?>" class="w-full h-full object-cover transition-transform duration-700 group-hover:scale-105">
                        <div class="absolute inset-0 bg-gradient-to-t from-black/60 via-transparent to-transparent"></div>
                        
                        <div class="absolute top-4 left-4 right-4 flex items-center justify-between gap-2">
                            <span class="text-[10px] font-extrabold uppercase tracking-wider bg-[#071D29]/80 backdrop-blur-md text-[#C9F24A] px-3 py-1 rounded-full border border-[#C9F24A]/30">
                                <?= htmlspecialchars($svc['category']) ?>
                            </span>
                            <?php if (!empty($svc['badge'])): ?>
                            <span class="text-[10px] font-bold bg-[#C9F24A] text-[#071D29] px-2.5 py-1 rounded-full shadow-sm">
                                <?= htmlspecialchars($svc['badge']) ?>
                            </span>
                            <?php endif; ?>
                        </div>
                    </div>

                    <!-- Card Body -->
                    <div class="p-6">
                        <h3 class="text-xl font-extrabold text-[#092532] tracking-tight group-hover:text-[#123B43] transition-colors">
                            <?= htmlspecialchars($svc['title']) ?>
                        </h3>
                        <p class="text-xs font-semibold text-[#123B43] mt-1">
                            <?= htmlspecialchars($svc['tagline']) ?>
                        </p>
                        <p class="text-xs sm:text-sm text-[#607078] mt-3 leading-relaxed">
                            <?= htmlspecialchars($svc['shortDesc']) ?>
                        </p>

                        <!-- Key Benefits List -->
                        <div class="mt-4 pt-4 border-t border-gray-100 space-y-2">
                            <?php 
                            $benefits = array_slice($svc['benefits'] ?? [], 0, 2);
                            foreach ($benefits as $b): 
                            ?>
                            <div class="flex items-start gap-2 text-xs text-[#092532]">
                                <i data-lucide="check-circle-2" class="w-3.5 h-3.5 text-emerald-600 shrink-0 mt-0.5"></i>
                                <span><?= htmlspecialchars($b) ?></span>
                            </div>
                            <?php endforeach; ?>
                        </div>
                    </div>
                </div>

                <!-- Card Footer Actions -->
                <div class="p-6 pt-0 flex items-center gap-3">
                    <button type="button" onclick="openServiceDetailModal('<?= htmlspecialchars($svc['id']) ?>')" class="flex-1 text-center bg-[#F7F8F5] hover:bg-gray-200 text-[#071D29] font-bold text-xs py-2.5 rounded-xl border border-gray-200 transition-colors">
                        Learn More
                    </button>
                    <button type="button" onclick="bookForService('<?= htmlspecialchars($svc['title']) ?>')" class="flex-1 flex items-center justify-center gap-1.5 bg-[#071D29] hover:bg-[#0B2733] text-[#C9F24A] font-bold text-xs py-2.5 rounded-xl transition-all shadow-md">
                        <span>Book Session</span>
                        <i data-lucide="arrow-up-right" class="w-3.5 h-3.5"></i>
                    </button>
                </div>
            </div>
            <?php endforeach; ?>
        </div>

    </div>
</section>
