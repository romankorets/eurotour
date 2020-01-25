@extends('layouts.app')

@section('content')
    <div class="container">
        <div class="row justify-content-center">
            <div class="col-md-6">
                <div class="card">
                    <div class="card-header">Головне меню</div>
                    <div class="card-body">
                        <div class="row justify-content-center">
                            <a class="btn btn-menu" href="{{route('tour.create')}}">Додати тур</a>
                        </div>
                        <div class="row justify-content-center">
                            <a class="btn btn-menu" href="{{route('place.create')}}">Додати локацію</a>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection
