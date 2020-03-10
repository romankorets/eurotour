<?php

namespace App\Http\Controllers;

use App\Events\NewPlaceAdded;
use App\Http\Requests\StorePlaceRequest;
use App\Http\Requests\UpdatePlaceRequest;
use App\Place;
use Illuminate\Contracts\View\Factory;
use Illuminate\Http\Request;
use Illuminate\View\View;

class PlaceController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return false|string
     */
    public function getPlaces()
    {
        return Place::select(['slug', 'lat', 'lng'])->paginate(2);
    }

    public function getPlace(Place $place)
    {
        $place->load(['comments' => function ($query) {
            $query->where('enabled', true);
        }, 'comments.user',
            'likes.user',
        ]);
        return $place;
    }

    /**
     * Display a listing of the resource.
     *
     * @return Factory|\Illuminate\Http\JsonResponse|View
     */
    public function index()
    {
        $places = Place::paginate(3);
        return view('admin.place.index', ['places' => $places]);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return Factory|\Illuminate\Http\RedirectResponse|View
     */
    public function create()
    {
        return view('admin.place.create');
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param Request $request
     * @return \Illuminate\Http\RedirectResponse
     */

    public function store(StorePlaceRequest $request)
    {
        $photos = array();
        foreach ($request->file('photos') as $photo) {
            $photos[] = '/storage/' . $photo->store('uploads', 'public');
        }
        $place = Place::create([
            'name' => $request->get('name'),
            'slug' => $request->get('slug'),
            'description' => $request->get('description'),
            'rating' => $request->get('rating'),
            'photos' => $photos,
            'lat' => $request->get('lat'),
            'lng' => $request->get('lng'),
        ]);
        event(new NewPlaceAdded($place));
        $request->session()->flash('flash_message', 'Нова локація додана');
        return redirect()->route('admin');
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  Place  $place
     * @return Factory|\Illuminate\Http\RedirectResponse|View
     */
    public function edit(Place $place)
    {
        return view('admin.place.edit', [
            'place' => $place
        ]);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  Place  $place
     * @return \Illuminate\Http\RedirectResponse
     */
    public function update(UpdatePlaceRequest $request, Place $place)
    {
        $photos = array();
        foreach ($request->file('photos') as $photo) {
            $photos[] = '/storage/' . $photo->store('uploads', 'public');
        }
        $place->fill([
            'name' => $request->get('name'),
            'slug' => $request->get('slug'),
            'description' => $request->get('description'),
            'rating' => $request->get('rating'),
            'duration' => $request->get('duration'),
            'photos' => $photos
        ]);
        $request->session()->flash('flash_message', 'Локація оновлена');
        return redirect()->route('admin');
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param Place $place
     * @return \Illuminate\Http\RedirectResponse|\Illuminate\Routing\Redirector
     * @throws \Exception
     */
    public function destroy(Place $place)
    {
        $place->delete();
        session()->flash('flash_message', 'Локація видалена');
        return redirect()->route('admin');
    }
}
