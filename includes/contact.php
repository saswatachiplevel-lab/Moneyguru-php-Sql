<?php
$siteSettings = getSiteSettings();
?>

<section id="contact" class="py-20 lg:py-28 bg-white relative">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <div class="grid grid-cols-1 lg:grid-cols-12 gap-12 lg:gap-16 items-start">
            
            <!-- Left Info Column -->
            <div class="lg:col-span-5 flex flex-col justify-start">
                <div class="inline-flex items-center gap-1.5 px-3 py-1 rounded-full bg-[#C9F24A]/25 border border-[#C9F24A]/50 w-fit mb-4">
                    <span class="w-1.5 h-1.5 rounded-full bg-[#071D29]"></span>
                    <span class="text-[11px] font-extrabold uppercase tracking-wider text-[#071D29]">LET'S TALK</span>
                </div>

                <h2 class="text-3xl sm:text-4xl lg:text-[42px] font-extrabold text-[#092532] tracking-tight leading-[1.15]">
                    Let's Start a Conversation About Your Financial Goals
                </h2>

                <p class="mt-4 text-base text-[#607078] leading-relaxed">
                    Have questions about your first mutual fund SIP, choosing between term vs health insurance, or restructuring high-interest loans? Schedule a free introductory discussion with one of our certified advisors.
                </p>

                <!-- Advisor Contact Highlight Card -->
                <div class="mt-8 p-5 sm:p-6 rounded-3xl bg-[#071D29] text-white border border-[#C9F24A]/40 shadow-xl relative overflow-hidden">
                    <div class="absolute top-0 right-0 w-36 h-36 bg-[#C9F24A]/10 rounded-full blur-2xl pointer-events-none"></div>
                    
                    <div class="flex items-center gap-2 mb-2">
                        <div class="w-2 h-2 rounded-full bg-[#C9F24A] animate-pulse"></div>
                        <span class="text-[10px] sm:text-[11px] font-extrabold uppercase tracking-widest text-[#C9F24A]">AMFI-Registered Mutual Fund Distributor</span>
                    </div>

                    <div class="flex items-center gap-4 border-b border-white/10 pb-4">
                        <img id="contactFounderPhoto" src="<?= htmlspecialchars(getFounderPhotoSrc()) ?>" onerror="this.onerror=null; this.src='assets/img/saswata-roy.svg';" alt="Saswata Roy" class="w-14 h-14 rounded-2xl object-cover object-top border border-[#C9F24A]/40 shadow-md shrink-0 bg-gray-900">
                        <div>
                            <h3 class="text-xl sm:text-2xl font-black text-white tracking-tight">SASWATA ROY</h3>
                            <div class="flex items-center gap-2 mt-1">
                                <span class="text-xs text-white/70">Registration ARN:</span>
                                <span class="text-xs font-mono font-bold bg-[#123B43] text-[#C9F24A] px-2.5 py-0.5 rounded-md border border-[#C9F24A]/30">ARN- 136048</span>
                            </div>
                        </div>
                    </div>

                    <div class="pt-4 flex flex-wrap items-center justify-between gap-3">
                        <div>
                            <span class="text-[11px] text-white/60 block">Direct Mobile / Consultation Line:</span>
                            <a href="tel:6291390883" class="text-base sm:text-lg font-bold text-[#C9F24A] hover:text-[#D9F77A] transition-colors flex items-center gap-2 mt-0.5">
                                <i data-lucide="phone-call" class="w-4 h-4"></i>
                                <span>6291390883</span>
                            </a>
                        </div>
                        <div class="flex items-center gap-2">
                            <a href="tel:6291390883" class="text-xs font-bold bg-white/10 hover:bg-white/20 text-white px-3.5 py-2 rounded-xl transition-all border border-white/15 flex items-center gap-1.5">
                                <i data-lucide="phone" class="w-3.5 h-3.5 text-[#C9F24A]"></i>
                                <span>Call Now</span>
                            </a>
                            <a href="https://wa.me/916291390883?text=Hi%20Saswata,%20I%20would%20like%20to%20consult%20regarding%20financial%20guidance." target="_blank" rel="noopener noreferrer" class="text-xs font-bold bg-[#C9F24A] hover:bg-[#D9F77A] text-[#071D29] px-3.5 py-2 rounded-xl transition-all shadow-md flex items-center gap-1.5">
                                <i data-lucide="message-circle" class="w-3.5 h-3.5"></i>
                                <span>WhatsApp</span>
                            </a>
                        </div>
                    </div>
                </div>

                <!-- 3 Feature Bullets -->
                <div class="mt-6 space-y-3.5">
                    <div class="flex items-center gap-3 p-3.5 rounded-2xl bg-[#F7F8F5] border border-gray-200">
                        <div class="w-8 h-8 rounded-xl bg-[#071D29] text-[#C9F24A] flex items-center justify-center shrink-0">
                            <i data-lucide="check" class="w-4 h-4 stroke-[3]"></i>
                        </div>
                        <div>
                            <h4 class="text-xs sm:text-sm font-bold text-[#092532]">Beginner-Friendly Experience</h4>
                            <p class="text-[11px] text-[#607078]">We start with basic fundamentals at your own comfort level.</p>
                        </div>
                    </div>

                    <div class="flex items-center gap-3 p-3.5 rounded-2xl bg-[#F7F8F5] border border-gray-200">
                        <div class="w-8 h-8 rounded-xl bg-[#071D29] text-[#C9F24A] flex items-center justify-center shrink-0">
                            <i data-lucide="check" class="w-4 h-4 stroke-[3]"></i>
                        </div>
                        <div>
                            <h4 class="text-xs sm:text-sm font-bold text-[#092532]">Zero Confusing Jargon</h4>
                            <p class="text-[11px] text-[#607078]">Plain language explanations of every fee, rule, and risk.</p>
                        </div>
                    </div>

                    <div class="flex items-center gap-3 p-3.5 rounded-2xl bg-[#F7F8F5] border border-gray-200">
                        <div class="w-8 h-8 rounded-xl bg-[#071D29] text-[#C9F24A] flex items-center justify-center shrink-0">
                            <i data-lucide="check" class="w-4 h-4 stroke-[3]"></i>
                        </div>
                        <div>
                            <h4 class="text-xs sm:text-sm font-bold text-[#092532]">Personalized Conversation</h4>
                            <p class="text-[11px] text-[#607078]">Recommendations structured strictly around your specific priorities.</p>
                        </div>
                    </div>
                </div>

            </div>

            <!-- Right Form Column -->
            <div class="lg:col-span-7 bg-[#F7F8F5] p-6 sm:p-10 rounded-[32px] border border-gray-200/90 shadow-lg relative">
                
                <!-- Success Message Display (hidden initially) -->
                <div id="bookingSuccessBox" class="hidden py-8 text-center animate-in fade-in">
                    <div class="w-16 h-16 rounded-full bg-[#C9F24A] text-[#071D29] flex items-center justify-center mx-auto mb-4 shadow-lg">
                        <i data-lucide="check-circle" class="w-8 h-8 stroke-[3]"></i>
                    </div>
                    <h3 class="text-2xl font-extrabold text-[#092532]">Consultation Booked!</h3>
                    <p class="text-xs sm:text-sm text-[#607078] mt-2 max-w-md mx-auto">
                        Thank you, <strong id="successClientName"></strong>. Saswata Roy (ARN-136048) will contact you shortly via <strong id="successClientContactMode">WhatsApp</strong> to schedule your free discussion.
                    </p>
                    <div class="mt-4 p-3 rounded-xl bg-white border border-gray-200 inline-block font-mono text-xs text-[#071D29]">
                        Booking Ref: <span id="successBookingRef" class="font-bold text-[#123B43]">MG-00000</span>
                    </div>
                    <div class="mt-6">
                        <button type="button" onclick="resetBookingForm()" class="bg-[#071D29] text-[#C9F24A] font-bold text-xs px-6 py-2.5 rounded-full hover:bg-[#0B2733] transition-colors">
                            Book Another Session
                        </button>
                    </div>
                </div>

                <!-- Consultation Form -->
                <form id="consultationForm" onsubmit="handleConsultationSubmit(event)" class="space-y-4">
                    <div class="mb-4">
                        <h3 class="text-xl font-extrabold text-[#092532]">Request a Free Consultation</h3>
                        <p class="text-xs text-[#607078] mt-1">Fill out the quick form below and we will get back to you promptly.</p>
                    </div>

                    <!-- Error Alert -->
                    <div id="bookingErrorBox" class="hidden p-3 rounded-xl bg-red-50 border border-red-200 text-xs text-red-700 flex items-center gap-2">
                        <i data-lucide="alert-circle" class="w-4 h-4 shrink-0"></i>
                        <span id="bookingErrorMessage">Please check the required fields.</span>
                    </div>

                    <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                        <div>
                            <label class="block text-xs font-bold text-[#092532] uppercase mb-1">Full Name *</label>
                            <input type="text" name="name" id="contactName" required placeholder="e.g. Rahul Sharma" class="w-full px-4 py-2.5 rounded-xl bg-white border border-gray-200 text-xs sm:text-sm focus:outline-none focus:ring-2 focus:ring-[#071D29]">
                        </div>
                        <div>
                            <label class="block text-xs font-bold text-[#092532] uppercase mb-1">Phone / WhatsApp Number *</label>
                            <input type="tel" name="phone" id="contactPhone" required placeholder="e.g. +91 98765 43210" class="w-full px-4 py-2.5 rounded-xl bg-white border border-gray-200 text-xs sm:text-sm focus:outline-none focus:ring-2 focus:ring-[#071D29]">
                        </div>
                    </div>

                    <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                        <div>
                            <label class="block text-xs font-bold text-[#092532] uppercase mb-1">Email Address *</label>
                            <input type="email" name="email" id="contactEmail" required placeholder="rahul@example.com" class="w-full px-4 py-2.5 rounded-xl bg-white border border-gray-200 text-xs sm:text-sm focus:outline-none focus:ring-2 focus:ring-[#071D29]">
                        </div>
                        <div>
                            <label class="block text-xs font-bold text-[#092532] uppercase mb-1">Financial Service of Interest</label>
                            <select name="service" id="contactService" class="w-full px-4 py-2.5 rounded-xl bg-white border border-gray-200 text-xs sm:text-sm focus:outline-none focus:ring-2 focus:ring-[#071D29]">
                                <option value="Mutual Funds & SIP">Mutual Funds &amp; Systematic SIPs</option>
                                <option value="Life Insurance (Pure Term)">Pure Term Life Insurance</option>
                                <option value="Health Insurance">Comprehensive Health Insurance</option>
                                <option value="Loans & Mortgages">Home Loans &amp; Balance Transfer</option>
                                <option value="Personal Loans & Debt Consolidation">Personal Loans &amp; Debt Consolidation</option>
                                <option value="Credit Cards">Credit Card Rewards &amp; Utilization</option>
                                <option value="Stocks & Equities">Direct Stocks &amp; Equity Basics</option>
                                <option value="Bonds & Fixed Income">Bonds &amp; Fixed Income Yields</option>
                                <option value="General Financial Guidance" selected>General Financial Guidance</option>
                            </select>
                        </div>
                    </div>

                    <!-- Preferred Contact Mode -->
                    <div>
                        <label class="block text-xs font-bold text-[#092532] uppercase mb-1.5">Preferred Contact Method</label>
                        <div class="grid grid-cols-3 gap-2">
                            <label class="contact-mode-label cursor-pointer text-center py-2 px-2 text-xs font-bold rounded-xl border border-gray-200 bg-white hover:border-[#071D29] transition-all has-[:checked]:bg-[#071D29] has-[:checked]:text-[#C9F24A] has-[:checked]:border-[#071D29]">
                                <input type="radio" name="preferred_contact" value="WhatsApp" checked class="hidden">
                                <span>WhatsApp</span>
                            </label>
                            <label class="contact-mode-label cursor-pointer text-center py-2 px-2 text-xs font-bold rounded-xl border border-gray-200 bg-white hover:border-[#071D29] transition-all has-[:checked]:bg-[#071D29] has-[:checked]:text-[#C9F24A] has-[:checked]:border-[#071D29]">
                                <input type="radio" name="preferred_contact" value="Phone Call" class="hidden">
                                <span>Phone Call</span>
                            </label>
                            <label class="contact-mode-label cursor-pointer text-center py-2 px-2 text-xs font-bold rounded-xl border border-gray-200 bg-white hover:border-[#071D29] transition-all has-[:checked]:bg-[#071D29] has-[:checked]:text-[#C9F24A] has-[:checked]:border-[#071D29]">
                                <input type="radio" name="preferred_contact" value="Email" class="hidden">
                                <span>Email</span>
                            </label>
                        </div>
                    </div>

                    <div>
                        <label class="block text-xs font-bold text-[#092532] uppercase mb-1">Additional Notes / Milestones</label>
                        <textarea name="message" id="contactMessage" rows="3" placeholder="Tell us briefly about your financial goals or questions..." class="w-full px-4 py-2.5 rounded-xl bg-white border border-gray-200 text-xs sm:text-sm focus:outline-none focus:ring-2 focus:ring-[#071D29]"></textarea>
                    </div>

                    <div class="flex items-start gap-2.5 pt-1">
                        <input type="checkbox" id="contactConsent" required checked class="mt-1 rounded accent-[#071D29]">
                        <label for="contactConsent" class="text-[11px] text-[#607078] leading-tight">
                            I agree to receive financial guidance calls or messages from Moneyguru Financial Services. No spam, ever.
                        </label>
                    </div>

                    <div class="pt-2">
                        <button type="submit" id="contactSubmitBtn" class="w-full flex items-center justify-center gap-2 bg-[#071D29] hover:bg-[#0B2733] text-[#C9F24A] font-extrabold text-sm py-3.5 rounded-2xl transition-all shadow-xl hover:shadow-2xl active:scale-98">
                            <span id="contactBtnText">Request a Consultation</span>
                            <i data-lucide="arrow-up-right" class="w-4 h-4"></i>
                        </button>
                    </div>

                    <div class="text-center pt-1">
                        <span class="text-[11px] text-[#607078] flex items-center justify-center gap-1">
                            <i data-lucide="lock" class="w-3 h-3 text-emerald-600"></i>
                            Your personal information is confidential &amp; never shared.
                        </span>
                    </div>
                </form>

            </div>

        </div>
    </div>
</section>
