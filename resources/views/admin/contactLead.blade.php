@extends('admin.layout.comman')

@section('title', 'Contact Lead')
@section('main')
    <h1>Contact Lead</h1>

    <div class="m-4">
        <table class="table table-striped  text-center" id="contactLead">
            <thead>
                <th>ID</th>
                <th>Name</th>
                <th>Email</th>
                <th>Phone</th>
                <th>Address</th>
                <th>Description</th>
                <th>Recived Date</th>
            </thead>
            <tbody>
                @foreach ($data as $contactLead)
                    <tr id={{ $contactLead->id }}>
                        <td>{{ $contactLead->id }}</td>
                        <td>{{ $contactLead->name }}</td>
                        <td>{{ $contactLead->email }}</td>
                        <td>{{ $contactLead->phone }}</td>
                        <td>{{ $contactLead->address }}</td>
                        <td><i class="fa fa-commenting description_icon" aria-hidden="true" data-bs-toggle="modal"
                                data-bs-target="#description"></i></td>
                        <td>{{ $contactLead->created_at }}</td>
                    </tr>
                @endforeach
            </tbody>
        </table>
    </div>

    <div class="modal fade" id="description" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered">
            <div class="modal-content">
                <div class="modal-header">
                        <h1 class="modal-title fs-5" id="staticBackdropLabel">Description</h1>
                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                      </div>
                <div class="modal-body">
                    <p id="description_details"></p>
                </div>

            </div>
        </div>
    </div>
    <script src="https://code.jquery.com/jquery-3.7.1.min.js"></script>
    <script src="https://cdn.datatables.net/2.3.3/js/dataTables.min.js"></script>  
    <script>
        $(document).ready(function() {

            $('#contactLead').DataTable();

            $('.description_icon').click(function() {

                let row = $(this).closest('tr');

                $.ajax({

                    url: "{{ route('getDescription') }}",
                    type: "POST",
                    data: {
                        id: row.attr('id'),
                        _token: "{{ csrf_token() }}"
                    },
                    success: function(response) {
                        $('#description_details').text(response);
                    }
                });

                $('#description_details').text();
            });


        });
    </script>

@endsection
