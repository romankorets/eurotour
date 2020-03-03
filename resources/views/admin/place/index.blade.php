@extends('layouts.app')

@section('content')
    <div class="container">
        <h1>Список локацій</h1>
        @foreach($places as $place)
            <div class="row justify-content-center place">
                <div class="col-md-12">
                    <div class="row justify-content-center">
                        <h2>{{ $place->name }} (id {{ $place->id }})</h2>
                    </div>
                    <div class="row justify-content-center">
                        <div class="col-md-4 photos-column">
                            <h3>Фото</h3>
                            <div id="carouselControls{{$place->id}}" class="carousel slide" data-ride="carousel">
                                <div class="carousel-inner">
                                    @foreach($place->photos as $photo)
                                        @if($loop->first)
                                            <div class="carousel-item active">
                                                <img class="d-block w-100" src="{{asset($photo)}}"
                                                     alt="{{$place->name}}">
                                            </div>
                                        @else
                                            <div class="carousel-item">
                                                <img class="d-block w-100" src="{{asset($photo)}}"
                                                     alt="{{$place->name}}">
                                            </div>
                                        @endif
                                    @endforeach
                                    <a class="carousel-control-prev" href="#carouselControls{{$place->id}}"
                                       role="button" data-slide="prev">
                                        <span class="carousel-control-prev-icon" aria-hidden="true"></span>
                                        <span class="sr-only">Previous</span>
                                    </a>
                                    <a class="carousel-control-next" href="#carouselControls{{$place->id}}"
                                       role="button" data-slide="next">
                                        <span class="carousel-control-next-icon" aria-hidden="true"></span>
                                        <span class="sr-only">Next</span>
                                    </a>
                                </div>
                            </div>
                        </div>
                        <div class="col-md-6 description-column">
                            <h3>Опис</h3>
                            <span>{{ $place->description }}</span>
                            <h3>Координати</h3>
                            <span>lat : {{$place->lat}}</span>
                            <span>lat : {{$place->lng}}</span>
                        </div>
                        <div class="col-md-2 rating-column">
                            <h3>Рейтинг</h3>
                            <div class="rating-result">
                                @for ($i = 0; $i < $place->rating; $i++)
                                    <span class="active"></span>
                                @endfor
                                @for($i = 0; 10 - $place->rating < $i ; $i++)
                                    <span></span>
                                @endfor
                            </div>
                        </div>
                    </div>
                    <div class="row justify-content-center">
                        <a class="btn btn-edit" href="{{ route('place.edit', ['place' => $place->id]) }}">Редагувати</a>
                        <form method="post" action="{{ route('place.destroy', ['place' => $place->id]) }}">
                            {{csrf_field()}}
                            {{method_field('delete')}}
                            <input type="submit" value="Видалити" class="btn btn-delete">
                        </form>
                    </div>
                </div>
            </div>
        @endforeach
        {{ $places->links() }}
        @if(count($places) === 0)
            <div>Локації відсутні</div>
        @endif
    </div>
@endsection
