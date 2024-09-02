<?php

namespace App\Http\Controllers;
use App\Models\User;
use App\Http\Controllers\Controller;
use Hash;
use Illuminate\Http\Request;
use App\Http\Resources\UserResource;
use Illuminate\Support\Facades\Auth;
use Validator;


class UserController extends Controller
{
    public function index(Request $request)
    {
        return UserResource::collection(User::all());

        try {
            if ($request->has('limit')) {
                return UserResource::collection(User::paginate($request->limit));
            }
            if ($request->has('search')) {
                return UserResource::collection(User::search($request->search)->get());
            } else {
                return UserResource::collection(User::all());
            }
        } catch (\Exception $e) {
            return $e->getMessage();

        }


    }

    public function show($id)
    {
        try {
            return new UserResource(User::findOrFail($id));
        } catch (\Exception $e) {
            return $e->getMessage();

        }
    }


    public function register(Request $request)
    {
        // Validate the request data
        $validator = Validator::make($request->all(), [
            'name' => 'required|string|max:255',
            'email' => 'required|string|email|max:255|unique:users',
            'password' => 'required|string|min:8|confirmed',
        ]);
        //password_confirmation
        if ($validator->fails()) {
            return response()->json($validator->errors(), 422);
        }

        // Create the user
        $user = User::create([
            'name' => $request->name,
            'email' => $request->email,
            'password' => Hash::make($request->password), // Hash the password using bcrypt
        ]);

        // Return a success response
        return response()->json(['message' => 'User registered successfully'], 201);
    }

    public function login(Request $request)
    {
        // Validate the request data
        $validator = Validator::make($request->all(), [
            'email' => 'required|string|email|max:255',
            'password' => 'required|string|min:8',
        ]);

        if ($validator->fails()) {
            return response()->json($validator->errors(), 422);
        }

        // Attempt to log the user in
        if (!Auth::attempt($request->only('email', 'password'))) {
            return response()->json(['error' => 'Invalid credentials'], 401);
        }

        // Generate a token for the user
        $user = Auth::user();
        $token = $user->createToken('auth_token');

        // Return a success response with the token
        return response()->json(['access_token' => $token, 'token_type' => 'Bearer'], 200);
    }

    public function update(Request $request, $id)
    {
        $user = User::findOrFail($id);

        if ($request->has('firstName'))
            $user['firstName'] = $request->input('firstName');

        if ($request->has('lastName'))
            $user['lastName'] = $request->input('lastName');

        if ($request->has('email'))
            $user['email'] = $request->input('email');

        if ($request->has('avatar'))
            $user['avatar'] = $request->input('avatar');

        $user->save();

        $success['0']['code'] = '0001';
        $success['0']['status'] = '200';
        $success['0']['title'] = 'Updated successfully';
        $success['0']['detail'] = 'User Update successfully';
        return response()->json(['data', $success], '200');

    }


    public function delete($id)
    {
        $user = User::find($id);
        $user->delete();

        $success['0']['code'] = '0001';
        $success['0']['status'] = '200';
        $success['0']['title'] = 'Deleted successfully';
        $success['0']['detail'] = 'User Delete successfully';
        return response()->json(['data', $success], '200');
    }
}
