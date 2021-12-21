@extends('page-sidebar')
@section('content')
    <div class="container">
        @if($showEvaluateBtn)
            <a class="btn btn-primary" href="/testexecution/evaluate/{{ $testExecution->id }}">Evaluate</a>
        @endif
        <h1>Test execution:</h1>
        <div>
            <label class="col-md-3 control-label left">Start time:</label>
            <div class="col-md-8">
                <p class="form-control-plaintext">
                    {{ $testExecution->start_time }}
                </p>
            </div>
        </div>
        <div>
            <label class="col-md-3 control-label left">End time:</label>
            <div class="col-md-8">
                <p class="form-control-plaintext">
                    {{ $testExecution->end_time }}
                </p>
            </div>
        </div>
        <div class="col-md-12">
            <label class="col-md-3 control-label left">Result points:</label>
            <div class="col-md-8">
                <p class="form-control-plaintext">
                    {{ $testExecution->result_points }}
                </p>
            </div>
        </div>
       @foreach($questions ?? [] as $index => $question)
            <div>
                <label class="col-md-2 control-label left">Question {{ $index + 1 }}:</label>
                <div class="col-md-10">
                    <p class="form-control-plaintext">
                        {{ $question->text }}
                    </p>
                </div>
            </div>
            @if($question->is_open)
                <div class="col-md-12">
                    <label class="col-md-3 control-label left">Answer:</label>
                    <div class="col-md-8">
                        <p class="form-control-plaintext">
                            {{ $question->response_text_short }}
                        </p>
                    </div>
                </div>
            @else
                @php
                    $testExecutionAnswers = explode(',', $question->answer_ids);
                @endphp
                @foreach($question->answers ??[] as $answer)
                    <div>
                        <input class="col-md-1" type="checkbox" disabled @if(in_array($answer->id, $testExecutionAnswers)) checked @endif/>
                        <div class="col-md-11 @if($answer->is_correct ===1) text-success @endif">{{ $answer->value }}</div>
                    </div>
                @endforeach
            @endif
       @endforeach
    </div>
@endsection
