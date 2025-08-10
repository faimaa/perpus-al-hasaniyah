/**
 * User Photos JavaScript Functionality
 * Advanced features for user photo management
 */

class UserPhotoManager {
    constructor() {
        this.init();
    }

    init() {
        this.setupPhotoModals();
        this.setupPhotoFilters();
        this.setupPhotoUpload();
        this.setupPhotoHoverEffects();
    }

    /**
     * Setup photo modal functionality
     */
    setupPhotoModals() {
        // Create modal HTML
        const modalHTML = `
            <div id="photoModal" class="photo-modal">
                <span class="photo-modal-close">&times;</span>
                <img class="photo-modal-content" id="photoModalImg">
            </div>
        `;
        
        // Add modal to body if it doesn't exist
        if (!document.getElementById('photoModal')) {
            document.body.insertAdjacentHTML('beforeend', modalHTML);
        }

        // Add click event to all user photos
        document.addEventListener('click', (e) => {
            if (e.target.classList.contains('user-photo') || e.target.classList.contains('img-thumbnail')) {
                this.openPhotoModal(e.target.src, e.target.alt);
            }
        });

        // Close modal functionality
        const modal = document.getElementById('photoModal');
        const closeBtn = document.querySelector('.photo-modal-close');
        
        if (closeBtn) {
            closeBtn.onclick = () => this.closePhotoModal();
        }
        
        if (modal) {
            modal.onclick = (e) => {
                if (e.target === modal) {
                    this.closePhotoModal();
                }
            };
        }
    }

    /**
     * Open photo modal
     */
    openPhotoModal(src, alt) {
        const modal = document.getElementById('photoModal');
        const modalImg = document.getElementById('photoModalImg');
        
        if (modal && modalImg) {
            modal.style.display = 'block';
            modalImg.src = src;
            modalImg.alt = alt;
            
            // Add loading animation
            modalImg.classList.add('photo-loading');
            
            // Remove loading when image loads
            modalImg.onload = () => {
                modalImg.classList.remove('photo-loading');
            };
        }
    }

    /**
     * Close photo modal
     */
    closePhotoModal() {
        const modal = document.getElementById('photoModal');
        if (modal) {
            modal.style.display = 'none';
        }
    }

    /**
     * Setup photo filters
     */
    setupPhotoFilters() {
        // Add filter buttons to photo containers
        const photoContainers = document.querySelectorAll('.user-photo-container');
        
        photoContainers.forEach(container => {
            const photo = container.querySelector('img');
            if (photo) {
                const filterBar = this.createFilterBar(photo);
                container.appendChild(filterBar);
            }
        });
    }

    /**
     * Create filter bar for photos
     */
    createFilterBar(photo) {
        const filterBar = document.createElement('div');
        filterBar.className = 'photo-filter-bar';
        filterBar.style.cssText = 'margin-top: 10px; text-align: center;';
        
        const filters = [
            { name: 'Normal', class: '' },
            { name: 'Grayscale', class: 'photo-filter-grayscale' },
            { name: 'Sepia', class: 'photo-filter-sepia' },
            { name: 'Blur', class: 'photo-filter-blur' },
            { name: 'Bright', class: 'photo-filter-brightness' }
        ];

        filters.forEach(filter => {
            const btn = document.createElement('button');
            btn.textContent = filter.name;
            btn.className = 'btn btn-xs btn-default';
            btn.style.cssText = 'margin: 2px; padding: 2px 6px; font-size: 10px;';
            
            btn.onclick = () => {
                // Remove all filter classes
                photo.className = photo.className.replace(/photo-filter-\w+/g, '');
                // Add selected filter class
                if (filter.class) {
                    photo.classList.add(filter.class);
                }
            };
            
            filterBar.appendChild(btn);
        });

        return filterBar;
    }

    /**
     * Setup photo upload with drag and drop
     */
    setupPhotoUpload() {
        const fileInputs = document.querySelectorAll('input[type="file"][accept*="image"]');
        
        fileInputs.forEach(input => {
            const container = input.parentNode;
            
            // Add drag and drop zone
            const dropZone = this.createDropZone(container);
            container.appendChild(dropZone);
            
            // Handle drag and drop events
            dropZone.addEventListener('dragover', (e) => {
                e.preventDefault();
                dropZone.classList.add('drag-over');
            });
            
            dropZone.addEventListener('dragleave', () => {
                dropZone.classList.remove('drag-over');
            });
            
            dropZone.addEventListener('drop', (e) => {
                e.preventDefault();
                dropZone.classList.remove('drag-over');
                
                const files = e.dataTransfer.files;
                if (files.length > 0 && files[0].type.startsWith('image/')) {
                    input.files = files;
                    this.handleFileSelect(input);
                }
            });
        });
    }

    /**
     * Create drag and drop zone
     */
    createDropZone(container) {
        const dropZone = document.createElement('div');
        dropZone.className = 'photo-drop-zone';
        dropZone.style.cssText = `
            border: 2px dashed #ccc;
            border-radius: 8px;
            padding: 20px;
            text-align: center;
            margin-top: 10px;
            background: #f9f9f9;
            transition: all 0.3s ease;
        `;
        dropZone.innerHTML = '<i class="fa fa-cloud-upload" style="font-size: 24px; color: #999;"></i><br><small>Drag & drop foto di sini</small>';
        
        return dropZone;
    }

    /**
     * Handle file selection
     */
    handleFileSelect(input) {
        const file = input.files[0];
        if (file) {
            // Validate file
            if (!this.validateImageFile(file)) {
                return;
            }
            
            // Show preview
            this.showImagePreview(input, file);
            
            // Show success message
            this.showMessage('Foto berhasil dipilih!', 'success');
        }
    }

    /**
     * Validate image file
     */
    validateImageFile(file) {
        // Check file type
        if (!file.type.startsWith('image/')) {
            this.showMessage('Silakan pilih file gambar yang valid', 'error');
            return false;
        }
        
        // Check file size (max 2MB)
        if (file.size > 2 * 1024 * 1024) {
            this.showMessage('Ukuran file terlalu besar. Maksimal 2MB', 'error');
            return false;
        }
        
        return true;
    }

    /**
     * Show image preview
     */
    showImagePreview(input, file) {
        const reader = new FileReader();
        reader.onload = (e) => {
            let previewContainer = input.parentNode.querySelector('.photo-preview');
            
            if (!previewContainer) {
                previewContainer = document.createElement('div');
                previewContainer.className = 'photo-preview';
                input.parentNode.appendChild(previewContainer);
            }
            
            previewContainer.innerHTML = `
                <img src="${e.target.result}" class="img-responsive img-thumbnail photo-hover-zoom" 
                     alt="Preview Foto" style="max-width:200px; height:auto;">
                <div style="margin-top: 10px;">
                    <small class="text-muted">
                        Nama: ${file.name}<br>
                        Ukuran: ${this.formatFileSize(file.size)}<br>
                        Tipe: ${file.type}
                    </small>
                </div>
            `;
        };
        reader.readAsDataURL(file);
    }

    /**
     * Format file size
     */
    formatFileSize(bytes) {
        if (bytes === 0) return '0 Bytes';
        const k = 1024;
        const sizes = ['Bytes', 'KB', 'MB', 'GB'];
        const i = Math.floor(Math.log(bytes) / Math.log(k));
        return parseFloat((bytes / Math.pow(k, i)).toFixed(2)) + ' ' + sizes[i];
    }

    /**
     * Show message
     */
    showMessage(message, type) {
        const messageDiv = document.createElement('div');
        messageDiv.className = `photo-${type}`;
        messageDiv.textContent = message;
        
        // Remove existing messages
        const existingMessages = document.querySelectorAll('.photo-error, .photo-success');
        existingMessages.forEach(msg => msg.remove());
        
        // Add new message
        document.body.appendChild(messageDiv);
        
        // Auto remove after 3 seconds
        setTimeout(() => {
            messageDiv.remove();
        }, 3000);
    }

    /**
     * Setup photo hover effects
     */
    setupPhotoHoverEffects() {
        // Add hover zoom effect to all photos
        const photos = document.querySelectorAll('.user-photo, .img-thumbnail');
        photos.forEach(photo => {
            photo.classList.add('photo-hover-zoom');
        });
    }

    /**
     * Refresh photo manager
     */
    refresh() {
        this.init();
    }
}

// Initialize when DOM is ready
document.addEventListener('DOMContentLoaded', () => {
    window.userPhotoManager = new UserPhotoManager();
});

// Export for use in other scripts
if (typeof module !== 'undefined' && module.exports) {
    module.exports = UserPhotoManager;
} 