// Mobile menu toggle functionality
document.addEventListener('DOMContentLoaded', function() {
  const mobileMenuButton = document.getElementById('mobile-menu-button');
  const mobileMenu = document.getElementById('mobile-menu');
  
  if (mobileMenuButton && mobileMenu) {
    mobileMenuButton.addEventListener('click', function() {
      const isExpanded = mobileMenuButton.getAttribute('aria-expanded') === 'true';
      mobileMenuButton.setAttribute('aria-expanded', !isExpanded);
      mobileMenu.classList.toggle('hidden');
      
      // Toggle icon
      const icon = mobileMenuButton.querySelector('i');
      if (icon) {
        if (mobileMenu.classList.contains('hidden')) {
          icon.className = 'acericon-menu text-base';
        } else {
          icon.className = 'acericon-close text-base';
        }
      }
    });
    
    // Close menu when clicking outside
    document.addEventListener('click', function(event) {
      if (!mobileMenuButton.contains(event.target) && !mobileMenu.contains(event.target)) {
        mobileMenu.classList.add('hidden');
        mobileMenuButton.setAttribute('aria-expanded', 'false');
        const icon = mobileMenuButton.querySelector('i');
        if (icon) {
          icon.className = 'acericon-menu text-base';
        }
      }
    });

    const header = document.querySelector("header");
    window.addEventListener("scroll", () => {
      if (window.scrollY > 10) {
        header.classList.add("sm:shadow-lg");
      } else {
        header.classList.remove("sm:shadow-lg");
      }
    });
  }

  // Dropdown menu toggle
  const dropdownToggles = document.querySelectorAll('[data-dropdown-toggle]');
  dropdownToggles.forEach(toggle => {
    const menuId = toggle.getAttribute('data-dropdown-toggle');
    const menu = document.getElementById(menuId);

    if (menu) {
      toggle.addEventListener('click', function(e) {
        e.stopPropagation(); // prevent closing immediately
        menu.classList.toggle('hidden');
      });

      // close when clicking outside
      document.addEventListener('click', function(e) {
        if (!toggle.contains(e.target) && !menu.contains(e.target)) {
          menu.classList.add('hidden');
        }
      });
    }
  });
});;
// function animateCounter(element, endValue) {
//   // Extract number only (ignores +, commas, text)
//   let numberOnly = parseInt(endValue.replace(/\D/g, ""), 10);

//   if (isNaN(numberOnly)) {
//     element.textContent = endValue; // fallback if no number found
//     return;
//   }

//   let startValue = 0;
//   let duration = 2000;
//   let increment = Math.ceil(numberOnly / (duration / 16));

//   let current = startValue;
//   let interval = setInterval(() => {
//     current += increment;
//     if (current >= numberOnly) {
//       clearInterval(interval);
//       element.textContent = endValue; // final value with "+" or extra text
//     } else {
//       element.textContent = current;
//     }
//   }, 16);
// }

// apply to all counters
//document.querySelectorAll("[data-counter]").forEach(el => {
  //animateCounter(el, el.getAttribute("data-counter"));
//});


document.querySelectorAll("[data-counter]").forEach(el => {
  el.textContent = el.getAttribute("data-counter");
});;
var swiper = new Swiper(".leadershipSwiper", {
  slidesPerView: 1.2,
  spaceBetween: 24,
  centeredSlides: true,
  loop: true, // set to true so autoplay works seamlessly
  //autoplay: {
    //delay: 3000, // 3 seconds between slides
    //disableOnInteraction: false, // keep autoplay running after user interactions
  //},
  breakpoints: {
    640: { slidesPerView: 2, spaceBetween: 24 },
    1024: { slidesPerView: 3, spaceBetween: 32 },
  },
  pagination: {
    el: ".swiper-pagination",
    clickable: true,
  },
  navigation: {
    nextEl: ".swiper-button-next",
    prevEl: ".swiper-button-prev",
  },
});;
(function () {
  function setTimelineLine() {
    const steps = document.querySelectorAll(".timeline-step");
    const timelineLine = document.getElementById("timeline-line");
    const parent = document.getElementById("parentWrapperforTimeline");
    const fill = document.getElementById("timeline-fill");

    if (!steps.length || !timelineLine || !parent || !fill) return;

    const firstHalf = steps[0].offsetHeight / 2;
    const lastHalf = steps[steps.length - 1].offsetHeight / 2;
    const parentHeight = parent.offsetHeight;
    const lineHeight = Math.max(0, parentHeight - (firstHalf + lastHalf));

    timelineLine.style.top = firstHalf + "px";
    timelineLine.style.height = lineHeight + "px";

    // Reset fill on setup
    fill.style.height = "0px";
  }

  function updateTimelineFill() {
    const timelineLine = document.getElementById("timeline-line");
    const fill = document.getElementById("timeline-fill");
    const steps = document.querySelectorAll(".timeline-step");
    const dots = document.querySelectorAll(".timeline-step > span > span");
    const parentWrapper = document.getElementById("timelineOuter");

    if (!timelineLine || !fill || !steps.length || !parentWrapper) return;

    const heading = parentWrapper.querySelector("h2");
    if (!heading) return;

    const headingRect = heading.getBoundingClientRect();
    const headingVisible = headingRect.top <= window.innerHeight * 0.8; 
    // start only when heading top is within 80% of viewport height

    if (!headingVisible) {
      // Keep reset before start
      fill.style.height = "0px";
      dots.forEach(d => {
        d.classList.remove("bg-primary-500");
        d.classList.add("bg-quinary-100");
      });
      return;
    }

    const lineRect = timelineLine.getBoundingClientRect();
    const lineTopDoc = lineRect.top + window.scrollY;
    const lineHeight = timelineLine.offsetHeight;
    const lineBottomDoc = lineTopDoc + lineHeight;

    const viewportCenterDoc = window.scrollY + window.innerHeight / 2;

    let progress = (viewportCenterDoc - lineTopDoc) / (lineBottomDoc - lineTopDoc);
    progress = Math.min(Math.max(progress, 0), 1);

    // Force 100% when scrolled to the bottom of the page
    const reachedEnd = window.innerHeight + window.scrollY >= document.body.offsetHeight - 2;
    if (reachedEnd) progress = 1;

    const fillHeight = Math.round(progress * lineHeight);
    fill.style.height = fillHeight + "px";

    steps.forEach((step, i) => {
      const stepCenterDoc = step.getBoundingClientRect().top + window.scrollY + step.offsetHeight / 2;
      
      if (progress === 1 && i === steps.length - 1) {
      // force last dot
      dots[i].classList.remove("bg-quinary-100");
      dots[i].classList.add("bg-primary-500");
    } else if (viewportCenterDoc >= stepCenterDoc) {
      dots[i].classList.remove("bg-quinary-100");
      dots[i].classList.add("bg-primary-500");
    } else {
      dots[i].classList.remove("bg-primary-500");
      dots[i].classList.add("bg-quinary-100");
    }
    });
  }

  // Init
  window.addEventListener("load", setTimelineLine);
  window.addEventListener("resize", () => {
    setTimelineLine();
    // do NOT auto-fill, just reset
  });
  window.addEventListener("scroll", updateTimelineFill);
})();;
// Dynamic year
document.getElementById("year").textContent = new Date().getFullYear();;
