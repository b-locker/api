<?php

namespace App\Http\Controllers;

use App\Models\ClientHasLocker;
use Illuminate\Http\Request;
use App\Http\Resources\ClientHasLockerResource;

class ClientHasLockerController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $clientHasLockers = ClientHasLocker::all();
        return ClientHasLockerResource::collection($clientHasLockers);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $clientHasLocker = ClientHasLocker::create($request->only(
            'client_id',
            'locker_id',
            'key_hash'
        ));

        return new ClientHasLockerResource($clientHasLocker);
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        $clientHasLocker = ClientHasLocker::findOrFail($id);
        return new ClientHasLockerResource($clientHasLocker);
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
        $clientHasLocker = ClientHasLocker::findOrFail($id);
        $clientHasLocker->update($request->only(
            'email'
        ));

        return new ClientHasLockerResource($clientHasLocker);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        $clientHasLocker = ClientHasLocker::findOrFail($id);
        $client->delete();

        return response()->json([
            'message' => 'OK.',
        ]);
    }
}
