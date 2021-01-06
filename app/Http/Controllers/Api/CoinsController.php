<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Coins;
use App\Models\CoinsLog;
class CoinsController extends Controller
{
    public function reciveCoins(Request $request) {
        $coins = Coins::where('user_id',$request->user_id)->value('coin');
        $newCoins = $coins + $request->coin;
        Coins::where('user_id',$request->user_id)->update([
            'coin' => $newCoins
        ]);

        $coinsLog = CoinsLog::create([
            'user_id' => $request->user_id,
            'coin' => $request->coin,
            'way' => $request->way
        ]);

        

        return response()->json([
            'coinsLog' => $coinsLog
        ]);
    }
}
