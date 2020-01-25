<?php

namespace App\Http\Controllers;

use App\Place;
use Illuminate\Contracts\View\Factory;
use Illuminate\Http\Request;
use Illuminate\View\View;

class PlaceController extends Controller
{
    /**
     * Show the form for creating a new resource.
     *
     * @return View
     */
    public function create()
    {
        return view('admin.place.create');
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\JsonResponse
     */
    public function show($id)
    {
        $post = Place::findOrFail($id);
        return response()->json([
            'name' => $post->name,
            'slug' => $post->slug,
            'description' => $post->description,
            'rating' => $post->rating,
            'photos' => $post->photos,
            'lat' => $post->lat,
            'lng' => $post->lng
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
        $place = Place::create([
            'name' => $request->get('name'),
            'slug' => $request->get('slug'),
            'description' => $request->get('description'),
            'rating' => $request->get('rating'),
            'photos' => $request->get('photos'),
            'lat' => $request->get('lat'),
            'lng' => $request->get('lng'),
        ]);
        if(!$place){
            return redirect()->back();
        }
        $request->session()->flash('flash_message', 'Нова локація додана');
        return redirect()->route('/admin');
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return Factory|View
     */
    public function edit($id)
    {
        $place = Place::findOrFail($id);
        return view('admin.place.edit', [
            'place' => $place]);
    }
}
