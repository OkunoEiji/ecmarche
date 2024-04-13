<?php

namespace App\Http\Controllers\Owner;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Image;
use Illuminate\Support\Facades\Auth;
use App\Http\Requests\UploadImageRequest;
use App\Services\ImageService;
use Illuminate\Support\Facades\Storage;

class ImageController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth:owners');

        // 他のショップの情報（ログインしている人以外）を見ることを制限
        $this->middleware(function($request, $next){

            $id = $request->route()->parameter('image'); // imageのid取得
            if(!is_null($id)){ // null判定
                $imagesOwnerId = Image::findOrFail($id)->owner->id; // 取得したiamgeのidからownerのidを取得
                $imageId = (int)$imagesOwnerId; // 文字列で取得されるため、数値に型変更
                if($imageId !== Auth::id()){ // ownerのidを取得、imageidの外部キー制約が違うなら、404エラー画面を表示
                    abort(404);
                }
            }
            return $next($request);
        });
    }

    public function index()
    {
        // ログインしているオーナーidでImageモデルを検索
        $images = Image::where('owner_id', Auth::id())
        ->orderBy('updated_at', 'desc') // 更新頻度が新しい順で表示
        ->paginate(20); // 20件ずつ表示
        // 取得した変数をビューに渡す
        return view('owner.images.index',
        compact('images'));
    }

    public function create()
    {
        return view('owner.images.create');
    }

    public function store(UploadImageRequest $request)
    {
        // 複数の画像を配列で取得
        $imageFiles = $request->file('files');
        // 一応、null判定する
        if(!is_null($imageFiles)){
            foreach($imageFiles as $imageFile){
                // 第2引数は保存先'products'
                $fileNameToStore = ImageService::upload($imageFile, 'products');
                // 保存する
                Image::create([
                    'owner_id' => Auth::id(),
                    'filename' => $fileNameToStore
                ]);
            }
        }

        return redirect()
        ->route('owner.images.index')
        ->with(['message'=>'画像登録を実施致しました。',
        'status'=>'info']);
    }

    public function edit(string $id)
    {
        $image = Image::findOrFail($id);
        return view('owner.images.edit', compact('image'));
    }

    public function update(Request $request, string $id)
    {
        $request->validate([
            'title' => ['string', 'max:50'],
        ]);

        $image = Image::findOrFail($id);
        $image->title = $request->title;
        $image->save();

        return redirect()
        ->route('owner.images.index')
        ->with(['message'=>'画像情報を更新致しました。',
        'status'=>'info']);
    }

    public function destroy(string $id)
    {
        $image = Image::findOrFail($id);
        $filePath = 'public/products/' . $image->filename;

        if(Storage::exists($filePath)){
            Storage::delete($filePath);
        }

        Image::findOrFail($id)->delete();

        return redirect()
        ->route('owner.images.index')
        ->with(['message'=>'画像を削除致しました。',
        'status'=>'alert']);
    }
}
