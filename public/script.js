$(document).ready(function () {
    $("#contactLead").DataTable({
        processing: true,
        serverSide: true,
        ajax: AJAX_URL,
        columns: [
            {
                data: "name",
                name: "name",
                orderable: true,
                searchable: true,
                className: "text-start",
            },
            {
                data: "email",
                name: "email",
                orderable: true,
                searchable: true,
                className: "text-start",
            },
            {
                data: "phone",
                name: "phone",
                orderable: false,
                searchable: false,
                className: "text-center",
            },
            {
                data: "description",
                name: "description",
                orderable: false,
                searchable: false,
                className: "text-center",
            },
            {
                data: "created_at",
                name: "created_at",
                orderable: true,
                searchable: false,
                className: "text-center",
            },
        ],
    });

    $("body").on("click", ".description_icon", function () {
        $("#description").modal("show");
        var id = $(this).attr("id");
        $.ajax({
            url: DESCRIPTION_URL,
            type: "POST",
            data: {
                id: id,
                _token: CSRF_TOKEN,
            },
            success: function (response) {
                $("#description_details").text(response);
            },
        });
        $("#description_details").text();
    });
});
