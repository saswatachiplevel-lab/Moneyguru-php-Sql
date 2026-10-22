/**
 * Moneyguru Financial Services - Interactive Client Logic
 */

// Embed full datasets for instant client-side modal rendering
let cachedServices = {};
let cachedArticles = {};

// Fetch or initialize datasets
document.addEventListener('DOMContentLoaded', () => {
    // Load services & articles from server or embedded store
    fetch('services.json')
        .then(res => res.json())
        .then(data => {
            data.forEach(s => { cachedServices[s.id] = s; });
        }).catch(err => console.log('Services loaded from DOM.'));

    fetch('articles.json')
        .then(res => res.json())
        .then(data => {
            data.forEach(a => { cachedArticles[a.id] = a; });
        }).catch(err => console.log('Articles loaded from DOM.'));

    // Initialize Calculators
    calculateSip();
    calculateEmi();

    // Start Market Ticker random simulation
    startTickerSimulation();

    // Clean up any legacy localStorage demo photo override
    cleanupLegacyPhotoCache();

    // Scroll listener for Navbar
    window.addEventListener('scroll', handleNavbarScroll);
});

// ==========================================
// 1. NAVBAR & MOBILE MENU
// ==========================================
function handleNavbarScroll() {
    const navContainer = document.getElementById('navContainer');
    if (!navContainer) return;

    if (window.scrollY > 40) {
        navContainer.className = "flex items-center justify-between px-5 py-3 transition-all duration-300 rounded-full bg-[#071D29]/90 backdrop-blur-xl border border-white/10 shadow-2xl shadow-black/40";
    } else {
        navContainer.className = "flex items-center justify-between px-5 py-3 transition-all duration-300 rounded-full bg-[#0B2733]/65 backdrop-blur-md border border-white/15";
    }
}

function toggleMobileMenu() {
    const menu = document.getElementById('mobileMenu');
    const hamburger = document.getElementById('hamburgerIcon');
    const closeIcon = document.getElementById('closeIcon');
    if (!menu) return;

    const isHidden = menu.classList.contains('hidden');
    if (isHidden) {
        menu.classList.remove('hidden');
        hamburger.classList.add('hidden');
        closeIcon.classList.remove('hidden');
    } else {
        menu.classList.add('hidden');
        hamburger.classList.remove('hidden');
        closeIcon.classList.add('hidden');
    }
}

function closeMobileMenu() {
    const menu = document.getElementById('mobileMenu');
    const hamburger = document.getElementById('hamburgerIcon');
    const closeIcon = document.getElementById('closeIcon');
    if (menu) menu.classList.add('hidden');
    if (hamburger) hamburger.classList.remove('hidden');
    if (closeIcon) closeIcon.classList.add('hidden');
}

// ==========================================
// 2. HERO MOCKUP TABS
// ==========================================
function switchHeroTab(tab) {
    const allocBtn = document.getElementById('tabAllocationBtn');
    const goalsBtn = document.getElementById('tabGoalsBtn');
    const allocView = document.getElementById('heroTabAllocation');
    const goalsView = document.getElementById('heroTabGoals');

    if (tab === 'allocation') {
        allocBtn.className = "flex-1 py-1.5 text-xs font-semibold rounded-lg transition-all bg-[#C9F24A] text-[#071D29] shadow-sm";
        goalsBtn.className = "flex-1 py-1.5 text-xs font-semibold rounded-lg transition-all text-white/60 hover:text-white";
        allocView.classList.remove('hidden');
        goalsView.classList.add('hidden');
    } else {
        goalsBtn.className = "flex-1 py-1.5 text-xs font-semibold rounded-lg transition-all bg-[#C9F24A] text-[#071D29] shadow-sm";
        allocBtn.className = "flex-1 py-1.5 text-xs font-semibold rounded-lg transition-all text-white/60 hover:text-white";
        goalsView.classList.remove('hidden');
        allocView.classList.add('hidden');
    }
}

// ==========================================
// 3. MARKET TICKER SIMULATION
// ==========================================
function startTickerSimulation() {
    setInterval(() => {
        const items = document.querySelectorAll('.ticker-item');
        if (!items || items.length === 0) return;

        const randIndex = Math.floor(Math.random() * items.length);
        const item = items[randIndex];
        const valEl = item.querySelector('.ticker-value');
        const chgEl = item.querySelector('.ticker-change');
        if (!valEl || !chgEl) return;

        // Subtle tick
        item.classList.add('bg-white/15');
        setTimeout(() => item.classList.remove('bg-white/15'), 1000);
    }, 3500);
}

// ==========================================
// 4. SERVICES CATEGORY FILTER
// ==========================================
function filterServices(group) {
    const buttons = document.querySelectorAll('.svc-filter-btn');
    buttons.forEach(btn => {
        btn.className = "svc-filter-btn px-5 py-2 rounded-full text-xs sm:text-sm font-bold transition-all text-[#607078] hover:text-[#092532]";
    });

    const activeMap = {
        'all': 'svcFilterAll',
        'invest': 'svcFilterInvest',
        'protect': 'svcFilterProtect',
        'borrow': 'svcFilterBorrow'
    };
    const activeBtn = document.getElementById(activeMap[group]);
    if (activeBtn) {
        activeBtn.className = "svc-filter-btn px-5 py-2 rounded-full text-xs sm:text-sm font-bold transition-all bg-[#071D29] text-white shadow-md";
    }

    const cards = document.querySelectorAll('.service-card');
    cards.forEach(card => {
        if (group === 'all' || card.getAttribute('data-group') === group) {
            card.classList.remove('hidden');
        } else {
            card.classList.add('hidden');
        }
    });
}

// ==========================================
// 5. SERVICE DETAIL MODAL
// ==========================================
function openServiceDetailModal(serviceId) {
    const svc = cachedServices[serviceId];
    if (!svc) {
        // Fallback: search card
        console.warn('Service not yet cached. ID:', serviceId);
    }

    document.getElementById('modalServiceCategory').innerText = svc ? svc.category : 'Financial Service';
    document.getElementById('modalServiceBadge').innerText = (svc && svc.badge) ? svc.badge : 'Recommended';
    document.getElementById('modalServiceTitle').innerText = svc ? svc.title : serviceId;
    document.getElementById('modalServiceImage').src = svc ? svc.imageUrl : 'https://images.unsplash.com/photo-1590283603385-17ffb3a7f29f?auto=format&fit=crop&w=800&q=80';
    document.getElementById('modalServiceTagline').innerText = svc ? svc.tagline : '';
    document.getElementById('modalServiceFullDesc').innerText = svc ? (svc.fullDesc || svc.shortDesc) : '';

    // Render benefits
    const benefitsBox = document.getElementById('modalServiceBenefits');
    benefitsBox.innerHTML = '';
    const benefits = (svc && svc.benefits) ? svc.benefits : ['Personalized goal orientation', 'Transparent fee structure', 'Regulated financial instruments'];
    benefits.forEach(b => {
        const item = document.createElement('div');
        item.className = "flex items-start gap-2.5 text-xs sm:text-sm text-[#092532]";
        item.innerHTML = `<i data-lucide="check-circle-2" class="w-4 h-4 text-emerald-600 shrink-0 mt-0.5"></i><span>${escapeHtml(b)}</span>`;
        benefitsBox.appendChild(item);
    });

    // Render beginner tips
    const tipsBox = document.getElementById('modalServiceTips');
    tipsBox.innerHTML = '';
    const tips = (svc && (svc.beginnerTips || svc.beginner_tips)) ? (svc.beginnerTips || svc.beginner_tips) : ['Start with manageable allocations', 'Focus on long-term compound growth'];
    tips.forEach(t => {
        const tipEl = document.createElement('p');
        tipEl.innerHTML = `&bull; ${escapeHtml(t)}`;
        tipsBox.appendChild(tipEl);
    });

    // Render considerations
    const considBox = document.getElementById('modalServiceConsiderations');
    considBox.innerHTML = '';
    const consids = (svc && (svc.keyConsiderations || svc.key_considerations)) ? (svc.keyConsiderations || svc.key_considerations) : ['Investments are subject to market risks; read scheme documents carefully.'];
    consids.forEach(c => {
        const cEl = document.createElement('p');
        cEl.innerHTML = `&bull; ${escapeHtml(c)}`;
        considBox.appendChild(cEl);
    });

    // Store selected title for booking
    window.currentModalService = svc ? svc.title : serviceId;

    document.getElementById('serviceDetailModal').classList.remove('hidden');
    if (typeof lucide !== 'undefined') lucide.createIcons();
}

function closeServiceDetailModal() {
    document.getElementById('serviceDetailModal').classList.add('hidden');
}

function bookFromModal() {
    closeServiceDetailModal();
    bookForService(window.currentModalService || 'Mutual Funds');
}

function bookForService(serviceName) {
    const serviceDropdown = document.getElementById('contactService');
    if (serviceDropdown) {
        // Look for matching option
        for (let i = 0; i < serviceDropdown.options.length; i++) {
            if (serviceDropdown.options[i].text.toLowerCase().includes(serviceName.toLowerCase()) || 
                serviceDropdown.options[i].value.toLowerCase().includes(serviceName.toLowerCase())) {
                serviceDropdown.selectedIndex = i;
                break;
            }
        }
    }
    const contactSec = document.getElementById('contact');
    if (contactSec) {
        contactSec.scrollIntoView({ behavior: 'smooth' });
    }
}

// ==========================================
// 6. ARTICLE READER MODAL
// ==========================================
function openArticleModal(articleId) {
    const post = cachedArticles[articleId];
    if (!post) {
        console.warn('Article not cached:', articleId);
        return;
    }

    document.getElementById('articleModalCategory').innerText = post.category || 'Article';
    document.getElementById('articleModalReadTime').innerText = post.readTime || '5 min read';
    document.getElementById('articleModalTitle').innerText = post.title;
    document.getElementById('articleModalAuthor').innerText = post.author || 'Moneyguru Research';
    document.getElementById('articleModalAuthorRole').innerText = post.authorRole || 'Wealth Advisory';
    document.getElementById('articleModalDate').innerText = post.publishedDate || 'Updated Oct 2026';
    document.getElementById('articleModalImage').src = post.imageUrl || '';

    const bodyContainer = document.getElementById('articleModalBody');
    bodyContainer.innerHTML = '';

    const paras = Array.isArray(post.content) ? post.content : [post.content || post.excerpt];
    paras.forEach(p => {
        const para = document.createElement('p');
        para.className = "text-xs sm:text-sm text-[#092532]/85 leading-relaxed";
        para.innerText = p;
        bodyContainer.appendChild(para);
    });

    document.getElementById('articleReaderModal').classList.remove('hidden');
    if (typeof lucide !== 'undefined') lucide.createIcons();
}

function closeArticleModal() {
    document.getElementById('articleReaderModal').classList.add('hidden');
}

// ==========================================
// 7. PARTNER MODAL
// ==========================================
function openPartnerModal() {
    document.getElementById('partnerSuccessBox').classList.add('hidden');
    document.getElementById('partnerForm').classList.remove('hidden');
    document.getElementById('partnerErrorBox').classList.add('hidden');
    document.getElementById('partnerModal').classList.remove('hidden');
    if (typeof lucide !== 'undefined') lucide.createIcons();
}

function closePartnerModal() {
    document.getElementById('partnerModal').classList.add('hidden');
}

function handlePartnerSubmit(e) {
    e.preventDefault();
    const form = e.target;
    const btn = document.getElementById('partnerSubmitBtn');
    const btnText = document.getElementById('partnerBtnText');
    const errorBox = document.getElementById('partnerErrorBox');
    const errorMsg = document.getElementById('partnerErrorMessage');

    errorBox.classList.add('hidden');
    btnText.innerText = "Submitting Application...";
    btn.disabled = true;

    const formData = new FormData(form);

    fetch('api/partner_inquiry.php', {
        method: 'POST',
        body: formData
    })
    .then(res => res.json())
    .then(data => {
        btnText.innerText = "Submit Partnership Proposal";
        btn.disabled = false;
        if (data.success) {
            document.getElementById('partnerSuccessName').innerText = formData.get('full_name');
            document.getElementById('partnerForm').classList.add('hidden');
            document.getElementById('partnerSuccessBox').classList.remove('hidden');
            form.reset();
        } else {
            errorMsg.innerText = data.error || "Failed to submit proposal.";
            errorBox.classList.remove('hidden');
        }
    })
    .catch(err => {
        btnText.innerText = "Submit Partnership Proposal";
        btn.disabled = false;
        errorMsg.innerText = "Network error. Please try again.";
        errorBox.classList.remove('hidden');
    });
}

// ==========================================
// 8. QUICK CONSULTATION MODAL
// ==========================================
function openConsultationModal() {
    document.getElementById('modalBookingSuccess').classList.add('hidden');
    document.getElementById('modalBookingForm').classList.remove('hidden');
    document.getElementById('modalBookingError').classList.add('hidden');
    document.getElementById('consultationModal').classList.remove('hidden');
    if (typeof lucide !== 'undefined') lucide.createIcons();
}

function closeConsultationModal() {
    document.getElementById('consultationModal').classList.add('hidden');
}

function handleModalConsultationSubmit(e) {
    e.preventDefault();
    const form = e.target;
    const btn = document.getElementById('modalFormSubmitBtn');
    const btnText = document.getElementById('modalFormBtnText');
    const errorBox = document.getElementById('modalBookingError');
    const errorMsg = document.getElementById('modalBookingErrorMsg');

    errorBox.classList.add('hidden');
    btnText.innerText = "Scheduling...";
    btn.disabled = true;

    const formData = new FormData(form);
    const prefContact = form.querySelector('input[name="modal_preferred_contact"]:checked');
    if (prefContact) formData.set('preferred_contact', prefContact.value);

    fetch('api/book_consultation.php', {
        method: 'POST',
        body: formData
    })
    .then(res => res.json())
    .then(data => {
        btnText.innerText = "Confirm Free Consultation";
        btn.disabled = false;
        if (data.success) {
            document.getElementById('modalSuccessRef').innerText = data.booking_reference || 'MG-00001';
            document.getElementById('modalBookingForm').classList.add('hidden');
            document.getElementById('modalBookingSuccess').classList.remove('hidden');
            form.reset();
        } else {
            errorMsg.innerText = data.error || "Could not book. Check inputs.";
            errorBox.classList.remove('hidden');
        }
    })
    .catch(err => {
        btnText.innerText = "Confirm Free Consultation";
        btn.disabled = false;
        errorMsg.innerText = "Server error. Please try again.";
        errorBox.classList.remove('hidden');
    });
}

// ==========================================
// 9. CLIENT / INVESTOR PORTAL
// ==========================================
function openPortalModal() {
    document.getElementById('portalModal').classList.remove('hidden');
    if (typeof lucide !== 'undefined') lucide.createIcons();
}

function closePortalModal() {
    document.getElementById('portalModal').classList.add('hidden');
}

function lookupClientConsultations() {
    const input = document.getElementById('portalIdentifierInput');
    const container = document.getElementById('portalResultsContainer');
    const val = input.value.trim();

    if (!val || val.length < 3) {
        alert("Please enter a valid email or phone number.");
        return;
    }

    container.innerHTML = `<div class="p-6 text-center text-xs text-gray-500">Searching records for ${escapeHtml(val)}...</div>`;

    fetch(`api/get_my_consultations.php?identifier=${encodeURIComponent(val)}`)
        .then(res => res.json())
        .then(data => {
            if (data.success && data.consultations && data.consultations.length > 0) {
                container.innerHTML = '';
                data.consultations.forEach(c => {
                    const row = document.createElement('div');
                    row.className = "p-4 rounded-xl bg-white border border-gray-200 shadow-sm flex flex-col sm:flex-row sm:items-center justify-between gap-3";
                    
                    const statusClass = c.status === 'Completed' ? 'bg-emerald-100 text-emerald-800' : 
                                      (c.status === 'Scheduled' ? 'bg-blue-100 text-blue-800' : 'bg-[#C9F24A]/40 text-[#071D29]');

                    row.innerHTML = `
                        <div>
                            <div class="flex items-center gap-2">
                                <span class="text-xs font-bold text-[#092532]">${escapeHtml(c.service)}</span>
                                <span class="text-[10px] font-semibold ${statusClass} px-2 py-0.5 rounded-full capitalize">${escapeHtml(c.status)}</span>
                            </div>
                            <p class="text-[11px] text-[#607078] mt-1">
                                Mode: <strong>${escapeHtml(c.preferred_contact)}</strong> &bull; Booked on: ${new Date(c.created_at).toLocaleDateString()}
                            </p>
                        </div>
                        <div class="text-[11px] font-mono text-gray-400">
                            Ref #MG-${String(c.id).padStart(5, '0')}
                        </div>
                    `;
                    container.appendChild(row);
                });
            } else {
                container.innerHTML = `
                    <div class="p-6 text-center text-xs text-[#607078] border border-dashed border-gray-200 rounded-2xl">
                        <p class="font-bold text-[#092532]">No consultation requests found for "${escapeHtml(val)}"</p>
                        <p class="mt-1">Would you like to schedule your first introductory consultation session?</p>
                        <button type="button" onclick="closePortalModal(); openConsultationModal();" class="mt-3 text-xs font-bold text-[#071D29] bg-[#C9F24A] px-4 py-2 rounded-full">
                            Book Free Session
                        </button>
                    </div>
                `;
            }
        })
        .catch(err => {
            container.innerHTML = `<div class="p-6 text-center text-xs text-red-500">Error retrieving inquiries. Please check connection.</div>`;
        });
}

// ==========================================
// 10. MAIN CONTACT SECTION BOOKING FORM
// ==========================================
function handleConsultationSubmit(e) {
    e.preventDefault();
    const form = e.target;
    const btn = document.getElementById('contactSubmitBtn');
    const btnText = document.getElementById('contactBtnText');
    const errorBox = document.getElementById('bookingErrorBox');
    const errorMsg = document.getElementById('bookingErrorMessage');

    errorBox.classList.add('hidden');
    btnText.innerText = "Booking Session...";
    btn.disabled = true;

    const formData = new FormData(form);

    fetch('api/book_consultation.php', {
        method: 'POST',
        body: formData
    })
    .then(res => res.json())
    .then(data => {
        btnText.innerText = "Request a Consultation";
        btn.disabled = false;
        if (data.success) {
            document.getElementById('successClientName').innerText = formData.get('name');
            document.getElementById('successClientContactMode').innerText = formData.get('preferred_contact') || 'WhatsApp';
            document.getElementById('successBookingRef').innerText = data.booking_reference || 'MG-00001';
            
            document.getElementById('consultationForm').classList.add('hidden');
            document.getElementById('bookingSuccessBox').classList.remove('hidden');
            form.reset();
        } else {
            errorMsg.innerText = data.error || "Please check all required fields.";
            errorBox.classList.remove('hidden');
        }
    })
    .catch(err => {
        btnText.innerText = "Request a Consultation";
        btn.disabled = false;
        errorMsg.innerText = "Network failure. Please retry.";
        errorBox.classList.remove('hidden');
    });
}

function resetBookingForm() {
    document.getElementById('bookingSuccessBox').classList.add('hidden');
    document.getElementById('consultationForm').classList.remove('hidden');
}

// ==========================================
// 11. CALCULATORS (SIP & LOAN EMI)
// ==========================================
function switchCalculator(type) {
    const sipBtn = document.getElementById('calcTabSip');
    const emiBtn = document.getElementById('calcTabEmi');
    const sipView = document.getElementById('sipCalcView');
    const emiView = document.getElementById('emiCalcView');

    if (type === 'sip') {
        sipBtn.className = "flex items-center gap-2 px-5 py-2 rounded-full text-xs font-bold transition-all bg-[#071D29] text-white shadow-md";
        emiBtn.className = "flex items-center gap-2 px-5 py-2 rounded-full text-xs font-bold transition-all text-[#607078] hover:text-[#092532]";
        sipView.classList.remove('hidden');
        emiView.classList.add('hidden');
    } else {
        emiBtn.className = "flex items-center gap-2 px-5 py-2 rounded-full text-xs font-bold transition-all bg-[#071D29] text-white shadow-md";
        sipBtn.className = "flex items-center gap-2 px-5 py-2 rounded-full text-xs font-bold transition-all text-[#607078] hover:text-[#092532]";
        emiView.classList.remove('hidden');
        sipView.classList.add('hidden');
    }
}

function formatINR(num) {
    return new Intl.NumberFormat('en-IN', {
        style: 'currency',
        currency: 'INR',
        maximumFractionDigits: 0
    }).format(num);
}

function calculateSip() {
    const amountSlider = document.getElementById('sipAmountSlider');
    const yearsSlider = document.getElementById('sipYearsSlider');
    const rateSlider = document.getElementById('sipRateSlider');

    if (!amountSlider) return;

    const P = Number(amountSlider.value);
    const Y = Number(yearsSlider.value);
    const R = Number(rateSlider.value);

    // Update displays
    document.getElementById('sipAmountDisplay').innerText = formatINR(P);
    document.getElementById('sipYearsDisplay').innerText = Y + " Years";
    document.getElementById('sipRateDisplay').innerText = R + "% p.a.";

    const n = Y * 12; // total months
    const i = R / 12 / 100; // monthly return rate

    const invested = P * n;
    const futureValue = P * (((Math.pow(1 + i, n) - 1) / i) * (1 + i));
    const estimatedGains = futureValue - invested;

    document.getElementById('sipTotalValue').innerText = formatINR(futureValue);
    document.getElementById('sipInvestedAmount').innerText = formatINR(invested);
    document.getElementById('sipGainAmount').innerText = formatINR(estimatedGains);
}

function calculateEmi() {
    const pSlider = document.getElementById('emiPrincipalSlider');
    const ySlider = document.getElementById('emiYearsSlider');
    const rSlider = document.getElementById('emiRateSlider');

    if (!pSlider) return;

    const P = Number(pSlider.value);
    const Y = Number(ySlider.value);
    const R = Number(rSlider.value);

    document.getElementById('emiPrincipalDisplay').innerText = formatINR(P);
    document.getElementById('emiYearsDisplay').innerText = Y + " Years";
    document.getElementById('emiRateDisplay').innerText = R + "%";

    const n = Y * 12; // total months
    const r = R / 12 / 100; // monthly rate

    const emi = (P * r * Math.pow(1 + r, n)) / (Math.pow(1 + r, n) - 1);
    const totalPayment = emi * n;
    const totalInterest = totalPayment - P;

    document.getElementById('emiMonthlyAmount').innerText = formatINR(emi);
    document.getElementById('emiPrincipalSummary').innerText = formatINR(P);
    document.getElementById('emiTotalInterest').innerText = formatINR(totalInterest);
    document.getElementById('emiTotalPayment').innerText = formatINR(totalPayment);
}

// ==========================================
// 12. FAQS INTERACTION & SEARCH
// ==========================================
function toggleFaq(index) {
    const ans = document.getElementById(`faqAnswer-${index}`);
    const chev = document.getElementById(`faqChevron-${index}`);
    if (!ans) return;

    const isHidden = ans.classList.contains('hidden');
    if (isHidden) {
        ans.classList.remove('hidden');
        if (chev) chev.classList.add('rotate-180');
    } else {
        ans.classList.add('hidden');
        if (chev) chev.classList.remove('rotate-180');
    }
}

let activeFaqCategory = 'all';

function selectFaqCategory(cat) {
    activeFaqCategory = cat;

    const buttons = document.querySelectorAll('.faq-cat-btn');
    buttons.forEach(b => {
        b.className = "faq-cat-btn px-4 py-1.5 rounded-full text-xs font-bold transition-all bg-white text-[#607078] hover:text-[#092532] border border-gray-200";
    });

    const activeMap = {
        'all': 'faqCatAll',
        'mutual-funds': 'faqCatMf',
        'insurance': 'faqCatIns',
        'loans': 'faqCatLoans'
    };
    const active = document.getElementById(activeMap[cat]);
    if (active) {
        active.className = "faq-cat-btn px-4 py-1.5 rounded-full text-xs font-bold transition-all bg-[#071D29] text-white shadow-sm";
    }

    filterFaqs();
}

function filterFaqs() {
    const input = document.getElementById('faqSearchInput');
    const query = input ? input.value.trim().toLowerCase() : '';
    const items = document.querySelectorAll('.faq-item');
    let visibleCount = 0;

    items.forEach(item => {
        const cat = item.getAttribute('data-category');
        const keywords = item.getAttribute('data-keywords') || '';

        const matchesCat = (activeFaqCategory === 'all' || cat === activeFaqCategory);
        const matchesQuery = (!query || keywords.includes(query));

        if (matchesCat && matchesQuery) {
            item.classList.remove('hidden');
            visibleCount++;
        } else {
            item.classList.add('hidden');
        }
    });

    const noRes = document.getElementById('faqNoResults');
    if (noRes) {
        if (visibleCount === 0) noRes.classList.remove('hidden');
        else noRes.classList.add('hidden');
    }
}

// ==========================================
// 13. LEGAL POLICIES MODAL
// ==========================================
function openLegalModal(type) {
    const titleEl = document.getElementById('legalModalTitle');
    const bodyEl = document.getElementById('legalModalBody');
    if (!titleEl || !bodyEl) return;

    if (type === 'privacy') {
        titleEl.innerText = "Privacy Policy";
        bodyEl.innerHTML = `
            <h4 class="font-bold text-[#092532] text-sm">1. Introduction &amp; Commitment</h4>
            <p>Moneyguru Financial Services is committed to safeguarding personal information collected from clients and website visitors. We respect confidentiality and comply with applicable data protection requirements.</p>
            <h4 class="font-bold text-[#092532] text-sm mt-3">2. Information Collection</h4>
            <p>We collect contact coordinates (name, email, phone) voluntarily submitted via consultation request forms or partnership inquiries.</p>
            <h4 class="font-bold text-[#092532] text-sm mt-3">3. Usage of Personal Data</h4>
            <p>Your details are used strictly to coordinate financial advisory meetings, answer questions, provide requested comparison data, and communicate regulatory disclosures. We never sell, rent, or trade client information.</p>
            <h4 class="font-bold text-[#092532] text-sm mt-3">4. Security Measures</h4>
            <p>Data stored in our MySQL relational systems is protected by role-based access, parameterized queries, and SSL encryption.</p>
        `;
    } else if (type === 'terms') {
        titleEl.innerText = "Terms of Service";
        bodyEl.innerHTML = `
            <h4 class="font-bold text-[#092532] text-sm">1. Scope of Guidance</h4>
            <p>Moneyguru Financial Services provides educational content and acts as an AMFI-Registered Mutual Fund Distributor (ARN-136048). Informational calculators and content do not constitute guaranteed investment returns.</p>
            <h4 class="font-bold text-[#092532] text-sm mt-3">2. User Responsibilities</h4>
            <p>Clients are encouraged to verify personal details, provide accurate income brackets for suitability matching, and inspect official offer documents before executing transactions.</p>
            <h4 class="font-bold text-[#092532] text-sm mt-3">3. Regulatory Jurisdiction</h4>
            <p>Services are governed in accordance with Indian SEBI, AMFI, and IRDAI statutory regulations.</p>
        `;
    } else {
        titleEl.innerText = "Regulatory Disclosures & Risk Warnings";
        bodyEl.innerHTML = `
            <h4 class="font-bold text-[#092532] text-sm">1. Mutual Fund Regulatory Disclaimer</h4>
            <p>Mutual Fund investments are subject to market risks, read all scheme-related documents carefully. Past performance does not assure or guarantee future returns.</p>
            <h4 class="font-bold text-[#092532] text-sm mt-3">2. Distributor Registration</h4>
            <p>Saswata Roy holds AMFI Registration Number (ARN- 136048). As a registered distributor, commissions from Asset Management Companies (AMCs) comply with SEBI transparency circulars.</p>
            <h4 class="font-bold text-[#092532] text-sm mt-3">3. Lending &amp; Insurance</h4>
            <p>Loans are subject to individual bank eligibility criteria, CIBIL scores, and legal property title verification. Insurance policies are governed by underwriting rules of respective IRDAI-regulated insurers.</p>
        `;
    }

    document.getElementById('legalModal').classList.remove('hidden');
}

function closeLegalModal() {
    document.getElementById('legalModal').classList.add('hidden');
}

// Utility: escape HTML
function escapeHtml(str) {
    if (!str) return '';
    return String(str)
        .replace(/&/g, '&amp;')
        .replace(/</g, '&lt;')
        .replace(/>/g, '&gt;')
        .replace(/"/g, '&quot;')
        .replace(/'/g, '&#039;');
}

// ==========================================
// 14. ADVISOR PHOTO CLEANUP
// Photo management is strictly handled via Admin Panel
// ==========================================
function cleanupLegacyPhotoCache() {
    try {
        if (localStorage.getItem('moneyguru_advisor_photo')) {
            localStorage.removeItem('moneyguru_advisor_photo');
        }
    } catch (e) {
        // Silently catch storage errors in private/sandboxed modes
    }
}

