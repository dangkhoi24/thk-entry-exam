// Admin screens:
//   [data-sidebar-toggle]     -> open / close the sidebar menu on mobile
//   [data-toast]              -> auto dismiss / close button for flash toasts
//   [data-modal-cancel]       -> Esc key closes the dialog (goes to its cancel URL)
//   <form data-dirty-check>   -> enables its submit buttons once something is changed
//   <input data-date-picker>  -> flatpickr calendar (opens when the field is clicked / focused)
//   <select data-select>      -> Tom Select dropdown with search
import flatpickr from 'flatpickr';
import { Japanese } from 'flatpickr/dist/l10n/ja.js';
import TomSelect from 'tom-select';

const sidebar = document.querySelector('[data-sidebar]');
const sidebarToggle = document.querySelector('[data-sidebar-toggle]');

function setSidebarOpen(isOpen) {
    sidebar.classList.toggle('is-open', isOpen);
    sidebarToggle.setAttribute('aria-expanded', String(isOpen));
}

if (sidebar && sidebarToggle) {
    sidebarToggle.addEventListener('click', () => {
        setSidebarOpen(!sidebar.classList.contains('is-open'));
    });
    document.addEventListener('keydown', (event) => {
        if (event.key === 'Escape') {
            setSidebarOpen(false);
        }
    });
}

const modal = document.querySelector('[data-modal-cancel]');

if (modal) {
    document.addEventListener('keydown', (event) => {
        if (event.key === 'Escape') {
            window.location.href = modal.dataset.modalCancel;
        }
    });
}

document.querySelectorAll('form[data-dirty-check]').forEach((form) => {
    const submitButtons = document.querySelectorAll(`button[form="${form.id}"], #${form.id} button[type="submit"]`);
    const enable = () => submitButtons.forEach((button) => { button.disabled = false; });
    form.addEventListener('input', enable);
    form.addEventListener('change', enable);
});

const TOAST_DURATION = 5000;

function dismissToast(toast) {
    toast.classList.add('is-leaving');
    toast.addEventListener('transitionend', () => toast.remove(), { once: true });
}

document.querySelectorAll('[data-toast]').forEach((toast) => {
    const timer = setTimeout(() => dismissToast(toast), TOAST_DURATION);
    toast.querySelector('[data-toast-close]').addEventListener('click', () => {
        clearTimeout(timer);
        dismissToast(toast);
    });
});

// calendar language follows <html lang> (English is flatpickr's default)
const isJapanese = document.documentElement.lang === 'ja';

document.querySelectorAll('[data-date-picker]').forEach((input) => {
    flatpickr(input, {
        locale: isJapanese ? Japanese : 'default',
        dateFormat: 'Y-m-d',
        allowInput: true,
        clickOpens: true,
        disableMobile: true,
    });
});

document.querySelectorAll('[data-select]').forEach((select) => {
    new TomSelect(select, {
        allowEmptyOption: true,
        maxOptions: null,
        // search box inside the dropdown, so the closed control stays one line
        plugins: ['dropdown_input'],
        closeAfterSelect: true,
        // highlight the first match so Enter selects it
        onType() {
            const firstOption = this.dropdown_content.querySelector('[data-selectable]');
            if (firstOption) {
                this.setActiveOption(firstOption);
            }
        },
    });
});
