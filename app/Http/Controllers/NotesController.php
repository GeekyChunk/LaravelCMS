<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Note;
use App\Models\Card;

class NotesController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        return Note::orderBy('created_at', 'desc')->get();
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $card = Card::find($request->input('card'))->id;

        $request->validate([
            'body' => 'required',
            'card' => 'required|integer'
        ]);

        $note = new Note($request->all());

        $note->user_id = $request->user()->id;

        $note->card_id = $card;

        $note->save();

        return $note;
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show(Note $note)
    {
        $note->load('card');
        $note->load('user');
        return $note;
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request,Note $note)
    {
        if ($request->user()->id == $note->user_id) {
            $note->update($request->all());
            return $note;
        } else {

            return response(['msg' => 'access denited'], 500);

        }
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy(Request $request, Note $note)
    {
        if ($request->user()->id == $note->user_id) {
            $note->delete();
            return ['msg' => 'Deleted Successfully'];
        } else {
            return response(['msg' => 'access denited'], 500);
        }
    }
}
