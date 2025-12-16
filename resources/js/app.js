// resources/js/app.js
import Alpine from 'alpinejs'

window.Alpine = Alpine

// Alpine stores
document.addEventListener('alpine:init', () => {
    Alpine.store('modal', {
        show: false,
        title: '',
        subtitle: '',
        content: null,

        open({ title = '', subtitle = '', content = null }) {
            this.title = title
            this.subtitle = subtitle
            this.content = content
            this.show = true
        },

        close() {
            this.show = false
            this.content = null
        }
    })
})

// Inisialisasi Quill editor
window.initQuill = function(editorSelector, hiddenInputSelector, placeholder='Type something...') {
    const quill = new Quill(editorSelector, {
        theme: 'snow',
        placeholder
    });

    const input = document.querySelector(hiddenInputSelector);
    const form = input.closest('form');

    if (form) {
        form.addEventListener('submit', () => {
            input.value = quill.root.innerHTML;
        });
    }
}

// Start Alpine
Alpine.start()
