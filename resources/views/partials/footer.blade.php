<!-- Footer -->
    <style>
    /* Footer Layout */
    .footer .container {
        text-align: center;
    }
    .footer-content {
        display: flex;
        flex-wrap: wrap;
        justify-content: center;
        align-items: flex-start;
        gap: 40px;
        text-align: center;
    }
    .footer-logo-section {
        flex: 0 0 auto;
        display: flex;
        align-items: flex-start;
        justify-content: center;
        padding-top: 4px;
    }
    .footer-logo-section img {
        width: 160px;
        height: auto;
        display: block;
        border-radius: 10px;
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
        .footer-logo-section img {
            width: 130px;
        }
    }
    </style>

    <footer class="footer">
      <div class="container">
        <div class="footer-content">

          <!-- Logo -->
          <div class="footer-logo-section">
            <a href="{{ url('/') }}" class="logo">
              <img src="{{ asset('assets/images/logos/logo.jpg') }}" alt="3Sixtyshows" />
            </a>
          </div>

          <div class="footer-section">
            <h3>Quick Links</h3>
            <ul>
              <li><a href="{{ route('index') }}">Home</a></li>
              <li><a href="{{ route('aboutus') }}">About</a></li>
              <li><a href="{{ route('events') }}">Events</a></li>
              <li><a href="{{ route('contactus') }}">Contact</a></li>
            </ul>
          </div>

          <div class="footer-section">
            <h3>Contact Info</h3>
            <ul>
              <li><i class="fas fa-phone"></i> 855-360-SHOW</li>
              <li><i class="fas fa-phone"></i> (855) 360-7469</li>
              <li>
                <i class="fas fa-envelope"></i>
                <a href="mailto:info@3sixtyshows.com">info@3sixtyshows.com</a>
              </li>
            </ul>
          </div>

          <div class="footer-section">
            <h3>Follow Us</h3>
            <div class="footer-social">
              <a href="https://www.facebook.com/profile.php?id=61587301180302"
                 target="_blank" rel="noopener noreferrer" aria-label="Facebook">
                <i class="fab fa-facebook-f"></i>
              </a>
              <a href="https://www.instagram.com/opaltickets/"
                 target="_blank" rel="noopener noreferrer" aria-label="Instagram">
                <i class="fab fa-instagram"></i>
              </a>
            </div>
          </div>

        </div>

        <div class="footer-bottom">
          <p>&copy; {{ date('Y') }} 3Sixtyshows. All rights reserved.</p>
        </div>
      </div>
    </footer>

    <script>

      // ── FAQ Accordion ────────────────────────────────────────────────────────
      // Mobile Nav Toggle is intentionally NOT here.
      // It is fully handled in partials/header.blade.php (DOMContentLoaded block).
      // A duplicate handler here caused the hamburger menu to open and immediately
      // close on mobile due to the document click listener firing on the same tap.

      const faqItems = document.querySelectorAll(".faq-item");
      faqItems.forEach((item) => {
        const btn    = item.querySelector(".faq-question");
        const answer = item.querySelector(".faq-answer");
        const icon   = item.querySelector(".faq-icon i");

        btn.addEventListener("click", () => {
          const isOpen = item.classList.contains("active");

          // Close all items first
          faqItems.forEach((i) => {
            i.classList.remove("active");
            i.querySelector(".faq-answer").style.maxHeight = null;
            i.querySelector(".faq-question").setAttribute("aria-expanded", "false");
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

    <style>
    /* Once a card is playing, disable hover-scale so the video doesn't jump */
    .video-item.is-playing {
        transform: none !important;
        box-shadow: 0 0 0 3px #C8102E, 0 10px 30px rgba(200,16,46,0.35) !important;
        cursor: default;
    }
    .video-item.is-playing .video-thumb { cursor: default; }

    /* iframe fills the .video-thumb container exactly */
    .video-thumb {
        position: relative;
    }
    .video-thumb iframe {
        position: absolute;
        inset: 0;
        width: 100%;
        height: 100%;
        border: 0;
        display: block;
    }
    </style>

    <script>

    function playVideo(el) {
        var card      = el.closest(".video-item");
        var youtubeId = card ? card.dataset.youtubeId : null;

        // Bail if no YouTube ID — prevents blank-page behaviour
        if (!youtubeId) return;

        // If THIS card is already playing, do nothing on re-click
        if (card.classList.contains("is-playing")) return;

        // Stop all other playing cards first
        pauseOtherVideos(card);

        // Build iframe with ALL required YouTube permissions
        var iframe = document.createElement("iframe");
        iframe.src =
            "https://www.youtube.com/embed/" + youtubeId +
            "?autoplay=1" +
            "&rel=0" +
            "&modestbranding=1" +
            "&playsinline=1" +
            "&enablejsapi=1" +
            "&origin=" + encodeURIComponent(window.location.origin);

        iframe.allow           = "autoplay; clipboard-write; encrypted-media; gyroscope; picture-in-picture; web-share; fullscreen";
        iframe.allowFullscreen = true;
        iframe.setAttribute("referrerpolicy", "strict-origin-when-cross-origin");

        // Replace thumbnail with iframe
        el.innerHTML = "";
        el.appendChild(iframe);

        // Mark card as playing — disables hover scale via CSS
        card.classList.add("is-playing");

        // Remove click handler so re-clicking thumb doesn't reload the video
        el.onclick = null;
    }

    function pauseOtherVideos(exceptCard) {
        document.querySelectorAll(".video-item.is-playing").forEach(function (playingCard) {
            if (playingCard === exceptCard) return;

            // Tell YouTube player to stop cleanly via postMessage API
            var frame = playingCard.querySelector(".video-thumb iframe");
            if (frame) {
                try {
                    frame.contentWindow.postMessage(
                        '{"event":"command","func":"stopVideo","args":""}', "*"
                    );
                } catch(e) {}
                frame.src = "";
            }

            // Restore the original thumbnail + play button
            var savedId = playingCard.dataset.youtubeId;
            var thumb   = playingCard.querySelector(".video-thumb");
            if (thumb && savedId) {
                thumb.innerHTML =
                    '<img src="https://img.youtube.com/vi/' + savedId + '/hqdefault.jpg" loading="lazy" alt="video">' +
                    '<div class="play-icon"><i class="fas fa-play" style="font-size:22px;margin-left:3px;"></i></div>';
                thumb.onclick = function () { playVideo(this); };
            }
            playingCard.classList.remove("is-playing");
        });
    }

    </script>
  </body>
</html>
