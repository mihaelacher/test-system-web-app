@if(\Illuminate\Support\Facades\Session::has('message'))
    <div class="alert {{ \Illuminate\Support\Facades\Session::get('classes') }} hide show-toastr">
        {!! \Illuminate\Support\Facades\Session::get('message') !!}
        <button type="button" class="close" data-dismiss="alert" aria-hidden="true"></button>
    </div>
@endif
