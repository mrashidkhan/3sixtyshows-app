<!-- Contact Section -->
<section class="contact" id="contact">
  <div class="container">
    <div class="section-header">
      <h2 class="section-title">Get in Touch</h2>
      <p class="section-subtitle">Contact Us</p>
      <p class="section-description">Have questions? We'd love to hear from you.</p>
    </div>

    <div class="contact-wrapper">
      {{-- Success / Error Alert --}}
      <div id="formAlert" style="display:none; padding:14px 18px; border-radius:6px; margin-bottom:20px; font-size:15px;"></div>

      <form class="contact-form" id="contactForm">
        @csrf
        <div class="form-row">
          <div class="form-group">
            <input type="text" id="name" name="name" placeholder="Your Name" autocomplete="name" required />
          </div>
          <div class="form-group">
            <input type="email" id="email" name="email" placeholder="Your Email" autocomplete="email" required />
          </div>
        </div>
        <div class="form-row">
          <div class="form-group">
            <input type="tel" id="phone" name="phone" placeholder="Phone Number" autocomplete="tel" required />
          </div>
          <div class="form-group">
            <input type="text" id="subject" name="subject" placeholder="Subject" autocomplete="off" required />
          </div>
        </div>
        <div class="form-group">
          <textarea id="message" name="message" rows="5" placeholder="Your Message" autocomplete="off" required></textarea>
        </div>
        <button type="submit" class="btn-submit" id="submitBtn">Send Message</button>
      </form>
    </div>
  </div>
</section>

<script>
document.getElementById('contactForm').addEventListener('submit', function(e) {
    e.preventDefault();

    const form      = this;
    const btn       = document.getElementById('submitBtn');
    const msgBox    = document.getElementById('formAlert'); // renamed: never use 'alert' — it shadows window.alert
    const formData  = new FormData(form);

    btn.disabled      = true;
    btn.textContent   = 'Sending...';
    msgBox.style.display = 'none';

    function showMsg(msg, success) {
        msgBox.style.background = success ? '#d4edda' : '#f8d7da';
        msgBox.style.color      = success ? '#155724' : '#721c24';
        msgBox.style.border     = success ? '1px solid #c3e6cb' : '1px solid #f5c6cb';
        msgBox.textContent      = msg;
        msgBox.style.display    = 'block';
        msgBox.scrollIntoView({ behavior: 'smooth', block: 'center' });
    }

    fetch('{{ route("contact.send") }}', {
        method: 'POST',
        headers: {
            'X-CSRF-TOKEN': document.querySelector('input[name="_token"]').value,
            'Accept': 'application/json',
        },
        body: formData
    })
    .then(function(res) {
        // Check HTTP status BEFORE trying to parse JSON.
        // Non-2xx responses (419 CSRF expired, 422 validation, 500 server error)
        // often return HTML — calling .json() on them throws and lands in catch().
        if (res.status === 419) {
            throw new Error('Session expired. Please refresh the page and try again.');
        }
        if (res.status === 422) {
            return res.json().then(function(data) {
                // Laravel validation errors come as { errors: { field: ['msg'] } }
                const messages = data.errors
                    ? Object.values(data.errors).flat().join(' ')
                    : (data.message || 'Validation error. Please check your inputs.');
                throw new Error(messages);
            });
        }
        if (!res.ok) {
            throw new Error('Server error (' + res.status + '). Please try again later.');
        }
        return res.json();
    })
    .then(function(data) {
        if (data.success) {
            showMsg(data.message || 'Message sent successfully! We\'ll be in touch soon.', true);
            form.reset();
        } else {
            showMsg(data.message || 'Something went wrong. Please try again.', false);
        }
    })
    .catch(function(err) {
        // err.message now contains the real reason — not a blank "Network error"
        showMsg(err.message || 'Unexpected error. Please try again.', false);
        console.error('[ContactForm]', err);
    })
    .finally(function() {
        btn.disabled    = false;
        btn.textContent = 'Send Message';
    });
});
</script>
