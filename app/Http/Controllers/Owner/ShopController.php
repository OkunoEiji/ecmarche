<?php

namespace App\Http\Controllers\Owner;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Models\Shop;
use Illuminate\Support\Facades\Storage;

class ShopController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth:owners');

        // 他のショップの情報（ログインしている人以外）を見ることを制限
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
        // $ownerId = Auth::id();
        // 取得したログインIDから、Shopモデルを検索
        $shops = Shop::where('owner_id', Auth::id())->get();
        // 取得した変数をビューに渡す
        return view('owner.shops.index',
        compact('shops'));
    }

    public function edit(string $id)
    {
        $shop = Shop::findOrFail($id);
        // dd(Shop::findOrFail($id));
        return view('owner.shops.edit', compact('shop'));
    }

    public function update(Request $request, string $id)
    {
        $imageFile = $request->image; // リクエストのimageをimageFileという変数に入れる
        if(!is_null($imageFile) && $imageFile->isValid()){ // null判定で選ばれている&念のためアップロードできているかの確認
            Storage::putFile('public/shops', $imageFile); //storageのapp/publicにshopsフォルダを作成し、ファイル名を作り保存
        }

        return redirect()->route('owner.shops.index');
    }
}
