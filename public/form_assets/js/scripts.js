// Function to update the date and time
function updateDateTime() {
    const now = new Date();
    const dateTimeString = now.toLocaleString();
    const dateTimeElement = document.getElementById('currentDateTime');

    // Check if the element exists before updating its content
    if (dateTimeElement) {
        dateTimeElement.textContent = dateTimeString;
    } else {
        console.error('Element with ID "currentDateTime" not found.');
    }
}

// Function to reset the form
function resetForm() {
    const form = document.getElementById('serviceForm');
    const bookingDate = document.getElementById('booking_date').value;
    const bookingTime = document.getElementById('booking_time').value;

    // Reset the form
    if (form) {
        form.reset();

        // Restore the booking date and time values
        document.getElementById('booking_date').value = bookingDate;
        document.getElementById('booking_time').value = bookingTime;
    } else {
        console.error('Form with ID "serviceForm" not found.');
    }
}

// Wait for the DOM to be fully loaded before running the script
document.addEventListener('DOMContentLoaded', function () {
    // Update the date and time immediately
    updateDateTime();

    // Update the date and time every second
    setInterval(updateDateTime, 1000);

    // Set default values for booking_date and booking_time
    const now = new Date();
    const bookingDateElement = document.getElementById('booking_date');
    const bookingTimeElement = document.getElementById('booking_time');

    if (bookingDateElement && bookingTimeElement) {
        bookingDateElement.value = now.toISOString().split('T')[0];
        bookingTimeElement.value = now.toTimeString().split(':').slice(0, 2).join(':');
    } else {
        console.error('Elements for booking date and time not found.');
    }
});