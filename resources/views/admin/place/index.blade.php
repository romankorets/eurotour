@extends('layouts.app')

@section('content')
    <div class="container">
        @if(count($places))
            <h1>Список локацій</h1>
            @foreach($places as $place)
                <div class="row justify-content-center place">
                    <div class="col-md-12">
                        <div class="row justify-content-center">
                            <h2>{{ $place->name }}</h2>
                        </div>
                        <div class="row justify-content-center">
                            <div class="col-md-4 photos">
                                <h3>Фото</h3>
                                <div id="carouselControls{{$place->id}}" class="carousel slide" data-ride="carousel">
                                    <div class="carousel-inner">
                                        @foreach(json_decode($place->photos) as $photo)
                                            @if($loop->first)
                                                <div class="carousel-item active">
                                                    <img class="d-block w-100" src="{{asset('/storage/' . $photo)}}" alt="{{$place->name}}">
                                                </div>
                                            @else
                                                <div class="carousel-item">
                                                    <img class="d-block w-100" src="{{asset('/storage/' . $photo)}}" alt="{{$place->name}}">
                                                </div>
                                            @endif
                                        @endforeach
                                            <a class="carousel-control-prev" href="#carouselControls{{$place->id}}" role="button" data-slide="prev">
                                                <span class="carousel-control-prev-icon" aria-hidden="true"></span>
                                                <span class="sr-only">Previous</span>
                                            </a>
                                            <a class="carousel-control-next" href="#carouselControls{{$place->id}}" role="button" data-slide="next">
                                                <span class="carousel-control-next-icon" aria-hidden="true"></span>
                                                <span class="sr-only">Next</span>
                                            </a>
                                    </div>
                                </div>
                            </div>
                            <div class="col-md-6 description">
                                <h3>Опис</h3>
                                <span>{{ $place->description }}</span>
                                <h3>Координати</h3>
                                <span>lat : {{$place->lat}}</span>
                                <span>lat : {{$place->lng}}</span>
                            </div>
                            <div class="col-md-2 rating">
                                <h3>Рейтинг</h3>
                                <span>{{ $place->rating }}</span>
                            </div>
                        </div>
                        <div class="row justify-content-center">
                            <a class="btn btn-edit" href="{{route('place.edit', ['place' => $place->id])}}">Редагувати</a>
                        </div>
                    </div>
                </div>
            @endforeach
        @else
            <h1>Локації відсутні</h1>
        @endif
    </div>
@endsection
