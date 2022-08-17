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
                    const handle = window.open(e, "Human_ID_Verification", 'width=100,height=100');
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
        $("#human_id_key").val(token)
        $("#human-id-verification-modal").hide();
        $("#commentform #submit").click()
    });
}

function verificationFailed(message) {
    showErrorMessage(message);
}