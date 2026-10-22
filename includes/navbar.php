<header id="mainHeader" class="fixed top-0 left-0 right-0 z-50 transition-all duration-300 px-4 sm:px-6 lg:px-8 pt-4">
    <div class="max-w-7xl mx-auto">
        <div id="navContainer" class="flex items-center justify-between px-5 py-3 transition-all duration-300 rounded-full bg-[#0B2733]/65 backdrop-blur-md border border-white/15">
            <!-- Brand Logo -->
            <a href="#home" class="flex items-center gap-3 group transition-transform hover:scale-[1.02]">
                <img src="assets/img/logo.svg" alt="Moneyguru Logo" class="w-10 h-10 rounded-full object-contain shadow-sm">
                <div class="flex flex-col text-left">
                    <span class="text-white font-extrabold text-lg sm:text-xl tracking-tight leading-none group-hover:text-[#C9F24A] transition-colors">
                        Moneyguru<span class="text-[#C9F24A]">.</span>
                    </span>
                    <span class="text-[10px] text-white/70 font-semibold tracking-widest uppercase">Grow with us</span>
                </div>
            </a>

            <!-- Desktop Nav Links -->
            <nav class="hidden lg:flex items-center gap-1 xl:gap-2">
                <a href="#home" class="text-white/80 hover:text-white px-3.5 py-1.5 text-sm font-medium rounded-full hover:bg-white/5 transition-all">Home</a>
                <a href="#about" class="text-white/80 hover:text-white px-3.5 py-1.5 text-sm font-medium rounded-full hover:bg-white/5 transition-all">About Us</a>
                <a href="#services" class="text-white/80 hover:text-white px-3.5 py-1.5 text-sm font-medium rounded-full hover:bg-white/5 transition-all">Services</a>
                <a href="#calculators" class="text-white/80 hover:text-white px-3.5 py-1.5 text-sm font-medium rounded-full hover:bg-white/5 transition-all">Calculators</a>
                <a href="#insights" class="text-white/80 hover:text-white px-3.5 py-1.5 text-sm font-medium rounded-full hover:bg-white/5 transition-all">Insights</a>
                <button type="button" onclick="openPartnerModal()" class="text-white/80 hover:text-white px-3.5 py-1.5 text-sm font-medium rounded-full hover:bg-white/5 transition-all">Partner</button>
                <a href="#faq" class="text-white/80 hover:text-white px-3.5 py-1.5 text-sm font-medium rounded-full hover:bg-white/5 transition-all">FAQ</a>
                <a href="#contact" class="text-white/80 hover:text-white px-3.5 py-1.5 text-sm font-medium rounded-full hover:bg-white/5 transition-all">Contact</a>
            </nav>

            <!-- Actions (Book Consultation) -->
            <div class="hidden sm:flex items-center gap-3">
                <button type="button" onclick="openConsultationModal()" class="group flex items-center gap-2 bg-[#C9F24A] hover:bg-[#D9F77A] text-[#071D29] font-bold text-xs sm:text-sm px-4 sm:px-5 py-2 sm:py-2.5 rounded-full transition-all shadow-md hover:shadow-lg hover:shadow-[#C9F24A]/25 active:scale-95">
                    <span>Book Consultation</span>
                    <span class="w-5 h-5 rounded-full bg-[#071D29] text-[#C9F24A] flex items-center justify-center transition-transform group-hover:translate-x-0.5 group-hover:-translate-y-0.5">
                        <i data-lucide="arrow-up-right" class="w-3 h-3"></i>
                    </span>
                </button>
            </div>

            <!-- Mobile Hamburger Toggle -->
            <button id="mobileMenuBtn" type="button" onclick="toggleMobileMenu()" class="lg:hidden p-2 rounded-full text-white/90 hover:text-white hover:bg-white/10 transition-colors focus:outline-none" aria-label="Toggle navigation menu">
                <i data-lucide="menu" id="hamburgerIcon" class="w-6 h-6"></i>
                <i data-lucide="x" id="closeIcon" class="w-6 h-6 hidden text-[#C9F24A]"></i>
            </button>
        </div>

        <!-- Mobile Drawer -->
        <div id="mobileMenu" class="hidden lg:hidden mt-2 bg-[#071D29]/95 backdrop-blur-2xl border border-white/15 rounded-3xl p-5 shadow-2xl transition-all">
            <div class="flex flex-col gap-1 pb-4 border-b border-white/10">
                <a href="#home" onclick="closeMobileMenu()" class="text-white/90 hover:text-[#C9F24A] px-4 py-2.5 text-base font-medium rounded-xl hover:bg-white/5">Home</a>
                <a href="#about" onclick="closeMobileMenu()" class="text-white/90 hover:text-[#C9F24A] px-4 py-2.5 text-base font-medium rounded-xl hover:bg-white/5">About Us</a>
                <a href="#services" onclick="closeMobileMenu()" class="text-white/90 hover:text-[#C9F24A] px-4 py-2.5 text-base font-medium rounded-xl hover:bg-white/5">Services (7 Categories)</a>
                <a href="#calculators" onclick="closeMobileMenu()" class="text-white/90 hover:text-[#C9F24A] px-4 py-2.5 text-base font-medium rounded-xl hover:bg-white/5">SIP & EMI Calculators</a>
                <a href="#insights" onclick="closeMobileMenu()" class="text-white/90 hover:text-[#C9F24A] px-4 py-2.5 text-base font-medium rounded-xl hover:bg-white/5">Financial Insights</a>
                <button type="button" onclick="closeMobileMenu(); openPartnerModal();" class="text-left text-white/90 hover:text-[#C9F24A] px-4 py-2.5 text-base font-medium rounded-xl hover:bg-white/5">Partner With Us</button>
                <a href="#faq" onclick="closeMobileMenu()" class="text-white/90 hover:text-[#C9F24A] px-4 py-2.5 text-base font-medium rounded-xl hover:bg-white/5">FAQ</a>
                <a href="#contact" onclick="closeMobileMenu()" class="text-white/90 hover:text-[#C9F24A] px-4 py-2.5 text-base font-medium rounded-xl hover:bg-white/5">Contact</a>
            </div>
            <div class="pt-4 flex flex-col gap-2.5">
                <button type="button" onclick="closeMobileMenu(); openConsultationModal();" class="w-full flex items-center justify-center gap-2 bg-[#C9F24A] text-[#071D29] font-bold py-3 rounded-2xl shadow-lg">
                    <span>Book a Free Consultation</span>
                    <i data-lucide="arrow-up-right" class="w-4 h-4"></i>
                </button>
            </div>
        </div>
    </div>
</header>
