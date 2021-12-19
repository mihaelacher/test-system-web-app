@extends('page-sidebar')
@section('content')
    <form id="testForm" action="/users/create" method="post">
        <input type="hidden" name="_token" value="{{ csrf_token() }}">
        <button type="submit" class="btn-primary btn">Submit</button>
        <div>
            <div class="form-group">
                <label class="col-md-2 control-label">First name:</label>
                <div class="col-md-9">
                    <input type="text" class="form-control placeholder-no-fix" placeholder="First name"
                           autocomplete="off" name="first_name"/>
                </div>
            </div>
            <div class="form-group">
                <label class="col-md-2 control-label">Last name:</label>
                <div class="col-md-9">
                    <input type="text" class="form-control placeholder-no-fix" placeholder="Last name"
                           autocomplete="off" name="last_name"/>
                </div>
            </div>
            <div class="form-group">
                <label class="col-md-2 control-label">Username:</label>
                <div class="col-md-9">
                    <input type="text" class="form-control placeholder-no-fix" placeholder="Username"
                           autocomplete="off" name="username"/>
                </div>
            </div>
            <div class="form-group">
                <label class="col-md-2 control-label">Email:</label>
                <div class="col-md-9">
                    <input type="text" class="form-control placeholder-no-fix" placeholder="Email"
                           autocomplete="off" name="email"/>
                </div>
            </div>
            <div class="form-group">
                <label class="col-md-2 control-label">Admin:</label>
                <div class="col-md-9">
                    <select class="form-control" name="is_admin">
                        <option value="0" selected> No </option>
                        <option value="1"> Yes </option>
                    </select>
                </div>
            </div>
        </div>
    </form>
@endsection
