<section id="calculators" class="py-20 bg-white border-t border-gray-100">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        
        <!-- Header -->
        <div class="text-center max-w-2xl mx-auto">
            <div class="inline-flex items-center gap-1.5 px-3 py-1 rounded-full bg-[#C9F24A]/25 border border-[#C9F24A]/50 w-fit mb-3">
                <i data-lucide="calculator" class="w-3.5 h-3.5 text-[#071D29]"></i>
                <span class="text-[11px] font-extrabold uppercase tracking-wider text-[#071D29]">INTERACTIVE PLANNING TOOLS</span>
            </div>
            <h2 class="text-3xl sm:text-4xl font-extrabold text-[#092532] tracking-tight">
                See How Your Money Can Grow or Work for You
            </h2>
            <p class="mt-3 text-sm sm:text-base text-[#607078]">
                Experiment with numbers in real-time. Calculate compound wealth via Systematic Investment Plans (SIP) or plan sensible monthly loan repayments.
            </p>

            <!-- Calculator Type Switcher -->
            <div class="mt-6 inline-flex p-1 rounded-full bg-[#F7F8F5] border border-gray-200">
                <button type="button" id="calcTabSip" onclick="switchCalculator('sip')" class="flex items-center gap-2 px-5 py-2 rounded-full text-xs font-bold transition-all bg-[#071D29] text-white shadow-md">
                    <i data-lucide="trending-up" class="w-3.5 h-3.5 text-[#C9F24A]"></i>
                    <span>SIP Growth Calculator</span>
                </button>
                <button type="button" id="calcTabEmi" onclick="switchCalculator('emi')" class="flex items-center gap-2 px-5 py-2 rounded-full text-xs font-bold transition-all text-[#607078] hover:text-[#092532]">
                    <i data-lucide="wallet" class="w-3.5 h-3.5 text-[#C9F24A]"></i>
                    <span>Loan EMI Calculator</span>
                </button>
            </div>
        </div>

        <div class="mt-12 bg-[#F7F8F5] rounded-3xl p-6 sm:p-10 border border-gray-200/90 shadow-sm max-w-4xl mx-auto">
            
            <!-- 1. SIP CALCULATOR VIEW -->
            <div id="sipCalcView" class="grid grid-cols-1 md:grid-cols-12 gap-8 items-center">
                <!-- Inputs Column -->
                <div class="md:col-span-7 space-y-6">
                    <!-- Monthly Investment -->
                    <div>
                        <div class="flex justify-between items-center mb-2">
                            <label class="text-xs font-bold uppercase tracking-wider text-[#092532]">Monthly Investment (SIP)</label>
                            <span id="sipAmountDisplay" class="text-sm font-extrabold text-[#071D29] bg-white px-3 py-1 rounded-lg border border-gray-200">₹10,000</span>
                        </div>
                        <input type="range" id="sipAmountSlider" min="500" max="100000" step="500" value="10000" oninput="calculateSip()" class="w-full h-2 bg-gray-300 rounded-lg appearance-none cursor-pointer accent-[#071D29]">
                        <div class="flex justify-between text-[10px] text-[#607078] mt-1">
                            <span>₹500</span>
                            <span>₹50,000</span>
                            <span>₹1,00,000</span>
                        </div>
                    </div>

                    <!-- Horizon -->
                    <div>
                        <div class="flex justify-between items-center mb-2">
                            <label class="text-xs font-bold uppercase tracking-wider text-[#092532]">Investment Horizon</label>
                            <span id="sipYearsDisplay" class="text-sm font-extrabold text-[#071D29] bg-white px-3 py-1 rounded-lg border border-gray-200">10 Years</span>
                        </div>
                        <input type="range" id="sipYearsSlider" min="1" max="30" step="1" value="10" oninput="calculateSip()" class="w-full h-2 bg-gray-300 rounded-lg appearance-none cursor-pointer accent-[#071D29]">
                        <div class="flex justify-between text-[10px] text-[#607078] mt-1">
                            <span>1 Yr</span>
                            <span>15 Yrs</span>
                            <span>30 Yrs</span>
                        </div>
                    </div>

                    <!-- Expected Return -->
                    <div>
                        <div class="flex justify-between items-center mb-2">
                            <label class="text-xs font-bold uppercase tracking-wider text-[#092532]">Expected Annual Return (%)</label>
                            <span id="sipRateDisplay" class="text-sm font-extrabold text-[#071D29] bg-white px-3 py-1 rounded-lg border border-gray-200">12% p.a.</span>
                        </div>
                        <input type="range" id="sipRateSlider" min="5" max="20" step="0.5" value="12" oninput="calculateSip()" class="w-full h-2 bg-gray-300 rounded-lg appearance-none cursor-pointer accent-[#071D29]">
                        <div class="flex justify-between text-[10px] text-[#607078] mt-1">
                            <span>5% (Debt)</span>
                            <span>12% (Equity)</span>
                            <span>20% (Aggressive)</span>
                        </div>
                    </div>
                </div>

                <!-- Result Card Column -->
                <div class="md:col-span-5 bg-[#071D29] rounded-2xl p-6 text-white border border-white/10 shadow-xl flex flex-col justify-between">
                    <div>
                        <span class="text-[10px] uppercase font-bold text-[#C9F24A] tracking-wider">Projected Corpus Value</span>
                        <div id="sipTotalValue" class="text-3xl font-extrabold text-[#C9F24A] mt-1">₹23,23,391</div>

                        <div class="mt-6 space-y-3 pt-4 border-t border-white/10">
                            <div class="flex justify-between text-xs">
                                <span class="text-white/60">Amount Invested:</span>
                                <span id="sipInvestedAmount" class="font-semibold text-white">₹12,00,000</span>
                            </div>
                            <div class="flex justify-between text-xs">
                                <span class="text-white/60">Est. Growth Gain:</span>
                                <span id="sipGainAmount" class="font-semibold text-[#D9F77A]">₹11,23,391</span>
                            </div>
                        </div>
                    </div>

                    <div class="mt-6 pt-4 border-t border-white/10">
                        <button type="button" onclick="bookForService('Mutual Funds')" class="w-full flex items-center justify-center gap-2 bg-[#C9F24A] text-[#071D29] font-bold text-xs py-2.5 rounded-xl hover:bg-[#D9F77A] transition-colors">
                            <span>Build This SIP Portfolio</span>
                            <i data-lucide="arrow-up-right" class="w-3.5 h-3.5"></i>
                        </button>
                        <p class="text-[10px] text-white/50 text-center mt-2">Projections are educational and assume steady compounding.</p>
                    </div>
                </div>
            </div>

            <!-- 2. LOAN EMI CALCULATOR VIEW -->
            <div id="emiCalcView" class="grid grid-cols-1 md:grid-cols-12 gap-8 items-center hidden">
                <!-- Inputs Column -->
                <div class="md:col-span-7 space-y-6">
                    <!-- Principal -->
                    <div>
                        <div class="flex justify-between items-center mb-2">
                            <label class="text-xs font-bold uppercase tracking-wider text-[#092532]">Loan Principal Amount</label>
                            <span id="emiPrincipalDisplay" class="text-sm font-extrabold text-[#071D29] bg-white px-3 py-1 rounded-lg border border-gray-200">₹25,00,000</span>
                        </div>
                        <input type="range" id="emiPrincipalSlider" min="100000" max="10000000" step="50000" value="2500000" oninput="calculateEmi()" class="w-full h-2 bg-gray-300 rounded-lg appearance-none cursor-pointer accent-[#071D29]">
                        <div class="flex justify-between text-[10px] text-[#607078] mt-1">
                            <span>₹1 Lakh</span>
                            <span>₹50 Lakhs</span>
                            <span>₹1 Crore</span>
                        </div>
                    </div>

                    <!-- Tenure -->
                    <div>
                        <div class="flex justify-between items-center mb-2">
                            <label class="text-xs font-bold uppercase tracking-wider text-[#092532]">Loan Tenure</label>
                            <span id="emiYearsDisplay" class="text-sm font-extrabold text-[#071D29] bg-white px-3 py-1 rounded-lg border border-gray-200">15 Years</span>
                        </div>
                        <input type="range" id="emiYearsSlider" min="1" max="30" step="1" value="15" oninput="calculateEmi()" class="w-full h-2 bg-gray-300 rounded-lg appearance-none cursor-pointer accent-[#071D29]">
                        <div class="flex justify-between text-[10px] text-[#607078] mt-1">
                            <span>1 Yr</span>
                            <span>15 Yrs</span>
                            <span>30 Yrs</span>
                        </div>
                    </div>

                    <!-- Interest Rate -->
                    <div>
                        <div class="flex justify-between items-center mb-2">
                            <label class="text-xs font-bold uppercase tracking-wider text-[#092532]">Interest Rate (% p.a.)</label>
                            <span id="emiRateDisplay" class="text-sm font-extrabold text-[#071D29] bg-white px-3 py-1 rounded-lg border border-gray-200">8.5%</span>
                        </div>
                        <input type="range" id="emiRateSlider" min="6" max="18" step="0.25" value="8.5" oninput="calculateEmi()" class="w-full h-2 bg-gray-300 rounded-lg appearance-none cursor-pointer accent-[#071D29]">
                        <div class="flex justify-between text-[10px] text-[#607078] mt-1">
                            <span>6% (Home Loan)</span>
                            <span>10.5% (Personal)</span>
                            <span>18%</span>
                        </div>
                    </div>
                </div>

                <!-- Result Card Column -->
                <div class="md:col-span-5 bg-[#071D29] rounded-2xl p-6 text-white border border-white/10 shadow-xl flex flex-col justify-between">
                    <div>
                        <span class="text-[10px] uppercase font-bold text-[#C9F24A] tracking-wider">Monthly EMI</span>
                        <div id="emiMonthlyAmount" class="text-3xl font-extrabold text-[#C9F24A] mt-1">₹24,628</div>

                        <div class="mt-6 space-y-3 pt-4 border-t border-white/10">
                            <div class="flex justify-between text-xs">
                                <span class="text-white/60">Principal Amount:</span>
                                <span id="emiPrincipalSummary" class="font-semibold text-white">₹25,00,000</span>
                            </div>
                            <div class="flex justify-between text-xs">
                                <span class="text-white/60">Total Interest Payable:</span>
                                <span id="emiTotalInterest" class="font-semibold text-[#D9F77A]">₹19,33,086</span>
                            </div>
                            <div class="flex justify-between text-xs">
                                <span class="text-white/60">Total Payment:</span>
                                <span id="emiTotalPayment" class="font-semibold text-white">₹44,33,086</span>
                            </div>
                        </div>
                    </div>

                    <div class="mt-6 pt-4 border-t border-white/10">
                        <button type="button" onclick="bookForService('Loans & Borrowing')" class="w-full flex items-center justify-center gap-2 bg-[#C9F24A] text-[#071D29] font-bold text-xs py-2.5 rounded-xl hover:bg-[#D9F77A] transition-colors">
                            <span>Compare Best Bank Rates</span>
                            <i data-lucide="arrow-up-right" class="w-3.5 h-3.5"></i>
                        </button>
                        <p class="text-[10px] text-white/50 text-center mt-2">Actual bank EMI may vary based on exact processing terms.</p>
                    </div>
                </div>
            </div>

        </div>

    </div>
</section>
