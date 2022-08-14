async function updateUser(human_id, status) {
    let formData = new FormData();
    formData.append("action", 'hidsf_update_user');
    formData.append("human_id", human_id);
    formData.append("status", status);

    return jQuery.ajax({
        type: "POST",
        contentType: false,
        processData: false,
        url: ajaxurl,
        data: formData,
        success: function () {
            return true
        },
        error: function () {
            return false;
        }
    });
}