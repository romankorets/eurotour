@extends('layouts.app')

@section('content')
    <div class="container">

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
                            <div id="carouselControls{{$tour->id}}" class="carousel slide" data-ride="false">
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
                                    <a class="carousel-control-prev" href="#carouselControls{{$tour->id}}" role="button"
                                       data-slide="prev">
                                        <span class="carousel-control-prev-icon" aria-hidden="true"></span>
                                        <span class="sr-only">Previous</span>
                                    </a>
                                    <a class="carousel-control-next" href="#carouselControls{{$tour->id}}" role="button"
                                       data-slide="next">
                                        <span class="carousel-control-next-icon" aria-hidden="true"></span>
                                        <span class="sr-only">Next</span>
                                    </a>
                                </div>
                            </div>
                        </div>
                        <div class="col-md-4 description-column">
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
                        <div class="col-md-4 places-column">
                            <h3>Локації, включені в тур</h3>
                            <div class="row">
                                <div class="col-md-2 bordered">
                                    <h4>id</h4>
                                </div>
                                <div class="col-md-6 bordered">
                                    <h4>Назва</h4>
                                </div>
                                <div class="col-md-4 bordered">
                                    <h4>Рейтинг</h4>
                                </div>
                            </div>
                            @foreach($tour->places()->get() as $place)
                                <div class="row places-column">
                                    <div class="col-md-2 bordered">
                                        <span>{{ $place->id }}</span>
                                    </div>
                                    <div class="col-md-6 bordered">
                                        <span>{{ $place->name }}</span>
                                    </div>
                                    <div class="col-md-4 bordered">
                                        <div class="rating-mini">
                                            @for ($i = 0; $i < $place->rating; $i++)
                                                <span class="active"></span>
                                            @endfor
                                            @for($i = 0; 10 - $place->rating < $i ; $i++)
                                                    <span></span>
                                            @endfor
                                        </div>
                                    </div>
                                </div>
                            @endforeach
                        </div>
                    </div>
                </div>
                <div class="row justify-content-center">
                    <a class="btn btn-edit" href="{{route('tour.edit', ['tour' => $tour->id])}}">Редагувати</a>
                    <form method="post" action="{{ route('tour.destroy', ['tour' => $tour->id]) }}">
                        {{csrf_field()}}
                        {{method_field('delete')}}
                        <input type="submit" value="Видалити" class="btn btn-delete">
                    </form>
                </div>
            </div>
        @endforeach
        @if(count($tours) === 0)
            <div>Тури відсутні</div>
        @endif
    </div>
@endsection
