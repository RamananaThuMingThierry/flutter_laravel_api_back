<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;

class AuthController extends Controller
{  
    // Register user
    public function register(Request $request){  
        $validator = Validator::make($request->all(), [
            'name' => 'required|max:191',
            'email' => 'required|email|max:191|unique:users',
            'password' => 'required|min:6|confirmed',
        ]);

        if($validator->fails()){
            return response()->json([
                'Validation_errors' => $validator->messages(),
            ]);
        }else{
            $user = User::create([
                'name' => $request->name,
                'email' => $request->email,
                'password' => Hash::make($request->password)
            ]);

            $token = $user->createToken($user->email.'_Token')->plainTextToken;

            return response()->json([
                'name' => $user->name,
                'token' => $token,
                'message' => 'Inscription avec succès!',
            ], 200);
        }
    }

    // Login user
    public function login(Request $request){
        $validator = Validator::make($request->all(), [
            'email' => 'required|email',
            'password' => 'required|min:6',
        ]);

        if($validator->fails()){
            return response()->json([
                'Validation_errors' => $validator->messages(),
            ]);
        }else{
       
            $user = User::where('email', $request->email)->first();

            if(!$user || !Hash::check($request->password, $user->password)){
                return response()->json([
                    'message' => 'Invalids Credentials',
                ], 403);
            }else{
                $token = $user->createToken($user->email.'_Token')->plainTextToken;
                return response()->json([
                    'name' => $user->name,
                    'token' => $token,
                    'message' => 'Connexion avec succès!'
                ], 200);
            }
        }
    }

    // Déconnection
    public function logout(){
        auth()->user()->tokens()->delete();
        return response()->json([
            'status' => 200,
            'message' => "Déconnexion effectuée!",
        ]);
    }

    // Update User
    public function update(Request $request){
        $attrs = $request->validate([
            'name' => 'required|string'
        ]);

        $image = $this->saveImage($request->image, 'profiles');

        auth()->user()->update([
            'name' => $attrs['name'],
            'image' => $image
        ]);

        return response()->json([
            'message' => 'User updated',
            'user' => auth()->user(),
        ], 200);
    }

    public function getUser()
    {
        return response()->json([
            'userId' => auth()->user()->id
        ]);
    }

    // Get user details
    public function user(){
        return response()->json([
          'user' => auth()->user(),
          'status' => 200 
        ]);
    }
}
