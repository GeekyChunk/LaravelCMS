<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Http\Response;
use App\Models\Card;

class CardsController extends Controller
{
    // TODO: AuthorOrReadonly


    /**
     * Display a listing of the resource.
     *
     */
    public function index()
    {
        return Card::orderBy('created_at', 'desc')->get();
    }

    /**
     * Store a newly created resource in storage.
     *
     */
    public function store(Request $request)
    {
        $request->validate([

            'title' => 'required',
            'slug' => 'required|unique:cards',

        ]);

        $card = new Card($request->all());

        $card->user_id = $request->user()->id;

        $card->save();

        return $card;
    }

    /**
     * Display the specified resource.
     *
     * 
     */
    public function show(Card $card)
    {
        $card->load('user');

        $card->notes = $card->notes()->orderBy('created_at', 'desc')->get();

        return $card;
    }

    /**
     * Update the specified resource in storage.
     *
     * 
     */
    public function update(Request $request,Card $card)
    {
        if ($request->user()->id == $card->user_id) {

            $card->update($request->all());

            return $card;

        } else {

            return response(['msg' => 'access denited'], 500);

        }
    }

    /**
     * Remove the specified resource from storage.
     *
     *
     */
    public function destroy(Request $request, Card $card)
    {
        if ($request->user()->id == $card->user_id) {

            $card->delete();

            return ['msg' => 'success'];

        } else {

            return response(['msg' => 'Access Denited'], 500);

        }
    }
}
