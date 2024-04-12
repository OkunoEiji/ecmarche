<?php

namespace App\Http\Controllers\Owner;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Models\Shop;

class ShopController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth:owners');

        $this->middleware(function($request, $next){
            // dd($request->route()->parameter('shop.index')); // 数字でなく、文字列で取得
            // dd(Auth::id()); // 文字列でなく、数字で取得

            $id = $request->route()->parameter('shop'); // shopのid取得
            if(!is_null($id)){ // null判定
                $shopsOwnerId = Shop::findOrFail($id)->owner->id; // 取得したshopのidからownerのidを取得
                $shopId = (int)$shopsOwnerId; // 文字列で取得されるため、数値に型変更
                $ownerId = Auth::id(); // ownerのidを取得
                if($shopId !== $ownerId){ // idが同じでないなら、404エラー画面を表示
                    abort(404);
                }
            }
            return $next($request);
        });
    }

    public function index()
    {
        // ログインしているオーナーidを取得
        $ownerId = Auth::id();
        // 取得したログインIDから、Shopモデルを検索
        $shops = Shop::where('owner_id', $ownerId)->get();
        // 取得した変数をビューに渡す
        return view('owner.shops.index',
        compact('shops'));
    }

    public function edit(string $id)
    {
        dd(Shop::findOrFail($id));
    }

    public function update(Request $request, string $id)
    {
    }
}
