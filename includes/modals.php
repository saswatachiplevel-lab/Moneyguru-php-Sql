<!-- ========================================== -->
<!-- 1. SERVICE DETAIL MODAL                     -->
<!-- ========================================== -->
<div id="serviceDetailModal" class="hidden fixed inset-0 z-50 flex items-center justify-center p-4 sm:p-6 overflow-y-auto bg-[#071D29]/80 backdrop-blur-md animate-in fade-in duration-200">
    <div class="relative w-full max-w-2xl bg-white rounded-3xl shadow-2xl overflow-hidden my-8 max-h-[90vh] flex flex-col">
        <!-- Modal Top Bar -->
        <div class="p-5 sm:p-6 bg-[#071D29] text-white flex items-center justify-between shrink-0">
            <div>
                <div class="flex items-center gap-2 mb-1">
                    <span id="modalServiceCategory" class="text-[10px] uppercase font-bold tracking-wider text-[#C9F24A] bg-[#123B43] px-2.5 py-0.5 rounded-full border border-[#C9F24A]/30">Category</span>
                    <span id="modalServiceBadge" class="text-[10px] font-bold bg-[#C9F24A] text-[#071D29] px-2 py-0.5 rounded-full">Badge</span>
                </div>
                <h3 id="modalServiceTitle" class="text-xl sm:text-2xl font-black text-white">Service Title</h3>
            </div>
            <button type="button" onclick="closeServiceDetailModal()" class="w-8 h-8 rounded-full bg-white/10 hover:bg-white/20 text-white flex items-center justify-center transition-colors focus:outline-none">
                <i data-lucide="x" class="w-4 h-4"></i>
            </button>
        </div>

        <!-- Scrollable Modal Body -->
        <div class="p-6 sm:p-8 overflow-y-auto space-y-6 text-[#092532]">
            <img id="modalServiceImage" src="" alt="Service preview" class="w-full h-52 sm:h-64 object-cover rounded-2xl shadow-md bg-gray-100">
            
            <div>
                <h4 id="modalServiceTagline" class="text-base sm:text-lg font-bold text-[#123B43]"></h4>
                <p id="modalServiceFullDesc" class="mt-2 text-xs sm:text-sm text-[#607078] leading-relaxed"></p>
            </div>

            <!-- Benefits -->
            <div>
                <h4 class="text-xs font-bold uppercase tracking-wider text-[#071D29] mb-3">Key Benefits &amp; Features</h4>
                <div id="modalServiceBenefits" class="space-y-2"></div>
            </div>

            <!-- Beginner Tips -->
            <div class="p-4 rounded-2xl bg-[#F7F8F5] border border-gray-200">
                <h4 class="text-xs font-bold uppercase tracking-wider text-[#071D29] mb-2 flex items-center gap-1.5">
                    <i data-lucide="lightbulb" class="w-3.5 h-3.5 text-amber-600"></i>
                    <span>Beginner Guidance</span>
                </h4>
                <div id="modalServiceTips" class="space-y-1.5 text-xs text-[#607078]"></div>
            </div>

            <!-- Considerations -->
            <div class="p-4 rounded-2xl bg-amber-50 border border-amber-200 text-xs text-amber-900">
                <h4 class="font-bold mb-1 flex items-center gap-1.5">
                    <i data-lucide="alert-triangle" class="w-3.5 h-3.5 text-amber-700"></i>
                    <span>Important Considerations &amp; Risk Notes</span>
                </h4>
                <div id="modalServiceConsiderations" class="space-y-1 text-amber-800"></div>
            </div>
        </div>

        <!-- Sticky Footer CTA -->
        <div class="p-4 sm:p-5 bg-gray-50 border-t border-gray-100 flex items-center justify-between gap-3 shrink-0">
            <button type="button" onclick="closeServiceDetailModal()" class="px-5 py-2.5 rounded-full text-xs font-bold text-[#607078] hover:text-[#092532] transition-colors">
                Close
            </button>
            <button type="button" id="modalBookServiceBtn" onclick="bookFromModal()" class="flex items-center gap-2 bg-[#071D29] hover:bg-[#0B2733] text-[#C9F24A] font-bold text-xs sm:text-sm px-6 py-2.5 rounded-full shadow-md transition-all">
                <span>Book Free Advisory For This</span>
                <i data-lucide="arrow-up-right" class="w-3.5 h-3.5"></i>
            </button>
        </div>
    </div>
</div>

<!-- ========================================== -->
<!-- 2. ARTICLE READER MODAL                     -->
<!-- ========================================== -->
<div id="articleReaderModal" class="hidden fixed inset-0 z-50 flex items-center justify-center p-4 sm:p-6 overflow-y-auto bg-[#071D29]/80 backdrop-blur-md animate-in fade-in duration-200">
    <div class="relative w-full max-w-3xl bg-white rounded-3xl shadow-2xl overflow-hidden my-8 max-h-[90vh] flex flex-col">
        <!-- Top Bar -->
        <div class="p-5 sm:p-6 bg-[#071D29] text-white flex items-center justify-between shrink-0">
            <div class="flex items-center gap-3">
                <span id="articleModalCategory" class="text-[10px] font-bold text-[#C9F24A] bg-[#123B43] px-2.5 py-0.5 rounded-full border border-[#C9F24A]/30">Category</span>
                <span id="articleModalReadTime" class="text-xs text-white/70">5 min read</span>
            </div>
            <button type="button" onclick="closeArticleModal()" class="w-8 h-8 rounded-full bg-white/10 hover:bg-white/20 text-white flex items-center justify-center transition-colors">
                <i data-lucide="x" class="w-4 h-4"></i>
            </button>
        </div>

        <!-- Scrollable Content -->
        <div class="p-6 sm:p-10 overflow-y-auto space-y-6 text-[#092532]">
            <div>
                <h2 id="articleModalTitle" class="text-2xl sm:text-3xl font-black text-[#092532] leading-tight">Article Title</h2>
                <div class="mt-3 flex items-center gap-3 text-xs text-[#607078] pb-4 border-b border-gray-100">
                    <span id="articleModalAuthor" class="font-bold text-[#071D29]">Moneyguru Research Team</span>
                    <span>&bull;</span>
                    <span id="articleModalAuthorRole">Investor Education Desk</span>
                    <span>&bull;</span>
                    <span id="articleModalDate">Oct 2026</span>
                </div>
            </div>

            <img id="articleModalImage" src="" alt="Article Banner" class="w-full h-56 sm:h-72 object-cover rounded-2xl shadow-sm bg-gray-100">

            <div id="articleModalBody" class="space-y-4 text-xs sm:text-sm text-[#092532]/85 leading-relaxed"></div>

            <div class="p-5 rounded-2xl bg-[#071D29] text-white flex flex-col sm:flex-row sm:items-center justify-between gap-4 shadow-lg">
                <div>
                    <h4 class="text-sm font-bold text-[#C9F24A]">Want personalized guidance on this strategy?</h4>
                    <p class="text-xs text-white/70 mt-0.5">Talk to Saswata Roy (AMFI ARN-136048) directly.</p>
                </div>
                <button type="button" onclick="closeArticleModal(); openConsultationModal();" class="bg-[#C9F24A] text-[#071D29] font-bold text-xs px-5 py-2.5 rounded-full shrink-0 hover:bg-[#D9F77A] transition-colors">
                    Schedule Free Call
                </button>
            </div>
        </div>
    </div>
</div>

<!-- ========================================== -->
<!-- 3. PARTNER WITH US MODAL                    -->
<!-- ========================================== -->
<div id="partnerModal" class="hidden fixed inset-0 z-50 flex items-center justify-center p-4 sm:p-6 overflow-y-auto bg-[#071D29]/80 backdrop-blur-md animate-in fade-in duration-200">
    <div class="relative w-full max-w-xl bg-white rounded-3xl shadow-2xl overflow-hidden my-8 p-6 sm:p-8">
        <button type="button" onclick="closePartnerModal()" class="absolute top-5 right-5 w-8 h-8 rounded-full bg-gray-100 hover:bg-gray-200 text-gray-700 flex items-center justify-center transition-colors">
            <i data-lucide="x" class="w-4 h-4"></i>
        </button>

        <!-- Success Message -->
        <div id="partnerSuccessBox" class="hidden py-8 text-center animate-in fade-in">
            <div class="w-16 h-16 rounded-full bg-[#C9F24A] text-[#071D29] flex items-center justify-center mx-auto mb-4 shadow-lg">
                <i data-lucide="check" class="w-8 h-8 stroke-[3]"></i>
            </div>
            <h3 class="text-2xl font-extrabold text-[#092532]">Partnership Application Logged</h3>
            <p class="text-xs sm:text-sm text-[#607078] mt-2 max-w-md mx-auto">
                Thank you, <strong id="partnerSuccessName"></strong>. Our institutional and partnership desk will review your proposal and get in touch within 1–2 business days.
            </p>
            <div class="mt-6">
                <button type="button" onclick="closePartnerModal()" class="bg-[#071D29] text-[#C9F24A] font-bold text-xs px-6 py-2.5 rounded-full hover:bg-[#0B2733] transition-colors">
                    Close Window
                </button>
            </div>
        </div>

        <!-- Form -->
        <form id="partnerForm" onsubmit="handlePartnerSubmit(event)" class="space-y-4">
            <div class="flex items-center gap-3">
                <div class="w-10 h-10 rounded-xl bg-[#071D29] text-[#C9F24A] flex items-center justify-center shrink-0">
                    <i data-lucide="handshake" class="w-5 h-5"></i>
                </div>
                <div>
                    <h3 class="text-xl font-extrabold text-[#092532]">Partner With Moneyguru</h3>
                    <p class="text-xs text-[#607078]">Collaborate on wellness workshops, corporate seminars, and client advisory.</p>
                </div>
            </div>

            <!-- Error Box -->
            <div id="partnerErrorBox" class="hidden p-3 rounded-xl bg-red-50 border border-red-200 text-xs text-red-700 flex items-center gap-2">
                <i data-lucide="alert-circle" class="w-4 h-4 shrink-0"></i>
                <span id="partnerErrorMessage">Please check required fields.</span>
            </div>

            <div class="grid grid-cols-1 sm:grid-cols-2 gap-3.5 pt-2">
                <div>
                    <label class="block text-xs font-bold text-[#092532] uppercase mb-1">Full Name *</label>
                    <input type="text" name="full_name" id="partnerName" required placeholder="e.g. Priya Iyer" class="w-full px-3.5 py-2 rounded-xl bg-gray-50 border border-gray-200 text-xs sm:text-sm focus:outline-none focus:ring-2 focus:ring-[#071D29]">
                </div>
                <div>
                    <label class="block text-xs font-bold text-[#092532] uppercase mb-1">Organization / Firm</label>
                    <input type="text" name="organization" id="partnerOrg" placeholder="e.g. TechCorp / FinLegal" class="w-full px-3.5 py-2 rounded-xl bg-gray-50 border border-gray-200 text-xs sm:text-sm focus:outline-none focus:ring-2 focus:ring-[#071D29]">
                </div>
            </div>

            <div class="grid grid-cols-1 sm:grid-cols-2 gap-3.5">
                <div>
                    <label class="block text-xs font-bold text-[#092532] uppercase mb-1">Business Email *</label>
                    <input type="email" name="email" id="partnerEmail" required placeholder="priya@company.com" class="w-full px-3.5 py-2 rounded-xl bg-gray-50 border border-gray-200 text-xs sm:text-sm focus:outline-none focus:ring-2 focus:ring-[#071D29]">
                </div>
                <div>
                    <label class="block text-xs font-bold text-[#092532] uppercase mb-1">Phone Number *</label>
                    <input type="tel" name="phone" id="partnerPhone" required placeholder="+91 98765 00000" class="w-full px-3.5 py-2 rounded-xl bg-gray-50 border border-gray-200 text-xs sm:text-sm focus:outline-none focus:ring-2 focus:ring-[#071D29]">
                </div>
            </div>

            <div>
                <label class="block text-xs font-bold text-[#092532] uppercase mb-1">Partnership Category</label>
                <select name="partnership_type" id="partnerType" class="w-full px-3.5 py-2 rounded-xl bg-gray-50 border border-gray-200 text-xs sm:text-sm focus:outline-none focus:ring-2 focus:ring-[#071D29]">
                    <option value="Referral Partner">Referral Partner / Affiliate</option>
                    <option value="Corporate Wellness">Corporate Employee Financial Wellness</option>
                    <option value="Financial Advisory Associate">Financial Advisory Associate (CA / Tax Advisor)</option>
                    <option value="Strategic Distribution">Strategic Distribution Network</option>
                    <option value="Other">Other Strategic Opportunity</option>
                </select>
            </div>

            <div>
                <label class="block text-xs font-bold text-[#092532] uppercase mb-1">Proposal / Message</label>
                <textarea name="message" id="partnerMessage" rows="3" placeholder="Briefly describe how you would like to collaborate..." class="w-full px-3.5 py-2 rounded-xl bg-gray-50 border border-gray-200 text-xs sm:text-sm focus:outline-none focus:ring-2 focus:ring-[#071D29]"></textarea>
            </div>

            <div class="pt-2">
                <button type="submit" id="partnerSubmitBtn" class="w-full flex items-center justify-center gap-2 bg-[#071D29] hover:bg-[#0B2733] text-[#C9F24A] font-bold text-xs sm:text-sm py-3 rounded-xl transition-all shadow-md">
                    <span id="partnerBtnText">Submit Partnership Proposal</span>
                    <i data-lucide="arrow-up-right" class="w-4 h-4"></i>
                </button>
            </div>

            <div class="flex items-center justify-center gap-1.5 text-[11px] text-[#607078]">
                <i data-lucide="lock" class="w-3.5 h-3.5 text-[#123B43]"></i>
                <span>Strictly confidential and non-binding preliminary inquiry.</span>
            </div>
        </form>
    </div>
</div>

<!-- ========================================== -->
<!-- 4. QUICK CONSULTATION BOOKING MODAL         -->
<!-- ========================================== -->
<div id="consultationModal" class="hidden fixed inset-0 z-50 flex items-center justify-center p-4 sm:p-6 overflow-y-auto bg-[#071D29]/80 backdrop-blur-md animate-in fade-in duration-200">
    <div class="relative w-full max-w-xl bg-white rounded-3xl shadow-2xl overflow-hidden my-8 p-6 sm:p-8">
        <button type="button" onclick="closeConsultationModal()" class="absolute top-5 right-5 w-8 h-8 rounded-full bg-gray-100 hover:bg-gray-200 text-gray-700 flex items-center justify-center transition-colors">
            <i data-lucide="x" class="w-4 h-4"></i>
        </button>

        <div id="modalBookingSuccess" class="hidden py-8 text-center animate-in fade-in">
            <div class="w-16 h-16 rounded-full bg-[#C9F24A] text-[#071D29] flex items-center justify-center mx-auto mb-4 shadow-lg">
                <i data-lucide="check" class="w-8 h-8 stroke-[3]"></i>
            </div>
            <h3 class="text-2xl font-extrabold text-[#092532]">Booking Confirmed!</h3>
            <p class="text-xs sm:text-sm text-[#607078] mt-2 max-w-md mx-auto">
                Saswata Roy (ARN-136048) will contact you shortly to confirm your preferred discussion time.
            </p>
            <div class="mt-4 p-2.5 rounded-xl bg-gray-100 inline-block font-mono text-xs text-[#071D29]">
                Booking Ref: <span id="modalSuccessRef" class="font-bold text-[#123B43]">MG-00000</span>
            </div>
            <div class="mt-6">
                <button type="button" onclick="closeConsultationModal()" class="bg-[#071D29] text-[#C9F24A] font-bold text-xs px-6 py-2.5 rounded-full hover:bg-[#0B2733] transition-colors">
                    Close
                </button>
            </div>
        </div>

        <form id="modalBookingForm" onsubmit="handleModalConsultationSubmit(event)" class="space-y-4">
            <div class="flex items-center gap-3">
                <div class="w-10 h-10 rounded-xl bg-[#071D29] text-[#C9F24A] flex items-center justify-center shrink-0">
                    <i data-lucide="calendar" class="w-5 h-5"></i>
                </div>
                <div>
                    <h3 class="text-xl font-extrabold text-[#092532]">Schedule a Discussion</h3>
                    <p class="text-xs text-[#607078]">Free 1-on-1 consultation with AMFI-Registered Distributor.</p>
                </div>
            </div>

            <div id="modalBookingError" class="hidden p-3 rounded-xl bg-red-50 border border-red-200 text-xs text-red-700 flex items-center gap-2">
                <i data-lucide="alert-circle" class="w-4 h-4 shrink-0"></i>
                <span id="modalBookingErrorMsg">Please check required fields.</span>
            </div>

            <div class="grid grid-cols-1 sm:grid-cols-2 gap-3.5 pt-2">
                <div>
                    <label class="block text-xs font-bold text-[#092532] uppercase mb-1">Your Name *</label>
                    <input type="text" name="name" id="modalFormName" required placeholder="Full Name" class="w-full px-3.5 py-2 rounded-xl bg-gray-50 border border-gray-200 text-xs sm:text-sm focus:outline-none focus:ring-2 focus:ring-[#071D29]">
                </div>
                <div>
                    <label class="block text-xs font-bold text-[#092532] uppercase mb-1">Phone / WhatsApp *</label>
                    <input type="tel" name="phone" id="modalFormPhone" required placeholder="+91 98765 43210" class="w-full px-3.5 py-2 rounded-xl bg-gray-50 border border-gray-200 text-xs sm:text-sm focus:outline-none focus:ring-2 focus:ring-[#071D29]">
                </div>
            </div>

            <div class="grid grid-cols-1 sm:grid-cols-2 gap-3.5">
                <div>
                    <label class="block text-xs font-bold text-[#092532] uppercase mb-1">Email Address *</label>
                    <input type="email" name="email" id="modalFormEmail" required placeholder="you@example.com" class="w-full px-3.5 py-2 rounded-xl bg-gray-50 border border-gray-200 text-xs sm:text-sm focus:outline-none focus:ring-2 focus:ring-[#071D29]">
                </div>
                <div>
                    <label class="block text-xs font-bold text-[#092532] uppercase mb-1">Financial Topic</label>
                    <select name="service" id="modalFormService" class="w-full px-3.5 py-2 rounded-xl bg-gray-50 border border-gray-200 text-xs sm:text-sm focus:outline-none focus:ring-2 focus:ring-[#071D29]">
                        <option value="Mutual Funds & SIP">Mutual Funds &amp; Systematic SIPs</option>
                        <option value="Life Insurance (Pure Term)">Pure Term Life Insurance</option>
                        <option value="Health Insurance">Comprehensive Health Insurance</option>
                        <option value="Loans & Mortgages">Home Loans &amp; Balance Transfer</option>
                        <option value="Credit Cards">Credit Cards &amp; Utilization</option>
                        <option value="Stocks & Equities">Direct Stocks &amp; Equity Basics</option>
                        <option value="Bonds & Fixed Income">Bonds &amp; Sovereign Gold Bonds</option>
                        <option value="General Financial Guidance" selected>General Financial Guidance</option>
                    </select>
                </div>
            </div>

            <div>
                <label class="block text-xs font-bold text-[#092532] uppercase mb-1">Preferred Contact Method</label>
                <div class="grid grid-cols-3 gap-2">
                    <label class="cursor-pointer text-center py-2 px-2 text-xs font-bold rounded-xl border border-gray-200 bg-gray-50 has-[:checked]:bg-[#071D29] has-[:checked]:text-[#C9F24A]">
                        <input type="radio" name="modal_preferred_contact" value="WhatsApp" checked class="hidden">
                        <span>WhatsApp</span>
                    </label>
                    <label class="cursor-pointer text-center py-2 px-2 text-xs font-bold rounded-xl border border-gray-200 bg-gray-50 has-[:checked]:bg-[#071D29] has-[:checked]:text-[#C9F24A]">
                        <input type="radio" name="modal_preferred_contact" value="Phone Call" class="hidden">
                        <span>Phone</span>
                    </label>
                    <label class="cursor-pointer text-center py-2 px-2 text-xs font-bold rounded-xl border border-gray-200 bg-gray-50 has-[:checked]:bg-[#071D29] has-[:checked]:text-[#C9F24A]">
                        <input type="radio" name="modal_preferred_contact" value="Email" class="hidden">
                        <span>Email</span>
                    </label>
                </div>
            </div>

            <div>
                <label class="block text-xs font-bold text-[#092532] uppercase mb-1">Your Questions or Notes (Optional)</label>
                <textarea name="message" id="modalFormMessage" rows="2" placeholder="Brief note about your timeline or questions..." class="w-full px-3.5 py-2 rounded-xl bg-gray-50 border border-gray-200 text-xs sm:text-sm focus:outline-none focus:ring-2 focus:ring-[#071D29]"></textarea>
            </div>

            <div class="pt-2">
                <button type="submit" id="modalFormSubmitBtn" class="w-full flex items-center justify-center gap-2 bg-[#071D29] hover:bg-[#0B2733] text-[#C9F24A] font-bold text-xs sm:text-sm py-3 rounded-xl transition-all shadow-md">
                    <span id="modalFormBtnText">Confirm Free Consultation</span>
                    <i data-lucide="arrow-up-right" class="w-4 h-4"></i>
                </button>
            </div>
        </form>
    </div>
</div>

<!-- ========================================== -->
<!-- 5. INVESTOR / CLIENT PORTAL MODAL          -->
<!-- ========================================== -->
<div id="portalModal" class="hidden fixed inset-0 z-50 flex items-center justify-center p-4 sm:p-6 overflow-y-auto bg-[#071D29]/80 backdrop-blur-md animate-in fade-in duration-200">
    <div class="relative w-full max-w-2xl bg-white rounded-3xl shadow-2xl overflow-hidden my-8 max-h-[90vh] flex flex-col">
        <div class="bg-[#071D29] p-6 text-white flex items-center justify-between shrink-0">
            <div class="flex items-center gap-3">
                <img src="assets/img/logo.svg" alt="Moneyguru" class="w-9 h-9 rounded-full">
                <div>
                    <h3 class="text-lg font-bold">Moneyguru Client Portal</h3>
                    <p class="text-xs text-white/60">Lookup your inquiries &amp; consultation history</p>
                </div>
            </div>
            <button type="button" onclick="closePortalModal()" class="w-8 h-8 rounded-full bg-white/10 hover:bg-white/20 text-white flex items-center justify-center transition-colors">
                <i data-lucide="x" class="w-4 h-4"></i>
            </button>
        </div>

        <div class="p-6 sm:p-8 overflow-y-auto space-y-6 text-[#092532]">
            <!-- Search Lookup Form -->
            <div class="p-4 rounded-2xl bg-[#F7F8F5] border border-gray-200">
                <h4 class="text-xs font-bold uppercase tracking-wider text-[#071D29] mb-2">Check My Consultation Bookings</h4>
                <div class="flex gap-2">
                    <input type="text" id="portalIdentifierInput" placeholder="Enter your registered Email or Phone..." class="flex-1 px-3.5 py-2 rounded-xl bg-white border border-gray-200 text-xs sm:text-sm focus:outline-none focus:ring-2 focus:ring-[#071D29]">
                    <button type="button" onclick="lookupClientConsultations()" class="bg-[#071D29] hover:bg-[#0B2733] text-[#C9F24A] font-bold text-xs px-5 py-2 rounded-xl transition-all">
                        Lookup
                    </button>
                </div>
            </div>

            <!-- Results Container -->
            <div id="portalResultsContainer" class="space-y-3">
                <div class="p-8 text-center text-xs text-[#607078] border border-dashed border-gray-200 rounded-2xl">
                    <i data-lucide="user-check" class="w-8 h-8 text-gray-400 mx-auto mb-2"></i>
                    Enter your email or phone number above to view all consultation requests and progress status logged in our system.
                </div>
            </div>
        </div>
    </div>
</div>

<!-- ========================================== -->
<!-- 6. LEGAL & REGULATORY POLICIES MODAL        -->
<!-- ========================================== -->
<div id="legalModal" class="hidden fixed inset-0 z-50 flex items-center justify-center p-4 sm:p-6 overflow-y-auto bg-[#071D29]/80 backdrop-blur-md animate-in fade-in duration-200">
    <div class="relative w-full max-w-3xl bg-white rounded-3xl shadow-2xl overflow-hidden my-8 max-h-[85vh] flex flex-col">
        <div class="p-6 bg-[#071D29] text-white flex items-center justify-between shrink-0">
            <div>
                <h3 id="legalModalTitle" class="text-lg font-bold">Legal Document</h3>
                <p class="text-[11px] text-white/60">Moneyguru Financial Services • Saswata Roy (ARN- 136048)</p>
            </div>
            <button type="button" onclick="closeLegalModal()" class="w-8 h-8 rounded-full bg-white/10 hover:bg-white/20 text-white flex items-center justify-center transition-colors">
                <i data-lucide="x" class="w-4 h-4"></i>
            </button>
        </div>

        <div id="legalModalBody" class="p-6 sm:p-8 overflow-y-auto space-y-4 text-xs sm:text-sm text-[#607078] leading-relaxed">
            <!-- Dynamically populated via JS depending on 'privacy', 'terms', or 'disclaimer' -->
        </div>

        <div class="p-4 bg-gray-50 border-t border-gray-100 text-right shrink-0">
            <button type="button" onclick="closeLegalModal()" class="bg-[#071D29] text-[#C9F24A] font-bold text-xs px-6 py-2 rounded-full">
                Close
            </button>
        </div>
    </div>
</div>
