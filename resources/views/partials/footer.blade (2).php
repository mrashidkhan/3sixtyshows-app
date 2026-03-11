<!-- Footer -->
    <style>
    /* Footer Centering */
    .footer .container {
        text-align: center;
    }
    .footer-content {
        display: flex;
        flex-wrap: wrap;
        justify-content: center;
        gap: 40px;
        text-align: center;
    }
    .footer-section {
        flex: 1 1 180px;
        max-width: 240px;
        text-align: center;
        display: flex;
        flex-direction: column;
        align-items: center;
    }
    .footer-section h3 {
        display: inline-block;
        width: auto;
        text-align: center;
        margin-bottom: 14px;
        padding-bottom: 8px;
        border-bottom: 2px solid #346AB4;
        /* Remove any ::after from global styles */
    }
    .footer-section h3::after,
    .footer-section h3::before {
        display: none !important;
        content: none !important;
    }
    .footer-section ul {
        list-style: none;
        padding: 0;
        margin: 0;
        display: inline-block;
        text-align: left;
    }
    .footer-section ul li {
        text-align: left;
        margin-bottom: 6px;
    }
    .footer-social {
        display: flex;
        justify-content: center;
        gap: 12px;
    }
    .footer-bottom {
        text-align: center;
    }
    @media (max-width: 640px) {
        .footer-content {
            flex-direction: column;
            align-items: center;
        }
        .footer-section {
            max-width: 100%;
            width: 100%;
        }
    }
    </style>

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
              <li><i class="fas fa-phone"></i> 855-360-SHOW</li>
              <li><i class="fas fa-phone"></i> (855) 360-7469</li>
              <li>
                <span class="top-bar-text">info@3sixtyshows.com</span>
              </li>
            </ul>
          </div>

          <div class="footer-section">
            <h3>Follow Us</h3>
            <div class="footer-social">
              <a href="https://www.facebook.com/profile.php?id=61587301180302"
                 target="_blank" rel="noopener noreferrer">
                <i class="fab fa-facebook-f"></i>
              </a>
              <a href="https://www.instagram.com/opaltickets/"
                 target="_blank" rel="noopener noreferrer">
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

    <script data-cfasync="false" src="/cdn-cgi/scripts/5c5dd728/cloudflare-static/email-decode.min.js"></script>
    <script>
      // Mobile Nav Toggle
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

      // Scroll Spy
      const sections = document.querySelectorAll("section[id]");
      const navLinks = document.querySelectorAll(".nav-link");
      window.addEventListener("scroll", () => {
        let current = "";
        sections.forEach((section) => {
          const sectionTop = section.offsetTop - 100;
          if (window.scrollY >= sectionTop)
            current = section.getAttribute("id");
        });
        navLinks.forEach((link) => {
          link.classList.remove("active");
          if (link.getAttribute("href") === "#" + current)
            link.classList.add("active");
        });
      });

      // FAQ Accordion
      const faqItems = document.querySelectorAll(".faq-item");
      faqItems.forEach((item) => {
        const btn = item.querySelector(".faq-question");
        const answer = item.querySelector(".faq-answer");
        const icon = item.querySelector(".faq-icon i");

        btn.addEventListener("click", () => {
          const isOpen = item.classList.contains("active");

          faqItems.forEach((i) => {
            i.classList.remove("active");
            i.querySelector(".faq-answer").style.maxHeight = null;
            i.querySelector(".faq-question").setAttribute("aria-expanded", "false");
            i.querySelector(".faq-icon i").className = "fas fa-plus";
          });

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
