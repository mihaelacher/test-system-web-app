@extends('content', ['title' => 'Change User Password'])
@section('sub-content')
    <div class="form-container">
        <form id="changePasswordForm" action="/users/{{ $userId }}/storePassword" method="post" role="form" novalidate>
            <input type="hidden" name="_token" value="{{ csrf_token() }}">
            <button type="submit" class="btnSubmitForm btn-success btn">SUBMIT</button>
            <div class="row">
                <div class="form-group mt-3">
                    <label class="label-text" for="password">NEW PASSWORD</label>
                    <input id="password" type="password" name="password" class="form-control" required>
                </div>
                <div class="form-group mt-3">
                    <label class="label-text" for="password_confirmation">CONFIRM PASSWORD</label>
                    <input type="password" name="password_confirmation" class="form-control" required>
                </div>
            </div>
        </form>
    </div>
@endsection
