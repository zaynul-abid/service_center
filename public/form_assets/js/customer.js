


$(document).ready(function () {
    let currentIndex = -1;  // Track the current highlighted suggestion

    $('#customerName').on('input', function () {
        let customerName = $(this).val();

        if (customerName.length >= 2) {
            $.ajax({
                url: '/customers/search',  // AJAX route to search customers
                method: 'GET',
                data: { customer_name: customerName },
                success: function (response) {
                    $('#customerSuggestions').empty();  // Clear old suggestions
                    currentIndex = -1;  // Reset the index after new suggestions are fetched

                    if (response.length > 0) {
                        response.forEach(customer => {
                            $('#customerSuggestions').append(`<li data-customer='${JSON.stringify(customer)}'>${customer.customer_name}</li>`);
                        });

                        $('#customerSuggestions').show();  // Show the suggestions list
                    } else {
                        $('#customerSuggestions').hide();  // Hide if no suggestions
                    }
                },
                error: function (xhr) {
                    console.log("Error: ", xhr.responseText);  // Log any errors
                }
            });
        } else {
            clearCustomerForm();  // Call this function if input is empty
            $('#customerSuggestions').hide();  // Hide suggestions if input has less than 2 characters
        }
    });

    // Handle arrow key navigation and Enter key selection
    $('#customerName').on('keydown', function (e) {
        let items = $('#customerSuggestions li');

        if (e.key === 'ArrowDown') {
            currentIndex = (currentIndex + 1) % items.length;  // Navigate down in circular fashion
            highlightSelection(items);
        } else if (e.key === 'ArrowUp') {
            currentIndex = (currentIndex - 1 + items.length) % items.length;  // Navigate up in circular fashion
            highlightSelection(items);
        } else if (e.key === 'Enter' && currentIndex >= 0) {
            e.preventDefault();  // Prevent form submission

            // Get the selected customer and fill the form fields
            let selectedCustomer = items.eq(currentIndex).data('customer');
            if (selectedCustomer) {
                $('#customerName').val(selectedCustomer.customer_name);
                $('#contactNumber1').val(selectedCustomer.contact_number_1);
                $('#contactNumber2').val(selectedCustomer.contact_number_2);
                $('#place').val(selectedCustomer.place);
            }

            $('#customerSuggestions').hide();  // Hide the suggestions list after selection
        }
    });

    // Handle mouse click on a suggestion and auto-fill the form
    $(document).on('click', '#customerSuggestions li', function () {
        let customer = $(this).data('customer');  // Get the customer details from data attribute

        // Auto-fill the form fields with selected customer data
        $('#customerName').val(customer.customer_name);
        $('#contactNumber1').val(customer.contact_number_1);
        $('#contactNumber2').val(customer.contact_number_2);
        $('#place').val(customer.place);

        $('#customerSuggestions').hide();  // Hide suggestions after selection
    });

    // Highlight the selected suggestion by adding a CSS class
    function highlightSelection(items) {
        items.removeClass('highlight');  // Remove highlight from all items
        if (items.length > 0 && currentIndex >= 0) {
            items.eq(currentIndex).addClass('highlight');  // Highlight the current item
        }
    }

    // Function to clear the AJAX-filled form fields
    function clearCustomerForm() {
        $('#contactNumber1').val('');
        $('#contactNumber2').val('');
        $('#place').val('');
    }
});
