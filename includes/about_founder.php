<section id="about" class="py-20 lg:py-28 bg-white relative overflow-hidden">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <div class="grid grid-cols-1 lg:grid-cols-12 gap-12 lg:gap-14 items-center">
            
            <!-- Left Founder Photo Column (lg:col-span-6) -->
            <div class="lg:col-span-6 relative">
                <div class="relative rounded-3xl sm:rounded-[36px] overflow-hidden shadow-2xl border border-gray-100 group bg-gray-100">
                    <img id="founderPhoto" 
                         src="<?= htmlspecialchars(getFounderPhotoSrc()) ?>" 
                         onerror="this.onerror=null; this.src='assets/img/saswata-roy.svg';" 
                         alt="Saswata Roy - AMFI-Registered Mutual Fund Distributor" 
                         class="w-full h-[460px] sm:h-[520px] object-cover object-top transition-transform duration-700 group-hover:scale-105">

                    <!-- Gradient Shadow Overlay -->
                    <div class="absolute inset-0 bg-gradient-to-t from-[#071D29]/80 via-transparent to-transparent pointer-events-none"></div>

                    <!-- Credential Pill Overlay -->
                    <div class="absolute bottom-6 left-6 right-6 bg-[#071D29]/95 backdrop-blur-md p-4 rounded-2xl border border-white/20 shadow-xl flex items-center justify-between pointer-events-auto">
                        <div class="flex items-center gap-3">
                            <div class="w-10 h-10 rounded-full bg-[#123B43] flex items-center justify-center text-[#C9F24A] shrink-0 border border-[#C9F24A]/40">
                                <i data-lucide="award" class="w-5 h-5"></i>
                            </div>
                            <div>
                                <p class="text-xs sm:text-sm font-extrabold text-white tracking-wide">SASWATA ROY</p>
                                <p class="text-[11px] text-[#C9F24A] font-semibold">AMFI-Registered Mutual Fund Distributor • ARN- 136048</p>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="absolute -bottom-6 -left-6 w-32 h-32 bg-[#C9F24A]/20 rounded-full blur-2xl -z-10"></div>
            </div>

            <!-- Right Content Column: ABOUT MONEYGURU (lg:col-span-6) -->
            <div class="lg:col-span-6 flex flex-col justify-center">
                <div class="inline-flex items-center gap-1.5 px-3 py-1 rounded-full bg-[#C9F24A]/25 border border-[#C9F24A]/50 w-fit mb-4">
                    <span class="w-1.5 h-1.5 rounded-full bg-[#071D29]"></span>
                    <span class="text-[11px] font-extrabold uppercase tracking-wider text-[#071D29]">ABOUT MONEYGURU</span>
                </div>

                <h2 class="text-3xl sm:text-4xl lg:text-[42px] font-extrabold text-[#092532] tracking-tight leading-[1.15]">
                    Financial Guidance Made Easier to Understand
                </h2>

                <p class="mt-5 text-base sm:text-lg text-[#607078] leading-relaxed">
                    At Moneyguru Financial Services, we believe that taking control of your financial destiny should not require deciphering hundred-page prospectuses or overwhelming financial jargon. We help beginner investors, families, and salaried professionals gain practical clarity across investments, insurance, loans, and wealth planning.
                </p>

                <!-- Value Proposition Grid -->
                <div class="mt-8 grid grid-cols-1 sm:grid-cols-2 gap-5">
                    <div class="p-4 sm:p-5 rounded-2xl bg-[#F7F8F5] border border-gray-200/80 hover:border-[#123B43]/30 transition-all">
                        <div class="w-9 h-9 rounded-xl bg-[#071D29] text-[#C9F24A] flex items-center justify-center mb-3 shadow-sm">
                            <i data-lucide="compass" class="w-5 h-5"></i>
                        </div>
                        <h3 class="text-base font-bold text-[#092532]">Clear Guidance</h3>
                        <p class="text-xs text-[#607078] mt-1.5 leading-relaxed">
                            We explain financial options in straightforward language so you truly understand the risks and rewards of every decision.
                        </p>
                    </div>

                    <div class="p-4 sm:p-5 rounded-2xl bg-[#F7F8F5] border border-gray-200/80 hover:border-[#123B43]/30 transition-all">
                        <div class="w-9 h-9 rounded-xl bg-[#071D29] text-[#C9F24A] flex items-center justify-center mb-3 shadow-sm">
                            <i data-lucide="target" class="w-5 h-5"></i>
                        </div>
                        <h3 class="text-base font-bold text-[#092532]">Goal-Focused Approach</h3>
                        <p class="text-xs text-[#607078] mt-1.5 leading-relaxed">
                            We begin with your unique family milestones and cash flow priorities before discussing any suitable financial products.
                        </p>
                    </div>
                </div>

                <!-- Callout Strip -->
                <div class="mt-8 p-4 sm:p-5 rounded-2xl bg-[#071D29] border border-white/10 flex flex-col sm:flex-row sm:items-center justify-between gap-4 shadow-xl">
                    <div class="flex items-center gap-3">
                        <div class="w-7 h-7 rounded-full bg-[#C9F24A] flex items-center justify-center text-[#071D29] shrink-0">
                            <i data-lucide="check" class="w-4 h-4 stroke-[3]"></i>
                        </div>
                        <p class="text-xs sm:text-sm font-semibold text-white">Start your financial conversation today</p>
                    </div>
                    <button type="button" onclick="openConsultationModal()" class="group flex items-center justify-center gap-2 bg-[#C9F24A] hover:bg-[#D9F77A] text-[#071D29] font-bold text-xs sm:text-sm px-4 py-2.5 rounded-full transition-all shrink-0">
                        <span>Book Consultation</span>
                        <i data-lucide="arrow-up-right" class="w-3.5 h-3.5 transition-transform group-hover:translate-x-0.5 group-hover:-translate-y-0.5"></i>
                    </button>
                </div>

            </div>

        </div>
    </div>
</section>
