<?php
 namespace App\Repositories\Auth;
 use App\Models\User;
 use Validator;
 use DB;

 class AuthRepository implements AuthInterface
 {
 	public function login($request)
 	{
 		DB::beginTransaction();
 		try
 		{
 			$validator = Validator::make($request->all(), [
                'wallet_address' => 'required|string',
                'signature' => 'required|string'
            ]);

            if($validator->fails()){
                DB::commit();
                return response()->json(['status'=>false, 'message'=>'The given data was invalid', 'data'=>$validator->errors()],422);  
            }

            $user = User::where('wallet_address',$request->wallet_address)->first();

            if($user)
            {
                $success['token'] = $user->createToken('MyApp')->plainTextToken;
                $success['id'] = $user->id;
                $success['name'] = $user->name;
                $success['wallet_address'] = $user->wallet_address;
                $user->signature = $request->signature;
                $user->update();
                DB::commit();
                return response()->json(['status' => true, 'message' => 'Successfully Logged In', 'data' => $success]);
            }

            DB::commit(); 
               
            return response()->json(['status' => false, 'message' => 'Invalid Credential', 'data' => new \stdClass()],500);

 		}catch(Exception $e){
            DB::rollback();
            return response()->json(['status'=>false, 'code'=>$e->getCode(), 'message'=>$e->getMessage()],500);
        }
 	}

 	public function userDetails()
 	{
 		try
 		{
 			$user = auth()->user();
 			return response()->json(['status'=>true, 'user'=>$user]);
 		}catch(Exception $e){
 			return response()->json(['status'=>false, 'code'=>$e->getCode(), 'message'=>$e->getMessage()],500);
 		}
 	}

 	public function logout()
 	{
 		try
 		{
 			auth()->user()->tokens()->delete();
            return response()->json(['status' => true, 'message' => 'Successfully logged out!']);
 		}catch(Exception $e){
 			return response()->json(['status'=>false, 'code'=>$e->getCode(), 'message'=>$e->getMessage()],500);
 		}
 	}
 }