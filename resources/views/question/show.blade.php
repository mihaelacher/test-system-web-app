@extends('page-sidebar')
@section('content')
    <div class="container">
        <h1>Question:</h1>
        <div>
            <label class="col-md-3 control-label left">Text:</label>
            <div class="col-md-8">
                <p class="form-control-plaintext">
                    {{ $question->text }}
                </p>
            </div>
        </div>
        <div>
            <label class="col-md-3 control-label left">Instruction:</label>
            <div class="col-md-8">
                <p class="form-control-plaintext">
                    {{ $question->instruction }}
                </p>
            </div>
        </div>
        <div>
            <label class="col-md-3 control-label left">Points:</label>
            <div class="col-md-8">
                <p class="form-control-plaintext">
                    {{ $question->points }}
                </p>
            </div>
        </div>
        <div>
            <label class="col-md-3 control-label left">Type:</label>
            <div class="col-md-8">
                <p class="form-control-plaintext">
                    {{ $question->type }}
                </p>
            </div>
        </div>
        <div>
            <label class="col-md-3 control-label left">Max markable answers:</label>
            <div class="col-md-8">
                <p class="form-control-plaintext">
                    {{ $question->max_markable_answers }}
                </p>
            </div>
        </div>
        <div>
            <label class="col-md-3 control-label left">Open question:</label>
            <div class="col-md-8">
                <p class="form-control-plaintext">
                    {{ $question->is_open === 1 ? 'Yes' : 'No' }}
                </p>
            </div>
        </div>
        <div>
            <label class="col-md-3 control-label left">Creation:</label>
            <div class="col-md-8">
                <p class="form-control-plaintext">
                    {{ \Carbon\Carbon::parse($question->created_at)->format('d.m.Y H:i:s') }}
                </p>
            </div>
        </div>
        @if(!$question->is_open)
            <h1>Answers:</h1>
            @foreach($answers ?? [] as $answer)
                <div>
                    <input class="col-md-1 disabled" disabled type="checkbox" @if($answer->is_correct) checked @endif />
                    <div class="col-md-11">
                        {{ $answer->value }}
                    </div>
                </div>
            @endforeach
        @endif
    </div>
@endsection
