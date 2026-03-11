<!-- Footer -->
    <footer class="footer">
      <div class="container">
        <div class="footer-content">
          <div class="footer-section">
            <h3>Quick Links</h3>
            <ul>
              <li><a href="#home">Home</a></li>
              <li><a href="#about">About</a></li>
              <li><a href="#events">Events</a></li>
              <li><a href="#contact">Contact</a></li>
            </ul>
          </div>

          <div class="footer-section">
            <h3>Contact Info</h3>
            <ul>
              <li><i class="fas fa-phone"></i> (855) OPAL-TIX</li>
              <li><i class="fas fa-phone"></i> (855) 672-5849</li>
              <li>
                <i class="fas fa-envelope"></i>
                <a
                  href="/cdn-cgi/l/email-protection"
                  class="__cf_email__"
                  data-cfemail="8ee7e0e8e1cee1feefe2fae7ede5ebfafda0ede1e3"
                  >[email&#160;protected]</a
                >
              </li>
            </ul>
          </div>

          <div class="footer-section">
            <h3>Follow Us</h3>
            <div class="footer-social">
              <a
                href="https://www.facebook.com/profile.php?id=61587301180302"
                target="_blank"
                rel="noopener noreferrer"
              >
                <i class="fab fa-facebook-f"></i>
              </a>
              <a
                href="https://www.instagram.com/opaltickets/"
                target="_blank"
                rel="noopener noreferrer"
              >
                <i class="fab fa-instagram"></i>
              </a>
            </div>
          </div>
        </div>

        <div class="footer-bottom">
          <p>&copy; 2026 3Sixtyshows. All rights reserved.</p>
        </div>
      </div>
    </footer>

    <script
      data-cfasync="false"
      src="/cdn-cgi/scripts/5c5dd728/cloudflare-static/email-decode.min.js"
    ></script>
    <script>
      // ── Mobile Nav Toggle ──────────────────────────────────
      const mobileToggle = document.getElementById("mobile-toggle");
      const nav = document.getElementById("nav");
      if (mobileToggle && nav) {
        mobileToggle.addEventListener("click", () => {
          nav.classList.toggle("active");
          const icon = mobileToggle.querySelector("i");
          icon.classList.toggle("fa-bars");
          icon.classList.toggle("fa-times");
        });
        document.addEventListener("click", (e) => {
          if (!nav.contains(e.target) && !mobileToggle.contains(e.target)) {
            nav.classList.remove("active");
            const icon = mobileToggle.querySelector("i");
            icon.classList.add("fa-bars");
            icon.classList.remove("fa-times");
          }
        });
      }

      // ── Scroll Spy ─────────────────────────────────────────
      const sections = document.querySelectorAll("section[id]");
      const navLinks = document.querySelectorAll(".nav-link");
      window.addEventListener("scroll", () => {
        let current = "";
        sections.forEach((section) => {
          const sectionTop = section.offsetTop - 100;
          if (window.pageYOffset >= sectionTop)
            current = section.getAttribute("id");
        });
        navLinks.forEach((link) => {
          link.classList.remove("active");
          if (link.getAttribute("href") === "#" + current)
            link.classList.add("active");
        });
      });

      // ── FAQ Accordion ──────────────────────────────────────
      const faqItems = document.querySelectorAll(".faq-item");
      faqItems.forEach((item) => {
        const btn = item.querySelector(".faq-question");
        const answer = item.querySelector(".faq-answer");
        const icon = item.querySelector(".faq-icon i");

        btn.addEventListener("click", () => {
          const isOpen = item.classList.contains("active");

          // Close all items
          faqItems.forEach((i) => {
            i.classList.remove("active");
            i.querySelector(".faq-answer").style.maxHeight = null;
            i.querySelector(".faq-question").setAttribute(
              "aria-expanded",
              "false",
            );
            i.querySelector(".faq-icon i").className = "fas fa-plus";
          });

          // Open clicked item if it was closed
          if (!isOpen) {
            item.classList.add("active");
            answer.style.maxHeight = answer.scrollHeight + "px";
            btn.setAttribute("aria-expanded", "true");
            icon.className = "fas fa-minus";
          }
        });
      });
    </script>
  </body>
</html>
