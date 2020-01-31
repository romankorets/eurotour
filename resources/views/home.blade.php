@extends('layouts.app')

@section('content')
    <div class="row justify-content-center">
        <div class="col-md-12">
            <google-map user-Id="{{\Illuminate\Support\Facades\Auth::user()->id}}"></google-map>
        </div>
    </div>
@endsection
