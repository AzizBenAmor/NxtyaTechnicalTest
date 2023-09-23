<?php

namespace App\Http\Controllers;

use App\Http\Requests\LoginAdminRequest;
use App\Http\Requests\RegisterRequest;
use App\Models\Admin;
use App\Models\Visitor;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;

class AdminController extends Controller
{


    /**
     * Register a newly created Admin in storage.
     */
    public function Register(RegisterRequest $request)
    {
        
        $admin=new Admin;
        $admin->name = $request->name;
        $admin->email = $request->email;
        $admin->password = Hash::make($request->password);
        $admin->save();

        $token= $admin->createToken('admin')->plainTextToken;
        $response=['Admin created Successfully','token'=>$token];
        return response($response,201);

    }

    /**
     * Admin Login.
     */
    public function LoginAdmin(LoginAdminRequest $request){
       
        $creds = $request->only('email','password');

        if( !Auth::guard('admin')->attempt($creds) ){

            return  response()->json(['message' =>'false credentials']);

         }
         
        return[
            'token'=>auth()->guard('admin')->user()->createToken('admin')->plainTextToken
        ];
    }  

    /**
     * Admin LogOut.
     */
    public function AdminLogout()
    {
      
        auth()->guard('admin-api')->user()->tokens()->delete();
        return response([
            'Admin Deconnected Succesfully'
        ],200);

    }

    /**
     * Soft Delete a Vistor.
     */
    public function DeleteVisitor(Request $request)
    {

        $request->validate([
            'email'=>'required|email|exists:visitors,email'
        ]);
        
        $visitor=Visitor::where('email',$request->email)->first();
        if (!$visitor) {
         
            return response('visitor already deleted');
        
        }
        $visitor->delete();

        return response(['Visitor has been deleted'],200);

    }

    /**
     * Remove the visitor resource from storage.
     */
    public function removeVisitor(Request $request)
    {
        
        $request->validate([
            'email'=>'required|email|exists:visitors,email'
        ]);
        $visitor=Visitor::withTrashed()->where('email',$request->email)->first();
        $visitor->forceDelete();

        return response(['Visitor has been Removed'],200);

    }

        /**
     *view trashed visitor.
     */
    public function TrashedVisitor()
    {
        
        $visitors = Visitor::onlyTrashed()->get();
        return response($visitors,200);

    }

       /**
     *retrieve trashed visitor.
     */
    public function RetrieveVisitor(Request $request)
    {
        
        $request->validate([
            'email'=>'required|email|exists:visitors,email'
        ]);
        $visitor=Visitor::onlyTrashed()->where('email',$request->email)->first();

        if (!$visitor) {
            
            return response('the visitor is not deleted');

        }

        $visitor->deleted_at=null;
        $visitor->update();

        return response('visitor retrieved successfully',200);

    }
}
