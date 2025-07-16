document.addEventListener('alpine:init', () => {
    Alpine.data('fileUploader', () => ({
        isDragging: false,
        isUploading: false,
        init() {
            console.log('File uploader initialized');
        },
        handleDrop(e) {
            console.log('File dropped');
            this.isDragging = false;
            const files = e.dataTransfer.files;
            if (files.length > 0) {
                this.handleFile(files[0]);
            }
        },
        handleFileSelect(e) {
            console.log('File selected');
            const files = e.target.files;
            if (files.length > 0) {
                this.handleFile(files[0]);
            }
        },
        handleFile(file) {
            console.log('Processing file:', file.name);
            if (!file.type.startsWith('image/')) {
                alert('Пожалуйста, выберите файл изображения');
                return;
            }
            if (file.size > 1024 * 1024) {
                alert('Размер файла не должен превышать 1MB');
                return;
            }
            this.isUploading = true;
            this.$wire.upload('photo', file, 
                (uploadedFilename) => {
                    this.isUploading = false;
                    console.log('File uploaded successfully');
                },
                (error) => {
                    this.isUploading = false;
                    console.error('Upload error:', error);
                    alert('Ошибка загрузки файла');
                },
                (event) => {
                    console.log('Upload progress:', event.detail.progress);
                }
            );
        }
    }));
}); 