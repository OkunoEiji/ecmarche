<?php

namespace App\Http\Controllers\Owner;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Image;
use Illuminate\Support\Facades\Auth;
use App\Models\Product;
use App\Models\PrimaryCategory;
use App\Models\Owner;
use App\Models\Shop;

class ProductController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth:owners');

        $this->middleware(function($request, $next){

            $id = $request->route()->parameter('product');
            if(!is_null($id)){
                // ログインしているオーナーのproductかの判定、productにはowner情報がないため、shopでつなぐ
                $productsOwnerId = Product::findOrFail($id)->shop->owner->id;
                $productId = (int)$productsOwnerId;
                if($productId !== Auth::id()){
                    abort(404);
                }
            }
            return $next($request);
        });
    }

    public function index()
    {
        // ログインしているオーナーのproductを取得（N+1問題）
        // ownerからshopからproductからimageFirstをまとめて取得する（条件：ログインしているオーナー）
        $ownerInfo = Owner::with('shop.product.imageFirst')
        ->where('id', Auth::id())->get();

        // // dd($ownerInfo);
        // foreach($ownerInfo as $owner){
        //     // dd($owner->shop->product);
        //     foreach($owner->shop->product as $product){
        //         // dd($product->imageFirst->filename);
        //     }
        // }

        // productsの情報を持って、indexにリダイレクト
        return view('owner.products.index',
        compact('ownerInfo'));
    }

    public function create()
    {
        // ログインしているオーナーから、Shopのid、nameを取得
        $shops = Shop::where('owner_id', Auth::id())
        ->select('id', 'name')
        ->get();

        // ログインしているオーナーから、Imageのid、title、filenameを取得
        $images = Image::where('owner_id', Auth::id())
        ->select('id', 'title', 'filename')
        ->orderBy('updated_at', 'desc')
        ->get();

        // PrimaryCategoryからリレーションで取得
        $categories = PrimaryCategory::with('secondary')
        ->get();

        // 3つの変数をowner.products.createに渡す
        return view('owner.products.create',
        compact('shops', 'images', 'categories'));
    }

    public function store(Request $request)
    {
        //
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
