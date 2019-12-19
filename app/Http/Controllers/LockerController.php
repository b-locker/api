<?php

namespace App\Http\Controllers;

use App\Models\Locker;
use App\Models\ClientHasLocker;
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
        $user = Locker::where('guid', '=', $guid)->first();
        if ($user !== null)
        {
            if(!ClientHasLocker::where('locker_id', '=', $user->id)->exists())
            {
                return response("Exists and claimable.", 200);
            }
            else
            {
                return response("Already claimed.", 409);
            }
        }
        else
        {
            return response("Doesn't exist", 404);
        }
        //return new LockerResource($locker);

        // Important call
        // Implement;
        // Check if locker exists, if not, return 404
        // Check if locker is claimed by searching the client_has_lockers table for the ID,
        // if it doesnt exist, return claimable, if it does exist, check if current date doesnt fall
        // in rent period. Only then will it return a positive flag

    }

    public function claim($email)
    {

    }

    public function setKey($key)
    {

    }
}
