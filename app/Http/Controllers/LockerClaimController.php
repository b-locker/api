<?php

namespace App\Http\Controllers;

use Carbon\Carbon;
use App\Models\Client;
use App\Models\Locker;
use App\Models\LockerClaim;
use Illuminate\Support\Str;
use Illuminate\Http\Request;
use App\Http\Resources\LockerClaimResource;
use App\Exceptions\NotYetImplementedException;
use App\Http\Requests\LockerClaimStoreRequest;

class LockerClaimController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @param  string  $lockerGuid
     * @return \Illuminate\Http\Response
     */
    public function index(string $lockerGuid)
    {
        $locker = Locker::where('guid', $lockerGuid)->firstOrFail();
        $lockerClaims = $locker->lockerClaims;

        return LockerClaimResource::collection($lockerClaims);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  string  $lockerGuid
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(string $lockerGuid, LockerClaimStoreRequest $request)
    {
        $locker = Locker::where('guid', $lockerGuid)->firstOrFail();

        $email = $request->get('email');
        $client = Client::where('email', $email)->firstOrCreate();

        $startMoment = $request->get('taken_at', Carbon::now());
        $endMoment = $request->get('invalid_at', Carbon::now()->addDays(7));

        $startMomentParsed = Carbon::parse($startMoment);
        $endMomentParsed = Carbon::parse($endMoment);

        if ($this->isLockerClaimed($locker, $startMomentParsed, $endMomentParsed)) {
            return response()->json([
                'message' =>
                    'Locker is already claimed somewhere between ' .
                    $startMomentParsed . 'and ' . $endMomentParsed . '.',
            ], 400);
        }

        $lockerClaim = $client->lockerClaims()->create([
            'locker_id' => $locker->id,
            'setup_token' => Str::random(),
            'taken_at' => $startMomentParsed,
            'invalid_at' => $endMomentParsed,
        ]);

        // TODO: Send email

        return new LockerClaimResource($lockerClaim);
    }

    private function isLockerClaimed(Locker $locker, Carbon $startMoment, Carbon $endMoment)
    {
        // TODO: Write logic.

        return false;
    }

    /**
     * Display the specified resource.
     *
     * @param  string  $lockerGuid
     * @param  int  $claimId
     * @return \Illuminate\Http\Response
     */
    public function show(string $lockerGuid, int $claimId)
    {
        $lockerClaim = LockerClaim::findOrFail($claimId);
        return new LockerClaimResource($lockerClaim);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  string  $lockerGuid
     * @param  int  $claimId
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function update(string $lockerGuid, int $claimId, Request $request)
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
