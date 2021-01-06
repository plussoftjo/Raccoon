<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Codes;
use App\Models\Coins;
use App\Models\CoinsLog;
use App\Models\Orders;
class OrderController extends Controller
{
    public function store(Request $request) {
        $subCategoriesID = $request->prizes_sub_categories_id;

        $code = Codes::where('prizes_sub_categories_id',$subCategoriesID)->where('active',1)->first();
        if(!$code) {
            return response()->json(['message' => 'There are no code'],500);
        }
        
        $user_id = $request->user_id;
        $prizes_categories_id = $request->prizes_categories_id;
        $codes_id = $code->id;
        $fee = $request->fee;
        $logs = $prizes_categories_id . ','.$subCategoriesID.','.$code->code;

        $orders = Orders::create([
            'user_id' => $user_id,
            'prizes_categories_id' => $prizes_categories_id,
            'prizes_sub_categories_id' => $subCategoriesID,
            'codes_id' => $codes_id,
            'fee' => $fee,
            'logs' => $logs
        ]);

        $Coin = Coins::where('user_id',$user_id)->value('coin');
        $newCoin = $Coin - $fee;
        Coins::where('user_id',$user_id)->update([
            'coin' => $newCoin
        ]);

       $order = Orders::where('id',$orders->id)->first();

        // Change Code To Not Active 
        Codes::where('id',$codes_id)->update([
            'active' => 0
        ]);

        return response()->json([
            'order' => $order,
            'newCoin' => $newCoin,
        ]);
    }
}
