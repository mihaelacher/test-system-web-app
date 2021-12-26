@extends('content', ['title' => 'Invite users'])
@section('sub-content')
    <div class="form-container">
        <form id="testParticipationForm" action="/tests/{{ $test->id }}/storeInvitations" method="post">
            <input type="hidden" name="_token" value="{{ csrf_token() }}">
            <button type="submit" class="btn-success btn">Submit</button>
            <div class="row">
                <div class="form-group mt-3">
                    <label class="label-text">ACTIVE FROM:</label>
                </div>
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
                <div class="form-group mt-3">
                    <label class="label-text">ACTIVE TO:</label>
                </div>
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
    </div>
@endsection
