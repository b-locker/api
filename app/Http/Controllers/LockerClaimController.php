<?php

namespace App\Http\Controllers;

use App\Models\Client;
use App\Models\Locker;
use App\Models\LockerClaim;
use Illuminate\Http\Request;
use App\Http\Resources\LockerClaimResource;
use App\Exceptions\NotYetImplementedException;

class LockerClaimController extends Controller
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
        $lockerClaims = $client->lockerClaims;

        return LockerClaimResource::collection($lockerClaims);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  int  $clientId
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(int $clientId, Request $request)
    {
        $lockerGuid = $request->get('guid');
        $locker = Locker::where('guid', $lockerGuid)->findOrFail();

        $email = $request->get('email');
        $client = Client::where('email', $email)->firstOrCreate();

        if (!$this->isLockerPresent()) {
            return response()->json([
                'message' => 'Locker is not present.',
            ], 404);
        }

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

        return response()->json([
            'message' => 'OK.',
        ]);

        return new LockerClaimResource($lockerClaim);
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $clientId
     * @param  int  $claimId
     * @return \Illuminate\Http\Response
     */
    public function show(int $clientId, int $claimId)
    {
        $lockerClaim = LockerClaim::findOrFail($claimId);
        return new LockerClaimResource($lockerClaim);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  int  $clientId
     * @param  int  $claimId
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function update(int $clientId, int $claimId, Request $request)
    {
        throw new NotYetImplementedException();

        // $lockerClaim = LockerClaim::findOrFail($claimId);
        // $lockerClaim->update([
        //     'invalid_at' => $request->get('invalid_at'),
        // ]);

        // return new LockerClaimResource($lockerClaim);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        throw new NotYetImplementedException();

        // $lockerClaim = LockerClaim::findOrFail($id);
        // $lockerClaim->delete();

        // return response()->json([
        //     'message' => 'OK.',
        // ]);
    }
}
