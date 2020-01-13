<?php

namespace App\Http\Controllers;

use App\Models\ClientNote;
use Illuminate\Http\Request;
use App\Http\Resources\ManagerResource;

class ClientNoteController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $clientNotes = ClientNote::all();
        return ManagerResource::collection($clientNotes);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $clientNote = ClientNote::create($request->only(
            'client_idd',
            'note',
            'created_by'
        ));

        return new ManagerResource($clientNote);
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        $clientNote = ClientNote::findOrFail($id);
        return new ManagerResource($clientNote);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        $clientNote = ClientNote::findOrFail($id);
        $clientNote->update($request->only(
            'client_id',
            'note',
            'created_by'
        ));

        return new ManagerResource($clientNote);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        $clientNote = ClientNote::findOrFail($id);
        $clientNote->delete();

        return response()->json([
            'message' => 'OK.',
        ]);
    }
}
