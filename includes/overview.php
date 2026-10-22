<section class="relative z-10 py-8 sm:py-12 max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-4 sm:gap-5">
        
        <!-- Card 1: How Can We Help -->
        <div onclick="openConsultationModal()" class="group relative h-[210px] sm:h-[225px] rounded-3xl overflow-hidden cursor-pointer shadow-xl border border-white/15 transition-all duration-300 hover:-translate-y-1 hover:shadow-2xl bg-[#071D29]">
            <img src="https://images.unsplash.com/photo-1573497019940-1c28c88b4f3e?auto=format&fit=crop&w=800&q=80" alt="Personalized Guidance" class="w-full h-full object-cover object-center transition-transform duration-500 group-hover:scale-105 opacity-60">
            <div class="absolute inset-0 bg-gradient-to-t from-[#071D29] via-[#071D29]/80 to-transparent flex flex-col justify-end p-5">
                <div class="flex items-center gap-1.5 mb-1">
                    <span class="text-[10px] uppercase font-bold tracking-wider text-[#C9F24A] bg-[#123B43]/90 px-2 py-0.5 rounded-full border border-[#C9F24A]/30">Personalized Guidance</span>
                </div>
                <h3 class="text-lg sm:text-xl font-bold text-white leading-tight">How Can We Help?</h3>
                <p class="text-xs text-white/70 mt-1 line-clamp-2 leading-relaxed">Explore tailored financial roadmaps and transparent advice designed for your family.</p>
                <div class="mt-3 flex items-center gap-1.5 text-xs font-semibold text-white/90 group-hover:text-[#C9F24A] transition-colors">
                    <span>Learn More</span>
                    <span class="w-4 h-4 rounded-full bg-[#C9F24A] text-[#071D29] flex items-center justify-center text-[10px] transition-transform group-hover:translate-x-1">
                        <i data-lucide="chevron-right" class="w-3 h-3 stroke-[3]"></i>
                    </span>
                </div>
            </div>
        </div>

        <!-- Card 2: Mutual Fund Guidance -->
        <div onclick="openServiceDetailModal('mutual-funds')" class="group relative h-[210px] sm:h-[225px] rounded-3xl overflow-hidden cursor-pointer shadow-xl border border-white/15 transition-all duration-300 hover:-translate-y-1 hover:shadow-2xl bg-[#071D29]">
            <img src="https://images.unsplash.com/photo-1611974789855-9c2a0a7236a3?auto=format&fit=crop&w=800&q=80" alt="Mutual Fund Investing" class="w-full h-full object-cover object-center transition-transform duration-500 group-hover:scale-105 opacity-60">
            <div class="absolute inset-0 bg-gradient-to-t from-[#071D29] via-[#071D29]/80 to-transparent flex flex-col justify-end p-5">
                <div class="flex items-center gap-1.5 mb-1">
                    <span class="text-[10px] uppercase font-bold text-[#C9F24A] tracking-wider bg-[#123B43]/90 px-2 py-0.5 rounded-full border border-[#C9F24A]/30">Investing</span>
                </div>
                <h3 class="text-lg sm:text-xl font-bold text-white leading-tight group-hover:text-[#D9F77A] transition-colors">Mutual Fund Guidance</h3>
                <p class="text-xs text-white/70 mt-1 line-clamp-2 leading-relaxed">Simple guidance for diversified funds and systematic monthly SIP compounding.</p>
                <div class="mt-3 flex items-center gap-1.5 text-xs font-semibold text-white/90 group-hover:text-[#C9F24A] transition-colors">
                    <span>Explore Funds</span>
                    <i data-lucide="arrow-up-right" class="w-3.5 h-3.5 transition-transform group-hover:translate-x-0.5 group-hover:-translate-y-0.5"></i>
                </div>
            </div>
        </div>

        <!-- Card 3: Insurance Planning -->
        <div onclick="openServiceDetailModal('life-insurance')" class="group relative h-[210px] sm:h-[225px] rounded-3xl overflow-hidden cursor-pointer shadow-xl border border-white/15 transition-all duration-300 hover:-translate-y-1 hover:shadow-2xl bg-[#071D29]">
            <img src="https://images.unsplash.com/photo-1576091160399-112ba8d25d1d?auto=format&fit=crop&w=800&q=80" alt="Insurance Protection Planning" class="w-full h-full object-cover object-center transition-transform duration-500 group-hover:scale-105 opacity-60">
            <div class="absolute inset-0 bg-gradient-to-t from-[#071D29] via-[#071D29]/80 to-transparent flex flex-col justify-end p-5">
                <div class="flex items-center gap-1.5 mb-1">
                    <span class="text-[10px] uppercase font-bold text-[#C9F24A] tracking-wider bg-[#123B43]/90 px-2 py-0.5 rounded-full border border-[#C9F24A]/30">Protection</span>
                </div>
                <h3 class="text-lg sm:text-xl font-bold text-white leading-tight group-hover:text-[#D9F77A] transition-colors">Insurance Planning</h3>
                <p class="text-xs text-white/70 mt-1 line-clamp-2 leading-relaxed">Explore pure term protection and comprehensive medical safety for your loved ones.</p>
                <div class="mt-3 flex items-center gap-1.5 text-xs font-semibold text-white/90 group-hover:text-[#C9F24A] transition-colors">
                    <span>Explore Insurance</span>
                    <i data-lucide="arrow-up-right" class="w-3.5 h-3.5 transition-transform group-hover:translate-x-0.5 group-hover:-translate-y-0.5"></i>
                </div>
            </div>
        </div>

        <!-- Card 4: Financial Solutions -->
        <div onclick="document.getElementById('services').scrollIntoView({behavior: 'smooth'})" class="group relative h-[210px] sm:h-[225px] rounded-3xl overflow-hidden cursor-pointer shadow-xl border border-white/15 transition-all duration-300 hover:-translate-y-1 hover:shadow-2xl bg-[#071D29]">
            <img src="https://images.unsplash.com/photo-1559526324-4b87b5e36e44?auto=format&fit=crop&w=800&q=80" alt="Financial Solutions" class="w-full h-full object-cover object-center transition-transform duration-500 group-hover:scale-105 opacity-60">
            <div class="absolute inset-0 bg-gradient-to-t from-[#071D29] via-[#071D29]/80 to-transparent flex flex-col justify-end p-5">
                <div class="flex items-center gap-1.5 mb-1">
                    <span class="text-[10px] uppercase font-bold text-[#C9F24A] tracking-wider bg-[#123B43]/90 px-2 py-0.5 rounded-full border border-[#C9F24A]/30">Solutions</span>
                </div>
                <h3 class="text-lg sm:text-xl font-bold text-white leading-tight group-hover:text-[#D9F77A] transition-colors">Financial Solutions</h3>
                <p class="text-xs text-white/70 mt-1 line-clamp-2 leading-relaxed">Understand loans, credit cards, stocks, and bonds in one place with zero marketing bias.</p>
                <div class="mt-3 flex items-center gap-1.5 text-xs font-semibold text-white/90 group-hover:text-[#C9F24A] transition-colors">
                    <span>Explore Solutions</span>
                    <i data-lucide="arrow-up-right" class="w-3.5 h-3.5 transition-transform group-hover:translate-x-0.5 group-hover:-translate-y-0.5"></i>
                </div>
            </div>
        </div>

    </div>
</section>
