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
// Dynamic year
document.getElementById("year").textContent = new Date().getFullYear();;
