/**
 * Lazy Loading Fallback for older browsers
 * This script provides lazy loading functionality for browsers that don't support the native loading="lazy" attribute
 */

(function() {
    'use strict';

    // Check if the browser supports native lazy loading
    if ('loading' in HTMLImageElement.prototype) {
        return; // Native lazy loading is supported, no need for fallback
    }

    // Intersection Observer API fallback for older browsers
    if (!('IntersectionObserver' in window)) {
        // For browsers without Intersection Observer, load all images immediately
        var lazyImages = document.querySelectorAll('img[loading="lazy"]');
        lazyImages.forEach(function(img) {
            img.src = img.src || img.dataset.src;
        });
        return;
    }

    var lazyImages = document.querySelectorAll('img[loading="lazy"]');
    var lazyImageObserver = new IntersectionObserver(function(entries, observer) {
        entries.forEach(function(entry) {
            if (entry.isIntersecting) {
                var lazyImage = entry.target;
                
                // Load the image
                if (lazyImage.dataset.src) {
                    lazyImage.src = lazyImage.dataset.src;
                    lazyImage.removeAttribute('data-src');
                }
                
                // Remove loading attribute to prevent future processing
                lazyImage.removeAttribute('loading');
                
                // Stop observing this image
                lazyImageObserver.unobserve(lazyImage);
                
                // Add a class to indicate the image has been loaded
                lazyImage.classList.add('lazy-loaded');
            }
        });
    }, {
        // Start loading when image is 100px away from viewport
        rootMargin: '100px'
    });

    // Start observing all lazy images
    lazyImages.forEach(function(lazyImage) {
        lazyImageObserver.observe(lazyImage);
    });

    // Handle images that are added dynamically to the page
    function observeNewImages() {
        var newLazyImages = document.querySelectorAll('img[loading="lazy"]:not(.lazy-loaded)');
        newLazyImages.forEach(function(lazyImage) {
            lazyImageObserver.observe(lazyImage);
        });
    }

    // Optional: Call observeNewImages() if you dynamically add images to the page
    window.observeLazyImages = observeNewImages;

})();