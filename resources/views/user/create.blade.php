@extends('content', ['title' => 'Create User'])
@section('sub-content')
    <div class="form-container">
        <form id="usersForm" action="/users/store" method="post" role="form" novalidate>
            <input type="hidden" name="_token" value="{{ csrf_token() }}">
            <button type="submit" class="btnSubmitForm btn-success btn">SUBMIT</button>
            <div class="row">
                <div class="form-group mt-3">
                    <label class="label-text" for="first_name">FIRST NAME</label>
                    <input type="text" name="first_name" class="form-control" required>
                </div>
                <div class="form-group mt-3">
                    <label class="label-text" for="last_name">LAST NAME</label>
                    <input type="text" name="last_name" class="form-control" required>
                </div>
                <div class="form-group mt-3">
                    <label class="label-text" for="username">USERNAME</label>
                    <input type="text" class="form-control" name="username" >
                </div>
                <div class="form-group mt-3">
                    <label class="label-text" for="email">EMAIL</label>
                    <input type="text" class="form-control" name="email" >
                </div>
                <div class="form-group mt-3">
                    <label class="label-text" for="is_admin">IS ADMIN</label>
                    <select class="form-control" name="is_admin">
                        <option value="0">NO</option>
                        <option value="1">YES</option>
                    </select>
                </div>
            </div>
        </form>
    </div>
@endsection
