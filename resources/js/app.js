import '../sass/app.scss';
import { Modal, Tooltip, Popover, Toast, Alert } from 'bootstrap';
import '@fortawesome/fontawesome-free/css/all.min.css';
import $ from 'jquery';

// Делаем Bootstrap доступным глобально
window.bootstrap = { Modal, Tooltip, Popover, Toast, Alert };
window.$ = window.jQuery = $;

// Инициализация компонентов Bootstrap при загрузке страницы
document.addEventListener('DOMContentLoaded', function() {
    // Инициализация всех tooltips
    const tooltipTriggerList = document.querySelectorAll('[data-bs-toggle="tooltip"]');
    tooltipTriggerList.forEach(el => new Tooltip(el));

    // Инициализация всех popovers
    const popoverTriggerList = document.querySelectorAll('[data-bs-toggle="popover"]');
    popoverTriggerList.forEach(el => new Popover(el));

    // Автоскрытие алертов через 5 секунд
    const alerts = document.querySelectorAll('.alert-dismissible');
    alerts.forEach(alert => {
        setTimeout(() => {
            const bsAlert = Alert.getOrCreateInstance(alert);
            bsAlert.close();
        }, 5000);
    });
    
    // Предпросмотр изображения в формах
    const imageInput = document.getElementById('image');
    const imagePreview = document.getElementById('image-preview');
    if (imageInput && imagePreview) {
        imageInput.addEventListener('change', function() {
            if (this.files && this.files[0]) {
                const reader = new FileReader();
                reader.onload = function(e) {
                    imagePreview.src = e.target.result;
                    imagePreview.classList.remove('d-none');
                };
                reader.readAsDataURL(this.files[0]);
            }
        });
    }
    
    // Подтверждение удаления
    const deleteForms = document.querySelectorAll('form[data-confirm]');
    deleteForms.forEach(form => {
        form.addEventListener('submit', function(e) {
            if (!confirm(this.getAttribute('data-confirm'))) {
                e.preventDefault();
            }
        });
    });
    
    // Валидация форм Bootstrap
    const forms = document.querySelectorAll('.needs-validation');
    Array.from(forms).forEach(form => {
        form.addEventListener('submit', event => {
            if (!form.checkValidity()) {
                event.preventDefault();
                event.stopPropagation();
            }
            form.classList.add('was-validated');
        }, false);
    });
});