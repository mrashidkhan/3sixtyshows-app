// script.js — utility handlers only
// NOTE: Mobile nav toggle and Gallery dropdown are fully handled
// in header_blade.php (DOMContentLoaded block). Do NOT duplicate here.

document.addEventListener('DOMContentLoaded', function() {

    // Header shadow on scroll
    var header = document.getElementById('header');
    if (header) {
        window.addEventListener('scroll', function() {
            header.classList.toggle('scrolled', window.scrollY > 50);
        });
    }

    // Contact Form — only if contactForm exists AND no other listener attached
    var contactForm = document.getElementById('contactForm');
    if (contactForm && !contactForm.dataset.listenerAttached) {
        contactForm.dataset.listenerAttached = 'true';
        contactForm.addEventListener('submit', function(e) {
            e.preventDefault();
            alert('Thank you for your message! We will get back to you soon.');
            contactForm.reset();
        });
    }

});
