@extends('page-sidebar', ['title' => 'Invite users'])
@section('content')
    <form id="testParticipationForm" action="/tests/storeInvitations/{{ $test->id }}" method="post">
        <input type="hidden" name="_token" value="{{ csrf_token() }}">
        <button type="submit" class="btn-success btn">Submit</button>
        <div>
            <div class="form-group">
                <label class="col-md-2 control-label">Active from:</label>
                <div class="container">
                    <div class="row">
                        <div class='col-sm-6'>
                            <div class="form-group">
                                <div class='input-group date' id='from-time-datetimepicker'>
                                    <input type='text' class="form-control" name="active_from"/>
                                    <span class="input-group-addon">
                                        <span class="glyphicon glyphicon-calendar"></span>
                                    </span>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <div class="form-group">
            <label class="col-md-2 control-label">Active to:</label>
            <div class="container">
                <div class="row">
                    <div class='col-sm-6'>
                        <div class="form-group">
                            <div class='input-group date' id='to-time-datetimepicker'>
                                <input type='text' class="form-control" name="active_to"/>
                                <span class="input-group-addon">
                                        <span class="glyphicon glyphicon-calendar"></span>
                                    </span>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        @include('user.blocks.index-table')
    </form>
@endsection
