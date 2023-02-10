let submit_button_reference = ''
let hid_reference = ''
let is_form = false;

jQuery(function ($) {
    $(document).ready(function () {
        $("#commentform").submit(function (e) {
            const comment = $("#commentform #comment").val().trim()
            const human_id_key = $("#commentform #human_id_key").val().trim()
            if (comment.length > 0 && human_id_key.length === 0) {
                e.preventDefault();
                $("#human-id-verification-modal").show();
            }
        })

        $("#close-human-id-verification").click(function () {
            $("#human-id-verification-modal").hide();
            $("#start-human-id-verification").show()
            $("#hid-verification-pending").hide()
        })

        $("#start-human-id-verification").click(function () {
            $("#start-human-id-verification").hide()
            $("#hid-verification-pending").show()
            $("#hid-verification-error-message").hide()

            let formData = new FormData();
            formData.append("action", 'hidsf_get_login_url');

            // ajax call to get login url
            return jQuery.ajax({
                type: "POST",
                contentType: false,
                processData: false,
                url: hid_ajax_object.ajax_url,
                data: formData,
                success: function (e) {
                    const handle = window.open(e, "Human_ID_Verification");
                    if (handle) {
                        if (window.focus) {
                            handle.focus()
                        }
                    } else {
                        // Browser has blocked popup
                        showErrorMessage("Please allow popups for this site");
                    }
                },
                error: function (e) {
                    if (e.responseJSON) {
                        // error from server
                        showErrorMessage(e.responseJSON.data)
                    } else {
                        // error from client
                        showErrorMessage("An error occurred. Please try again")
                    }
                }
            });
        });

        $('.wpcf7-form').submit(function (e) {
            // resets human id value on form submit
            $(this).on('wpcf7mailsent', function () {
                $(this).find('.human-id').val('')
            });

            // validates all required fields
            const required_fields = $(this).find('.wpcf7-validates-as-required');
            if (required_fields.length > 0) {
                for (let i = 0; i < required_fields.length; i++) {
                    if ($(required_fields[i]).val().trim().length === 0) {
                        return;
                    }
                }
            }

            const not_valid_fields = $(this).find('.wpcf7-not-valid');
            if (not_valid_fields.length > 0) {
                return;
            }

            // checks if human id is present
            if ($(this).find('.human-id').length > 0) {
                hid_reference = $(this).find('.human-id');
                if (hid_reference.val().trim().length == 0) {
                    e.preventDefault();
                    is_form = true
                    submit_button_reference = $(this).find('.wpcf7-submit');
                    $("#human-id-verification-modal").show();
                }
            }
        })
    })
})

function showErrorMessage(message) {
    jQuery(function ($) {
        $("#hid-verification-error-message").html(message)
        $("#hid-verification-error-message").show()
        $("#start-human-id-verification").show()
        $("#hid-verification-pending").hide()
    });
}

function verificationSuccess(token) {
    jQuery(function ($) {
        if (is_form) {
            hid_reference.val(token)
            submit_button_reference.click()
        } else {
            $("#human_id_key").val(token)
            $("#commentform #submit").click()
        }
        $("#human-id-verification-modal").hide();
    });
}

function verificationFailed(message) {
    showErrorMessage(message);
}