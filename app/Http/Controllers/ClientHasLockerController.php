<?php

namespace App\Http\Controllers;

use Carbon\Carbon;
use App\Models\Client;
use App\Models\Locker;
use Illuminate\Http\Request;
use App\Models\ClientHasLocker;
use App\Http\Requests\LockerClaimStoreRequest;
use App\Http\Resources\ClientHasLockerResource;
use Illuminate\Database\Eloquent\ModelNotFoundException;

class ClientHasLockerController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @param  int  $clientId
     * @return \Illuminate\Http\Response
     */
    public function index(int $clientId)
    {
        $client = Client::findOrFail($clientId);
        $lockerClaims = $client->lockerClaims();

        return ClientHasLockerResource::collection($lockerClaims);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  int  $clientId
     * @param  LockerClaimStoreRequest  $request
     * @return \Illuminate\Http\Response
     */
    public function store(int $clientId, LockerClaimStoreRequest $request)
    {
        $client = Client::findOrFail($clientId);

        $lockerGuid = $request->get('guid');

        // try {
            $locker = Locker::where('guid', $lockerGuid)->firstOrFail();
        // } catch (ModelNotFoundException $e) {
        //     return response()->json([
        //         'message' => 'Locker is not present.',
        //     ], 404);
        // }

        if ($this->isLockerClaimed($locker)) {
            return response()->json([
                'message' => 'Locker is already claimed.',
            ], 400);
        }

        $client->lockerClaims()->create($request->only([
            'client_id',
            'locker_id',
            'claim_hash',
        ]));

        $client->lockerClaims()->create([
            'guid' => $request->get('guid'),
            'invalid_at' => Carbon::now()->addDays(14),
        ]);

        return response()->json([
            'message' => 'OK.',
        ]);

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
