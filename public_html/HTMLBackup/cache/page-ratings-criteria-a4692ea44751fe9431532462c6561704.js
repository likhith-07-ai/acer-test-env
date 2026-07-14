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
// Get all necessary elements
const faqContainer = document.getElementById('faqContainer');
const faqItems = document.querySelectorAll('.faq-item');
const showAllBtn = document.getElementById('showAllBtn');
const toggleStatus = document.getElementById("attribute").getAttribute("Toggle-Status");
const initialVisibleItems = parseInt(faqContainer.getAttribute('data-initial-visible')) || 3;

console.log(toggleStatus);
let isMultipleOpen = toggleStatus;
let isShowingAll = false;

// Function to toggle FAQ item
function toggleFAQ(item) {
    const content = item.querySelector('.faq-content');
    const icon = item.querySelector('.acericon-down-angle');
    const isOpen = !content.classList.contains('hidden');

    if (!isMultipleOpen) {
        // Close all other items if multiple open is not allowed
        faqItems.forEach(otherItem => {
            if (otherItem !== item) {
                otherItem.querySelector('.faq-content').classList.add('hidden');
                otherItem.querySelector('.acericon-down-angle').style.transform = 'rotate(0deg)';
            }
        });
    }

    // Toggle current item
    content.classList.toggle('hidden');
    icon.style.transform = isOpen ? 'rotate(0deg)' : 'rotate(180deg)';
}

// Function to initialize FAQ visibility
function initializeFAQVisibility() {
    // Show first FAQ item by default
    const firstItem = faqItems[0];
    if (firstItem) {
        firstItem.querySelector('.faq-content').classList.remove('hidden');
        firstItem.querySelector('.acericon-down-angle').style.transform = 'rotate(180deg)';
    }

    if (showAllBtn) {
        faqItems.forEach((item, index) => {
            if (index >= initialVisibleItems) {
                item.style.display = 'none';
            }
        });

        // Update button text based on total items
        if (faqItems.length > initialVisibleItems) {
            showAllBtn.textContent = `Show All (${faqItems.length - initialVisibleItems} more)`;
        }
    }
}

// Function to show/hide additional FAQ items
function toggleFAQItems() {
    faqItems.forEach((item, index) => {
        if (index >= initialVisibleItems) {
            if (isShowingAll) {
                item.style.display = 'none';
            } else {
                item.style.display = 'block';
            }
        }
    });

    isShowingAll = !isShowingAll;
    
    // Update button text
    if (isShowingAll) {
        showAllBtn.textContent = 'Show Less';
    } else {
        showAllBtn.textContent = `Show All (${faqItems.length - initialVisibleItems} more)`;
    }
}

// Add click event listeners to FAQ buttons
faqItems.forEach(item => {
    const button = item.querySelector('.faq-button');
    button.addEventListener('click', () => toggleFAQ(item));
});

// Show All button functionality - now handles showing/hiding items
if (showAllBtn) {
    showAllBtn.addEventListener('click', toggleFAQItems);
}

// Initialize FAQ visibility on page load
document.addEventListener('DOMContentLoaded', () => {
    initializeFAQVisibility();
});;
// Dynamic year
document.getElementById("year").textContent = new Date().getFullYear();;
