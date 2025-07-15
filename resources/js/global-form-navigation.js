/**
 * Глобальная навигация по формам с помощью клавиши Enter
 * Автоматически переключает фокус на следующее поле при нажатии Enter
 */

class GlobalFormNavigation {
    constructor() {
        this.init();
    }

    init() {
        // Инициализируем при загрузке страницы
        document.addEventListener('DOMContentLoaded', () => this.setupEventListeners());
        
        // Переинициализируем после обновлений Livewire
        document.addEventListener('livewire:navigated', () => this.setupEventListeners());
        document.addEventListener('livewire:load', () => this.setupEventListeners());
        
        // Обработка для Livewire v3
        if (typeof Livewire !== 'undefined') {
            document.addEventListener('livewire:init', () => {
                Livewire.hook('morph.updated', () => this.setupEventListeners());
                
                // Слушаем специальные события для фокусировки
                Livewire.on('focus-next-field', (event) => this.handleFocusEvent(event));
            });
        }
    }

    setupEventListeners() {
        // Удаляем старые обработчики, чтобы избежать дублирования
        document.removeEventListener('keydown', this.handleKeydown);
        
        // Добавляем новый обработчик
        document.addEventListener('keydown', this.handleKeydown.bind(this));
    }

    handleKeydown(event) {
        // Проверяем, что нажата клавиша Enter
        if (event.key !== 'Enter' || event.shiftKey) {
            return;
        }

        const target = event.target;

        // Проверяем, что это поле ввода
        if (!this.isInputElement(target)) {
            return;
        }

        // Исключения: textarea, поля поиска, кнопки
        if (this.shouldSkipElement(target)) {
            return;
        }

        // Проверяем, что элемент находится в форме
        if (!this.isInForm(target)) {
            return;
        }

        // Исключение для формы логина - разрешаем отправку по Enter
        if (this.isLoginForm(target)) {
            return;
        }

        // Предотвращаем отправку формы
        event.preventDefault();

        // Находим следующее поле и переключаем фокус
        this.focusNextField(target);
    }

    isInputElement(element) {
        const inputTypes = ['input', 'select', 'textarea'];
        return inputTypes.includes(element.tagName.toLowerCase());
    }

    shouldSkipElement(element) {
        // Пропускаем textarea (кроме случаев, когда это однострочное поле)
        if (element.tagName.toLowerCase() === 'textarea') {
            return true;
        }

        // Пропускаем поля поиска
        const placeholder = element.getAttribute('placeholder') || '';
        if (placeholder.toLowerCase().includes('поиск') || 
            placeholder.toLowerCase().includes('search')) {
            return true;
        }

        // Пропускаем скрытые и отключенные поля
        if (element.type === 'hidden' || 
            element.type === 'button' || 
            element.type === 'submit' ||
            element.disabled) {
            return true;
        }

        // Пропускаем поля с специальным атрибутом
        if (element.hasAttribute('data-skip-enter-navigation')) {
            return true;
        }

        return false;
    }

    isInForm(element) {
        // Проверяем, что элемент находится внутри формы или модального окна
        return element.closest('form') !== null || 
               element.closest('[role="dialog"]') !== null ||
               element.closest('.modal') !== null;
    }

    isLoginForm(element) {
        const form = element.closest('form');
        if (!form) {
            return false;
        }

        // Проверяем, что форма имеет атрибут wire:submit="login"
        const wireSubmit = form.getAttribute('wire:submit');
        if (wireSubmit === 'login') {
            return true;
        }

        // Альтернативная проверка: ищем поля email и password в форме
        const hasEmailField = form.querySelector('input[type="email"]') !== null;
        const hasPasswordField = form.querySelector('input[type="password"]') !== null;
        const submitButton = form.querySelector('button[type="submit"]');
        
        // Проверяем текст кнопки отправки (может содержать "Log in", "Войти" и т.д.)
        const isLoginButton = submitButton && 
            (submitButton.textContent.toLowerCase().includes('log in') ||
             submitButton.textContent.toLowerCase().includes('войти') ||
             submitButton.textContent.toLowerCase().includes('вход'));

        return hasEmailField && hasPasswordField && isLoginButton;
    }

    focusNextField(currentElement) {
        // Находим контейнер (форма, модальное окно)
        const container = currentElement.closest('form') || 
                         currentElement.closest('[role="dialog"]') || 
                         currentElement.closest('.modal') || 
                         document;

        // Получаем все видимые поля ввода в контейнере
        const inputs = this.getVisibleInputs(container);
        
        // Находим индекс текущего элемента
        const currentIndex = inputs.indexOf(currentElement);
        
        // Переходим к следующему полю
        if (currentIndex >= 0 && currentIndex < inputs.length - 1) {
            const nextInput = inputs[currentIndex + 1];
            
            setTimeout(() => {
                nextInput.focus();
                
                // Выделяем весь текст для числовых и текстовых полей
                if (this.shouldSelectText(nextInput)) {
                    nextInput.select();
                }
            }, 50);
        }
    }

    getVisibleInputs(container) {
        const selector = 'input:not([type="hidden"]):not([type="button"]):not([type="submit"]):not([disabled]), select:not([disabled]), textarea:not([disabled])';
        
        return Array.from(container.querySelectorAll(selector))
            .filter(el => {
                // Проверяем видимость элемента
                const rect = el.getBoundingClientRect();
                const style = window.getComputedStyle(el);
                
                return rect.width > 0 && 
                       rect.height > 0 && 
                       style.visibility !== 'hidden' &&
                       style.display !== 'none' &&
                       !this.shouldSkipElement(el);
            });
    }

    shouldSelectText(element) {
        const selectableTypes = ['text', 'number', 'email', 'password', 'search', 'tel', 'url'];
        return selectableTypes.includes(element.type);
    }

    // Обработка специальных событий Livewire
    handleFocusEvent(event) {
        const currentFieldId = event.fieldId;
        
        // Находим все видимые поля ввода в модальном окне
        const modal = document.querySelector('[role="dialog"]') || document;
        const inputs = this.getVisibleInputs(modal);
        
        // Находим текущий элемент
        let currentIndex = -1;
        
        // Пытаемся найти по ID
        if (currentFieldId) {
            const elementById = document.getElementById(currentFieldId);
            if (elementById) {
                currentIndex = inputs.indexOf(elementById);
            }
        }
        
        // Если не найден по ID, ищем по активному элементу
        if (currentIndex === -1) {
            const currentElement = document.activeElement;
            currentIndex = inputs.indexOf(currentElement);
        }
        
        // Переходим к следующему полю
        if (currentIndex >= 0 && currentIndex < inputs.length - 1) {
            const nextInput = inputs[currentIndex + 1];
            
            setTimeout(() => {
                nextInput.focus();
                if (this.shouldSelectText(nextInput)) {
                    nextInput.select();
                }
            }, 50);
        }
    }
}

// Инициализируем глобальную навигацию при загрузке скрипта
window.globalFormNavigation = new GlobalFormNavigation();

// Экспортируем для использования в других модулях
if (typeof module !== 'undefined' && module.exports) {
    module.exports = GlobalFormNavigation;
} 