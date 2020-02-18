<?php

namespace App\Http\Controllers;

use App\Http\Requests\StoreTourRequest;
use App\Place;
use App\Tour;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;
use Illuminate\View\View;

class TourController extends Controller
{

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\JsonResponse
     */

    public function getTours()
    {
        $tours = Tour::with('places')->get();
        return response()->json(json_encode($tours));
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
        $user = Auth::user();
        $places = Place::paginate(10);
        if ($user->can('create', Tour::class)) {
            return view('admin.tour.create', ['places' => $places]);
        } else return redirect()->back();
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
        foreach ($request->file('photos') as $photo)
        {
            $photos[] = $photo->store('uploads', 'public');
        }
        $tour = Tour::create([
            'name' => $request->get('name'),
            'slug' => $request->get('slug'),
            'description' => $request->get('description'),
            'photos' => json_encode($photos),
            'duration' => $request->get('duration'),
        ]);
        $arrayOfPlacesId = $request->get('places');
        $startPlaceId = $request->get('startPlace');
        $finishPlaceId = $request->get('finishPlace');
        foreach ($arrayOfPlacesId as $placeId){
            $place = Place::findOrFail($placeId);
            if ($placeId == $startPlaceId && $placeId == $finishPlaceId){
                $tour->places()->attach($place, [
                    'isStartPlace' => true,
                    'isFinishPlace' => true
                ]);
            } else if($placeId == $startPlaceId) {
                $tour->places()->attach($place, [
                    'isStartPlace' => true,
                    'isFinishPlace' => false
                ]);
            } else if($placeId == $finishPlaceId){
                $tour->places()->attach($place, [
                    'isStartPlace' => false,
                    'isFinishPlace' => true
                ]);
            } else {
                $tour->places()->attach($place, [
                    'isStartPlace' => false,
                    'isFinishPlace' => false
                ]);
            }
        }
        if(!$tour){
            return redirect()->back();
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
        $user = Auth::user();
        $places = Place::paginate(5);
        if ($user->can('update', $tour)) {
            return view('admin.tour.edit', [
                'tour' => $tour,
                'places' => $places
            ]);
        } else return redirect()->back();
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  Tour  $tour
     * @return \Illuminate\Http\RedirectResponse
     */
    public function update(Request $request, Tour $tour)
    {
        $photos = array();
        if ($request->has('photos')) {
            foreach ($request->file('photos') as $photo) {
                $photos[] = $photo->store('uploads', 'public');
            }
        }

        $tour->places()->detach();
        if (count($photos) > 0) {
            $tourPhotos = $tour->photos;
            $tourPhotos = json_decode($tourPhotos);
            Storage::disk('public')->delete($tourPhotos);
            $tour->fill([
                'name' => $request->get('name'),
                'slug' => $request->get('slug'),
                'description' => $request->get('description'),
                'duration' => $request->get('duration'),
                'photos' => json_encode($photos)
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
        foreach ($arrayOfPlacesId as $placeId){
            $place = Place::findOrFail($placeId);
            if ($placeId == $startPlaceId && $placeId == $finishPlaceId){
                $tour->places()->attach($place, [
                    'isStartPlace' => true,
                    'isFinishPlace' => true
                ]);
            } else if($placeId == $startPlaceId) {
                $tour->places()->attach($place, [
                    'isStartPlace' => true,
                    'isFinishPlace' => false
                ]);
            } else if($placeId == $finishPlaceId){
                $tour->places()->attach($place, [
                    'isStartPlace' => false,
                    'isFinishPlace' => true
                ]);
            } else {
                $tour->places()->attach($place, [
                    'isStartPlace' => false,
                    'isFinishPlace' => false
                ]);
            }
        }
        if(!$tour->save()){
            return redirect()->back()->withErrors('Помилка оновлення туру');
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
        $user = Auth::user();
        if ($user->can('delete', $tour)){
            $tour->detach();
            if(!$tour->delete()){
                return redirect()->back()->withErrors('Помилка видалення');
            }
            session()->flash('flash_message', 'Тур видалено');
            return redirect()->back();
        } else {
            return redirect()->back()->withErrors('Не достатньо прав');
        }

    }
}
