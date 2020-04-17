<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use App\User;
use App\Http\Resources\UserResource;
use App\Http\Resources\UserCollection;
use Illuminate\Http\Request;

class UserController extends Controller
{
    public function index()
    {
        return (UserCollection::collection(User::paginate(10)))->additional([
            'msg' => 'users list'
        ]);
    }

    public function loginPage()
    {
        return response()->json([
            'login' => [
                'href' => route('login'),
                'method' => 'POST',
                'params' => 'email, password'
            ],
            'register' => [
                'href' => route('register'),
                'method' => 'POST',
                'params' => 'username, email, password, password_confirmation'
            ]
        ], 200);
    }

    public function login(Request $request)
    {
        $this->validate($request, [
            'email' => 'required|email',
            'password' => 'required|string'
        ]);

        if(auth()->attempt(['email' => $request->email, 'password' => $request->password])) {
            $token = auth()->user()->createToken('TutsForWeb')->accessToken;
            return response()->json(['token' => $token], 200);
        } else {
            return response()->json(['error' => 'unauthorized'], 401);
        }
    }

    public function register(Request $request)
    {
        $this->validate($request, [
            'username' => 'required|min:3|max:50',
            'email' => 'required|email|unique:users',
            'password' => 'required|string|min:5|confirmed'
        ]);

        $user = User::create([
            'name' => $request->username,
            'email' => $request->email,
            'password' => bcrypt($request->password),
            'role' => 'custome'
        ]);

        $token = $user->createToken('TutsForWeb')->accessToken;
        return response()->json(['token' => $token], 201);
    }

    public function show($id)
    {
        return (new UserResource(User::find($id)))->additional([
            'update user' => [
                'href' => route('users.update', $id),
                'method' => 'PUT',
                'params' => 'role'
            ],
            'delete user' => [
                'href' => route('users.destroy', $id),
                'method' => 'DELETE'
            ]
        ]);
    }

    public function update(Request $request, $id)
    {
        if (auth()->user()->role == 'admin') {
            $this->validate($request, [
                'role' => 'required|in:admin,employee,custome'
            ]);

            $user = User::find($id);
            $user->role = $request->role;
            $user->save();
            return redirect()->route('users.show', $id);
        } else {
            return response()->json([
                'msg' => 'You have not permission to update user'
            ], 401);
        }
    }

    public function destroy($id)
    {
        if (auth()->user()->role == 'admin') {

            $user = User::find($id);
            $user->delete();
            return redirect()->route('users.index');
        } else {
            return response()->json([
                'msg' => 'You have not permission to delete user'
            ], 401);
        }
    }
}
