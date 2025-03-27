// $(document).ready(function () {
//     let currentIndex = -1;  // Track the current highlighted suggestion

//     $('#vehicleNumber').on('input', function () {
//         let vehicleNumber = $(this).val();

//         if (vehicleNumber.length >= 2) {
//             $.ajax({
//                 url: '/vehicles/search', // Your AJAX route
//                 method: 'GET',
//                 data: { vehicle_number: vehicleNumber },
//                 success: function (response) {
//                     $('#vehicleSuggestions').empty();  // Clear old suggestions
//                     currentIndex = -1;  // Reset the index after new suggestions are fetched

//                     if (response.length > 0) {
//                         response.forEach(vehicle => {
//                             $('#vehicleSuggestions').append(`<li data-vehicle='${JSON.stringify(vehicle)}'>${vehicle.vehicle_number}</li>`);
//                         });

//                         $('#vehicleSuggestions').show();  // Show the suggestions list
//                     } else {
//                         $('#vehicleSuggestions').hide();  // Hide if no suggestions
//                     }
//                 },
//                 error: function (xhr) {
//                     console.log("Error: ", xhr.responseText);  // Log any errors
//                 }
//             });
//         } else {
//             $('#vehicleSuggestions').hide();  // Hide suggestions if input has less than 2 characters
//         }
//     });

//     // Handle arrow key navigation and Enter key selection
//     $('#vehicleNumber').on('keydown', function (e) {
//         let items = $('#vehicleSuggestions li');

//         if (e.key === 'ArrowDown') {
//             currentIndex = (currentIndex + 1) % items.length;  // Navigate down in a circular way
//             highlightSelection(items);
//         } else if (e.key === 'ArrowUp') {
//             currentIndex = (currentIndex - 1 + items.length) % items.length;  // Navigate up in a circular way
//             highlightSelection(items);
//         } else if (e.key === 'Enter' && currentIndex >= 0) {
//             e.preventDefault();  // Prevent form submission

//             // Get the selected vehicle and fill the form fields
//             let selectedVehicle = items.eq(currentIndex).data('vehicle');
//             if (selectedVehicle) {
//                 $('#vehicleNumber').val(selectedVehicle.vehicle_number);
//                 $('#vehicle_type').val(selectedVehicle.vehicle_type);
//                 $('#vehicleCompany').val(selectedVehicle.vehicle_company);
//                 $('#vehicleModel').val(selectedVehicle.vehicle_model);
//                 $('#fuel_type').val(selectedVehicle.fuel_type);
//             }

//             $('#vehicleSuggestions').hide();  // Hide the suggestions list after selection
//         }
//     });

//     // Handle mouse click on a suggestion and auto-fill the form
//     $(document).on('click', '#vehicleSuggestions li', function () {
//         let vehicle = $(this).data('vehicle');  // Get the vehicle details from the data attribute

//         // Auto-fill the form fields with selected vehicle data
//         $('#vehicleNumber').val(vehicle.vehicle_number);
//         $('#vehicle_type').val(vehicle.vehicle_type);
//         $('#vehicleCompany').val(vehicle.vehicle_company);
//         $('#vehicleModel').val(vehicle.vehicle_model);
//         $('#fuel_type').val(vehicle.fuel_type);

//         $('#vehicleSuggestions').hide();  // Hide suggestions after selection
//     });

//     // Highlight the selected suggestion by adding a CSS class
//     function highlightSelection(items) {
//         items.removeClass('highlight');  // Remove highlight from all items
//         if (items.length > 0 && currentIndex >= 0) {
//             items.eq(currentIndex).addClass('highlight');  // Highlight the current item
//         }
//     }
// });




// $(document).ready(function () {
//     let currentIndex = -1; // Track the currently selected suggestion

//     $('#vehicleNumber').on('input', function () {
//         let vehicleNumber = $(this).val();

//         if (vehicleNumber.length >= 2) {
//             $.ajax({
//                 url: '/vehicles/search', // Your AJAX route
//                 method: 'GET',
//                 data: { vehicle_number: vehicleNumber },
//                 success: function (response) {
//                     $('#vehicleSuggestions').empty(); // Clear old suggestions
//                     currentIndex = -1; // Reset index when suggestions change

//                     if (response.length > 0) {
//                         response.forEach(vehicle => {
//                             $('#vehicleSuggestions').append(`<li data-vehicle='${JSON.stringify(vehicle)}'>${vehicle.vehicle_number}</li>`);
//                         });

//                         $('#vehicleSuggestions').show(); // Show the suggestions list
//                     } else {
//                         $('#vehicleSuggestions').hide(); // Hide if no suggestions
//                     }
//                 },
//                 error: function (xhr) {
//                     console.log("Error: ", xhr.responseText); // Log any errors
//                 }
//             });
//         } else if (vehicleNumber === '' || vehicleNumber === null) {
//             clearVehicleForm(); // Clear fields only if the vehicleNumber input is emptied
//             $('#vehicleSuggestions').hide(); // Also hide the suggestions list
//         }
//     });

//     // Handle keydown for arrow navigation and Enter key selection
//     $('#vehicleNumber').on('keydown', function (e) {
//         let items = $('#vehicleSuggestions li');

//         if (e.key === 'ArrowDown') {
//             currentIndex = (currentIndex + 1) % items.length; // Move down in circular fashion
//             highlightSelection(items);
//         } else if (e.key === 'ArrowUp') {
//             currentIndex = (currentIndex - 1 + items.length) % items.length; // Move up in circular fashion
//             highlightSelection(items);
//         } else if (e.key === 'Enter' && currentIndex >= 0) {
//             e.preventDefault(); // Prevent form submission on Enter
//             let selectedVehicle = items.eq(currentIndex).data('vehicle');
//             autoFillForm(selectedVehicle); // Auto-fill the form with the selected vehicle
//             $('#vehicleSuggestions').hide(); // Hide suggestions after selection
//         }
//     });

//     // Handle click on a suggestion and auto-fill the form
//     $(document).on('click', '#vehicleSuggestions li', function () {
//         let vehicle = $(this).data('vehicle'); // Get the vehicle details from data attribute
//         autoFillForm(vehicle); // Auto-fill the form
//         $('#vehicleSuggestions').hide(); // Hide suggestions after selection
//     });

//     // Function to highlight a selection during arrow key navigation
//     function highlightSelection(items) {
//         items.removeClass('highlight');
//         if (items.length > 0 && currentIndex >= 0) {
//             items.eq(currentIndex).addClass('highlight');
//         }
//     }

//     // Function to auto-fill form fields based on selected vehicle
//     function autoFillForm(vehicle) {
//         $('#vehicleNumber').val(vehicle.vehicle_number);
//         $('#vehicle_type').val(vehicle.vehicle_type);
//         $('#vehicleCompany').val(vehicle.vehicle_company);
//         $('#vehicleModel').val(vehicle.vehicle_model);
//         $('#fuel_type').val(vehicle.fuel_type);
//     }

//     // Function to clear form fields when vehicle number input is empty
//     function clearVehicleForm() {
//         $('#vehicle_type').val('');
//         $('#vehicleCompany').val('');
//         $('#vehicleModel').val('');
//         $('#fuel_type').val('');
//     }
// });

$(document).ready(function () {
    let currentIndex = -1; // Track the currently selected suggestion

    $('#vehicleNumber').on('input', function () {
        let vehicleNumber = $(this).val();

        if (vehicleNumber.length >= 2) {
            $.ajax({
                url: '/vehicles/search',
                method: 'GET',
                data: { vehicle_number: vehicleNumber },
                success: function (response) {
                    $('#vehicleSuggestions').empty();
                    currentIndex = -1;

                    if (response.length > 0) {
                        response.forEach(vehicle => {
                            $('#vehicleSuggestions').append(`<li data-vehicle='${JSON.stringify(vehicle)}'>${vehicle.vehicle_number}</li>`);
                        });

                        $('#vehicleSuggestions').show();
                    } else {
                        $('#vehicleSuggestions').hide();
                    }
                },
                error: function (xhr) {
                    console.log("Error: ", xhr.responseText);
                }
            });
        } else {
            clearVehicleForm();
            $('#vehicleSuggestions').hide();
        }
    });

    $('#vehicleNumber').on('keydown', function (e) {
        let items = $('#vehicleSuggestions li');

        if (e.key === 'ArrowDown') {
            currentIndex = (currentIndex + 1) % items.length;
            highlightSelection(items);
        } else if (e.key === 'ArrowUp') {
            currentIndex = (currentIndex - 1 + items.length) % items.length;
            highlightSelection(items);
        } else if (e.key === 'Enter' && currentIndex >= 0) {
            e.preventDefault();
            let selectedVehicle = items.eq(currentIndex).data('vehicle');
            autoFillForm(selectedVehicle);
            $('#vehicleSuggestions').hide();
            focusNextUnfilledInputAfter('#vehicleNumber'); // Move focus to the next unfilled input after #vehicleNumber
        }
    });

    $(document).on('click', '#vehicleSuggestions li', function () {
        let vehicle = $(this).data('vehicle');
        autoFillForm(vehicle);
        $('#vehicleSuggestions').hide();
        focusNextUnfilledInputAfter('#vehicleNumber');
    });

    function highlightSelection(items) {
        items.removeClass('highlight');
        if (items.length > 0 && currentIndex >= 0) {
            items.eq(currentIndex).addClass('highlight');
        }
    }

    function autoFillForm(vehicle) {
        $('#vehicleNumber').val(vehicle.vehicle_number);
        $('#vehicle_type').val(vehicle.vehicle_type);
        $('#vehicleCompany').val(vehicle.vehicle_company);
        $('#vehicleModel').val(vehicle.vehicle_model);
        $('#fuel_type').val(vehicle.fuel_type);
    }

    function clearVehicleForm() {
        $('#vehicle_type').val('');
        $('#vehicleCompany').val('');
        $('#vehicleModel').val('');
        $('#fuel_type').val('');
    }

    function focusNextUnfilledInputAfter(currentInputSelector) {
        // Find the next unfilled input after the currently selected input
        let inputs = $('input:visible:not([readonly]):not([disabled])');
        let currentIndex = inputs.index($(currentInputSelector));

        for (let i = currentIndex + 1; i < inputs.length; i++) {
            if (inputs.eq(i).val() === '') {
                inputs.eq(i).focus(); // Focus on the next unfilled input
                return;
            }
        }
    }
});






$(document).ready(function () {
    let currentIndex = -1;  // Track the current selection index

    $('#vehicleCompany').on('input', function () {
        let companyInput = $(this).val();

        if (companyInput.length >= 2) {
            $.ajax({
                url: '/vehicles/companies',  // AJAX route to fetch vehicle company suggestions
                method: 'GET',
                data: { company_name: companyInput },
                success: function (response) {
                    $('#companySuggestions').empty();  // Clear previous suggestions
                    currentIndex = -1;

                    if (response.length > 0) {
                        response.forEach(company => {
                            $('#companySuggestions').append(`<li>${company}</li>`);
                        });

                        $('#companySuggestions').show();  // Show suggestions list
                    } else {
                        $('#companySuggestions').hide();  // Hide list if no results
                    }
                },
                error: function (xhr) {
                    console.error("Error: ", xhr.responseText);
                }
            });
        } else {
            $('#companySuggestions').hide();  // Hide suggestions if input is short
        }
    });

    // Handle keyboard navigation and selection
    $('#vehicleCompany').on('keydown', function (e) {
        let items = $('#companySuggestions li');

        if (e.key === 'ArrowDown') {
            currentIndex = (currentIndex + 1) % items.length;  // Circular navigation
            highlightSelection(items);
        } else if (e.key === 'ArrowUp') {
            currentIndex = (currentIndex - 1 + items.length) % items.length;  // Navigate up in circular fashion
            highlightSelection(items);
        } else if (e.key === 'Enter' && currentIndex >= 0) {
            e.preventDefault();  // Prevent form submission

            // Select the highlighted suggestion
            $('#vehicleCompany').val(items.eq(currentIndex).text());
            $('#companySuggestions').hide();  // Hide the suggestions list
        }
    });

    // Handle mouse click on a suggestion
    $(document).on('click', '#companySuggestions li', function () {
        $('#vehicleCompany').val($(this).text());
        $('#companySuggestions').hide();
    });

    // Highlight the selected suggestion by adding a CSS class
    function highlightSelection(items) {
        items.removeClass('highlight');  // Remove highlight from all items
        if (items.length > 0 && currentIndex >= 0) {
            items.eq(currentIndex).addClass('highlight');  // Highlight the selected item
        }
    }
});



$(document).ready(function () {
    let currentIndex = -1;  // Track the current selection index

    $('#vehicleModel').on('input', function () {
        let modelInput = $(this).val();

        if (modelInput.length >= 2) {
            $.ajax({
                url: '/vehicles/models',  // Route to fetch vehicle models
                method: 'GET',
                data: { model_name: modelInput },
                success: function (response) {
                    $('#modelSuggestions').empty();  // Clear previous suggestions
                    currentIndex = -1;

                    if (response.length > 0) {
                        response.forEach(model => {
                            $('#modelSuggestions').append(`<li>${model}</li>`);
                        });

                        $('#modelSuggestions').show();  // Show suggestions list
                    } else {
                        $('#modelSuggestions').hide();  // Hide list if no results
                    }
                },
                error: function (xhr) {
                    console.error("Error: ", xhr.responseText);
                }
            });
        } else {
            $('#modelSuggestions').hide();  // Hide suggestions if input is short
        }
    });

    // Handle keyboard navigation and selection
    $('#vehicleModel').on('keydown', function (e) {
        let items = $('#modelSuggestions li');

        if (e.key === 'ArrowDown') {
            currentIndex = (currentIndex + 1) % items.length;  // Circular navigation
            highlightSelection(items);
        } else if (e.key === 'ArrowUp') {
            currentIndex = (currentIndex - 1 + items.length) % items.length;  // Navigate up in circular fashion
            highlightSelection(items);
        } else if (e.key === 'Enter' && currentIndex >= 0) {
            e.preventDefault();  // Prevent form submission

            // Select the highlighted suggestion
            $('#vehicleModel').val(items.eq(currentIndex).text());
            $('#modelSuggestions').hide();  // Hide the suggestions list
        }
    });

    // Handle mouse click on a suggestion
    $(document).on('click', '#modelSuggestions li', function () {
        $('#vehicleModel').val($(this).text());
        $('#modelSuggestions').hide();
    });

    // Highlight the selected suggestion by adding a CSS class
    function highlightSelection(items) {
        items.removeClass('highlight');  // Remove highlight from all items
        if (items.length > 0 && currentIndex >= 0) {
            items.eq(currentIndex).addClass('highlight');  // Highlight the selected item
        }
    }
});


