// theme.js
window.cmsMetadata = {
    template: "acer",
    page: "index",
    baseUrl: "https://acerratings.com/",
    // Flag to indicate server-side rendering is complete
    rendered: true,
};
class ThemeManager {
    constructor() {
        // Force light theme - ignore system preferences
        this.prefersDarkScheme = null; // Disable system preference detection
        this.savedTheme = localStorage.getItem("theme");

        // Clear any existing dark theme preference and set light
        localStorage.setItem("theme", "light");
        this.savedTheme = "light";

        // Initialize theme immediately
        this.initializeTheme();

        // Set up observers and listeners
        this.setupObservers();
        this.setupEventListeners();
    }

    initializeTheme() {
        // Always default to light theme
        const theme = this.savedTheme || "light";
        this.setTheme(theme);
    }

    setupObservers() {
        // Create an observer for theme toggle button
        this.observer = new MutationObserver((mutations) => {
            mutations.forEach((mutation) => {
                if (mutation.addedNodes.length) {
                    this.initializeElements();
                }
            });
        });

        // Start observing
        this.observer.observe(document.body, {
            childList: true,
            subtree: true,
        });
    }

    initializeElements() {
        this.themeToggle = document.querySelector(".theme-toggle");
        this.themeIcon = this.themeToggle?.querySelector("i");
        this.hamburgerMenu = document.querySelector(".hamburger-menu i");
        this.navLinks = document.querySelector(".nav-links");

        if (this.themeToggle && !this.themeToggle.hasListener) {
            this.setupThemeToggle();
            this.themeToggle.hasListener = true;
        }

        if (this.hamburgerMenu && !this.hamburgerMenu.hasListener) {
            this.setupMobileMenu();
            this.hamburgerMenu.hasListener = true;
        }

        // Update icon based on current theme
        this.updateThemeIcon(
            document.documentElement.getAttribute("data-theme")
        );
    }

    setTheme(theme) {
        // Always force light theme regardless of input
        const forcedTheme = "light";

        document.documentElement.setAttribute("data-theme", forcedTheme);
        document.documentElement.classList.remove("dark"); // Remove TailwindCSS dark class
        document.documentElement.classList.add("light"); // Add light class

        localStorage.setItem("theme", forcedTheme);
        this.updateThemeIcon(forcedTheme);

        // Override TailwindCSS color-scheme
        document.documentElement.style.colorScheme = "light";

        // Add aggressive CSS override
        this.injectLightThemeCSS();
    }

    injectLightThemeCSS() {
        // Remove existing override if any
        const existingStyle = document.getElementById("force-light-theme");
        if (existingStyle) {
            existingStyle.remove();
        }

        // Create and inject CSS that forces light theme
        const style = document.createElement("style");
        style.id = "force-light-theme";
        style.textContent = `
            /* Force light theme - highest priority */
            html, body {
                color-scheme: light !important;
                background-color: white !important;
                color: #666666 !important;
            }
            
            /* Override all dark classes */
            .dark\\:bg-gray-900,
            .dark\\:bg-gray-800,
            .dark\\:bg-gray-700,
            .dark\\:bg-slate-900,
            .dark\\:bg-slate-800 {
                background-color: white !important;
            }
            
            .dark\\:text-white,
            .dark\\:text-gray-100,
            .dark\\:text-gray-200 {
                color: #666666 !important;
            }
            
            /* Override media query completely */
            @media (prefers-color-scheme: dark) {
                *, *::before, *::after {
                    color-scheme: light !important;
                }
                
                html, body {
                    background: white !important;
                    color: #666666 !important;
                }
            }
        `;

        document.head.appendChild(style);
    }

    updateThemeIcon(theme) {
        if (this.themeIcon) {
            this.themeIcon.className =
                theme === "dark" ? "fas fa-sun" : "fas fa-moon";
        }
    }

    setupEventListeners() {
        // Completely ignore system theme changes - force light theme always
        // No system preference listener needed

        // Initialize elements when DOM is ready
        if (document.readyState === "loading") {
            document.addEventListener("DOMContentLoaded", () =>
                this.initializeElements()
            );
        } else {
            this.initializeElements();
        }

        // Listen for dynamic content loading
        window.addEventListener("contentLoaded", () =>
            this.initializeElements()
        );

        // Force light theme on page load and refresh
        window.addEventListener("load", () => {
            this.setTheme("light");
        });
    }

    setupThemeToggle() {
        if (this.themeToggle) {
            this.themeToggle.addEventListener("click", (e) => {
                e.preventDefault();
                // Always stay on light theme - disable toggle functionality
                this.setTheme("light");
            });
        }
    }

    setupMobileMenu() {
        if (this.hamburgerMenu && this.navLinks) {
            this.hamburgerMenu.addEventListener("click", () => {
                this.navLinks.classList.toggle("show");
                document.body.classList.toggle("menu-open");
                this.hamburgerMenu.classList.toggle("fa-bars");
                this.hamburgerMenu.classList.toggle("fa-times");
            });
        }
    }
}

//export default ThemeManager;
new ThemeManager();

// Combined SEO Manager File
class SEOManager {
    constructor() {
        // Use server-injected metadata if available, otherwise detect dynamically
        if (window.cmsMetadata) {
            this.baseUrl = window.cmsMetadata.baseUrl;
            this.templateName = window.cmsMetadata.template;
            this.pageName = window.cmsMetadata.page;
            this.metadataPath = window.cmsMetadata.metadataPath;
            this.templatePath = window.cmsMetadata.templatePath;
        } else {
            // Fallback to dynamic detection
            this.baseUrl = this.getBaseUrl();
            this.templateName = document.body.dataset.template || "default";
            this.pageName = document.body.dataset.page || "home";
            this.metadataPath = null;
            this.templatePath = null;
        }
        this.metadata = null;
        this.template = null;
    }

    getBaseUrl() {
        // Get the current domain and protocol
        const protocol = window.location.protocol;
        const host = window.location.host;
        const pathname = window.location.pathname;

        // Try to detect base path from various indicators
        let basePath = "";

        // Method 1: Check for common CMS directory patterns
        if (pathname.includes("/cms/")) {
            basePath = pathname.substring(0, pathname.indexOf("/cms/") + 4);
        }
        // Method 2: Check if we're in a subdirectory (not root domain)
        else if (pathname !== "/" && !pathname.match(/\.(php|html|htm)$/)) {
            // Extract base path from current URL
            const pathParts = pathname
                .split("/")
                .filter((part) => part.length > 0);
            if (pathParts.length > 0) {
                // Use first part as potential base directory
                basePath = "/" + pathParts[0];
            }
        }
        // Method 3: Check for script tag src to determine base path
        else {
            const scripts = document.querySelectorAll(
                'script[src*="/includes/"]'
            );
            if (scripts.length > 0) {
                const scriptSrc = scripts[0].src;
                const includesIndex = scriptSrc.indexOf("/includes/");
                if (includesIndex > 0) {
                    const baseFromScript = scriptSrc.substring(
                        0,
                        includesIndex
                    );
                    const urlObj = new URL(baseFromScript);
                    basePath = urlObj.pathname;
                }
            }
        }

        return `${protocol}//${host}${basePath}/includes`;
    }

    async initialize() {
        try {
            // Skip external file loading if server-rendered metadata is available
            if (window.cmsMetadata && window.cmsMetadata.rendered) {
                return true;
            }

            // Fallback for non-server-rendered pages (legacy support)
            await this.loadTemplate();
            await this.loadMetadata();
            this.updatePageMetadata();
            return true;
        } catch (error) {
            console.error("SEO Manager initialization failed:", error);
            return false;
        }
    }

    async loadTemplate() {
        // Use server-provided path if available, otherwise try multiple URLs
        const possibleUrls = [];

        if (this.templatePath) {
            possibleUrls.push(`${this.baseUrl}${this.templatePath}`);
        }

        // Add fallback URLs
        possibleUrls.push(
            `${this.baseUrl}/templates/${this.templateName}/blocks/global/metadata.tmpl`,
            `/includes/templates/${this.templateName}/blocks/global/metadata.tmpl`,
            `./includes/templates/${this.templateName}/blocks/global/metadata.tmpl`,
            `/cms/includes/templates/${this.templateName}/blocks/global/metadata.tmpl`
        );

        for (const templateUrl of possibleUrls) {
            try {
                const response = await fetch(templateUrl);
                if (response.ok) {
                    this.template = await response.text();
                    return;
                }
            } catch (error) {
                // Continue to next URL
            }
        }

        console.error("Failed to load metadata template from all URLs");
        this.template = this.getDefaultTemplate();
    }

    async loadMetadata() {
        // Use server-provided path if available, otherwise try multiple URLs
        const possibleUrls = [];

        if (this.metadataPath) {
            possibleUrls.push(`${this.baseUrl}${this.metadataPath}`);
        }

        // Add fallback URLs
        possibleUrls.push(
            `${this.baseUrl}/templates/${this.templateName}/content/global/metadata.json`,
            `/includes/templates/${this.templateName}/content/global/metadata.json`,
            `./includes/templates/${this.templateName}/content/global/metadata.json`,
            `/cms/includes/templates/${this.templateName}/content/global/metadata.json`
        );

        for (const metadataUrl of possibleUrls) {
            try {
                const response = await fetch(metadataUrl);
                if (response.ok) {
                    const data = await response.json();

                    // Create metadata object with proper fallback chain
                    this.metadata = {
                        ...data.global, // Global values as base
                        ...data.default, // Default page values override globals
                        ...(data.pages?.[this.pageName] || {}), // Page-specific values override defaults
                        global: data.global, // Keep global values accessible for template variables
                    };
                    return;
                }
            } catch (error) {
                // Continue to next URL
            }
        }

        console.error("Failed to load metadata from all URLs");
        this.metadata = {};
    }

    processTemplate(template, data) {
        let processed = template;

        // Process nested replacements until no more changes are made
        let previousResult;
        do {
            previousResult = processed;
            processed = processed.replace(/\{\{([^}]+)\}\}/g, (match, key) => {
                // Handle nested properties (e.g., {{global.siteName}})
                if (key.includes(".")) {
                    const [obj, prop] = key.split(".");
                    return data[obj]?.[prop] || "";
                }
                // Handle direct properties
                return data[key] || "";
            });
        } while (processed !== previousResult);

        return processed;
    }

    updatePageMetadata() {
        if (!this.template || !this.metadata) return;

        // Process template with metadata
        const processedTemplate = this.processTemplate(
            this.template,
            this.metadata
        );

        // Create temporary element to parse processed template
        const tempDiv = document.createElement("div");
        tempDiv.innerHTML = processedTemplate;

        // Update or create meta tags
        const metaTags = tempDiv.querySelectorAll("meta, title, link");
        metaTags.forEach((newTag) => {
            const selector = this.createSelector(newTag);
            const existingTag = document.querySelector(selector);

            if (existingTag) {
                this.updateTag(existingTag, newTag);
            } else if (this.hasValidContent(newTag)) {
                document.head.appendChild(newTag.cloneNode(true));
            }
        });
    }

    createSelector(tag) {
        if (tag.tagName === "TITLE") return "title";

        const attributes = Array.from(tag.attributes);
        const selectorParts = attributes
            .filter((attr) => ["name", "property", "rel"].includes(attr.name))
            .map((attr) => `[${attr.name}="${attr.value}"]`);

        return `${tag.tagName.toLowerCase()}${selectorParts.join("")}`;
    }

    updateTag(existing, newTag) {
        if (existing.tagName === "TITLE") {
            if (newTag.textContent) {
                existing.textContent = newTag.textContent;
            }
            return;
        }

        Array.from(newTag.attributes).forEach((attr) => {
            const value = attr.value.trim();
            if (value && value !== "{{undefined}}" && !value.includes("{{")) {
                existing.setAttribute(attr.name, value);
            }
        });
    }

    hasValidContent(tag) {
        if (tag.tagName === "TITLE") {
            return tag.textContent && !tag.textContent.includes("{{");
        }

        return Array.from(tag.attributes).some((attr) => {
            const value = attr.value.trim();
            return value && !value.includes("{{") && value !== "undefined";
        });
    }

    getDefaultTemplate() {
        return `
            <title>{{title}}</title>
            <meta charset="UTF-8">
            <meta name="viewport" content="width=device-width, initial-scale=1.0">
            <meta name="description" content="{{description}}">
            <meta name="keywords" content="{{keywords}}">
            <meta name="robots" content="{{robots}}">
            <link rel="canonical" href="{{canonical}}">
            <meta property="og:type" content="{{ogType}}">
            <meta property="og:title" content="{{ogTitle}}">
            <meta property="og:description" content="{{ogDescription}}">
            <meta property="og:image" content="{{ogImage}}">
            <meta property="og:url" content="{{ogUrl}}">
            <meta property="og:site_name" content="{{siteName}}">
            <meta name="twitter:card" content="{{twitterCard}}">
            <meta name="twitter:title" content="{{twitterTitle}}">
            <meta name="twitter:description" content="{{twitterDescription}}">
            <meta name="twitter:image" content="{{twitterImage}}">
            <meta name="theme-color" content="{{themeColor}}">
            <link rel="icon" type="image/x-icon" href="{{favicon}}">
        `;
    }
}

// Initialize SEO metadata as soon as the file loads
(async function () {
    const seoManager = new SEOManager();
    await seoManager.initialize();
})();

// Optionally export the SEOManager class if needed elsewhere
// export default SEOManager; // Commented out for combined JS compatibility;

// assets/js/templateManager.js
class TemplateManager {
    constructor() {
        // Handle initialization
        this.init();
        // Update storage info after DOM is ready
        if (document.readyState === "loading") {
            document.addEventListener("DOMContentLoaded", () =>
                this.updateStorageInfo()
            );
        } else {
            this.updateStorageInfo();
        }
    }

    init() {
        const urlParams = new URLSearchParams(window.location.search);

        // Handle template setting
        if (urlParams.has("set")) {
            const templateName = urlParams.get("set");
            // Only set if it's different from current
            if (templateName !== this.getStoredTemplate()) {
                this.setTemplate(templateName);
                // Reload without params
                const url = new URL(window.location.href);
                url.searchParams.delete("set");
                window.location.href = url.toString();
                return; // Stop further execution
            }
        }

        // Handle template reset
        if (urlParams.has("reset") && urlParams.get("reset") === "yes") {
            this.resetTemplate();
            // Reload without params
            const url = new URL(window.location.href);
            url.searchParams.delete("reset");
            window.location.href = url.toString();
            return; // Stop further execution
        }

        // Update body template if no URL params
        this.updateBodyTemplate();
    }

    getStoredTemplate() {
        try {
            return localStorage.getItem("cms_template");
        } catch (e) {
            console.error("Failed to access localStorage:", e);
            return null;
        }
    }

    setTemplate(template) {
        try {
            localStorage.setItem("cms_template", template);
            localStorage.setItem("cms_domain", window.location.hostname);

            // Set cookie for PHP
            document.cookie = `cms_template=${template};path=/`;

            this.updateBodyTemplate();
            this.updateStorageInfo();

            // Reload page
            const url = new URL(window.location.href);
            url.searchParams.delete("set");
            window.location.href = url.toString();
        } catch (e) {
            console.error("Failed to save template to localStorage:", e);
        }
    }

    resetTemplate() {
        try {
            localStorage.removeItem("cms_template");
            localStorage.removeItem("cms_domain");

            // Remove cookie
            document.cookie =
                "cms_template=;path=/;expires=Thu, 01 Jan 1970 00:00:01 GMT";

            this.updateBodyTemplate();
            this.updateStorageInfo();

            // Reload page
            const url = new URL(window.location.href);
            url.searchParams.delete("reset");
            window.location.href = url.toString();
        } catch (e) {
            console.error("Failed to reset template:", e);
        }
    }

    updateStorageInfo() {
        try {
            const template = this.getStoredTemplate() || "Not set";
            const domain = localStorage.getItem("cms_domain") || "Not set";

            // Update storage info display
            const storageInfoElement = document.getElementById("storageInfo");
            if (storageInfoElement) {
                storageInfoElement.textContent = `Template: ${template}\nDomain: ${domain}`;
            }

            // Update debug info if in development mode
            const debugInfo = document.querySelector(".debug-info");
            if (debugInfo) {
                const templateResolution = debugInfo.querySelector("pre");
                if (templateResolution) {
                    const content = templateResolution.textContent;
                    if (content.includes("<will be shown in JavaScript>")) {
                        templateResolution.textContent = content.replace(
                            "<will be shown in JavaScript>",
                            `localStorage template: ${template}`
                        );
                    }
                }
            }

            // Storage info updated silently
        } catch (e) {
            console.error("Failed to update storage info:", e);
            const storageInfoElement = document.getElementById("storageInfo");
            if (storageInfoElement) {
                storageInfoElement.textContent = "localStorage not available";
            }
        }
    }

    updateBodyTemplate() {
        const storedTemplate = this.getStoredTemplate();
        if (storedTemplate) {
            document.body.setAttribute("data-template", storedTemplate);
        }
    }
}

// Initialize template manager after DOM is ready
if (document.readyState === "loading") {
    document.addEventListener("DOMContentLoaded", () => {
        window.templateManager = new TemplateManager();
    });
} else {
    window.templateManager = new TemplateManager();
}
