@extends('page-sidebar', ['title' => 'Create Question'])
@section('content')
    <form id="questionForm" action="/questions/create" method="post">
        <input type="hidden" name="_token" value="{{ csrf_token() }}">
        <button type="submit" class="btn-success btn">Submit</button>
    <div>
        <div class="form-group">
            <label class="col-md-2 control-label">Text:</label>
            <div class="col-md-9">
                <input type="text" class="form-control placeholder-no-fix" placeholder="Text" autocomplete="off" name="text"/>
            </div>
        </div>
        <div class="form-group">
            <label class="col-md-2 control-label">Instruction:</label>
            <div class="col-md-9">
                <input type="text" class="form-control placeholder-no-fix" placeholder="Instruction" autocomplete="off" name="instruction"/>
            </div>
        </div>
        <div class="form-group">
            <label class="col-md-2 control-label">Points:</label>
            <div class="col-md-9">
                <input class="form-control placeholder-no-fix" placeholder="Points" autocomplete="off" name="points"/>
            </div>
        </div>
        <div class="form-group">
            <label class="col-md-2 control-label">Type:</label>
            <div class="col-md-9">
               <select class="form-control" name="type">
                   @foreach($questionTypes as $type)
                        <option value="{{ $type->id }}"> {{ $type->name }} </option>
                   @endforeach
               </select>
            </div>
        </div>
        <div class="form-group">
            <label class="col-md-2 control-label">Max markable answers:</label>
            <div class="col-md-9">
                <input type="number" class="form-control placeholder-no-fix"
                       placeholder="Max markable answers" autocomplete="off" name="max_markable_answers"/>
            </div>
        </div>
        <div class="form-group">
            <label class="col-md-2 control-label">Is question open:</label>
            <div class="col-md-9">
                <select class="form-control" name="is_open" id="isQuestionOpen">
                    <option value="0"> No </option>
                    <option value="1" selected> Yes </option>
                </select>
            </div>
        </div>
    </div>
    <div id="answersForm" class="hidden">
        <div class="col-md-12 mt-5">
            <div class="form-group mt-5">
                <input class="col-md-1 isCorrectSelect" type="checkbox">
            </div>
            <div class="form-group">
                <label class="col-md-1">Answer number:</label>
                <div class="col-md-2">
                    <input type="number" min="0" class="form-control"
                           placeholder="Question number" autocomplete="off" name="order_num[]"/>
                </div>
            </div>
            <div class="form-group">
                <label class="col-md-1">Answer:</label>
                <div class="col-md-5">
                    <input type="text" class="form-control"
                           placeholder="Answer" autocomplete="off" name="value[]"/>
                </div>
            </div>
        </div>
        <div class="col-md-12 mt-5 questionAnswerForm">
            <div class="form-group mt-5">
                <input class="col-md-1 isCorrectSelect" type="checkbox">
            </div>
            <div class="form-group">
                <label class="col-md-1">Answer number:</label>
                <div class="col-md-2">
                    <input type="number" min="0" class="form-control"
                           placeholder="Question number" autocomplete="off" name="order_num[]"/>
                </div>
            </div>
            <div class="form-group">
                <label class="col-md-1">Answer:</label>
                <div class="col-md-5">
                    <input type="text" class="form-control"
                           placeholder="Answer" autocomplete="off" name="value[]"/>
                </div>
            </div>
        </div>
        <div class="col-md-12 mt-5 questionAnswerForm">
            <div class="form-group mt-5">
                <input class="col-md-1 isCorrectSelect" type="checkbox">
            </div>
            <div class="form-group">
                <label class="col-md-1">Answer number:</label>
                <div class="col-md-2">
                    <input type="number" min="0" class="form-control"
                           placeholder="Question number" autocomplete="off" name="order_num[]"/>
                </div>
            </div>
            <div class="form-group">
                <label class="col-md-1">Answer:</label>
                <div class="col-md-5">
                    <input type="text" class="form-control"
                           placeholder="Answer" autocomplete="off" name="value[]"/>
                </div>
            </div>
        </div>
        <div class="col-md-12 mt-5 questionAnswerForm">
            <div class="form-group mt-5">
                <input class="col-md-1 isCorrectSelect" type="checkbox">
            </div>
            <div class="form-group">
                <label class="col-md-1">Answer number:</label>
                <div class="col-md-2">
                    <input type="number" min="0" class="form-control"
                           placeholder="Question number" autocomplete="off" name="order_num[]"/>
                </div>
            </div>
            <div class="form-group">
                <label class="col-md-1">Answer:</label>
                <div class="col-md-5">
                    <input type="text" class="form-control"
                           placeholder="Answer" autocomplete="off" name="value[]"/>
                </div>
            </div>
        </div>
    </div>
    </form>
@endsection
