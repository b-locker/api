<?php

namespace App\Http\Controllers;

use App\Models\Locker;
use App\Models\ClientHasLocker;
use App\Models\Client;

use Illuminate\Http\Request;
use App\Http\Resources\LockerResource;

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
        $locker = Locker::where('guid', '=', $guid)->first();
        if ($locker !== null){
            if(!ClientHasLocker::where('locker_id', '=', $locker->id)->exists())
            {
                return response("Exists and claimable.", 200);
                // Show register page after this.
            }
            else
            {
                return response("Already claimed.", 409);
                // Show login page after this.
            }
        }
        else
        {
            return response("Doesn't exist", 404);
            // Show unavailable page after this.
        }

    }

    public function claim(Request $request)
    {
        $client = Client::create($request->only('email'));
        return new ClientResource($client);

        // Create a client, generate a hash, create a clienthaslocker with no key
    }

    public function set($guid, $key, $hash)
    {
        // Set client validation to true,
        // Check hash validity and then remove the hash
    }
}
