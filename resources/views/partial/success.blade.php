@if (\Illuminate\Support\Facades\Session::has('flash_message'))
    <div class="alert alert-success">
        <p>{{ \Illuminate\Support\Facades\Session::get('flash_message') }}</p>
    </div>
@endif
