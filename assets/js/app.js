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

            return jQuery.ajax({
                type: "POST",
                contentType: false,
                processData: false,
                url: hid_ajax_object.ajax_url,
                data: formData,
                success: function (e) {
                    const handle = window.open(e, "Human_ID_Verification", 'width=900,height=650');
                    if (handle) {
                        if (window.focus) {
                            handle.focus()
                        }
                    } else {
                        showErrorMessage("Please allow popups for this site");
                    }
                },
                error: function (e) {
                    if (e.responseJSON) {
                        showErrorMessage(e.responseJSON.data)
                    } else {
                        showErrorMessage("An error occurred. Please try again")
                    }
                }
            });
        });

        $('.wpcf7-form').submit(function (e) {

            $(this).on('wpcf7mailsent', function () {
                $(this).find('.human-id').val('')
            });
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