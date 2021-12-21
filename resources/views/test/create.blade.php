@extends('page-sidebar', ['title' => 'Create Test'])
@section('content')
    <form id="testForm" action="/tests/create" method="post">
        <input type="hidden" name="_token" value="{{ csrf_token() }}">
        <button type="submit" class="btn-success btn">Submit</button>
        <div>
            <div class="form-group">
                <label class="col-md-2 control-label">Name:</label>
                <div class="col-md-9">
                    <input type="text" class="form-control placeholder-no-fix" placeholder="Name" autocomplete="off" name="name"/>
                </div>
            </div>
            <div class="form-group">
                <label class="col-md-2 control-label">Intro text:</label>
                <div class="col-md-9">
                    <input type="text" class="form-control placeholder-no-fix" placeholder="Intro text" autocomplete="off" name="intro_text"/>
                </div>
            </div>
            <div class="form-group">
                <label class="col-md-2 control-label">Max duration:</label>
                <div class="col-md-9">
                    <input class="form-control placeholder-no-fix" placeholder="In minutes" autocomplete="off" name="max_duration"/>
                </div>
            </div>
            <div class="form-group">
                <label class="col-md-2 control-label">Is visible for other admins:</label>
                <div class="col-md-9">
                    <select class="form-control" name="is_visible_for_admins">
                        <option value="0"> No </option>
                        <option value="1"> Yes </option>
                    </select>
                </div>
            </div>
        </div>
        <div id="questionsTable" class="col-md-12 mt-5"></div>
    </form>
    <button id="questionsLoadBtn" class="col-md-3 btn-secondary btn">Select questions from Database</button>
@endsection
