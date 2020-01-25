<?php

namespace App\Http\Controllers;

use App\Tour;
use Illuminate\Http\Request;
use Illuminate\View\View;

class TourController extends Controller
{
    /**
     * Show the form for creating a new resource.
     *
     * @return View
     */
    public function create()
    {
        return view('admin.tour.create');
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\JsonResponse
     */
    public function show($id)
    {
        $tour = Tour::findOrFail($id);

        return response()->json([
            'name' => $tour->name,
            'slug' => $tour->slug,
            'description' => $tour->description,
            'photos' => $tour->photos,
            'duration' => $tour->duration,
            'startPlace' => $tour->places()->wherePivot('isStartPlace', true)->first(),
            'finishPlace' => $tour->places()->wherePivot('isFinishPlace', true)->first(),
            'places' => $tour->places()->get()
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
        $tour = Tour::create([
            'name' => $request->get('name'),
            'slug' => $request->get('slug'),
            'description' => $request->get('description'),
            'photos' => $request->get('photos'),
            'duration' => $request->get('duration'),
        ]);
        $places = $request->get('places');
        foreach ($places as $place){
            $tour->places()->attach($place->id, [
                'isStartPlace' => $place->isStartPlace,
                'isFinishPlace' => $place->isFinishPlace
            ]);
        }
        if(!$tour){
            return redirect()->back();
        }
        $request->session()->flash('flash_message', 'Новий тур додано');
        return redirect()->route('/admin');
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return View
     */
    public function edit($id)
    {
        $tour = Tour::findOrFail($id);
        return view('admin.tour.edit', [
            'tour' => $tour]);
    }
}
