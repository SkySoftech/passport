<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\User; 
use Illuminate\Support\Facades\Auth; 
use Validator;
class UserController extends Controller
{
	public $successStatus = 200;
	/** 
	* login api
	*/ 
	public function login(){ 
        if(Auth::attempt(['mobile_no' => request('mobile_no'), 'otp' => request('otp')])){ 
            $user = Auth::user(); 
            $success['token'] =  $user->createToken('MyApp')-> accessToken; 
            return response()->json(['success' => $success], $this-> successStatus); 
        } 
        else{ 
            return response()->json(['error'=>'Unauthorised'], 401); 
        } 
	}

	/** 
    * Register api 
    */ 
    public function register(Request $request) 
    { 
  //       $validator = Validator::make($request->all(), [ 
  //           'mobile_no' => 'required|mobile_no'
  //       ]);
		// if ($validator->fails()) { 
  //           return response()->json(['error'=>$validator->errors()], 401);            
  //       }
		$input = $request->all(); 
		$otp = rand(0000,9999);
        $input['otp'] = bcrypt($otp); 

        if (User::where('mobile_no',$input['mobile_no'])->update(['otp' => $input['otp']])) {
        	$user = User::where('mobile_no',$input['mobile_no'])->get();
        	$user = $user[0];
        } else {
        	$user = User::create($input);  
        }
        
        $success['token'] =  $user->createToken('MyApp')-> accessToken;
        $success['mobile_no'] =  $user->mobile_no;
        $success['otp'] = $otp;
		return response()->json(['success'=>$success], $this-> successStatus); 
    }
	
}