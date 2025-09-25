@extends('admin.layout.comman')

@section('title', 'Contact Lead')
@section('main')
    <h1>Contact Lead</h1>

    <div class="m-4">
        <table class="table table-bordered   text-center" id="contactLead" style="font-size: 15px;">
            <thead>
                <th>Name</th>
                <th>Email</th>
                <th>Phone</th>
                <th>Description</th>
                <th>Recived Date</th>
            </thead>

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
@endsection
@section('script')
    <script>       
        var AJAX_URL = "{{ route('admin-contactLead') }}";
        var DESCRIPTION_URL = "{{ route('getDescription') }}";
        var CSRF_TOKEN = "{{ csrf_token() }}";
    </script>
    <script src="{{ asset('JS/contactLead.js') }}"></script>
@endsection

