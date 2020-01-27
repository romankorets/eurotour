<?php

namespace App\Http\Controllers;

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
     * @return \Illuminate\Http\JsonResponse
     */
    public function index()
    {
        $places = Place::all();
        return response()->json([
            'places' => $places
        ]);
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
     * @param  int  $id
     * @return \Illuminate\Http\JsonResponse
     */
    public function show($id)
    {
        $place = Place::findOrFail($id);
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

    public function store(Request $request)
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
            return redirect()->back();
        }
        $request->session()->flash('flash_message', 'Нова локація додана');
        return redirect()->route('admin');
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return Factory|\Illuminate\Http\RedirectResponse|View
     */
    public function edit($id)
    {
        $user = Auth::user();
        $place = Place::findOrFail($id);
        if ($user->can('update', $place)) {
            return view('admin.place.edit', [
                'place' => $place]);
        } else return redirect()->back();
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\RedirectResponse
     */
    public function update(Request $request, $id)
    {
        $post = Place::findOrFail($id);
        $post->fill($request->all());
        if(!$post->save()){
            return redirect()->back()->withErrors('Update error');
        }
        $request->session()->flash('flash_message', 'Локація оновлена');
        return redirect()->route('/admin');
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\RedirectResponse|\Illuminate\Routing\Redirector
     */
    public function destroy($id)
    {
        $user = Auth::user();
        $place = Place::findOrFail($id);
        if ($user->can('delete', $place)){
            if(!$place->delete()){
                return redirect()->back()-withErrors('Помилка видалення');
            }
            session()->flash('flash_message', 'Локація видалена');
            return redirect()->back();
        } else {
            return redirect()->back()->withErrors('Не достатньо прав');
        }

    }
}
