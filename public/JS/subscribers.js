$(document).ready(function () {
    $("#subscribers").dataTable({
        processing: true,
        serverSide: true,
        ajax: SUBSCRIBERS_AJAX_URL,
        columns: [
            {
                data: "event_title",
                name: "event_title",
                orderable: true,
                searchable: true,
                className: "text-start",
            },
            {
                data: "name",
                name: "name",
                orderable: true,
                searchable: true,
                className: "text-start",
            },
            {
                data: "created_at",
                name: "created_at",
                orderable: true,
                searchable: false,
                className: "text-center",
            },
            {
                data: "email",
                name: "email",
                orderable: false,
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
        ],
    });
});
