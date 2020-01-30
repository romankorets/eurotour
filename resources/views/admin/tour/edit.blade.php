@extends('layouts.app')

@section('content')
    <div class="container">
        <div class="row justify-content-center">
            <div class="col-md-offset-9 col-md-9">
                <form method="post" action="{{ route('tour.update', ['tour' => $tour->id])}}" enctype="multipart/form-data">
                    {{ csrf_field() }}
                    {{ method_field('put') }}
                    <div class="row">
                        <div class="col-md-6">
                            <div class="form-group">
                                <label for="name">Назва туру</label>
                                <input type="text" class="form-control" id="name" name="name" value="{{ $tour->name }}">
                            </div>
                            <div class="form-group">
                                <label for="slug">Slug</label>
                                <input type="text" class="form-control" id="slug" name="slug" value="{{ $tour->slug }}">
                            </div>
                            <div class="form-group">
                                <label for="description">Опис</label>
                                <textarea id="description" name="description"
                                          class="form-control">{{ $tour->description }}</textarea>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="form-group">
                                <label for="photos">Фото</label>
                                <input type="file" multiple="multiple" class="form-control photos-input" id="photos"
                                       name="photos[]">
                            </div>
                            <div class="form-group">
                                <label for="duration">Тривалість(в днях)</label>
                                <input type="number" class="form-control" id="duration" name="duration"
                                       value="{{ $tour->duration }}">
                            </div>
                        </div>
                    </div>
                    @if(count($places))
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
                                            <div id="carouselControls{{$place->id}}" class="carousel slide"
                                                 data-ride="carousel">
                                                <div class="carousel-inner">
                                                    @foreach(json_decode($place->photos) as $photo)
                                                        @if($loop->first)
                                                            <div class="carousel-item active">
                                                                <img class="d-block w-100"
                                                                     src="{{asset('/storage/' . $photo)}}"
                                                                     alt="{{$place->name}}">
                                                            </div>
                                                        @else
                                                            <div class="carousel-item">
                                                                <img class="d-block w-100"
                                                                     src="{{asset('/storage/' . $photo)}}"
                                                                     alt="{{$place->name}}">
                                                            </div>
                                                        @endif
                                                    @endforeach
                                                    <a class="carousel-control-prev"
                                                       href="#carouselControls{{$place->id}}" role="button"
                                                       data-slide="prev">
                                                        <span class="carousel-control-prev-icon"
                                                              aria-hidden="true"></span>
                                                        <span class="sr-only">Previous</span>
                                                    </a>
                                                    <a class="carousel-control-next"
                                                       href="#carouselControls{{$place->id}}" role="button"
                                                       data-slide="next">
                                                        <span class="carousel-control-next-icon"
                                                              aria-hidden="true"></span>
                                                        <span class="sr-only">Next</span>
                                                    </a>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="col-md-6 description-column">
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
                                                @if($place->rating < 10)
                                                    @for($i = 10 - $place->rating; $i > 0; $i--)
                                                        <span></span>
                                                    @endfor
                                                @endif
                                            </div>
                                        </div>
                                    </div>
                                    <div class="row justify-content-start">
                                        <div class="col-md-4">
                                            <label class="checkbox-transform">
                                                @if($tour->places()->where('id', $place->id)->exists())
                                                    <input type="checkbox" class="checkbox__input" checked
                                                           name="places[]" value="{{ $place->id }}">
                                                @else
                                                    <input type="checkbox" class="checkbox__input" name="places[]"
                                                           value="{{ $place->id }}">
                                                @endif
                                                <span class="checkbox__label">Додати до туру</span>
                                            </label>
                                        </div>
                                        <div class="col-md-4">
                                            <label class="checkbox-transform">
                                                @if($tour->places()->where('id', $place->id)->wherePivot('isStartPlace', true)->exists())
                                                    <input type="radio" class="checkbox__input" checked
                                                           name="startPlace" value="{{ $place->id }}">
                                                @else
                                                    <input type="radio" class="checkbox__input" name="startPlace"
                                                           value="{{ $place->id }}">
                                                @endif
                                                <span class="checkbox__label">Початкова локація</span>
                                            </label>
                                        </div>
                                        <div class="col-md-4">
                                            <label class="checkbox-transform">
                                                @if($tour->places()->where('id', $place->id)->wherePivot('isFinishPlace', true)->exists())
                                                    <input type="radio" class="checkbox__input" name="finishPlace"
                                                           checked value="{{ $place->id }}">
                                                @else
                                                    <input type="radio" class="checkbox__input" name="finishPlace"
                                                           value="{{ $place->id }}">
                                                @endif
                                                <span class="checkbox__label">Кінцева локація</span>
                                            </label>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        @endforeach
                        {{ $places->links() }}
                    @else
                        <h1>Локації відсутні</h1>
                    @endif
                    <div class="row justify-content-center">
                        <input type="submit" value="Відправити" class="btn btn-success">
                    </div>
                </form>
            </div>
        </div>
    </div>
@endsection
