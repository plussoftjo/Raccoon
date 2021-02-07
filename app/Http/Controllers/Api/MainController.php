<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\CoinsLog;
use App\Models\Posts;
use App\Models\Follows;
use App\Models\Comments;
use App\Models\PrizesCategories;
use App\Models\Orders;
use App\Models\Deploy;
use App\Models\NotificationsToken;
use Carbon\Carbon;
class MainController extends Controller
{
    public function index(Request $request) {
        $coinsLogs = CoinsLog::where('user_id',$request->user_id)->whereDate('created_at',Carbon::today())->get();
        $todayCoins = CoinsLog::where('user_id',$request->user_id)->whereDate('created_at',Carbon::today())->sum('coin');
        $posts = Posts::where('user_id',$request->user_id)->get();
        $follows = Follows::where('user_id',$request->user_id)->count();
        $followers = Follows::where('follow_id',$request->user_id)->count();

        // Social Tasks 
        $todayFollowPersion = Follows::where('user_id',$request->user_id)->whereDate('created_at',Carbon::today())->count();
        $todayMakePost = Posts::where('user_id',$request->user_id)->whereDate('created_at',Carbon::today())->count();
        $todayMakeComment = Comments::where('user_id',$request->user_id)->whereDate('created_at',Carbon::today())->count();


        $prizesCategories = PrizesCategories::get();

        $orders = Orders::where('user_id',$request->user_id)->get();


        $dev = Deploy::where("key","dev")->first();

        return response()->json([
            'coinsLogs' => $coinsLogs,
            'todayCoins' => $todayCoins,
            'posts' => $posts,
            'follows' => $follows,
            'followers' => $followers,
            'todayFollowPersion' => $todayFollowPersion,
            'todayMakePost' => $todayMakePost,
            'todayMakeComment' => $todayMakeComment,
            'prizesCategories'=> $prizesCategories,
            'orders' => $orders,
            "dev" => $dev
        ]);
    }

    public function StoreToken(Request $request) {
        $checkToken = NotificationsToken::where("token",$request->token)->where("user_id",$request->user_id)->count();
        if($checkToken == 0){
            NotificationsToken::create([
                'token' => $request->token,
                "user_id" => $request->user_id
            ]);
        }
    }
}
