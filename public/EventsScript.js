$(document).ready(function () {
    $('#events').DataTable({
        processing: true,
        serverSide: true,
        ajax: EVENTS_AJAX_URL,
        columns: [{
                data: 'event_title',
                name: 'event_title',
                orderable: true,
                searchable: true,
                className: 'text-start'
            },
            {
                data: 'category',
                name: 'category',
                orderable: false,
                searchable: false,
                className: 'text-start'
            },
            {
                data: 'start_date',
                name: 'start_date',
                orderable: false,
                searchable: false,
                className: 'text-center'
            },
            {
                data: 'end_date',
                name: 'end_date',
                orderable: false,
                searchable: false,
                className: 'text-center'
            },
            {
                data: 'event_image',
                name: 'event_image',
                orderable: false,
                searchable: false,
                className: 'text-center'
            },
            {
                data: 'document',
                name: 'document',
                orderable: false,
                searchable: false,
                className: 'text-center'
            },
            {
                data: 'status',
                name: 'status',
                orderable: false,
                searchable: false,
                className: 'text-center'
            },
            {
                data: 'action',
                name: 'action',
                orderable: false,
                searchable: false,
                className: 'text-center'
            },
        ]
    });
    
    
    //Publish/Unpublish Event.
    $('body').on('change','.eventStatus', function() {
    
        $.ajax({
            url: EVENTS_STATUS_URL,
            type: 'POST',
            data: {
                'eventId': $(this).attr('id'),
                'status': $(this).is(':checked') ? 'Y' : 'N',
                '_token': EVENTS_CSRF_TOKEN
            },
            success: function(response) {
                if (response.status == "success") {
                    $('#events').DataTable();
                    toastr.success(response.message, 'Success');
                } else if (response.status == "error") {
                    toastr.error(response.message, 'Error');
                }
            },
        });
    });

    $("#errorAlert").delay(3000).fadeOut(500, function() {
        $(this).remove();
    });

    $("#successAlert").delay(3000).fadeOut(100, function() {
        $(this).remove();
    });
});