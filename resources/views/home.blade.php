@extends('layouts.app')

@section('content')
    <div class="row justify-content-center">
        <div class="col-md-12">
            <main-page user-Id="{{\Illuminate\Support\Facades\Auth::user()->id}}"></main-page>
        </div>
    </div>
@endsection
