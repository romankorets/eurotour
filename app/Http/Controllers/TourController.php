<?php

namespace App\Http\Controllers;

use App\Http\Requests\StoreTourRequest;
use App\Http\Requests\UpdateTourRequest;
use App\Place;
use App\Tour;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;
use Illuminate\View\View;

class TourController extends Controller
{

    public function getTour(Tour $tour)
    {
        $tour->load('places');
        return $tour;
    }

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Contracts\View\Factory|View
     */
    public function index()
    {
        $tours = Tour::all();
        return view('admin.tour.index', [
            'tours' => $tours,
        ]);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\RedirectResponse|View
     */
    public function create()
    {
        $places = Place::paginate(10);
        return view('admin.tour.create', ['places' => $places]);
    }
        /**
         * Store a newly created resource in storage.
         *
         * @param Request $request
         * @return \Illuminate\Http\RedirectResponse
         */

    public function store(StoreTourRequest $request)
    {
        $photos = array();
        foreach ($request->file('photos') as $photo) {
            $photos[] = $photo->store('uploads', 'public');
        }
        $tour = Tour::create([
            'name' => $request->get('name'),
            'slug' => $request->get('slug'),
            'description' => $request->get('description'),
            'photos' => $photos,
            'duration' => $request->get('duration'),
        ]);
        $arrayOfPlacesId = $request->get('places');
        $startPlaceId = $request->get('startPlace');
        $finishPlaceId = $request->get('finishPlace');
        foreach ($arrayOfPlacesId as $placeId) {
            $place = Place::findOrFail($placeId);
            $tour->places()->attach($place, [
                'isStartPlace' => $placeId === $startPlaceId,
                'isFinishPlace' => $placeId === $finishPlaceId,
            ]);
        }
        $request->session()->flash('flash_message', 'Новий тур додано');
        return redirect()->route('admin');
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  Tour  $tour
     * @return \Illuminate\Http\RedirectResponse|View
     */
    public function edit(Tour $tour)
    {
        $places = Place::paginate(5);
        return view('admin.tour.edit', [
            'tour' => $tour,
            'places' => $places
        ]);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  Tour  $tour
     * @return \Illuminate\Http\RedirectResponse
     */
    public function update(UpdateTourRequest $request, Tour $tour)
    {
        $photos = array();
        if ($request->has('photos')) {
            foreach ($request->file('photos') as $photo) {
                $photos[] = $photo->store('uploads', 'public');
            }
            $tour->fill([
                'name' => $request->get('name'),
                'slug' => $request->get('slug'),
                'description' => $request->get('description'),
                'duration' => $request->get('duration'),
                'photos' => $photos,
            ]);
        } else {
            $tour->fill([
                'name' => $request->get('name'),
                'slug' => $request->get('slug'),
                'description' => $request->get('description'),
                'duration' => $request->get('duration'),
            ]);
        }
        $arrayOfPlacesId = $request->get('places');
        $startPlaceId = $request->get('startPlace');
        $finishPlaceId = $request->get('finishPlace');
        $tour->places()->detach();
        foreach ($arrayOfPlacesId as $placeId) {
            $place = Place::findOrFail($placeId);
            $tour->places()->attach($place, [
                'isStartPlace' => $placeId === $startPlaceId,
                'isFinishPlace' => $placeId === $finishPlaceId,
            ]);
        }
        $request->session()->flash('flash_message', 'Тур оновлено');
        return redirect()->route('admin');
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param Tour $tour
     * @return \Illuminate\Http\RedirectResponse|\Illuminate\Routing\Redirector
     * @throws \Exception
     */
    public function destroy(Tour $tour)
    {
        $tour->delete();
        session()->flash('flash_message', 'Тур видалено');
        return redirect()->route('admin');
    }
}
