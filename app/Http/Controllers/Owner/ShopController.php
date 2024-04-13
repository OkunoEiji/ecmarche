<?php

namespace App\Http\Controllers\Owner;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Models\Shop;
use Illuminate\Support\Facades\Storage;
use InterventionImage;
use App\Http\Requests\UploadImageRequest;
use App\Services\ImageService;

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

    // 引数をUploadImageRequestに置き換え、バリデーションが使用可能。
    public function update(UploadImageRequest $request, string $id)
    {
        // 店名、店舗情報、販売中か停止中かはこちらでバリデーションをかける。
        $request->validate([
            'name' => ['required', 'string', 'max:50'],
            'information' => ['required', 'string', 'max:1000'],
            'is_selling' => ['required'],
        ]);

        // 画像ファイルに関しては、フォームリクエストでバリデーションをかける。
        $imageFile = $request->image; // リクエストのimageをimageFileという変数に入れる
        if(!is_null($imageFile) && $imageFile->isValid()){ // null判定で選ばれている&念のためアップロードできているかの確認
            $fileNameToStore = ImageService::upload($imageFile, 'shops');
            // // Storage::putFile('public/shops', $imageFile); //storageのapp/publicにshopsフォルダを作成し、ファイル名を作り保存（リサイズなしの場合）
            // $fileName = uniqid(rand().'_'); // ランダムなファイル名にする
            // $extension = $imageFile->extension(); // アップロードされたimageFileの拡張子を取得
            // $fileNameToStore = $fileName. '.' .$extension; // ファイル名と拡張子を付けて、変数に入れる
            // $resizedImage = InterventionImage::make($imageFile)->resize(1920, 1080)->encode(); // アップロードされた画像をInterventionのmakeに入れ、リサイズする
            // // dd($imageFile ,$resizedImage);

            // Storage::put('public/shops/' .$fileNameToStore, $resizedImage); // 第1引数がフォルダからのファイル名、第2引数がリサイズした画像
        }

        $shop = Shop::findOrFail($id);
        $shop->name = $request->name;
        $shop->information = $request->information;
        $shop->is_selling = $request->is_selling;
        //　ファイルをアップしない場合もあるので、空ではない場合の条件をつける。$fileNameToStoreを張り付ける。
        if(!is_null($imageFile) && $imageFile->isValid()){
            $shop->filename = $fileNameToStore;
        }
        // 保存
        $shop->save();

        return redirect()
        ->route('owner.shops.index')
        ->with(['message'=>'店舗情報を更新致しました。',
        'status'=>'info']);
    }
}
