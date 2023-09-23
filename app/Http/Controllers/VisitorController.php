<?php

namespace App\Http\Controllers;

use App\Http\Requests\LoginVisitorRequest;
use App\Http\Requests\RegisterVisitorRequest;
use App\Models\Visitor;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;

class VisitorController extends Controller
{
    /**
     * Register a newly created visitor in storage.
     */
    public function Register(RegisterVisitorRequest $request)
    {
        
        $visitor=new Visitor;
        $visitor->name = $request->name;
        $visitor->email = $request->email;
        $visitor->password = Hash::make($request->password);
        $visitor->save();

        $token= $visitor->createToken('visitor')->plainTextToken;
        $response=['Visitor created Successfully','token'=>$token];
        return response($response,201);

    }

    /**
     * visitor Login.
     */
    public function LoginVisitor(LoginVisitorRequest $request){
       
        $creds = $request->only('email','password');

        if( !Auth::guard('visitor')->attempt($creds) ){

            return  response()->json(['message' =>'false credentials']);

         }
         
        return[
            'token'=>auth()->guard('visitor')->user()->createToken('visitor')->plainTextToken
        ];
    }  

    /**
     * visitor Logout.
     */
    public function VisitorLogout()
    {
      
        auth()->guard('visitor-api')->user()->tokens()->delete();
        return response([
            'Visitor Deconnected Succesfully'
        ],200);

    }

    /**
     * visitor data based on their token
     */
    public function VisitorData()
    {
      
        $visitor=Auth::user()->select('name','email')->get();
        return response($visitor,200);

    }

   
}
