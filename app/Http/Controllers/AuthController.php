<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Auth;
use Symfony\Component\HttpFoundation\Response;
use App\Models\User;
use Illuminate\Support\Facades\Cookie;
use DB;
use Exception;
use Validator;

class AuthController extends Controller
{
    //
    public function __construct(){
        // $this->middleware('throttle:5,5')->only('login');
    }

    public function register(Request $request){
        try{
            DB::beginTransaction();
            
            $validator = Validator::make($request->all(), [
                'email' => ['unique:users','email','required'],
                'password' => 'required',
            ]);
            if($validator->fails()){
                return response()->json($validator->errors()->all(),400);
            }

            $user = User::create([
                'email'=> $request->email,
                'password'=> Hash::make($request->password)
            ]);

            if($user->save()){
                DB::commit();
                return response()->json(['message'=>'User successfully registered'],201);
            }
        }catch(Exception $e){
            DB::rollback();

            return response()->json(['message'=>'Error, '.$e]);
        }
    }

    public function login(Request $request){
        try{
            if( !Auth::attempt($request->only('email','password')) ){
                return response([
                    'message'=>'Invalid credentials'
                ], 401);
            }
    
            $user = Auth::user();
    
            $token = $user->createToken('token')->plainTextToken;
            $cookie = cookie('jwt',$token,60*24); //1day
            
            return response()->json(["access_token" => $token],201)->withCookie($cookie);
        }catch(Exception $e){
            return 'sheeesh';
        }
    }
    
    public function logout(){
        $cookie = Cookie::forget('jwt');

        return response([
            'message'=>'success'
        ])->withCookie($cookie);
    }
}
