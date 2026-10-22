<?php
$articlesList = getAllArticles();
?>

<section id="insights" class="py-20 lg:py-28 bg-[#F7F8F5] relative">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        
        <!-- Header -->
        <div class="text-center max-w-3xl mx-auto mb-12 sm:mb-16">
            <div class="inline-flex items-center gap-1.5 px-3 py-1 rounded-full bg-[#C9F24A]/25 border border-[#C9F24A]/50 w-fit mb-3">
                <i data-lucide="book-open" class="w-3.5 h-3.5 text-[#071D29]"></i>
                <span class="text-[11px] font-extrabold uppercase tracking-wider text-[#071D29]">FINANCIAL KNOWLEDGE DESK</span>
            </div>
            <h2 class="text-3xl sm:text-4xl lg:text-5xl font-black text-[#092532] tracking-tight">
                Clear Answers for Practical Decision-Making
            </h2>
            <p class="mt-4 text-base sm:text-lg text-[#607078] leading-relaxed">
                Short, digestible educational guides to help beginners decode Indian personal finance, taxes, and smart compound investing.
            </p>
        </div>

        <!-- Articles Grid -->
        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-8">
            <?php foreach ($articlesList as $post): ?>
            <article class="group bg-white rounded-3xl overflow-hidden border border-gray-200/80 hover:border-[#071D29]/30 shadow-sm hover:shadow-xl transition-all duration-300 flex flex-col justify-between">
                <div>
                    <!-- Image -->
                    <div class="relative h-48 sm:h-52 overflow-hidden bg-gray-100">
                        <img src="<?= htmlspecialchars($post['imageUrl']) ?>" alt="<?= htmlspecialchars($post['title']) ?>" class="w-full h-full object-cover transition-transform duration-700 group-hover:scale-105">
                        <div class="absolute top-4 left-4">
                            <span class="text-[10px] font-extrabold uppercase tracking-wider bg-[#071D29]/80 backdrop-blur-md text-[#C9F24A] px-3 py-1 rounded-full border border-[#C9F24A]/30">
                                <?= htmlspecialchars($post['category']) ?>
                            </span>
                        </div>
                    </div>

                    <!-- Article Body -->
                    <div class="p-6">
                        <div class="flex items-center justify-between text-xs text-[#607078] mb-3">
                            <span class="flex items-center gap-1">
                                <i data-lucide="clock" class="w-3.5 h-3.5 text-[#123B43]"></i>
                                <?= htmlspecialchars($post['readTime']) ?>
                            </span>
                            <span><?= htmlspecialchars($post['publishedDate']) ?></span>
                        </div>

                        <h3 class="text-lg font-bold text-[#092532] tracking-tight group-hover:text-[#123B43] transition-colors leading-snug">
                            <?= htmlspecialchars($post['title']) ?>
                        </h3>

                        <p class="text-xs sm:text-sm text-[#607078] mt-2.5 line-clamp-3 leading-relaxed">
                            <?= htmlspecialchars($post['excerpt']) ?>
                        </p>
                    </div>
                </div>

                <!-- Footer Action -->
                <div class="p-6 pt-0">
                    <button type="button" onclick="openArticleModal('<?= htmlspecialchars($post['id']) ?>')" class="w-full flex items-center justify-center gap-2 bg-[#F7F8F5] hover:bg-[#071D29] text-[#071D29] hover:text-[#C9F24A] font-bold text-xs py-2.5 rounded-xl border border-gray-200 hover:border-[#071D29] transition-all">
                        <span>Read Full Guide</span>
                        <i data-lucide="arrow-up-right" class="w-3.5 h-3.5"></i>
                    </button>
                </div>
            </article>
            <?php endforeach; ?>
        </div>

    </div>
</section>
