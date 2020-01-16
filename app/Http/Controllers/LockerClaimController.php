<?php

namespace App\Http\Controllers;

use App\LockerKey;
use Carbon\Carbon;
use App\Models\Client;
use App\Models\Locker;
use App\Models\LockerClaim;
use Illuminate\Support\Str;
use Illuminate\Http\Request;
use App\Mail\LockerSetupMail;
use App\Mail\LockerEndOwnershipMail;
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

        if (!$locker->isCurrentlyClaimable()) {
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
        //
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

    /**
     * Setup is used to confirm a locker claim.
     *
     * @param string $lockerGuid
     * @param int $claimId
     * @param LockerClaimUpdateRequest $request
     * @return void
     */
    public function setup(string $lockerGuid, int $claimId, LockerClaimUpdateRequest $request)
    {
        $lockerClaim = LockerClaim::findOrFail($claimId);
        $lockerClaim->setup_token = null;
        $lockerClaim->key_hash = bcrypt($request->get('key'));
        $lockerClaim->save();

        return new LockerClaimResource($lockerClaim);
    }

    public function end(string $lockerGuid, int $claimId, LockerClaimUpdateRequest $request)
    {
        $lockerClaim = LockerClaim::findOrFail($claimId);
        $key = $request->get('key');

        $lockerKey = new LockerKey($key);

        try {
            $lockerKey->attempt($lockerClaim);
        } catch (LockerKeyException $e) {
            return response()->json([
                'message' => $e->getMessage(),
            ], 400);
        }

        $lockerClaim->end_at = Carbon::now();
        $lockerClaim->save();

        $client = $lockerClaim->client;
        $mail = new LockerEndOwnershipMail($lockerClaim);
        Mail::to($client->email)->send($mail);

        return new LockerClaimResource($lockerClaim);
    }

    // Using regular Request here for now because it requires 2 keys, the current and new ones.
    public function updateKey(string $lockerGuid, int $claimId, Request $request)
    {
        $lockerClaim = LockerClaim::findOrFail($claimId);
        $key = $request->get('key');

        $lockerKey = new LockerKey($key);

        try {
            $lockerKey->attempt($lockerClaim);
        } catch (LockerKeyException $e) {
            return response()->json([
                'message' => $e->getMessage(),
            ], 400);
        }

        $lockerClaim->key_hash = bcrypt($request->get('newkey'));
        $lockerClaim->save();

        return new LockerClaimResource($lockerClaim);
    }

    public function setNewKey(string $lockerGuid, int $claimId, LockerClaimUpdateRequest $request)
    {
        $lockerClaim = LockerClaim::findOrFail($claimId);
        $lockerClaim->setup_token = null;
        $lockerClaim->key_hash = bcrypt($request->get('key'));
        $lockerClaim->save();

        return new LockerClaimResource($lockerClaim);

        // Overwrite old key.


        return response()->json([
            'message' => 'OK.',
        ]);
    }
}
