$.ajaxSetup({
    headers: {
        "X-CSRF-TOKEN": $('meta[name="csrf-token"]').attr("content"),
    },
});

/* Upload File */
/*Upload File */
$("#upload").change(function () {
    const form = new FormData();
    form.append("file", $(this)[0].files[0]);

    $.ajax({
        processData: false,
        contentType: false,
        type: "POST",
        dataType: "JSON",
        data: form,
        url: "/admin/upload/services",
        success: function (results) {
            if (results.error === false) {
                $("#image_show").html(
                    '<a href="' +
                        results.url +
                        '" target="_blank">' +
                        '<img src="' +
                        results.url +
                        '" width="100px"></a>'
                );

                $("#thumb").val(results.url);
            } else {
                alert("Upload File Lỗi");
            }
        },
    });
});

/* Xóa hàng */
function removeRow(id, url) {
    if (confirm("Xóa mà không thể khôi phục. Bạn có chắc chắn không?")) {
        $.ajax({
            type: "DELETE",
            dataType: "JSON", // Sửa lỗi cú pháp từ "datatype" thành "dataType"
            data: { id },
            url: url,
            success: function (result) {
                if (result.error === false) {
                    alert(result.message);
                    location.reload(); // Tải lại trang sau khi xóa thành công
                } else {
                    alert("Xóa lỗi, vui lòng thử lại.");
                }
            },
            error: function (xhr, status, error) {
                console.log("Lỗi khi xóa: ", error);
                alert("Xóa lỗi, vui lòng thử lại.");
            },
        });
    }
}
