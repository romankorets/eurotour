<?php

namespace App\Http\Controllers;

use App\Tour;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\View\View;

class TourController extends Controller
{

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function index()
    {
        $tours = Tour::all();
        return response()->json([
            'tours' => $tours
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
        if ($user->can('create', Tour::class)) {
            return view('admin.tour.create');
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
     * @return \Illuminate\Http\RedirectResponse|View
     */
    public function edit($id)
    {
        $user = Auth::user();
        $tour = Tour::findOrFail($id);
        if ($user->can('update', $tour)) {
            return view('admin.tour.edit', [
                'tour' => $tour]);
        } else return redirect()->back();
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
        $tour = Place::findOrFail($id);
        if ($user->can('delete', $tour)){
            if(!$tour->delete()){
                return redirect()->back()-withErrors('Помилка видалення');
            }
            session()->flash('flash_message', 'Тур видалено');
            return redirect()->back();
        } else {
            return redirect()->back()->withErrors('Не достатньо прав');
        }

    }
}
