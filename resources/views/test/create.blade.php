@extends('content', ['title' => 'Create Test'])
@section('sub-content')
    <div class="form-container">
        <form id="testForm" action="/tests/create" method="post" role="form" novalidate>
            <input type="hidden" name="_token" value="{{ csrf_token() }}">
            <button type="submit" class="btnSubmitForm btn-success btn">SUBMIT</button>
            <div class="row">
                <div class="form-group mt-3">
                    <label class="label-text" for="name">NAME</label>
                    <input type="text" name="name" class="form-control" required>
                </div>
                <div class="form-group mt-3">
                    <label class="label-text" for="intro_text">INTRO TEXT</label>
                    <input type="text" name="intro_text" class="form-control">
                </div>
                <div class="form-group mt-3">
                    <label class="label-text" for="max_duration">MAX DURATION</label>
                    <input type="number" class="form-control" name="max_duration" required>
                </div>
                <div class="form-group mt-3">
                    <label class="label-text" for="is_visible_for_admins">IS PUBLIC</label>
                    <select class="form-control" name="is_visible_for_admins">
                        <option value="0">NO</option>
                        <option value="1">YES</option>
                    </select>
                </div>
            </div>
            <div id="questionsTable" class="col-md-12 mt-5"></div>
        </form>
        <button id="questionsLoadBtn" type="button" class="col-md-3 btn-secondary btn">LOAD QUESTIONS</button>
    </div>
@endsection
