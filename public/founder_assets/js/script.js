document.addEventListener('DOMContentLoaded', function() {
    const form = document.getElementById('companyForm');
    const inputs = form.querySelectorAll('input, textarea');

    // Handle Enter key press
    form.addEventListener('keydown', function(e) {
        if (e.key === 'Enter') {
            e.preventDefault();

            const activeElement = document.activeElement;

            if (activeElement && (activeElement.tagName === 'INPUT' || activeElement.tagName === 'TEXTAREA')) {
                // Check if this is the last field that should submit on Enter
                if (activeElement.hasAttribute('data-submit-on-enter')) {
                    form.submit();
                }
                // Otherwise move to next field
                else {
                    const nextFieldName = activeElement.getAttribute('data-next');
                    if (nextFieldName) {
                        const nextField = form.querySelector(`[name="${nextFieldName}"]`);
                        if (nextField) {
                            nextField.focus();
                        }
                    }
                }
            }
        }
    });
});
