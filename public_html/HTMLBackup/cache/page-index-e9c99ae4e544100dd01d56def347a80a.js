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
document.getElementById('contactForm').addEventListener('submit', function(e) {
            e.preventDefault();
            
            const submitBtn = document.getElementById('submitBtn');
            const originalText = submitBtn.textContent;
            
            // Show loading state
            submitBtn.disabled = true;
            submitBtn.textContent = 'Sending...';
            
            // Get form data
            const formData = new FormData(this);
            
            // Send AJAX request
            fetch('send-mail.php', {
                method: 'POST',
                body: formData
            })
            .then(response => response.json())
            .then(data => {
                if (data.success) {
                    showNotification('success', 'Message Sent Successfully', 'Thank you for reaching out. Our team will get back to you shortly.');
                    // Reset form
                    document.getElementById('contactForm').reset();
                } else {
                    showNotification('error', 'Submission Failed', 'We couldn\'t process your request right now. Please check your details or try again later.');
                }
            })
            .catch(error => {
                showNotification('error', 'Submission Failed', 'We couldn\'t process your request right now. Please check your details or try again later.');
            })
            .finally(() => {
                // Reset button state
                submitBtn.disabled = false;
                submitBtn.textContent = originalText;
            });
        });
        
        function showNotification(type, title, message) {
            const notification = document.getElementById('resultNotification');
            const notificationIcon = document.getElementById('notificationIcon');
            const notificationTitle = document.getElementById('notificationTitle');
            const notificationMessage = document.getElementById('notificationMessage');
            
            if (type === 'success') {
                notificationIcon.className = 'notification-icon success-icon-container';
                notificationIcon.innerHTML = `
                    <svg width="20" height="20" viewBox="0 0 20 20" fill="none">
                        <path d="M4.16602 12.0833C4.16602 12.0833 5.41602 12.0833 7.08268 15C7.08268 15 11.715 7.36112 15.8327 5.83334" stroke="#54B69C" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"></path> 
                    </svg>
                `;
            } else {
                notificationIcon.className = 'notification-icon error-icon-container';
                notificationIcon.innerHTML = `
                    <svg width="20" height="20" viewBox="0 0 20 20" fill="none">
                        <g clip-path="url(#clip0_4434_4426)">
                        <path d="M9.99935 18.3333C14.6017 18.3333 18.3327 14.6024 18.3327 9.99999C18.3327 5.39762 14.6017 1.66666 9.99935 1.66666C5.39698 1.66666 1.66602 5.39762 1.66602 9.99999C1.66602 14.6024 5.39698 18.3333 9.99935 18.3333Z" stroke="#B42318" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"></path>
                        <path d="M10 6.66666V10.4167" stroke="#B42318" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"></path>
                        <path d="M10 13.3236V13.3319" stroke="#B42318" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"></path>
                        </g>
                        <defs>
                        <clipPath id="clip0_4434_4426">
                        <rect width="20" height="20" fill="white"></rect>
                        </clipPath>
                        </defs>
                    </svg>
                `;
            }
            
            notificationTitle.textContent = title;
            notificationMessage.textContent = message;
            notification.style.display = 'block';
            
            // Auto hide after 5 seconds
            setTimeout(() => {
                closeNotification();
            }, 5000);
        }
        
        function closeNotification() {
            document.getElementById('resultNotification').style.display = 'none';
        };
// Dynamic year
document.getElementById("year").textContent = new Date().getFullYear();;
