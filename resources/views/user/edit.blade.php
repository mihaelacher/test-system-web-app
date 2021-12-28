@extends('content', ['title' => 'Edit User'])
@section('sub-content')
    <div class="form-container">
        <form id="usersForm" action="/users/{{ $user->id }}/update" method="post" role="form" novalidate>
            <input type="hidden" name="_token" value="{{ csrf_token() }}">
            <button type="submit" class="btnSubmitForm btn-success btn">SUBMIT</button>
            <div class="row">
                <div class="form-group mt-3">
                    <label class="label-text" for="first_name">FIRST NAME</label>
                    <input type="text" name="first_name" class="form-control" required
                           value="{{ old('first_name') ?? $user->first_name }}">
                </div>
                <div class="form-group mt-3">
                    <label class="label-text" for="last_name">LAST NAME</label>
                    <input type="text" name="last_name" class="form-control" required
                           value="{{ old('last_name') ?? $user->last_name }}">
                </div>
                <div class="form-group mt-3">
                    <label class="label-text" for="username">USERNAME</label>
                    <input type="text" class="form-control" name="username"
                           value="{{ old('username') ?? $user->username }}">
                </div>
                <div class="form-group mt-3">
                    <label class="label-text" for="email">EMAIL</label>
                    <input type="text" class="form-control" name="email"
                           value="{{ old('email') ?? $user->email }}">
                </div>
                @php
                    $isAdmin = old('is_admin') ?? $user->is_admin;
                @endphp
                <div class="form-group mt-3">
                    <label class="label-text" for="is_admin">IS ADMIN</label>
                    <select class="form-control" name="is_admin" >
                        <option value="0" @if(!$isAdmin) selected @endif>NO</option>
                        <option value="1" @if($isAdmin) selected @endif>YES</option>
                    </select>
                </div>
            </div>
        </form>
    </div>
@endsection
