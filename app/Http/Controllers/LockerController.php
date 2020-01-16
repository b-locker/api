<?php

namespace App\Http\Controllers;

use App\Models\Client;
use App\Models\Locker;
use Illuminate\Support\Str;
use Illuminate\Http\Request;
use App\Mail\LockerForgotKeyMail;
use Illuminate\Support\Facades\Mail;
use App\Http\Resources\LockerResource;
use Illuminate\Support\Facades\Artisan;
use App\Http\Requests\LockerUnlockRequest;
use App\Exceptions\NotYetImplementedException;

class LockerController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $lockers = Locker::all();
        return LockerResource::collection($lockers);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store()
    {
        $locker = Locker::create(['guid' => Str::random(8)]);
        return new LockerResource($locker);
    }

    /**
     * Display the specified resource.
     *
     * @param  string  $lockerGuid
     * @return \Illuminate\Http\Response
     */
    public function show(string $lockerGuid)
    {
        $locker = Locker::where('guid', $lockerGuid)->firstOrFail();
        return new LockerResource($locker);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  string  $lockerGuid
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function update(string $lockerGuid, Request $request)
    {
        $locker = Locker::where('guid', $lockerGuid)->firstOrFail();
        $locker->update($request->only('guid'));

        return new LockerResource($locker);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  string  $lockerGuid
     * @return \Illuminate\Http\Response
     */
    public function destroy(string $lockerGuid)
    {
        $locker = Locker::where('guid', $lockerGuid)->firstOrFail();
        $locker->delete();

        return response()->json([
            'message' => 'OK.',
        ]);
    }

    public function unlock(string $lockerGuid, LockerUnlockRequest $request)
    {
        $locker = Locker::where('guid', $lockerGuid)->firstOrFail();

        if (!$locker->isUnlockable()) {
            return response()->json([
                'message' => 'The locker is not unlockable. It could be locked down due to too many failed attempts.',
            ], 400);
        }

        $lockerClaim = $locker->activeClaim();

        $storedKeyHash = $lockerClaim->key_hash;
        $key = $request->get('key');

        if (!password_verify($key, $storedKeyHash)) {
            $lockerClaim->failed_attempts++;
            $lockerClaim->save();

            $message = 'The provided key does not work.';

            $attemptsLeft = $lockerClaim->attemptsLeft();

            if ($attemptsLeft > 0) {
                $message .= ' You have ' . $attemptsLeft . ' attempt(s) left.';
            } else {
                $message .= ' You have no more attempts left.';
            }

            return response()->json([
                'message' => $message,
            ], 400);
        }

        $lockerClaim->failed_attempts = 0;
        $lockerClaim->save();

        $exitCode = Artisan::call('locker:unlock', [
            'lockerGuid' => $locker->guid,
        ]);

        if ($exitCode !== 0) {
            return response()->json([
                'message' => 'Oops. Something wrong happened at our side.',
            ], 500);
        }

        return response()->json([
            'message' => 'OK.',
        ]);
    }

    public function forgotKey(string $lockerGuid)
    {
        $locker = Locker::where('guid', $lockerGuid)->firstOrFail();
        $activeClaim = $locker->activeClaim();

        $activeClaim->setup_token = Str::random();
        $activeClaim->save();

        $client = $activeClaim->client;
        $mail = new LockerForgotKeyMail($activeClaim);
        Mail::to($client->email)->send($mail);

        return response()->json([
            'message' => 'OK.',
        ]);
    }
}
