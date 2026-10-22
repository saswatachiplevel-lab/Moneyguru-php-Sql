<footer class="bg-[#071D29] text-white border-t border-white/10 pt-16 pb-12">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        
        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-12 gap-10 lg:gap-8 pb-14 border-b border-white/10">
            
            <!-- Col 1: Brand Info -->
            <div class="lg:col-span-4">
                <div class="flex items-center gap-3 mb-4">
                    <img src="assets/img/logo.svg" alt="Moneyguru Logo" class="w-11 h-11 rounded-full object-contain">
                    <div class="flex flex-col">
                        <span class="text-white font-extrabold text-xl tracking-tight leading-none">
                            Moneyguru<span class="text-[#C9F24A]">.</span>
                        </span>
                        <span class="text-[10px] text-white/70 font-semibold tracking-widest uppercase">Grow with us</span>
                    </div>
                </div>

                <p class="text-xs sm:text-sm text-white/70 leading-relaxed max-w-sm mt-3">
                    Clear, practical financial guidance across investments, insurance, loans, credit, stocks, and bonds. Helping beginners build long-term wealth with total peace of mind.
                </p>

                <div class="mt-5 flex items-center gap-3">
                    <div class="w-8 h-8 rounded-full bg-white/5 border border-white/10 flex items-center justify-center text-[#C9F24A]">
                        <i data-lucide="shield-check" class="w-4 h-4"></i>
                    </div>
                    <span class="text-xs text-white/80 font-medium">Regulated products &amp; unbiased advisory</span>
                </div>
            </div>

            <!-- Col 2: Financial Services -->
            <div class="lg:col-span-3">
                <h4 class="text-xs font-extrabold uppercase tracking-widest text-[#C9F24A] mb-4">Financial Services</h4>
                <ul class="space-y-2.5 text-xs text-white/75">
                    <li><button type="button" onclick="openServiceDetailModal('mutual-funds')" class="hover:text-[#C9F24A] transition-colors">Mutual Funds (SIP &amp; Lumpsum)</button></li>
                    <li><button type="button" onclick="openServiceDetailModal('life-insurance')" class="hover:text-[#C9F24A] transition-colors">Life &amp; Term Insurance</button></li>
                    <li><button type="button" onclick="openServiceDetailModal('health-insurance')" class="hover:text-[#C9F24A] transition-colors">Health Insurance &amp; Mediclaim</button></li>
                    <li><button type="button" onclick="openServiceDetailModal('loans')" class="hover:text-[#C9F24A] transition-colors">Loans &amp; EMI Planning</button></li>
                    <li><button type="button" onclick="openServiceDetailModal('credit-cards')" class="hover:text-[#C9F24A] transition-colors">Credit Cards &amp; Score Health</button></li>
                    <li><button type="button" onclick="openServiceDetailModal('stocks')" class="hover:text-[#C9F24A] transition-colors">Stocks &amp; Equity Markets</button></li>
                    <li><button type="button" onclick="openServiceDetailModal('bonds')" class="hover:text-[#C9F24A] transition-colors">Bonds &amp; Fixed Income</button></li>
                </ul>
            </div>

            <!-- Col 3: Navigation -->
            <div class="lg:col-span-2">
                <h4 class="text-xs font-extrabold uppercase tracking-widest text-[#C9F24A] mb-4">Quick Links</h4>
                <ul class="space-y-2.5 text-xs text-white/75">
                    <li><a href="#about" class="hover:text-[#C9F24A] transition-colors">About Saswata Roy</a></li>
                    <li><a href="#calculators" class="hover:text-[#C9F24A] transition-colors">SIP &amp; EMI Calculators</a></li>
                    <li><a href="#journey" class="hover:text-[#C9F24A] transition-colors">4-Step Journey</a></li>
                    <li><a href="#insights" class="hover:text-[#C9F24A] transition-colors">Educational Guides</a></li>
                    <li><button type="button" onclick="openPartnerModal()" class="hover:text-[#C9F24A] transition-colors">Partner With Us</button></li>
                    <li><button type="button" onclick="openPortalModal()" class="hover:text-[#C9F24A] transition-colors">Client Portal</button></li>
                    <li><a href="admin/login.php" class="text-white/40 hover:text-white transition-colors">Admin Login</a></li>
                </ul>
            </div>

            <!-- Col 4: Registered Advisor Details -->
            <div class="lg:col-span-3">
                <h4 class="text-xs font-extrabold uppercase tracking-widest text-[#C9F24A] mb-4">Advisory Desk</h4>
                
                <div class="p-4 rounded-2xl bg-white/5 border border-white/10 space-y-2 text-xs">
                    <p class="font-bold text-white">Saswata Roy</p>
                    <p class="text-[11px] text-white/70">AMFI-Registered Mutual Fund Distributor</p>
                    <p class="font-mono text-[#C9F24A] text-xs font-bold">ARN- 136048</p>
                    
                    <div class="pt-2 border-t border-white/10 flex flex-col gap-1">
                        <a href="tel:6291390883" class="text-white/80 hover:text-[#C9F24A] flex items-center gap-1.5 transition-colors">
                            <i data-lucide="phone" class="w-3.5 h-3.5 text-[#C9F24A]"></i>
                            <span>6291390883</span>
                        </a>
                        <a href="https://wa.me/916291390883?text=Hi%20Saswata,%20I%20would%20like%20to%20consult%20regarding%20financial%20guidance." target="_blank" rel="noopener noreferrer" class="text-white/80 hover:text-[#C9F24A] flex items-center gap-1.5 transition-colors">
                            <i data-lucide="message-circle" class="w-3.5 h-3.5 text-[#C9F24A]"></i>
                            <span>WhatsApp Consultation</span>
                        </a>
                    </div>
                </div>
            </div>

        </div>

        <!-- Compliance & Regulatory Disclosures -->
        <div class="pt-8 text-xs text-white/50 space-y-3 leading-relaxed">
            <p>
                <strong>Disclaimer:</strong> Mutual Fund investments are subject to market risks, read all scheme-related documents carefully before investing. Moneyguru Financial Services and Saswata Roy (ARN- 136048) act as an AMFI-Registered Mutual Fund Distributor. Past performance of any scheme or portfolio is not an indicator of future results.
            </p>
            <p>
                Insurance is a subject matter of solicitation. All loan comparisons and credit card reward analyses are for illustrative purposes and subject to respective bank underwriting and approval terms.
            </p>
            
            <div class="pt-4 flex flex-col sm:flex-row items-center justify-between gap-4 border-t border-white/5 text-[11px]">
                <p>&copy; <?= date('Y') ?> Moneyguru Financial Services. All rights reserved.</p>
                <div class="flex items-center gap-4">
                    <button type="button" onclick="openLegalModal('privacy')" class="hover:text-white transition-colors">Privacy Policy</button>
                    <span>&bull;</span>
                    <button type="button" onclick="openLegalModal('terms')" class="hover:text-white transition-colors">Terms of Service</button>
                    <span>&bull;</span>
                    <button type="button" onclick="openLegalModal('disclaimer')" class="hover:text-white transition-colors">Regulatory Disclosures</button>
                </div>
            </div>
        </div>

    </div>
</footer>

<!-- Modals Container Include -->
<?php require_once __DIR__ . '/modals.php'; ?>

<!-- Main Client Logic Scripts -->
<script src="assets/js/main.js"></script>

<!-- Initialize Lucide Icons -->
<script>
    if (typeof lucide !== 'undefined') {
        lucide.createIcons();
    }
</script>
</body>
</html>
