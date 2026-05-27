document.addEventListener('DOMContentLoaded', function () {
    document.querySelectorAll('[data-confirm]').forEach(function (form) {
        form.addEventListener('submit', function (e) {
            var message = form.getAttribute('data-confirm') || 'Tem a certeza?';
            if (!window.confirm(message)) {
                e.preventDefault();
            }
        });
    });
});
