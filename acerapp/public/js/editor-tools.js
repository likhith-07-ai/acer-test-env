/**
 * Editor.js Custom Tools
 * All custom rich content tools for Research Articles Editor
 */

// Image Upload Tool
class ImageUploadTool {
    static get toolbox() {
        return {
            title: 'Image',
            icon: '<svg width="20" height="20" viewBox="0 0 20 20" fill="none" xmlns="http://www.w3.org/2000/svg"><path d="M4 3C2.89543 3 2 3.89543 2 5V15C2 16.1046 2.89543 17 4 17H16C17.1046 17 18 16.1046 18 15V5C18 3.89543 17.1046 3 16 3H4ZM4 5H16V15H4L4 5ZM6 7C5.44772 7 5 7.44772 5 8C5 8.55228 5.44772 9 6 9C6.55228 9 7 8.55228 7 8C7 7.44772 6.55228 7 6 7ZM5 11L5 13L7 13L10 10L13 13L15 13L15 11L13 11L10 8L7 11L5 11Z" fill="currentColor"/></svg>'
        };
    }

    constructor({ data, api, readOnly }) {
        this.api = api;
        this.readOnly = readOnly;
        this.data = {
            url: data.url || '',
            caption: data.caption || ''
        };
        this.wrapper = null;
        this.uploadImageUrl = window.editorToolsConfig?.uploadImageUrl || '';
    }

    render() {
        this.wrapper = document.createElement('div');
        this.wrapper.classList.add('image-tool');

        if (this.data.url) {
            this._createImage();
        } else {
            this._createUploadButton();
        }

        return this.wrapper;
    }

    _createUploadButton() {
        const uploadButton = document.createElement('button');
        uploadButton.type = 'button';
        uploadButton.classList.add('cdx-button', 'cdx-button--primary');
        uploadButton.innerHTML = '<svg width="20" height="20" viewBox="0 0 20 20" fill="none" xmlns="http://www.w3.org/2000/svg"><path d="M11 9H16.5L11 3.5V9ZM4 2H12L18 8V16C18 17.1 17.1 18 16 18H4C2.9 18 2 17.1 2 16V4C2 2.9 2.9 2 4 2ZM4 4V16H16V9H9V4H4Z" fill="currentColor"/></svg> Upload Image';
        uploadButton.addEventListener('click', () => this._handleUpload());
        this.wrapper.appendChild(uploadButton);
    }

    _createImage() {
        const imageContainer = document.createElement('div');
        imageContainer.classList.add('image-tool__image');
        
        const img = document.createElement('img');
        img.src = this.data.url;
        img.style.maxWidth = '100%';
        img.style.height = 'auto';
        img.style.borderRadius = '8px';
        
        const captionInput = document.createElement('input');
        captionInput.type = 'text';
        captionInput.placeholder = 'Image caption (optional)';
        captionInput.value = this.data.caption || '';
        captionInput.classList.add('cdx-input');
        captionInput.style.marginTop = '10px';
        captionInput.addEventListener('input', (e) => {
            this.data.caption = e.target.value;
        });

        const removeButton = document.createElement('button');
        removeButton.type = 'button';
        removeButton.classList.add('cdx-button', 'cdx-button--danger');
        removeButton.style.marginTop = '10px';
        removeButton.textContent = 'Remove';
        removeButton.addEventListener('click', () => {
            this.data.url = '';
            this.data.caption = '';
            this.wrapper.innerHTML = '';
            this._createUploadButton();
        });

        imageContainer.appendChild(img);
        imageContainer.appendChild(captionInput);
        imageContainer.appendChild(removeButton);
        this.wrapper.innerHTML = '';
        this.wrapper.appendChild(imageContainer);
    }

    async _handleUpload() {
        const input = document.createElement('input');
        input.type = 'file';
        input.accept = 'image/*';
        input.onchange = async (e) => {
            const file = e.target.files[0];
            if (!file) return;

            const formData = new FormData();
            formData.append('image', file);

            try {
                const uploadUrl = this.uploadImageUrl || window.editorToolsConfig?.uploadImageUrl;
                if (!uploadUrl) {
                    alert('Upload URL not configured');
                    return;
                }

                const response = await fetch(uploadUrl, {
                    method: 'POST',
                    body: formData,
                    headers: {
                        'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
                    }
                });

                const result = await response.json();
                if (result.success === 1) {
                    this.data.url = result.file.url;
                    this._createImage();
                } else {
                    alert('Failed to upload image');
                }
            } catch (error) {
                console.error('Upload error:', error);
                alert('Error uploading image');
            }
        };
        input.click();
    }

    save() {
        return this.data;
    }

    static get sanitize() {
        return {
            url: {},
            caption: {}
        };
    }
}

// Document Attachment Tool
class DocumentAttachmentTool {
    static get toolbox() {
        return {
            title: 'Document',
            icon: '<svg width="20" height="20" viewBox="0 0 20 20" fill="none" xmlns="http://www.w3.org/2000/svg"><path d="M4 2C2.9 2 2 2.9 2 4V16C2 17.1 2.9 18 4 18H16C17.1 18 18 17.1 18 16V7L11 0H4ZM13 7V2L18 7H13ZM4 4H10V9H15V16H4V4Z" fill="currentColor"/></svg>'
        };
    }

    constructor({ data, api, readOnly }) {
        this.api = api;
        this.readOnly = readOnly;
        this.data = {
            url: data.url || '',
            name: data.name || '',
            size: data.size || 0
        };
        this.wrapper = null;
        this.uploadDocumentUrl = window.editorToolsConfig?.uploadDocumentUrl || '';
    }

    render() {
        this.wrapper = document.createElement('div');
        this.wrapper.classList.add('attachment-tool');

        if (this.data.url) {
            this._createAttachment();
        } else {
            this._createUploadButton();
        }

        return this.wrapper;
    }

    _createUploadButton() {
        const uploadButton = document.createElement('button');
        uploadButton.type = 'button';
        uploadButton.classList.add('cdx-button', 'cdx-button--primary');
        uploadButton.innerHTML = '<svg width="20" height="20" viewBox="0 0 20 20" fill="none" xmlns="http://www.w3.org/2000/svg"><path d="M11 9H16.5L11 3.5V9ZM4 2H12L18 8V16C18 17.1 17.1 18 16 18H4C2.9 18 2 17.1 2 16V4C2 2.9 2.9 2 4 2ZM4 4V16H16V9H9V4H4Z" fill="currentColor"/></svg> Attach Document';
        uploadButton.addEventListener('click', () => this._handleUpload());
        this.wrapper.appendChild(uploadButton);
    }

    _createAttachment() {
        const attachmentContainer = document.createElement('div');
        attachmentContainer.classList.add('attachment-tool__file');
        attachmentContainer.style.border = '1px solid #e5e7eb';
        attachmentContainer.style.borderRadius = '8px';
        attachmentContainer.style.padding = '12px';
        attachmentContainer.style.backgroundColor = '#f9fafb';
        attachmentContainer.style.display = 'flex';
        attachmentContainer.style.alignItems = 'center';
        attachmentContainer.style.gap = '12px';

        const icon = document.createElement('div');
        icon.innerHTML = '<svg width="24" height="24" viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg"><path d="M14 2H6C4.9 2 4 2.9 4 4V20C4 21.1 4.89 22 5.99 22H18C19.1 22 20 21.1 20 20V8L14 2ZM18 20H6V4H13V9H18V20Z" fill="currentColor"/></svg>';
        icon.style.color = '#6b7280';

        const fileInfo = document.createElement('div');
        fileInfo.style.flex = '1';

        const fileName = document.createElement('div');
        fileName.style.fontWeight = '500';
        fileName.style.color = '#111827';
        fileName.textContent = this.data.name || 'Document';

        const fileSize = document.createElement('div');
        fileSize.style.fontSize = '12px';
        fileSize.style.color = '#6b7280';
        fileSize.textContent = this._formatFileSize(this.data.size);

        fileInfo.appendChild(fileName);
        fileInfo.appendChild(fileSize);

        const downloadLink = document.createElement('a');
        downloadLink.href = this.data.url;
        downloadLink.target = '_blank';
        downloadLink.classList.add('cdx-button', 'cdx-button--primary');
        downloadLink.style.textDecoration = 'none';
        downloadLink.textContent = 'Download';

        const removeButton = document.createElement('button');
        removeButton.type = 'button';
        removeButton.classList.add('cdx-button', 'cdx-button--danger');
        removeButton.textContent = 'Remove';
        removeButton.addEventListener('click', () => {
            this.data.url = '';
            this.data.name = '';
            this.data.size = 0;
            this.wrapper.innerHTML = '';
            this._createUploadButton();
        });

        attachmentContainer.appendChild(icon);
        attachmentContainer.appendChild(fileInfo);
        attachmentContainer.appendChild(downloadLink);
        attachmentContainer.appendChild(removeButton);
        this.wrapper.innerHTML = '';
        this.wrapper.appendChild(attachmentContainer);
    }

    _formatFileSize(bytes) {
        if (!bytes) return '';
        if (bytes < 1024) return bytes + ' B';
        if (bytes < 1024 * 1024) return (bytes / 1024).toFixed(1) + ' KB';
        return (bytes / (1024 * 1024)).toFixed(1) + ' MB';
    }

    async _handleUpload() {
        const input = document.createElement('input');
        input.type = 'file';
        input.accept = '.pdf,.doc,.docx,.xls,.xlsx,.ppt,.pptx,.txt';
        input.onchange = async (e) => {
            const file = e.target.files[0];
            if (!file) return;

            const formData = new FormData();
            formData.append('file', file);

            try {
                const uploadUrl = this.uploadDocumentUrl || window.editorToolsConfig?.uploadDocumentUrl;
                if (!uploadUrl) {
                    alert('Upload URL not configured');
                    return;
                }

                const response = await fetch(uploadUrl, {
                    method: 'POST',
                    body: formData,
                    headers: {
                        'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
                    }
                });

                const result = await response.json();
                if (result.success === 1) {
                    this.data.url = result.file.url;
                    this.data.name = result.file.name;
                    this.data.size = result.file.size;
                    this._createAttachment();
                } else {
                    alert('Failed to upload document');
                }
            } catch (error) {
                console.error('Upload error:', error);
                alert('Error uploading document');
            }
        };
        input.click();
    }

    save() {
        return this.data;
    }

    static get sanitize() {
        return {
            url: {},
            name: {},
            size: {}
        };
    }
}

// Video Embed Tool
class VideoEmbedTool {
    static get toolbox() {
        return {
            title: 'Video',
            icon: '<svg width="20" height="20" viewBox="0 0 20 20" fill="none" xmlns="http://www.w3.org/2000/svg"><path d="M2 5C2 3.89543 2.89543 3 4 3H16C17.1046 3 18 3.89543 18 5V15C18 16.1046 17.1046 17 16 17H4C2.89543 17 2 16.1046 2 15V5ZM4 5V15H16V5H4ZM8 7L13 10L8 13V7Z" fill="currentColor"/></svg>'
        };
    }

    constructor({ data, api, readOnly }) {
        this.api = api;
        this.readOnly = readOnly;
        this.data = {
            url: data.url || '',
            service: data.service || 'youtube',
            embed: data.embed || ''
        };
        this.wrapper = null;
    }

    render() {
        this.wrapper = document.createElement('div');
        this.wrapper.classList.add('video-embed-tool');

        if (this.data.embed) {
            this._createEmbed();
        } else {
            this._createInput();
        }

        return this.wrapper;
    }

    _createInput() {
        const container = document.createElement('div');
        container.style.padding = '16px';
        container.style.border = '1px dashed #d1d5db';
        container.style.borderRadius = '8px';
        container.style.backgroundColor = '#f9fafb';

        const label = document.createElement('label');
        label.textContent = 'Video URL (YouTube/Vimeo)';
        label.style.display = 'block';
        label.style.marginBottom = '8px';
        label.style.fontWeight = '500';
        label.style.color = '#374151';

        const input = document.createElement('input');
        input.type = 'url';
        input.placeholder = 'https://www.youtube.com/watch?v=... or https://vimeo.com/...';
        input.value = this.data.url || '';
        input.classList.add('cdx-input');
        input.style.width = '100%';
        input.style.marginBottom = '12px';

        const button = document.createElement('button');
        button.type = 'button';
        button.classList.add('cdx-button', 'cdx-button--primary');
        button.textContent = 'Embed Video';
        button.addEventListener('click', () => this._handleEmbed(input.value));

        container.appendChild(label);
        container.appendChild(input);
        container.appendChild(button);
        this.wrapper.appendChild(container);
    }

    _createEmbed() {
        const container = document.createElement('div');
        container.style.position = 'relative';
        container.style.paddingBottom = '56.25%';
        container.style.height = '0';
        container.style.overflow = 'hidden';
        container.style.borderRadius = '8px';
        container.style.marginBottom = '12px';

        const iframe = document.createElement('iframe');
        iframe.src = this.data.embed;
        iframe.style.position = 'absolute';
        iframe.style.top = '0';
        iframe.style.left = '0';
        iframe.style.width = '100%';
        iframe.style.height = '100%';
        iframe.setAttribute('frameborder', '0');
        iframe.setAttribute('allowfullscreen', 'true');

        const removeButton = document.createElement('button');
        removeButton.type = 'button';
        removeButton.classList.add('cdx-button', 'cdx-button--danger');
        removeButton.textContent = 'Remove';
        removeButton.style.marginTop = '8px';
        removeButton.addEventListener('click', () => {
            this.data.url = '';
            this.data.embed = '';
            this.wrapper.innerHTML = '';
            this._createInput();
        });

        container.appendChild(iframe);
        this.wrapper.innerHTML = '';
        this.wrapper.appendChild(container);
        this.wrapper.appendChild(removeButton);
    }

    _handleEmbed(url) {
        if (!url) return;

        let embedUrl = '';
        let service = 'youtube';

        // YouTube
        const youtubeRegex = /(?:youtube\.com\/(?:[^\/]+\/.+\/|(?:v|e(?:mbed)?)\/|.*[?&]v=)|youtu\.be\/)([^"&?\/\s]{11})/;
        const youtubeMatch = url.match(youtubeRegex);
        if (youtubeMatch) {
            embedUrl = `https://www.youtube.com/embed/${youtubeMatch[1]}`;
            service = 'youtube';
        }
        // Vimeo
        else {
            const vimeoRegex = /vimeo\.com\/(?:.*\/)?(\d+)/;
            const vimeoMatch = url.match(vimeoRegex);
            if (vimeoMatch) {
                embedUrl = `https://player.vimeo.com/video/${vimeoMatch[1]}`;
                service = 'vimeo';
            }
        }

        if (embedUrl) {
            this.data.url = url;
            this.data.service = service;
            this.data.embed = embedUrl;
            this._createEmbed();
        } else {
            alert('Invalid YouTube or Vimeo URL');
        }
    }

    save() {
        return this.data;
    }

    static get sanitize() {
        return {
            url: {},
            service: {},
            embed: {}
        };
    }
}

// Alert Box Tool
class AlertBoxTool {
    static get toolbox() {
        return {
            title: 'Alert',
            icon: '<svg width="20" height="20" viewBox="0 0 20 20" fill="none" xmlns="http://www.w3.org/2000/svg"><path d="M10 2C5.58172 2 2 5.58172 2 10C2 14.4183 5.58172 18 10 18C14.4183 18 18 14.4183 18 10C18 5.58172 14.4183 2 10 2ZM9 13V11H11V13H9ZM9 9V6H11V9H9Z" fill="currentColor"/></svg>'
        };
    }

    constructor({ data, api, readOnly }) {
        this.api = api;
        this.readOnly = readOnly;
        this.data = {
            type: data.type || 'info',
            message: data.message || ''
        };
        this.wrapper = null;
    }

    render() {
        this.wrapper = document.createElement('div');
        this._createBox();
        return this.wrapper;
    }

    _createBox() {
        const container = document.createElement('div');
        const colors = {
            info: { bg: '#dbeafe', border: '#3b82f6', text: '#1e40af', icon: 'M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z' },
            success: { bg: '#d1fae5', border: '#10b981', text: '#065f46', icon: 'M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z' },
            warning: { bg: '#fef3c7', border: '#f59e0b', text: '#92400e', icon: 'M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z' },
            error: { bg: '#fee2e2', border: '#ef4444', text: '#991b1b', icon: 'M10 14l2-2m0 0l2-2m-2 2l-2-2m2 2l2 2m7-2a9 9 0 11-18 0 9 9 0 0118 0z' }
        };

        const color = colors[this.data.type] || colors.info;

        container.style.border = `2px solid ${color.border}`;
        container.style.borderRadius = '8px';
        container.style.padding = '16px';
        container.style.backgroundColor = color.bg;
        container.style.display = 'flex';
        container.style.gap = '12px';
        container.style.marginBottom = '12px';

        const icon = document.createElement('div');
        icon.innerHTML = `<svg width="24" height="24" viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg"><path d="${color.icon}" fill="${color.text}"/></svg>`;
        icon.style.flexShrink = '0';

        const content = document.createElement('div');
        content.style.flex = '1';

        const typeSelect = document.createElement('select');
        typeSelect.value = this.data.type;
        typeSelect.style.marginBottom = '8px';
        typeSelect.style.padding = '4px 8px';
        typeSelect.style.borderRadius = '4px';
        typeSelect.style.border = '1px solid #d1d5db';
        ['info', 'success', 'warning', 'error'].forEach(t => {
            const option = document.createElement('option');
            option.value = t;
            option.textContent = t.charAt(0).toUpperCase() + t.slice(1);
            typeSelect.appendChild(option);
        });
        typeSelect.addEventListener('change', (e) => {
            this.data.type = e.target.value;
            this._createBox();
        });

        const messageInput = document.createElement('textarea');
        messageInput.value = this.data.message;
        messageInput.placeholder = 'Enter alert message...';
        messageInput.style.width = '100%';
        messageInput.style.minHeight = '60px';
        messageInput.style.padding = '8px';
        messageInput.style.borderRadius = '4px';
        messageInput.style.border = '1px solid #d1d5db';
        messageInput.addEventListener('input', (e) => {
            this.data.message = e.target.value;
        });

        content.appendChild(typeSelect);
        content.appendChild(messageInput);
        container.appendChild(icon);
        container.appendChild(content);
        this.wrapper.innerHTML = '';
        this.wrapper.appendChild(container);
    }

    save() {
        return this.data;
    }

    static get sanitize() {
        return {
            type: {},
            message: {}
        };
    }
}

// Gallery Tool
class GalleryTool {
    static get toolbox() {
        return {
            title: 'Gallery',
            icon: '<svg width="20" height="20" viewBox="0 0 20 20" fill="none" xmlns="http://www.w3.org/2000/svg"><path d="M4 3C2.89543 3 2 3.89543 2 5V15C2 16.1046 2.89543 17 4 17H16C17.1046 17 18 16.1046 18 15V5C18 3.89543 17.1046 3 16 3H4ZM4 5H16V15H4L4 5ZM6 7C5.44772 7 5 7.44772 5 8C5 8.55228 5.44772 9 6 9C6.55228 9 7 8.55228 7 8C7 7.44772 6.55228 7 6 7ZM5 11L5 13L7 13L10 10L13 13L15 13L15 11L13 11L10 8L7 11L5 11Z" fill="currentColor"/></svg>'
        };
    }

    constructor({ data, api, readOnly }) {
        this.api = api;
        this.readOnly = readOnly;
        this.data = {
            images: data.images || []
        };
        this.wrapper = null;
        this.uploadImageUrl = window.editorToolsConfig?.uploadImageUrl || '';
    }

    render() {
        this.wrapper = document.createElement('div');
        this._createGallery();
        return this.wrapper;
    }

    _createGallery() {
        const container = document.createElement('div');
        container.style.display = 'grid';
        container.style.gridTemplateColumns = 'repeat(auto-fill, minmax(150px, 1fr))';
        container.style.gap = '12px';
        container.style.marginBottom = '12px';

        this.data.images.forEach((img, index) => {
            const imgContainer = document.createElement('div');
            imgContainer.style.position = 'relative';
            imgContainer.style.aspectRatio = '1';
            imgContainer.style.borderRadius = '8px';
            imgContainer.style.overflow = 'hidden';
            imgContainer.style.border = '2px solid #e5e7eb';

            const imgEl = document.createElement('img');
            imgEl.src = img.url;
            imgEl.style.width = '100%';
            imgEl.style.height = '100%';
            imgEl.style.objectFit = 'cover';

            const removeBtn = document.createElement('button');
            removeBtn.textContent = '×';
            removeBtn.style.position = 'absolute';
            removeBtn.style.top = '4px';
            removeBtn.style.right = '4px';
            removeBtn.style.width = '24px';
            removeBtn.style.height = '24px';
            removeBtn.style.borderRadius = '50%';
            removeBtn.style.backgroundColor = 'rgba(0,0,0,0.7)';
            removeBtn.style.color = 'white';
            removeBtn.style.border = 'none';
            removeBtn.style.cursor = 'pointer';
            removeBtn.addEventListener('click', () => {
                this.data.images.splice(index, 1);
                this._createGallery();
            });

            imgContainer.appendChild(imgEl);
            imgContainer.appendChild(removeBtn);
            container.appendChild(imgContainer);
        });

        const addButton = document.createElement('button');
        addButton.type = 'button';
        addButton.classList.add('cdx-button', 'cdx-button--primary');
        addButton.textContent = '+ Add Image';
        addButton.style.width = '100%';
        addButton.addEventListener('click', () => this._addImage());

        this.wrapper.innerHTML = '';
        this.wrapper.appendChild(container);
        if (this.data.images.length > 0 || !this.readOnly) {
            this.wrapper.appendChild(addButton);
        }
    }

    async _addImage() {
        const input = document.createElement('input');
        input.type = 'file';
        input.accept = 'image/*';
        input.multiple = true;
        input.onchange = async (e) => {
            const files = Array.from(e.target.files);
            for (const file of files) {
                const formData = new FormData();
                formData.append('image', file);

                try {
                    const uploadUrl = this.uploadImageUrl || window.editorToolsConfig?.uploadImageUrl;
                    if (!uploadUrl) {
                        alert('Upload URL not configured');
                        continue;
                    }

                    const response = await fetch(uploadUrl, {
                        method: 'POST',
                        body: formData,
                        headers: {
                            'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
                        }
                    });

                    const result = await response.json();
                    if (result.success === 1) {
                        this.data.images.push({ url: result.file.url });
                    }
                } catch (error) {
                    console.error('Upload error:', error);
                }
            }
            this._createGallery();
        };
        input.click();
    }

    save() {
        return this.data;
    }

    static get sanitize() {
        return {
            images: {}
        };
    }
}

// Button Tool
class ButtonTool {
    static get toolbox() {
        return {
            title: 'Button',
            icon: '<svg width="20" height="20" viewBox="0 0 20 20" fill="none" xmlns="http://www.w3.org/2000/svg"><path d="M10 2C5.58172 2 2 5.58172 2 10C2 14.4183 5.58172 18 10 18C14.4183 18 18 14.4183 18 10C18 5.58172 14.4183 2 10 2ZM11 14H9V12H11V14ZM11 10H9V6H11V10Z" fill="currentColor"/></svg>'
        };
    }

    constructor({ data, api, readOnly }) {
        this.api = api;
        this.readOnly = readOnly;
        this.data = {
            text: data.text || 'Click here',
            url: data.url || '',
            style: data.style || 'primary'
        };
        this.wrapper = null;
    }

    render() {
        this.wrapper = document.createElement('div');
        this._createButton();
        return this.wrapper;
    }

    _createButton() {
        const container = document.createElement('div');
        container.style.padding = '12px';
        container.style.border = '1px dashed #d1d5db';
        container.style.borderRadius = '8px';
        container.style.backgroundColor = '#f9fafb';

        const textInput = document.createElement('input');
        textInput.type = 'text';
        textInput.value = this.data.text;
        textInput.placeholder = 'Button text';
        textInput.style.width = '100%';
        textInput.style.marginBottom = '8px';
        textInput.style.padding = '8px';
        textInput.style.borderRadius = '4px';
        textInput.style.border = '1px solid #d1d5db';
        textInput.addEventListener('input', (e) => {
            this.data.text = e.target.value;
            previewButton.textContent = e.target.value || 'Click here';
        });

        const urlInput = document.createElement('input');
        urlInput.type = 'url';
        urlInput.value = this.data.url;
        urlInput.placeholder = 'Button URL';
        urlInput.style.width = '100%';
        urlInput.style.marginBottom = '8px';
        urlInput.style.padding = '8px';
        urlInput.style.borderRadius = '4px';
        urlInput.style.border = '1px solid #d1d5db';
        urlInput.addEventListener('input', (e) => {
            this.data.url = e.target.value;
            previewButton.href = e.target.value || '#';
        });

        const styleSelect = document.createElement('select');
        styleSelect.value = this.data.style;
        styleSelect.style.width = '100%';
        styleSelect.style.marginBottom = '8px';
        styleSelect.style.padding = '8px';
        styleSelect.style.borderRadius = '4px';
        styleSelect.style.border = '1px solid #d1d5db';
        ['primary', 'secondary', 'success', 'danger'].forEach(s => {
            const option = document.createElement('option');
            option.value = s;
            option.textContent = s.charAt(0).toUpperCase() + s.slice(1);
            styleSelect.appendChild(option);
        });
        styleSelect.addEventListener('change', (e) => {
            this.data.style = e.target.value;
            previewButton.className = `cdx-button cdx-button--${e.target.value}`;
        });

        const previewButton = document.createElement('a');
        previewButton.href = this.data.url || '#';
        previewButton.textContent = this.data.text || 'Click here';
        previewButton.classList.add('cdx-button', `cdx-button--${this.data.style}`);
        previewButton.style.display = 'inline-block';
        previewButton.style.textDecoration = 'none';
        previewButton.target = '_blank';

        container.appendChild(textInput);
        container.appendChild(urlInput);
        container.appendChild(styleSelect);
        container.appendChild(previewButton);
        this.wrapper.innerHTML = '';
        this.wrapper.appendChild(container);
    }

    save() {
        return this.data;
    }

    static get sanitize() {
        return {
            text: {},
            url: {},
            style: {}
        };
    }
}

// Accordion Tool
class AccordionTool {
    static get toolbox() {
        return {
            title: 'Accordion',
            icon: '<svg width="20" height="20" viewBox="0 0 20 20" fill="none" xmlns="http://www.w3.org/2000/svg"><path d="M10 2L3 7V9H17V7L10 2ZM3 11V13H17V11H3ZM3 15V17H17V15H3Z" fill="currentColor"/></svg>'
        };
    }

    constructor({ data, api, readOnly }) {
        this.api = api;
        this.readOnly = readOnly;
        this.data = {
            items: data.items || [{ title: 'Title', content: 'Content' }]
        };
        this.wrapper = null;
    }

    render() {
        this.wrapper = document.createElement('div');
        this._createAccordion();
        return this.wrapper;
    }

    _createAccordion() {
        const container = document.createElement('div');
        container.style.border = '1px solid #e5e7eb';
        container.style.borderRadius = '8px';
        container.style.overflow = 'hidden';

        this.data.items.forEach((item, index) => {
            const itemDiv = document.createElement('div');
            itemDiv.style.borderBottom = index < this.data.items.length - 1 ? '1px solid #e5e7eb' : 'none';

            const header = document.createElement('div');
            header.style.padding = '12px';
            header.style.backgroundColor = '#f9fafb';
            header.style.display = 'flex';
            header.style.justifyContent = 'space-between';
            header.style.alignItems = 'center';
            header.style.cursor = 'pointer';

            const titleInput = document.createElement('input');
            titleInput.type = 'text';
            titleInput.value = item.title;
            titleInput.placeholder = 'Title';
            titleInput.style.flex = '1';
            titleInput.style.border = 'none';
            titleInput.style.backgroundColor = 'transparent';
            titleInput.style.fontWeight = '500';
            titleInput.addEventListener('input', (e) => {
                this.data.items[index].title = e.target.value;
            });

            const removeBtn = document.createElement('button');
            removeBtn.textContent = '×';
            removeBtn.style.marginLeft = '8px';
            removeBtn.style.width = '24px';
            removeBtn.style.height = '24px';
            removeBtn.style.borderRadius = '50%';
            removeBtn.style.border = 'none';
            removeBtn.style.backgroundColor = '#fee2e2';
            removeBtn.style.color = '#991b1b';
            removeBtn.style.cursor = 'pointer';
            removeBtn.addEventListener('click', () => {
                this.data.items.splice(index, 1);
                this._createAccordion();
            });

            const contentDiv = document.createElement('div');
            contentDiv.style.padding = '12px';
            contentDiv.style.display = 'none';

            const contentInput = document.createElement('textarea');
            contentInput.value = item.content;
            contentInput.placeholder = 'Content';
            contentInput.style.width = '100%';
            contentInput.style.minHeight = '60px';
            contentInput.style.border = '1px solid #d1d5db';
            contentInput.style.borderRadius = '4px';
            contentInput.style.padding = '8px';
            contentInput.addEventListener('input', (e) => {
                this.data.items[index].content = e.target.value;
            });

            header.addEventListener('click', () => {
                contentDiv.style.display = contentDiv.style.display === 'none' ? 'block' : 'none';
            });

            header.appendChild(titleInput);
            header.appendChild(removeBtn);
            contentDiv.appendChild(contentInput);
            itemDiv.appendChild(header);
            itemDiv.appendChild(contentDiv);
            container.appendChild(itemDiv);
        });

        const addButton = document.createElement('button');
        addButton.type = 'button';
        addButton.classList.add('cdx-button', 'cdx-button--primary');
        addButton.textContent = '+ Add Item';
        addButton.style.width = '100%';
        addButton.style.marginTop = '8px';
        addButton.addEventListener('click', () => {
            this.data.items.push({ title: 'Title', content: 'Content' });
            this._createAccordion();
        });

        this.wrapper.innerHTML = '';
        this.wrapper.appendChild(container);
        this.wrapper.appendChild(addButton);
    }

    save() {
        return this.data;
    }

    static get sanitize() {
        return {
            items: {}
        };
    }
}

// Table of Contents Tool
class TableOfContentsTool {
    static get toolbox() {
        return {
            title: 'Table of Contents',
            icon: '<svg width="20" height="20" viewBox="0 0 20 20" fill="none" xmlns="http://www.w3.org/2000/svg"><path d="M3 3H17V5H3V3ZM3 7H17V9H3V7ZM3 11H17V13H3V11ZM3 15H17V17H3V15Z" fill="currentColor"/></svg>'
        };
    }

    constructor({ data, api, readOnly }) {
        this.api = api;
        this.readOnly = readOnly;
        this.data = {
            title: data.title || 'Table of Contents'
        };
        this.wrapper = null;
    }

    render() {
        this.wrapper = document.createElement('div');
        const container = document.createElement('div');
        container.style.padding = '16px';
        container.style.border = '1px dashed #d1d5db';
        container.style.borderRadius = '8px';
        container.style.backgroundColor = '#f9fafb';

        const label = document.createElement('label');
        label.textContent = 'Title';
        label.style.display = 'block';
        label.style.marginBottom = '8px';
        label.style.fontWeight = '500';

        const input = document.createElement('input');
        input.type = 'text';
        input.value = this.data.title;
        input.style.width = '100%';
        input.style.padding = '8px';
        input.style.borderRadius = '4px';
        input.style.border = '1px solid #d1d5db';
        input.addEventListener('input', (e) => {
            this.data.title = e.target.value;
        });

        const note = document.createElement('p');
        note.textContent = 'Note: This will auto-generate from headings in the content';
        note.style.marginTop = '8px';
        note.style.fontSize = '12px';
        note.style.color = '#6b7280';

        container.appendChild(label);
        container.appendChild(input);
        container.appendChild(note);
        this.wrapper.appendChild(container);
        return this.wrapper;
    }

    save() {
        return this.data;
    }

    static get sanitize() {
        return {
            title: {}
        };
    }
}

// Raw HTML Tool
class RawHTMLTool {
    static get toolbox() {
        return {
            title: 'Raw HTML',
            icon: '<svg width="20" height="20" viewBox="0 0 20 20" fill="none" xmlns="http://www.w3.org/2000/svg"><path d="M2 3C2 2.44772 2.44772 2 3 2H17C17.5523 2 18 2.44772 18 3V17C18 17.5523 17.5523 18 17 18H3C2.44772 18 2 17.5523 2 17V3ZM4 4V16H16V4H4ZM6 6H14V8H6V6ZM6 10H14V12H6V10ZM6 14H10V16H6V14Z" fill="currentColor"/></svg>'
        };
    }

    constructor({ data, api, readOnly }) {
        this.api = api;
        this.readOnly = readOnly;
        this.data = {
            html: data.html || ''
        };
        this.wrapper = null;
    }

    render() {
        this.wrapper = document.createElement('div');
        const container = document.createElement('div');
        container.style.padding = '16px';
        container.style.border = '1px dashed #d1d5db';
        container.style.borderRadius = '8px';
        container.style.backgroundColor = '#f9fafb';

        const label = document.createElement('label');
        label.textContent = 'HTML Code';
        label.style.display = 'block';
        label.style.marginBottom = '8px';
        label.style.fontWeight = '500';

        const textarea = document.createElement('textarea');
        textarea.value = this.data.html;
        textarea.placeholder = '<div>Your HTML code here</div>';
        textarea.style.width = '100%';
        textarea.style.minHeight = '120px';
        textarea.style.padding = '8px';
        textarea.style.borderRadius = '4px';
        textarea.style.border = '1px solid #d1d5db';
        textarea.style.fontFamily = 'monospace';
        textarea.style.fontSize = '12px';
        textarea.addEventListener('input', (e) => {
            this.data.html = e.target.value;
        });

        const preview = document.createElement('div');
        preview.style.marginTop = '12px';
        preview.style.padding = '12px';
        preview.style.border = '1px solid #e5e7eb';
        preview.style.borderRadius = '4px';
        preview.style.backgroundColor = 'white';
        preview.style.minHeight = '60px';

        const updatePreview = () => {
            preview.innerHTML = this.data.html || '<em>Preview will appear here</em>';
        };

        textarea.addEventListener('input', updatePreview);
        updatePreview();

        container.appendChild(label);
        container.appendChild(textarea);
        container.appendChild(preview);
        this.wrapper.appendChild(container);
        return this.wrapper;
    }

    save() {
        return this.data;
    }

    static get sanitize() {
        return {
            html: {}
        };
    }
}

/**
 * Get Editor Tools Configuration
 * Returns an object with all custom tools configured
 * 
 * @param {string} uploadImageUrl - URL for image upload endpoint
 * @param {string} uploadDocumentUrl - URL for document upload endpoint
 * @returns {Object} Tools configuration object
 */
function getEditorTools(uploadImageUrl, uploadDocumentUrl) {
    // Set global config for tools to access
    if (!window.editorToolsConfig) {
        window.editorToolsConfig = {};
    }
    window.editorToolsConfig.uploadImageUrl = uploadImageUrl;
    window.editorToolsConfig.uploadDocumentUrl = uploadDocumentUrl;

    return {
        // Custom image upload tool
        image: {
            class: ImageUploadTool,
            inlineToolbar: true
        },

        // Custom document attachment tool
        attachment: {
            class: DocumentAttachmentTool
        },

        // Custom video embed tool
        videoEmbed: {
            class: VideoEmbedTool
        },

        // Custom alert box tool
        alertBox: {
            class: AlertBoxTool
        },

        // Custom gallery tool
        gallery: {
            class: GalleryTool
        },

        // Custom button tool
        button: {
            class: ButtonTool
        },

        // Custom accordion tool
        accordion: {
            class: AccordionTool
        },

        // Custom table of contents tool
        tableOfContents: {
            class: TableOfContentsTool
        },

        // Custom raw HTML tool
        rawHTML: {
            class: RawHTMLTool
        }
    };
}

