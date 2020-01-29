@extends('layouts.app')

@section('content')
    <div class="container">
        @if(count($tours))
            <h1>Список турів</h1>
            @foreach($tours as $tour)
                <div class="row justify-content-center tour">
                    <div class="col-md-12">
                        <div class="row justify-content-center">
                            <h2>{{ $tour->name }}</h2>
                        </div>
                        <div class="row justify-content-center">
                            <div class="col-md-4 photos-column">
                                <h3>Фото</h3>
                                <div id="carouselControls{{$tour->id}}" class="carousel slide" data-ride="carousel">
                                    <div class="carousel-inner">
                                        @foreach(json_decode($tour->photos) as $photo)
                                            @if($loop->first)
                                                <div class="carousel-item active">
                                                    <img class="d-block w-100" src="{{asset('/storage/' . $photo)}}" alt="{{$tour->name}}">
                                                </div>
                                            @else
                                                <div class="carousel-item">
                                                    <img class="d-block w-100" src="{{asset('/storage/' . $photo)}}" alt="{{$tour->name}}">
                                                </div>
                                            @endif
                                        @endforeach
                                        <a class="carousel-control-prev" href="#carouselControls{{$tour->id}}" role="button" data-slide="prev">
                                            <span class="carousel-control-prev-icon" aria-hidden="true"></span>
                                            <span class="sr-only">Previous</span>
                                        </a>
                                        <a class="carousel-control-next" href="#carouselControls{{$tour->id}}" role="button" data-slide="next">
                                            <span class="carousel-control-next-icon" aria-hidden="true"></span>
                                            <span class="sr-only">Next</span>
                                        </a>
                                    </div>
                                </div>
                            </div>
                            <div class="col-md-6 description-column">
                                <h3>Опис</h3>
                                <span>{{ $tour->description }}</span>
                                <h3>Тривалість</h3>
                                @if($tour->duration == 1)
                                    <span>{{ $tour->duration }} день</span>
                                    @elseif($tour->duration > 1 && $tour->duration < 5)
                                        <span>{{ $tour->duration }} дні</span>
                                    @elseif($tour->duration >= 5)
                                        <span>{{ $tour->duration }} днів</span>
                                @endif
                            </div>
{{--                            TODO: add third column for places--}}
                        </div>
                    </div>
                </div>
            @endforeach
        @endif
    </div>
@endsection
