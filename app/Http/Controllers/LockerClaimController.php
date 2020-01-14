<?php

namespace App\Http\Controllers;

use Carbon\Carbon;
use App\Models\Client;
use App\Models\Locker;
use App\Models\LockerClaim;
use Illuminate\Support\Str;
use Illuminate\Http\Request;
use App\Mail\LockerSetupMail;
use Illuminate\Support\Facades\Mail;
use App\Http\Resources\LockerClaimResource;
use App\Exceptions\NotYetImplementedException;
use App\Http\Requests\LockerClaimStoreRequest;
use App\Http\Requests\LockerClaimUpdateRequest;

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
        $lockerClaims = $locker->claims;

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
        $client = Client::where('email', $email)->firstOrCreate([
            'email' => $email,
        ]);

        $startMoment = $request->get('start_at', Carbon::now());
        $endMoment = $request->get('end_at', Carbon::now()->addDays(7));

        $startMomentParsed = Carbon::parse($startMoment);
        $endMomentParsed = Carbon::parse($endMoment);

        if (!$locker->isCurrentlyAvailable()) {
            return response()->json([
                'message' =>
                    'The locker is not available, because it is already ' .
                    'claimed somewhere between ' . $startMomentParsed .
                    ' and ' . $endMomentParsed . '.',
            ], 400);
        }

        $lockerClaim = $client->lockerClaims()->create([
            'locker_id' => $locker->id,
            'setup_token' => Str::random(),
            'start_at' => $startMomentParsed,
            'end_at' => $endMomentParsed,
        ]);

        $mail = new LockerSetupMail($lockerClaim);
        Mail::to($client->email)->send($mail);

        return new LockerClaimResource($lockerClaim);
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
     * @param  LockerClaimUpdateRequest  $request
     * @return \Illuminate\Http\Response
     */
    public function update(string $lockerGuid, int $claimId, LockerClaimUpdateRequest $request)
    {
        $lockerClaim = LockerClaim::findOrFail($claimId);
        $lockerClaim->setup_token = null;
        $lockerClaim->key_hash = bcrypt($request->get('key'));
        $lockerClaim->save();

        return new LockerClaimResource($lockerClaim);
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

    public function unlock(LockerUnlockRequest $request)
    {

    }
}
