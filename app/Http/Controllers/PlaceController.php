<?php

namespace App\Http\Controllers;

use App\Http\Requests\StorePlaceRequest;
use App\Place;
use Illuminate\Contracts\View\Factory;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
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
        $places = Place::with(['comments' => function ($query) {
            $query->where('enabled', true);
        },
            'comments.user', 'likes.user'])->paginate(2);
        return json_encode($places);
    }


    public function getCount()
    {
        return Place::count();
    }
    /**
     * Display a listing of the resource.
     *
     * @return Factory|\Illuminate\Http\JsonResponse|View
     */
    public function index()
    {
        $places = Place::paginate(5);
        return view('admin.place.index', ['places' => $places]);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return Factory|\Illuminate\Http\RedirectResponse|View
     */
    public function create()
    {
        $user = Auth::user();
        if ($user->can('create', Place::class)){
            return view('admin.place.create');
        } else return redirect()->back();
    }

    /**
     * Display the specified resource.
     *
     * @param  Place  $place
     * @return \Illuminate\Http\JsonResponse
     */
    public function show(Place $place)
    {
        return response()->json([
            'name' => $place->name,
            'slug' => $place->slug,
            'description' => $place->description,
            'rating' => $place->rating,
            'photos' => $place->photos,
            'lat' => $place->lat,
            'lng' => $place->lng
        ]);
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
        foreach ($request->file('photos') as $photo)
        {
            $photos[] = $photo->store('uploads', 'public');
        }
        $place = Place::create([
            'name' => $request->get('name'),
            'slug' => $request->get('slug'),
            'description' => $request->get('description'),
            'rating' => $request->get('rating'),
            'photos' => json_encode($photos),
            'lat' => $request->get('lat'),
            'lng' => $request->get('lng'),
        ]);
        if(!$place){
            return redirect()->back()->withErrors('Не всі поля заповнені');
        }
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
        $user = Auth::user();
        if ($user->can('update', $place)) {
            return view('admin.place.edit', [
                'place' => $place]);
        } else return redirect()->back();
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  Place  $place
     * @return \Illuminate\Http\RedirectResponse
     */
    public function update(Request $request, Place $place)
    {
        $photos = array();
        foreach ($request->file('photos') as $photo)
        {
            $photos[] = $photo->store('uploads', 'public');
        }
        $place -> fill([
            'name' => $request->get('name'),
            'slug' => $request->get('slug'),
            'description' => $request->get('description'),
            'rating' => $request->get('rating'),
            'duration' => $request->get('duration'),
            'photos' => json_encode($photos)
        ]);
        if(!$place->save()){
            return redirect()->back()->withErrors('Помилка оновлення локації');
        }
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
        $user = Auth::user();
        if ($user->can('delete', $place)){
            $place->detach();
            if(!$place->delete()){
                return redirect()->back()->withErrors('Помилка видалення');
            }
            session()->flash('flash_message', 'Локація видалена');
            return redirect()->back();
        } else {
            return redirect()->back()->withErrors('Не достатньо прав');
        }
    }
}
