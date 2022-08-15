jQuery(function ($) {
    $(document).ready(function () {
        $("#commentform").submit(function (e) {
            const comment = $("#commentform #comment").val().trim()
            if (comment.length > 0) {
                e.preventDefault();
                $("#human-id-verification-modal").show();
            }
        })

        $("#close-human-id-verification").click(function () {
            $("#human-id-verification-modal").hide();
        })

        $("#start-human-id-verification").click(function () {
            //POST https://core.human-id.org/v0.0.3/server/users/web-login
            const headers = {
                "client-id": hid_ajax_object.client_id,
                "client-secret": hid_ajax_object.client_secret
            }
            jQuery.ajax({
                headers: headers,
                type: "POST",
                contentType: "application/json",
                processData: false,
                url: "https://core.human-id.org/v0.0.3/server/users/web-login",
                success: function (e) {
                    console.log("received", e)
                    return true
                },
                error: function () {
                    return false;
                }
            });

            // const handle = window.open("http://google.com", "Human_ID_Verification", 'width=100,height=100');
            // if (handle) {
            //     if (window.focus) {
            //         handle.focus()
            //     }
            // }
            // else {
            //         alert("Please enable popup")
            //     }
        })
    })
})