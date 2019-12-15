<?php

namespace App\Http\Controllers;

use App\Models\Manager;
use Illuminate\Http\Request;
use Tymon\JWTAuth\Facades\JWTAuth;

// Auth testing.
use Illuminate\Support\Facades\Hash;
use App\Http\Resources\ManagerResource;
use Illuminate\Support\Facades\Auth;
use Tymon\JWTAuth\Exceptions\JWTException;

class ManagerController extends Controller
{
    // Testing auth.
    public function register(Request $request)
    {
        $manager = Manager::create($request->only(
            'first_name',
            'last_name',
            'email',
            'password',
            'role_id'
        ));

        $token = JWTAuth::fromUser($manager);

        return response()->json(compact('manager','token'),201);
    }

    public function login(Request $request)
    {
        $credentials = $request->only('email', 'password');

        try {
            if (! $token = JWTAuth::attempt($credentials)) {
                dump($credentials);
                dump(JWTAuth::attempt($credentials));
                die;
                return response()->json(['error' => 'invalid_credentials'], 400);
            }
        } catch (JWTException $e) {
            return response()->json(['error' => 'could_not_create_token'], 500);
        }
        return response()->json(compact('token'));
    }

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $managers = Manager::all();
        return ManagerResource::collection($managers);
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
        $manager = Manager::create($request->only(
            'first_name',
            'last_name',
            'email',
            'password',
            'role_id'
        ));
        return new ManagerResource($manager);
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        $manager = Manager::findOrFail($id);
        return new ManagerResource($manager);
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
        $manager = Manager::findOrFail($id);
        $manager->update($request->only(
            'first_name',
            'last_name',
            'email',
            'password',
            'role_id'
        ));
        return new ManagerResource($manager);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        $manager = Manager::findOrFail($id);
        $manager->delete();
        return response()->json([
            'message' => 'OK.',
        ]);
    }
}
