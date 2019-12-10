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
        $clientnotes = ClientNote::all();
        return ManagerResource::collection($clientnotes);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $json = json_decode(($request->getContent()));
        $clientnote = ClientNote::create($request->only(
            'client_id',
            'note',
            'created_by'
        ));
        return new ManagerResource($clientnote);
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        $clientnote = ClientNote::findOrFail($id);
        return new ManagerResource($clientnote);
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
        $clientnote = ClientNote::findOrFail($id);
        $clientnote->update($request->only(
            'client_id',
            'note',
            'created_by'
        ));
        return new ManagerResource($clientnote);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        $clientnote = ClientNote::findOrFail($id);
        $clientnote->delete();
        return response()->json([
            'message' => 'OK.',
        ]);
    }
}
