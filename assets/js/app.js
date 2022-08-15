jQuery(function ($) {
    $(document).ready(function () {
        $("#commentform").submit(function (e) {
            e.preventDefault();
            const comment = $("#commentform #comment").val().trim()
            if (comment.length > 0) {
                const handle = window.open("http://google.com", "Human_ID_Verification", 'width=100,height=100');
                if (handle) {
                    if (window.focus) {
                        handle.focus()
                    }
                }
            } else {
                alert("Please enable popup")
            }

            // alert(comment)
        })
    })
})