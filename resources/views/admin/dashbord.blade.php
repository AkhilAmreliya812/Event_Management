@extends('admin.layout.comman')

@section('title', 'Dashbord')
@section('main')
    <h1>Dashbord</h1>

    <div class="row">
        <div class="col-md-6">
            <table class="table" id="contactLead">
                <thead>
                    <th>Name</th>
                    <th>Email</th>
                    <th>Recived Date</th>
                </thead>
                <tbody>
                    @foreach($conatctLead as $contactLead) 
                        <tr>
                            <td>{{$contactLead->name}}</td>
                            <td>{{$contactLead->email}}</td>
                            <td>{{ date('Y M, d', strtotime($contactLead->created_at)) }}</td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
            <div class="d-flex justify-content-center m-3">
                <a class="btn btn-secondary" href="{{ route('admin-contactLead') }}">View all records</a>
            </div>
        </div>
    </div>
@endsection
