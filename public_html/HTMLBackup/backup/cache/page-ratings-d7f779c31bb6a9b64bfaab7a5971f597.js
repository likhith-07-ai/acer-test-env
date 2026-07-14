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
        header.classList.add("sm:shadow-lg","headerScrolled");
      } else {
        header.classList.remove("sm:shadow-lg","headerScrolled");
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
(function () {
        var PAGE_SIZE = 6;
        var stateMap = new WeakMap();

        function selectEls(root) {
            var tbody = root.querySelector('tbody');
            var footer = root.querySelector('.flex.items-center.justify-between.mt-8.text-gray-600');
            if (!footer) return null;
            var info = footer.querySelector('p');
            var controls = footer.querySelector('.flex.items-center.gap-2');
            if (!controls) return null;
            var prevBtn = controls.querySelector('button[data-action="prev"]');
            var nextBtn = controls.querySelector('button[data-action="next"]');
            if (!tbody || !info || !prevBtn || !nextBtn) return null;
            return {
                tbody: tbody,
                info: info,
                controls: controls,
                prevBtn: prevBtn,
                nextBtn: nextBtn
            };
        }

        function hydrateFromDOM(root) {
            var els = selectEls(root);
            if (!els) return false;
            var trList = els.tbody.querySelectorAll('tr');
            var rows = Array.prototype.map.call(trList, function (tr) {
                return tr.outerHTML;
            });
            stateMap.set(root, {
                page: 1,
                total: rows.length,
                rows: rows
            });
            return true;
        }

        function buildPages(current, total) {
            var out = [];
            if (total <= 7) {
                for (var i = 1; i <= total; i++) out.push(i);
                return out;
            }
            out.push(1);
            if (current > 3) out.push('...');
            var start = Math.max(2, current - 1);
            var end = Math.min(total - 1, current + 1);
            for (var j = start; j <= end; j++) out.push(j);
            if (current < total - 2) out.push('...');
            out.push(total);
            return out;
        }

        function applyPillStyles(root) {
            var pills = root.querySelectorAll('.rating-pill');
            pills.forEach(function (el) {
                var bg = el.getAttribute('data-bg') || '';
                var color = el.getAttribute('data-color') || '';
                if (bg) el.style.backgroundColor = bg;
                if (color) el.style.color = color;
            });
        }

        function render(root) {
            var els = selectEls(root);
            if (!els) return;
            var state = stateMap.get(root);
            if (!state) return;

            var totalPages = Math.max(1, Math.ceil(state.total / PAGE_SIZE));
            if (state.page > totalPages) state.page = totalPages;

            var startIdx = (state.page - 1) * PAGE_SIZE;
            var endIdx = Math.min(startIdx + PAGE_SIZE, state.total);

            var html = '';
            for (var i = startIdx; i < endIdx; i++) html += state.rows[i];
            els.tbody.innerHTML = html;

            applyPillStyles(root);

            var from = state.total === 0 ? 0 : startIdx + 1;
            var to = endIdx;
            els.info.textContent = 'Showing ' + from + ' to ' + to + ' of ' + state.total + ' results';

            var isFirst = state.page === 1;
            var isLast = state.page === totalPages;
            els.prevBtn.classList.toggle('text-gray-400', isFirst);
            els.prevBtn.classList.toggle('cursor-not-allowed', isFirst);
            els.nextBtn.classList.toggle('text-gray-400', isLast);
            els.nextBtn.classList.toggle('cursor-not-allowed', isLast);

            // Remove buttons between prev and next
            var toRemove = [];
            var node = els.prevBtn.nextSibling;
            while (node && node !== els.nextBtn) {
                var next = node.nextSibling;
                if (node.nodeType === 1 || node.nodeType === 3) toRemove.push(node);
                node = next;
            }
            toRemove.forEach(function (n) {
                els.controls.removeChild(n);
            });

            // Insert page buttons
            var pages = buildPages(state.page, totalPages);
            var insertBefore = els.nextBtn;
            pages.forEach(function (p) {
                if (p === '...') {
                    var span = document.createElement('span');
                    span.className = 'px-2';
                    span.textContent = '...';
                    els.controls.insertBefore(span, insertBefore);
                } else {
                    var btn = document.createElement('button');
                    btn.className = 'w-8 h-8 sm:w-12 sm:h-12 border rounded-lg sm:rounded-xl flex justify-center items-center ' + (p ===
                        state.page ? 'border-[#2299AA] text-[#2299AA] font-semibold' :
                        'border-[#E0E0E0] text-[#0A0A0A]');
                    btn.textContent = String(p);
                    btn.setAttribute('data-page', String(p));
                    btn.addEventListener('click', function (e) {
                        var n = parseInt(e.currentTarget.getAttribute('data-page'), 10);
                        if (!isNaN(n) && n !== state.page) {
                            state.page = n;
                            render(root);
                        }
                    });
                    els.controls.insertBefore(btn, insertBefore);
                }
            });
        }

        function attachEvents(root) {
            var els = selectEls(root);
            if (!els) return;
            els.prevBtn.addEventListener('click', function () {
                var state = stateMap.get(root);
                if (state && state.page > 1) {
                    state.page -= 1;
                    render(root);
                }
            });
            els.nextBtn.addEventListener('click', function () {
                var state = stateMap.get(root);
                if (!state) return;
                var totalPages = Math.max(1, Math.ceil(state.total / PAGE_SIZE));
                if (state.page < totalPages) {
                    state.page += 1;
                    render(root);
                }
            });
        }

        function initContainer(root) {
            if (!hydrateFromDOM(root)) return;
            attachEvents(root);
            render(root);
        }

        function initAll() {
            var containers = document.querySelectorAll('.cmsContainer');
            containers.forEach(initContainer);
        }

        if (document.readyState === 'loading') {
            document.addEventListener('DOMContentLoaded', initAll);
        } else {
            initAll();
        }
    })();;
// Dynamic year
document.getElementById("year").textContent = new Date().getFullYear();;
