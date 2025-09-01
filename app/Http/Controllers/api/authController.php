<?php

namespace App\Http\Controllers\api;

use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Validator;
use Spatie\Permission\Models\Role;
use App\Traits\ResponseHelper;
use Illuminate\Http\Request;
use App\Models\User;
use DB;

class authController extends Controller
{
    use ResponseHelper;

    public function register(Request $request){

        $rules = array(
            'name' => 'required',
            'email' => 'required|email|unique:users,email',
            'phone' => 'required|numeric|unique:users,phone|digits:10',
            'password' => 'required|string|confirmed|regex:/^(?=.*[a-z])(?=.*[A-Z])(?=.*\d)(?=.*[@$!%*#?&])[A-Za-z\d@$!%*#?&]{6,}$/',
            
        );

        $messages=array(
            'name.required' => 'Name is required.',
            'email.required' => 'Email is required.',
            'email.unique' => 'This E-mail ID has already been taken. Please register with a different E-mail ID.',
            'email.email' => 'Entered E-mail is Incorrect.',
            'password.required' => 'Password is required.',
            'password.confirmed' => 'Password confirmation does not match.',
            'password.regex' => 'Password must be at least 6 characters long and include at least one uppercase letter, one lowercase letter, one number, and one special character.',
            'phone.required' => 'Phone Number is required.',
            'phone.numeric' => 'Phone number must always be in numeric format.',
            'phone.unique' => 'This mobile number has already been taken. Please register with a different mobile number.',
        );

        $validator=Validator::make($request->all(),$rules,$messages);

        if ($validator->fails()) {
            return $this->validationFailed($validator->errors(),"The given data was invalid.");
        }

        DB::beginTransaction();

        $user = User::create([
            'role_id' => 2,
            'name' => $request->name,
            'email' => $request->email,
            'phone' => $request->phone,
            'password' => \Hash::make($request->password),
        ]);

        $role = Role::where('id',2)->first()->name;
        $user->assignRole($role);

        DB::commit();

        return $this->successResponse($user, 200,"Successfully Registered");
    }

    public function login(Request $request)
    {
        $rules=array(
            'phone' => 'required|numeric|digits:10',
            'password' => 'required|string',
        );
        
        $validator=Validator::make($request->all(),$rules);


        if ($validator->fails()) {
            return $this->validationFailed($validator->errors());
        }
        
       
        $user = User::where('phone', $request->phone)->get()->first();

        if ($user && \Hash::check($request->password, $user->password)) 
        {

            if($user->is_active == 1)
            {

                if($user->role_id == 2)
                {

                    $user->auth_token = $user->createToken('authToken')->plainTextToken;

                    return $this->successResponse($user, 200, 'Successfully Logged in');
                }
                else
                {
                    return $this->errorResponse("Admin cant Login",400,"Failed to Login");
                }
            }
            else
            {
                return $this->errorResponse("Please verify your phone",400,"Failed to Login");
            }
        }
        else
        {

            return $this->errorResponse("Invalid Login Credentials",400,"Failed to Login");
        }
            
    }

    public function activate_user(Request $request,$id)
    {
        $user = User::where('id',$id)->update(['is_active' => 1]);
        $user = User::where('id',$id)->first();
        $user->auth_token = $user->createToken('authToken')->plainTextToken;
        return $this->successResponse($user, 200, 'User activated successfully');

    }

    public function logout(Request $request)
    {
        // Delete all tokens of the user
        $request->user()->tokens()->delete();

        return $this->successResponse('Success', 200, 'Logged out from all devices successfully');
    }
}
