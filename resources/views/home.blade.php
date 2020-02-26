@extends('layouts.app')

@section('content')
    <div class="row justify-content-center b-container">
        <div class="col-md-12">
            <router-view name="placesMap"></router-view>
            <router-view name="placeModal"></router-view>
            <div class="row justify-content-center">
                <h1>Тури</h1>
            </div>
            @foreach($tours as $tour)
                <div class="row justify-content-center tour">
                    <div class="col-md-12">
                        <div class="row justify-content-center">
                            <h3>{{ $tour->name }}</h3>
                        </div>
                        <div class="row justify-content-center ">
                            <div class="col-md-4 bordered">
                                <div id="carouselControls{{ $tour->id }}" class="carousel slide" data-ride="carousel">
                                    <div class="carousel-inner">
                                        @foreach($tour->photos as $photo)
                                            @if($loop->first)
                                                <div class="carousel-item active">
                                                    <img class="d-block w-100" src="{{asset('/storage/' . $photo)}}"
                                                         alt="{{$tour->name}}">
                                                </div>
                                            @else
                                                <div class="carousel-item">
                                                    <img class="d-block w-100" src="{{asset('/storage/' . $photo)}}"
                                                         alt="{{$tour->name}}">
                                                </div>
                                            @endif
                                        @endforeach
                                        <a class="carousel-control-prev" href="#carouselControls{{ $tour->id }}"
                                           role="button"
                                           data-slide="prev">
                                            <span class="carousel-control-prev-icon" aria-hidden="true"></span>
                                            <span class="sr-only">Previous</span>
                                        </a>
                                        <a class="carousel-control-next" href="#carouselControls{{ $tour->id }}"
                                           role="button"
                                           data-slide="next">
                                            <span class="carousel-control-next-icon" aria-hidden="true"></span>
                                            <span class="sr-only">Next</span>
                                        </a>
                                    </div>
                                </div>
                            </div>
                            <div class="col-md-5 justify-content-center bordered">
                                <h4>Опис</h4>
                                {{ $tour->description }}
                            </div>
                            <div class="col-md-3 justify-content-center bordered">
                                <h4>Тривалість (днів)</h4>
                                {{ $tour->duration }}
                            </div>
                        </div>
                        <div class="row justify-content-center">
                            <button class="btn btn-primary">Показати на карті</button>
                        </div>
                    </div>
                </div>
            @endforeach
            <div class="row justify-content-center">
                {{ $tours->links() }}
            </div>
        </div>
    </div>
@endsection
