jQuery(document).ready(function ($) {
    // Function to toggle the visibility of the email text container
    function toggleEmailInput() {
        if ($('#email_report').is(':checked')) {
            $('.email-text-container').show(); // Show the email input if checked
        } else {
            $('.email-text-container').hide(); // Hide the email input if unchecked
        }
    }

    // Event listener for changes to the checkbox
    $('#email_report').change(function () {
        toggleEmailInput(); // Call the toggle function on change
    });

    // Initial check on page load to ensure proper visibility state
    toggleEmailInput();
});
