document.addEventListener("DOMContentLoaded", () => {
  // ===== Smooth scrolling for nav links =====
  document
    .querySelectorAll(".nav-links a, .hero-buttons a, .btn.block")
    .forEach((link) => {
      link.addEventListener("click", (e) => {
        if (link.getAttribute("href").startsWith("#")) {
          e.preventDefault();
          const targetId = link.getAttribute("href").substring(1);
          const target = document.getElementById(targetId);
          if (target)
            target.scrollIntoView({ behavior: "smooth", block: "start" });
        }
      });
    });

  // ===== Highlight active nav link on scroll =====
  const sections = document.querySelectorAll("section");
  const navLinks = document.querySelectorAll(".nav-links a");

  window.addEventListener("scroll", () => {
    let current = "";
    sections.forEach((section) => {
      const sectionTop = section.offsetTop - 80;
      const sectionHeight = section.clientHeight;
      if (scrollY >= sectionTop && scrollY < sectionTop + sectionHeight) {
        current = section.getAttribute("id");
      }
    });
    navLinks.forEach((link) => {
      link.classList.remove("active");
      if (link.getAttribute("href").substring(1) === current) {
        link.classList.add("active");
      }
    });
  });

  // ===== Pricing toggle functionality =====
  const toggle = document.getElementById("billing");
  if (toggle) {
    document
      .querySelectorAll(".price-yearly")
      .forEach((el) => (el.style.display = "none"));
    document
      .querySelectorAll(".price-monthly")
      .forEach((el) => (el.style.display = "flex"));

    toggle.addEventListener("change", () => {
      const isYearly = toggle.checked;
      document.querySelectorAll(".price-monthly").forEach((el) => {
        el.style.display = isYearly ? "none" : "flex";
      });
      document.querySelectorAll(".price-yearly").forEach((el) => {
        el.style.display = isYearly ? "flex" : "none";
      });
    });
  }

  // ===== Contact form AJAX submission =====
  const contactForm = document.getElementById("contact-form");
  const contactResponse = document.getElementById("contact-response");

  if (contactForm && contactResponse) {
    contactForm.addEventListener("submit", async (e) => {
      e.preventDefault(); // STOP page reload immediately

      contactResponse.textContent = "Sending...";
      contactResponse.style.color = "blue";

      const formData = new FormData(contactForm);

      try {
        const response = await fetch("contact_submit.php", {
          method: "POST",
          body: formData,
        });

        const data = await response.json();
        contactResponse.textContent = data.message;
        contactResponse.style.color =
          data.status === "success" ? "green" : "red";

        if (data.status === "success") contactForm.reset();
      } catch (error) {
        contactResponse.textContent = "An error occurred. Please try again.";
        contactResponse.style.color = "red";
      }
    });
  }

  // ===== Newsletter form AJAX submission =====
  const newsletterForm = document.getElementById("newsletter-form");
  const newsletterResponse = document.createElement("div");
  if (newsletterForm) {
    newsletterForm.appendChild(newsletterResponse);
    newsletterForm.addEventListener("submit", async (e) => {
      e.preventDefault(); // STOP reload

      newsletterResponse.textContent = "Subscribing...";
      newsletterResponse.style.color = "blue";

      const formData = new FormData(newsletterForm);

      try {
        const response = await fetch("newsletter_submit.php", {
          method: "POST",
          body: formData,
        });

        const result = await response.text();
        newsletterResponse.textContent = result;
        newsletterResponse.style.color = "green";
        newsletterForm.reset();
      } catch (error) {
        newsletterResponse.textContent =
          "Subscription failed. Please try again.";
        newsletterResponse.style.color = "red";
      }
    });
  }
});
