<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Auth;
use App\Models\User;
use App\Models\Posts;
use App\Models\Profiles;
use App\Models\Orders;
use App\Models\Coins;
use App\Models\CoinsLog;
use Carbon\Carbon;
class AuthController extends Controller
{
    public function register(Request $request)
    {
        // Fetch User Inputs from the request
        $user_input = $request->all();
        

        if($request->image == 'null') {
            $user = User::create([
                'name' => $user_input['name'],
                'phone' => $user_input['phone'],
                'password' => bcrypt($user_input['password']),
                'role_id' => $user_input['role_id']
            ]);
        }else {
            if ($request->hasFile('image')) {
                //  Let's do everything here
                if ($request->file('image')->isValid()) {
                    //
                    $validated = $request->validate([
                        'image' => 'mimes:jpeg,png,jpg',
                    ]);
                    $extension = $request->image->extension();
                    $filename = Carbon::now()->format('YmdHs').'.'.$extension;
                    $request->image->storeAs('/public/users', $filename);
                    $url = 'storage/users/'.$filename;
                    
                   
                    $user = User::create([
                        'name' => $user_input['name'],
                        'phone' => $user_input['phone'],
                        'password' => bcrypt($user_input['password']),
                        'role_id' => $user_input['role_id'],
                        'avatar' => $url
                    ]); 
                   
                    
                }
            }else {
                $user = User::create([
                    'name' => $user_input['name'],
                    'phone' => $user_input['phone'],
                    'password' => bcrypt($user_input['password']),
                    'role_id' => $user_input['role_id']
                ]);
            }
        }


        // Create Token
        $token = $user->createToken('auth')->accessToken;

        // Fetch User
        $user_data = User::where('id', $user->id)->first();

        if($request->referral_code !== '') {
            if(Coins::where('user_id',$request->referral_code)->first()) {
                $lastCoinValue = Coins::where('user_id',$request->referral_code)->value('coin');
                Coins::where('user_id',$request->referral_code)->update([
                    'coin' => $lastCoinValue + 3
                ]);
    
                $coins_logs = CoinsLog::create([
                    'user_id' => $request->referral_code,
                    'way' => 'From Invite',
                    'coin' => 3
                ]);
            }
        }

        // Return data
        return response()->json([
            'token' => $token,
            'user' => $user_data,
        ]);
    }
    public function login(Request $request)
    {
        // Fetch Input
        $input = $request->all();
        // IF Right Values
        if (Auth::attempt(['phone' => $input['phone'], 'password' => $input['password']])) {
            // Auth User
            $user = Auth::User();
            $token = $user->createToken('auth')->accessToken; #Fetch Token


            //return data
            return response()->json([
                'token' => $token,
                'user' => $user,
            ], 200);
        }

        // if not correct
        return response()->json(['error' => 'Unauthorised'], 401);
    }

    public function auth()
    {
        $user = Auth::User();

        return response()->json($user);
    }

    public function test() {
        
        return response()->json(CoinsLog::get());
    }

    public function seeder() {
        Posts::factory()
            ->times(5)
            ->create();
    }

    public function updateBio(Request $request) {
        $profile = Profiles::where('user_id',$request->user_id)->update([
            'bio' => $request->bio
        ]);

        return response()->json([
            'message' => 'Updated'
        ]);
    }

    public function updateAddress(Request $request) {
        $profile = Profiles::where('user_id',$request->user_id)->update([
            'address' => $request->address
        ]);

        return response()->json([
            'message' => 'Updated'
        ]);
    }

    public function updateProfile(Request $request) {
        if($request->password == '') {
            $user = User::where('id',$request->user_id)->update([
                'name' => $request->name,
                'phone' => $request->phone
            ]);
        }else {
            $user = User::where('id',$request->user_id)->update([
                'name' => $request->name,
                'phone' => $request->phone,
                'password' => bcrypt($request->password)
            ]);
        }

        return response()->json(['message' => 'Success Update Profile'],200);
    }

    public function updateAvatar(Request $request) {
        if ($request->hasFile('image')) {
            //  Let's do everything here
            if ($request->file('image')->isValid()) {
                //
                $validated = $request->validate([
                    'image' => 'mimes:jpeg,png,jpg',
                ]);
                $extension = $request->image->extension();
                $filename = Carbon::now()->format('YmdHs').'.'.$extension;
                $request->image->storeAs('/public/users', $filename);
                $url = 'storage/users/'.$filename;
                
               
                User::where('id',$request->user_id)->update([
                    'avatar' => $url
                ]);

                $user = User::where('id',$request->user_id)->first();
                return response()->json($user);
               
                
            }
        }
    }
}
