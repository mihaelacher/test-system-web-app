@extends('page-sidebar')
@section('content')
    <form id="testForm" action="/users/changePassword/{{ $userId }}" method="post">
        <input type="hidden" name="_token" value="{{ csrf_token() }}">
        <button type="submit" class="btn-primary btn">Submit</button>
        <div>
            <div class="form-group">
                <label class="col-md-2 control-label">New password:</label>
                <div class="col-md-9">
                    <input type="text" class="form-control placeholder-no-fix" placeholder="Password"
                           autocomplete="off" name="password"/>
                </div>
            </div>
        </div>
    </form>
@endsection
