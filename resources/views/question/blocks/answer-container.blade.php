<div class="js-answer-container col-md-12 @if($hide) hidden @endif">
    <div class="form-group col-md-1 mt-5">
        <input class="js-correct-answer" type="{{ $questionType === $multipleChoiceType ? 'checkbox' : 'radio' }}"
               name="correct_answer[]" {{ optional($answer)->is_correct ? 'checked' : '' }}>
    </div>
    <div class="form-group col-md-7 mt-3">
        <label class="label-text" for="value">ANSWER TEXT</label>
        <input type="text" class="form-control" name="value[]" value="{{  optional($answer)->value ?? '' }}">
    </div>
    <button class="mt-5 btn-danger js-remove-answer-container-btn" disabled type="button">-</button>
</div>
