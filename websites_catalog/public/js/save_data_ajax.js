jQuery(document).ready(function($) {
    $('#wc_website_submit').on('click', function() {
        var name = $('#user_name').val();
        var url = $('#site_url').val();
        var current_element = jQuery(this);
        // Perform basic validation
        if (name && url) {
            // check is the url valid
            if (!wcCheckIsValidUrl(url)) {
                alert('Please provide a valid url.');
                return; // return if url is not valid
            }
            current_element.prop('disabled', true);
            $.ajax({
                type: 'POST',
                url: ajax_object.ajax_url,
                data: {
                    action: 'save_website_data',
                    name: name,
                    url: url,
                    security: ajax_object.security,
                },
                success: function(response) {
                    current_element.prop('disabled', false);
                    var data = JSON.parse(response);
                    if (data.status === 'success') {
                        $('#wc_submission_message').addClass('wc_message_success');
                        $('#wc_submission_message').removeClass('wc_message_error');
                        $('#wc_submission_message').text(data.message);
                        $("#wc-website-form")[0].reset();
                    } else {
                        $('#wc_submission_message').removeClass('wc_message_success');
                        $('#wc_submission_message').addClass('wc_message_error');
                        $('#wc_submission_message').text(data.message);
                    }

                    setTimeout(function() {
                        $('#wc_submission_message').text(''); //remove response message after 5 seconds
                    }, 5000);

                },
            });
        } else {
            // Handle validation error
            alert('Please fill in both name and URL fields.');
        }
    });
});



/*
*
*
The regex used in wcCheckIsValidUrl function is copied from the internet
*
*/
const wcCheckIsValidUrl = urlString=> {
    var urlPattern = new RegExp('^(https?:\\/\\/)?'+ // validate protocol
        '((([a-z\\d]([a-z\\d-]*[a-z\\d])*)\\.)+[a-z]{2,}|'+ // validate domain name
        '((\\d{1,3}\\.){3}\\d{1,3}))'+ // validate OR ip (v4) address
        '(\\:\\d+)?(\\/[-a-z\\d%_.~+]*)*'+ // validate port and path
        '(\\?[;&a-z\\d%_.~+=-]*)?'+ // validate query string
        '(\\#[-a-z\\d_]*)?$','i'); // validate fragment locator
    return !!urlPattern.test(urlString);
}