<?php

namespace App\Http\Controllers\API\v1;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Validator;
use Illuminate\Support\Facades\Auth;

class BuyController extends Controller
{
    //
    public function addCard(Request $request,$user_id = '0'){
        $input              = $request->all(); 
        if($user_id=='0'){
            $input['user_id']=Auth::user()['id'];
        }else{
            $input['user_id']=$user_id;
        }
        $validator = Validator::make($input, [ 
            // 'user_id'      => 'required',
            'product_id'=> 'required', 
        ]);
        if ($validator->fails()) { 
            return response()->json(['error'=>$validator->errors()], 401);            
        }
        return response()->json(['success' => $input], 200); 
    }
}
