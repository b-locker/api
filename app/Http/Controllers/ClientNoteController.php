<?php

namespace App\Http\Controllers;

use App\Http\Requests\ClientNoteStoreRequest;
use App\Models\ClientNote;
use Illuminate\Http\Request;
use App\Http\Resources\ClientNoteResource;

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
        return ClientNoteResource::collection($clientNotes);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  ClientNoteStoreRequest  $request
     * @return \Illuminate\Http\Response
     */
    public function store(ClientNoteStoreRequest $request)
    {
        $clientNote = ClientNote::create($request->only(
            'client_id',
            'body',
            'created_by'
        ));

        return new ClientNoteResource($clientNote);
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
        return new ClientNoteResource($clientNote);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  ClientNoteStoreRequest  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(ClientNoteStoreRequest $request, $id)
    {
        $clientNote = ClientNote::findOrFail($id);
        $clientNote->update($request->only(
            'client_id',
            'body',
            'created_by'
        ));

        return new ClientNoteResource($clientNote);
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
