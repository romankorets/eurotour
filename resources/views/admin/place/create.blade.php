@extends('layouts.app')

@section('content')
    <div class="container">
        <div class="row justify-content-center">
            <div class="col-md-offset-9 col-md-9">
                <form method="post" action="{{ route('place.store') }}">
                    {{ csrf_field() }}
                    <div class="row">
                        <div class="col-md-6">
                            <div class="form-group">
                                <label for="name">Назва локації</label>
                                <input type="text" class="form-control" id="name" name="name">
                            </div>
                            <div class="form-group">
                                <label for="slug">Slug</label>
                                <input type="text" class="form-control" id="slug" name="slug">
                            </div>
                            <div class="form-group">
                                <label for="description">Опис</label>
                                <textarea id="description" name="description" class="form-control"></textarea>
                            </div>
                            <div class="form-group">
                                <label for="rating">Оцінка</label>
                                <input type="number" class="form-control" id="rating" name="rating">
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="form-group">
                                <label for="lat">Широта(lat)</label>
                                <input type="text" class="form-control" id="lat" name="lat">
                            </div>
                            <div class="form-group">
                                <label for="lng">Довгота(lng)</label>
                                <input type="text" class="form-control" id="lng" name="lng">
                            </div>
                            <div class="form-group">
                                <label for="photos">Фото</label>
                                <input type="file" multiple="multiple" class="form-control photos-input" id="photos" name="photos">
                            </div>
                        </div>
                    </div>
                    <div class="row justify-content-center">
                        <input type="submit" value="Відправити" class="btn btn-success">
                    </div>

                </form>
            </div>
        </div>
    </div>
@endsection
