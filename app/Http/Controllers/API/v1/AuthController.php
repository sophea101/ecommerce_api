<?php

namespace App\Http\Controllers\API\v1;

use Illuminate\Support\Facades\Auth;
use App\Http\Controllers\Controller;
use App\Http\Resources\UserResource;
use Illuminate\Http\Request;
use Validator;
use App\User;

class AuthController extends Controller
{
    public $successStatus = 200;

    public function login()
    {
        $validator = Validator::make(request()->all(), [
            'phone'     => 'required', 
            'password'  => 'required'
        ]);
        if ($validator->fails()) { 
            return response()->json(['error'=>$validator->errors()], 401);            
        }
        $login = is_numeric(request()['phone']) ? 'phone':'email';
        if(Auth::attempt([$login => request('phone'), 'password' => request('password')])){ 
            $user = Auth::user(); 
            $success['token'] =  $user->createToken('ecommerce_api_app')-> accessToken; 
            // return response()->json([
            //     'token' => $success['token'],
            //     'name'  => $user->name,
            //     'email' => $user->email,
            //     'phone' => $user->phone,
            // ], $this-> successStatus); 
            
        return response()->json(['token' => $success['token'],'data' => $user], $this-> successStatus); 
        } 
        else{ 
            return response()->json(['error'=>'Unauthorised'], 401); 
        } 
    }

    public function register(Request $request) 
    { 
        $validator = Validator::make($request->all(), [ 
            'name'      => 'required', 
            'email'     => 'required|unique:users,email', 
            'password'  => 'required', 
            'phone'     => 'required|unique:users,phone',
            'c_password'=> 'required|same:password', 
        ]);
        if ($validator->fails()) { 
            return response()->json([$validator->errors()], 401);            
        }else{
            $input              = $request->all(); 
            $input['password']  = bcrypt($input['password']); 
            $user               = User::create($input); 
            $success['token']   =  $user->createToken('ecommerce_api_app')-> accessToken; 
            return response()->json($success, $this-> successStatus); 
        }
        
    }
      
    /**
     * Logout user (Revoke the token)
     *
     * @return [string] message
     */
    public function logout(Request $request)
    {
        $request->user()->token()->revoke();
        return response()->json([
            'message' => 'Successfully logged out'
        ]);
    }
    
    public function user() 
    { 
        $user = Auth::user(); 
        return response()->json(['success' => $user], $this-> successStatus); 
    } 

    public function resetPassword()
    {
        $validator = Validator::make(request()->all(), [ 
            'email'     => 'required',
            'password'  => 'required'
        ]);
        if ($validator->fails()) { 
            return response()->json(['error'=>$validator->errors()], 401);            
        }
        $user = User::where('phone', request()['email'])->Orwhere('email', request()['email'])->first();
        if($user){
            $user->update([
                'password' => bcrypt(request()['password'])
            ]);
            $success = 'Password reset successfully.';
            return response()->json(['success'  =>$success], $this-> successStatus); 
        }else{
            return response()->json(['error'    =>'Your Phone or Email not found!'], 404); 
        }
    }

    public function users()
    {
        $users = User::paginate(20);
        return UserResource::collection($users)
                            ->additional([
                                'status'    => 200,
                                'message'   => 'User get successfully.'
                            ]);
    }

    public function upload(Request $request)
    {
        $user = Auth::user();
        if($user){
        if($user->profile){
            $filename = public_path().$user->profile;
            \File::delete($filename);   
        }
        $this->validate($request, [
            'image' => 'required|image|mimes:jpeg,png,jpg,gif,svg|max:2048',
            ]);
            if ($request->hasFile('image')) {
                $image              = $request->file('image');
                $name               = time().'.'.$image->getClientOriginalExtension();
                $destinationPath    = public_path('uploads/profile');
                $image->move($destinationPath, $name);
            }
            $request->user()->forceFill([
                'profile' => '/uploads/profile/'.$name,
            ])->save();
            return response()->json(['status'    =>'Image Upload successfully'], 200); 
        } 
    }
}
        