@extends('page-sidebar')
@section('content')
    <form id="executionForm" action="/tests/execute/{{ $testExecutionId }}" method="post">
        <input type="hidden" name="_token" value="{{ csrf_token() }}">
        <input type="hidden" value="{{ $timeRemainingInSec }}" id="timeRemainingInSec">
        <input type="hidden" value="{{ $testExecutionId }}" id="testExecutionId">
        <button id="finishExecution" type="submit" class="btn-primary btn">Submit</button>
        <div>
             Time remaining:<span class="big fw-bold" id="testCountDown">{{ $remainingTime }}</span>
        </div>
        @foreach($questions ?? [] as $index => $question)

        <div class="col-md-12 mt-5">
            <div>
                <label class="col-md-2 control-label left">Question {{ $index + 1 }}:</label>
                <div class="col-md-10">
                    <p class="form-control-plaintext">
                        {{ $question->text }}
                    </p>
                </div>
            </div>
            <div>
                <label class="col-md-2 control-label left">Instruction:</label>
                <div class="col-md-10">
                    <p class="form-control-plaintext">
                        {{ $question->instruction }}
                    </p>
                </div>
            </div>
            @php
                $isOpen = $question->is_open;
            @endphp
            @if($isOpen)
                <textarea class="js-question-answers" data-question_id="{{ $question->id }}" rows="3" cols="150"></textarea>
            @else
                @php
                $answers = explode(',', $question->answers);
                @endphp
                @foreach($answers ?? [] as $answer)
                    @php
                    $answerIdValueArr = explode('-', $answer);
                    $id = $answerIdValueArr[0];
                    $value = $answerIdValueArr[1];
                    @endphp
                    <div>
                        <input data-question_id="{{ $question->id }}" data-answer_id="{{ $id }}"
                               class="col-md-1 js-question-answers" type="checkbox"/>
                        <div class="col-md-11">{{ $value }}</div>
                    </div>
                @endforeach
            @endif
        </div>
        @endforeach
    </form>
@endsection
