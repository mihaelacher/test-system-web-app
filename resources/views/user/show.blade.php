@extends('content', ['title' => 'User'])
@section('sub-content')
    <div class="form-container">
        <input type="hidden" name="_token" value="{{ csrf_token() }}">
        <a class="btn btn-success" href="/users/{{ $user->id }}/edit">EDIT</a>
        <a class="btn btn-danger" href="/users/{{ $user->id }}/delete" data-method="delete"
           data-token="{{csrf_token()}}" data-confirm="Are you sure, you want to delete this user?">DELETE</a>
        <a class="btn btn-secondary" href="/users/{{ $user->id }}/changePassword">CHANGE PASSWORD</a>
        <div class="row">
            <div class="form-group mt-3">
                <label class="label-text" for="first_name">FIRST NAME</label>
                <p>{{ $user->first_name }}</p>
            </div>
            <div class="form-group mt-3">
                <label class="label-text" for="last_name">LAST NAME</label>
                <p>{{ $user->last_name }}</p>
            </div>
            <div class="form-group mt-3">
                <label class="label-text" for="username">USERNAME</label>
                <p>{{ $user->username }}</p>
            </div>
            <div class="form-group mt-3">
                <label class="label-text" for="email">EMAIL</label>
                <p>{{ $user->email }}</p>
            </div>
            <div class="form-group mt-3">
                <label class="label-text" for="is_admin">IS ADMIN</label>
                <p>{{ $user->is_admin ? "Yes" : "No" }}</p>
            </div>
        </div>
    </div>
@endsection
