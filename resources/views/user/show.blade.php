@extends('page-sidebar')
@section('content')
    <div class="container">
        <a class="btn btn-primary" href="/users/edit/{{ $user->id }}">Edit</a>
        <a class="btn btn-secondary" href="/users/changePassword/{{ $user->id }}">Change password</a>
        <h1>User:</h1>
        <div>
            <label class="col-md-3 control-label left">First name:</label>
            <div class="col-md-8">
                <p class="form-control-plaintext">
                    {{ $user->first_name }}
                </p>
            </div>
        </div>
        <div>
            <label class="col-md-3 control-label left">Last name:</label>
            <div class="col-md-8">
                <p class="form-control-plaintext">
                    {{ $user->last_name }}
                </p>
            </div>
        </div>
        <div>
            <label class="col-md-3 control-label left">Username:</label>
            <div class="col-md-8">
                <p class="form-control-plaintext">
                    {{ $user->username }}
                </p>
            </div>
        </div>
        <div>
            <label class="col-md-3 control-label left">Email:</label>
            <div class="col-md-8">
                <p class="form-control-plaintext">
                    {{ $user->email }}
                </p>
            </div>
        </div>
        <div>
            <label class="col-md-3 control-label left">Admin:</label>
            <div class="col-md-8">
                <p class="form-control-plaintext">
                    {{ $user->is_admin ? "Yes" : "No" }}
                </p>
            </div>
        </div>
        <div>
            <label class="col-md-3 control-label left">Creation:</label>
            <div class="col-md-8">
                <p class="form-control-plaintext">
                    {{ \Carbon\Carbon::parse($user->created_at)->format('d.m.Y H:i:s') }}
                </p>
            </div>
        </div>
    </div>
@endsection
