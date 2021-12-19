@extends('page-sidebar')
@section('content')
    <div class="portlet">
        <a class="btn btn-primary" href="/users/create">Create new</a>
        <div class="portlet-title">
            <div class="caption">All users</div>
        </div>

        <div class="table-responsive">
            <table id="usersIndexTable" class="table table-striped table-hover table-bordered">
                <thead>
                <tr>
                    <th>Full name</th>
                    <th>Username</th>
                    <th>Email</th>
                    <th>Admin</th>
                </tr>
                </thead>
                <tbody>
                </tbody>
            </table>
        </div>
    </div>
@endsection
