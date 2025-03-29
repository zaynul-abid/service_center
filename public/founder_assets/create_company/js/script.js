// ENTER AND ESC KEY USED FOR MOVING FORWARD AND BACKWARD.

document.addEventListener('DOMContentLoaded', function () {
    // Form validation
    const forms = document.querySelectorAll('.needs-validation');
    Array.from(forms).forEach(function(form) {
        form.addEventListener('submit', function(event) {
            if (!form.checkValidity()) {
                event.preventDefault();
                event.stopPropagation();
            }
            form.classList.add('was-validated');
        }, false);
    });

    // Keyboard navigation
    const form = document.getElementById('companyForm');
    if (form) {
        form.addEventListener('keydown', function (e) {
            const activeElement = document.activeElement;
            if (!activeElement || !form.contains(activeElement)) return;

            // Handle Enter key navigation
            if (e.key === 'Enter' && !e.shiftKey) {
                e.preventDefault();
                if (activeElement.hasAttribute('data-submit-on-enter')) {
                    form.submit();
                } else if (activeElement.tagName === 'SELECT' && activeElement.hasAttribute('data-next')) {
                    const nextField = form.querySelector(`[name="${activeElement.getAttribute('data-next')}"]`);
                    if (nextField) nextField.focus();
                } else if (activeElement.hasAttribute('data-next')) {
                    const nextField = form.querySelector(`[name="${activeElement.getAttribute('data-next')}"]`);
                    if (nextField) nextField.focus();
                }
            }

            // Handle backward navigation with Escape
            if (e.key === 'Escape') {
                e.preventDefault();
                if (activeElement.hasAttribute('data-previous') && activeElement.getAttribute('data-previous') !== '') {
                    const prevField = form.querySelector(`[name="${activeElement.getAttribute('data-previous')}"]`);
                    if (prevField) prevField.focus();
                }
            }
        });
    }
});

//UPDATE DATE
function updateEndDate() {
    const planSelect = document.getElementById('plan_id');
    const startDateInput = document.getElementById('subscription_start_date');
    const endDateInput = document.getElementById('subscription_end_date');

    const selectedPlan = planSelect.options[planSelect.selectedIndex];
    const days = selectedPlan?.getAttribute('data-days');

    if (startDateInput.value && days) {
        const startDate = new Date(startDateInput.value);
        startDate.setDate(startDate.getDate() + parseInt(days));

        const formattedDate = startDate.toISOString().split('T')[0];
        endDateInput.value = formattedDate;
    } else {
        endDateInput.value = '';
    }
}

//PLAN DETAILS SECTION

function updatePlanDetails() {
    const planSelect = document.getElementById('plan_id');
    const planDetails = document.getElementById('planDetails');
    const planAmountInput = document.getElementById('plan_amount');
    const finalPriceInput = document.getElementById('final_price');
    const discountInput = document.getElementById('discount');

    const selectedPlan = planSelect.options[planSelect.selectedIndex];
    const planAmount = selectedPlan?.getAttribute('data-amount');

    if (planAmount) {
        planDetails.style.display = 'block';
        planAmountInput.value = planAmount;
        finalPriceInput.value = planAmount;
        discountInput.value = 0;
        updateEndDate();
    } else {
        planDetails.style.display = 'none';
    }
}

//CALCULATING FINAL PRICE
function calculateFinalPrice() {
    const planAmount = parseFloat(document.getElementById('plan_amount').value) || 0;
    const discount = parseFloat(document.getElementById('discount').value) || 0;
    const finalPriceInput = document.getElementById('final_price');

    // Ensure discount doesn't exceed plan amount
    const validatedDiscount = Math.min(discount, planAmount);
    if (discount !== validatedDiscount) {
        document.getElementById('discount').value = validatedDiscount;
    }

    const finalPrice = planAmount - validatedDiscount;
    finalPriceInput.value = finalPrice.toFixed(2);
}

document.getElementById('plan_id').addEventListener('change', function () {
    const selectedOption = this.options[this.selectedIndex];
    document.getElementById('plan_amount').value = selectedOption.getAttribute('data-amount') || 0;

    calculateFinalPrice();  // Automatically recalculate after selecting a plan
})
