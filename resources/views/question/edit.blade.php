@extends('page-sidebar')
@section('content')
    <form id="questionForm" action="/questions/update/{{ $question->id }}" method="post">
        <input type="hidden" name="_token" value="{{ csrf_token() }}">
        <button type="submit" class="btn-primary btn">Submit</button>
        <div>
            <div class="form-group">
                <label class="col-md-2 control-label">Text:</label>
                <div class="col-md-9">
                    <input type="text" class="form-control placeholder-no-fix" placeholder="Text"
                           autocomplete="off" name="text" value="{{ $question->text }}"/>
                </div>
            </div>
            <div class="form-group">
                <label class="col-md-2 control-label">Instruction:</label>
                <div class="col-md-9">
                    <input type="text" class="form-control placeholder-no-fix" placeholder="Instruction"
                           autocomplete="off" name="instruction" value="{{ $question->instruction }}"/>
                </div>
            </div>
            <div class="form-group">
                <label class="col-md-2 control-label">Points:</label>
                <div class="col-md-9">
                    <input class="form-control placeholder-no-fix" placeholder="Points"
                           autocomplete="off" name="points" value="{{ $question->points }}"/>
                </div>
            </div>
            <div class="form-group">
                <label class="col-md-2 control-label">Type:</label>
                <div class="col-md-9">
                    <select class="form-control" name="type">
                        @foreach($questionTypes as $type)
                            <option value="{{ $type->id }}"
                                @if($question->question_type_id === $type->id) selected @endif>
                                {{ $type->name }} </option>
                        @endforeach
                    </select>
                </div>
            </div>
            <div class="form-group">
                <label class="col-md-2 control-label">Max markable answers:</label>
                <div class="col-md-9">
                    <input type="number" class="form-control placeholder-no-fix" placeholder="Max markable answers"
                           autocomplete="off" name="max_markable_answers" value="{{ $question->max_markable_answers }}"/>
                </div>
            </div>
            @php
            $isOpen = $question->is_open;
            @endphp
            <div class="form-group">
                <label class="col-md-2 control-label">Is question open:</label>
                <div class="col-md-9">
                    <select class="form-control" name="is_open" id="isQuestionOpen">
                        <option value="0" @if(!$isOpen) selected @endif> No </option>
                        <option value="1" @if($isOpen) selected @endif> Yes </option>
                    </select>
                </div>
            </div>
        </div>
        @if(!$isOpen)
            <div id="answersForm">
                @foreach($question->answers ?? [] as $answer)
                    <input type="hidden" name="answer_id[]" value="{{ $answer->id }}">
                    <div class="col-md-12 mt-5">
                        <div class="form-group mt-5">
                            <input class="col-md-1 isCorrectSelect" type="checkbox"
                                   @if($answer->is_correct) checked @endif>
                        </div>
                        <div class="form-group">
                            <label class="col-md-1">Answer number:</label>
                            <div class="col-md-2">
                                <input type="number" min="0" class="form-control" placeholder="Question number"
                                       autocomplete="off" name="order_num[]" value="{{ $answer->order_num }}"/>
                            </div>
                        </div>
                        <div class="form-group">
                            <label class="col-md-1">Answer:</label>
                            <div class="col-md-5">
                                <input type="text" class="form-control" placeholder="Answer" autocomplete="off"
                                       name="value[]" value="{{ $answer->value }}"/>
                            </div>
                        </div>
                    </div>
                @endforeach
            </div>
        @endif
    </form>
@endsection
