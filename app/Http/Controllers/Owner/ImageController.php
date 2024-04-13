<?php

namespace App\Http\Controllers\Owner;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Image;
use Illuminate\Support\Facades\Auth;
use App\Http\Requests\UploadImageRequest;

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
        dd($request);
    }

    public function show(string $id)
    {
        //
    }

    public function edit(string $id)
    {
        //
    }

    public function update(Request $request, string $id)
    {
        //
    }

    public function destroy(string $id)
    {
        //
    }
}
