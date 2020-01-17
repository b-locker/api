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
use App\Exceptions\LockerKeyException;
use App\Http\Requests\LockerEndRequest;
use App\Http\Resources\LockerClaimResource;
use App\Http\Requests\LockerUpdateKeyRequest;
use App\Exceptions\NotYetImplementedException;
use App\Http\Requests\LockerClaimStoreRequest;
use App\Http\Requests\LockerClaimUpdateRequest;
use App\Http\Requests\LockerLiftLockdownRequest;

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

        if (!$locker->isCurrentlyClaimable()) {
            $activeClaim = $locker->activeClaim();

            return response()->json([
                'message' =>
                    'The locker is not available, because it is already' .
                    ' claimed between ' . $activeClaim->start_at .
                    ' and ' . $activeClaim->end_at . '.',
            ], 400);
        }

        $lockerClaim = $client->lockerClaims()->create([
            'locker_id' => $locker->id,
            'setup_token' => Str::random(),
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
        $activeClaim = $lockerClaim->locker->activeClaim();

        if (
            $this->isLockerActive($lockerClaim->locker) &&
            $this->isOtherClaim($lockerClaim->id, $activeClaim->id)
        ) {
            return response()->json([
                'message' => 'The locker is already claimed.',
            ], 400);
        }

        $lockerClaim->setup_token = null;
        $lockerClaim->key_hash = bcrypt($request->get('key'));

        $lockerClaim->start_at = Carbon::now();
        $lockerClaim->end_at = Carbon::now()->addDays(7);

        $lockerClaim->save();

        return new LockerClaimResource($lockerClaim);
    }

    private function isOtherClaim(int $claim1, int $claim2)
    {
        return ($claim1 !== $claim2);
    }

    private function isLockerActive(Locker $locker)
    {
        return (!$locker->isCurrentlyClaimable());
    }

    public function end(string $lockerGuid, int $claimId, LockerEndRequest $request)
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

    public function updateKey(string $lockerGuid, int $claimId, LockerUpdateKeyRequest $request)
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

        $lockerClaim->key_hash = bcrypt($request->get('new_key'));
        $lockerClaim->save();

        return new LockerClaimResource($lockerClaim);
    }

    public function liftLockdown(string $lockerGuid, int $claimId, LockerLiftLockdownRequest $request)
    {
        $lockerClaim = LockerClaim::findOrFail($claimId);
        $lockerClaim->setup_token = null;
        $lockerClaim->failed_attempts = 0;
        $lockerClaim->save();

        return new LockerClaimResource($lockerClaim);
    }
}
