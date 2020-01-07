<?php

namespace App\Http\Controllers;

use App\Models\Client;
use App\Models\Locker;
use Illuminate\Http\Request;

use App\Models\ClientHasLocker;
use App\Http\Resources\ClientResource;
use App\Http\Resources\LockerResource;
use Illuminate\Database\Eloquent\ModelNotFoundException;

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
    public function store(Request $request)
    {
        $json = json_decode(($request->getContent()));
        $locker = Locker::create($request->only('guid'));
        return new LockerResource($locker);
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        $locker = Locker::findOrFail($id);
        return new LockerResource($locker);
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
        $locker = Locker::findOrFail($id);
        $locker->update($request->only('guid'));
        return new LockerResource($locker);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        $locker = Locker::findOrFail($id);
        $locker->delete();
        return response()->json([
            'message' => 'OK.',
        ]);
    }

    // All special calls.
    public function check($guid)
    {
        return $this->lockerAvailable($guid);
    }

    public function claim(Request $request)
    {
        $client = Client::where('email', '=', $request->only('email'))->first();

        // Client creation if doesn't exist.
        if ($client == null) {
            $client = Client::create($request->only('email'));
        }

        // Locker available?
        if ($this->lockerAvailable($request->only('guid')) == response("Exists and claimable.", 200))
        {
            $clientHasLocker = ClientHasLocker::create($request->only(
            'client_id',
            'locker_id',
            'claim_hash'
            ));

            return response(substr(md5(rand()), 0, 8));
        }
        else
        {
            // Locked claimed, send error code?
            return response("aii, dit lukt niet");
        }


        // Create a client if they don't exist
        // generate a hash,
        // create a clienthaslocker (if it isnt taken yet) with no key
        // if the locker doesnt get set within 10 minutes, the clienthaslocker gets removed
        // send an email to the clients email with the hash as unlock
    }

    public function set($guid, $key, $hash)
    {
        // Set client validation to true,
        // Check hash validity and then remove the hash
    }




    // Non-route functions.
    public function lockerAvailable($guid)
    {
        try {
            $locker = Locker::where('guid', '=', $guid)->firstOrFail();

            dd($locker->clientClaims->last());

            if (!ClientHasLocker::where('locker_id', '=', $locker->id)->exists())
            {
                return response("Exists and claimable.", 200);
                // Show register page after this.
            }
            else
            {
                return response("Already claimed.", 409);
                // Show login page after this.
            }
        } catch (ModelNotFoundException $e) {
            return response("Doesn't exist", 404);
            // Show unavailable page after this.
        }
    }
}
