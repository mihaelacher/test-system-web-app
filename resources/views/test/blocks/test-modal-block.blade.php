<div class="modal-header">
    <h5 class="modal-title" id="exampleModalLongTitle">{{ $test->name }}</h5>
    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
        <span aria-hidden="true">&times;</span>
    </button>
</div>
<div class="modal-body">
    @if($test->intro_text)
        <div class="form-group mt-3">
            <label class="label-text" for="name">INTRO TEXT</label>
            <p>{{ $test->intro_text }}</p>
        </div>
    @endif
    <div class="form-group mt-3">
        <label class="label-text" for="name">MAX DURATION</label>
        <p>{{ $test->max_duration }}</p>
    </div>
    <div class="form-group mt-3">
        <label class="label-text" for="name">ACTIVE FROM</label>
        <p>{{ \App\Services\UtilService::formatDate($test->active_from)}}</p>
    </div>
    <div class="form-group mt-3">
        <label class="label-text" for="name">ACTIVE TO</label>
        <p>{{ \App\Services\UtilService::formatDate($test->active_to) }}</p>
    </div>
</div>
