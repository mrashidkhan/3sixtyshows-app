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
            <input type="text" id="name" name="name" placeholder="Your Name" required />
          </div>
          <div class="form-group">
            <input type="email" id="email" name="email" placeholder="Your Email" required />
          </div>
        </div>
        <div class="form-row">
          <div class="form-group">
            <input type="tel" id="phone" name="phone" placeholder="Phone Number" required />
          </div>
          <div class="form-group">
            <input type="text" id="subject" name="subject" placeholder="Subject" required />
          </div>
        </div>
        <div class="form-group">
          <textarea id="message" name="message" rows="5" placeholder="Your Message" required></textarea>
        </div>
        <button type="submit" class="btn-submit" id="submitBtn">Send Message</button>
      </form>
    </div>
  </div>
</section>

<script>
document.getElementById('contactForm').addEventListener('submit', function(e) {
    e.preventDefault();

    const form = this;
    const btn = document.getElementById('submitBtn');
    const alert = document.getElementById('formAlert');
    const formData = new FormData(form);

    btn.disabled = true;
    btn.textContent = 'Sending...';
    alert.style.display = 'none';

    fetch('{{ route("contact.send") }}', {
        method: 'POST',
        headers: {
            'X-CSRF-TOKEN': document.querySelector('input[name="_token"]').value,
            'Accept': 'application/json',
        },
        body: formData
    })
    .then(res => res.json())
    .then(data => {
        if (data.success) {
            alert.style.background = '#d4edda';
            alert.style.color = '#155724';
            alert.style.border = '1px solid #c3e6cb';
            alert.textContent = data.message;
            form.reset();
        } else {
            alert.style.background = '#f8d7da';
            alert.style.color = '#721c24';
            alert.style.border = '1px solid #f5c6cb';
            alert.textContent = 'Something went wrong. Please try again.';
        }
        alert.style.display = 'block';
        alert.scrollIntoView({ behavior: 'smooth', block: 'center' });
    })
    .catch(() => {
        alert.style.background = '#f8d7da';
        alert.style.color = '#721c24';
        alert.style.border = '1px solid #f5c6cb';
        alert.textContent = 'Network error. Please try again.';
        alert.style.display = 'block';
        alert.scrollIntoView({ behavior: 'smooth', block: 'center' });
    })
    .finally(() => {
        btn.disabled = false;
        btn.textContent = 'Send Message';
    });
});
</script>
