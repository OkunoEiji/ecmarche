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
use App\Models\Stock;
use Illuminate\Support\Facades\DB;
use Throwable;
use Illuminate\Support\Facades\Log;
use App\Http_Requests\ProductRequest;

class ProductController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth:owners');

        $this->middleware(function ($request, $next) {

            $id = $request->route()->parameter('product');
            if (!is_null($id)) {
                // ログインしているオーナーのproductかの判定、productにはowner情報がないため、shopでつなぐ
                $productsOwnerId = Product::findOrFail($id)->shop->owner->id;
                $productId = (int)$productsOwnerId;
                if ($productId !== Auth::id()) {
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
        return view(
            'owner.products.index',
            compact('ownerInfo')
        );
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
        return view(
            'owner.products.create',
            compact('shops', 'images', 'categories')
        );
    }

    public function store(ProductRequest $request)
    {
        try {
            DB::transaction(function () use ($request) {
                // 作成した情報を$productの変数に入れる
                $product = Product::create([
                    'name' => $request->name,
                    'information' => $request->information,
                    'price' => $request->price,
                    'sort_order' => $request->sort_order,
                    'quantity' => $request->quantity,
                    'shop_id' => $request->shop_id,
                    'secondary_category_id' => $request->category,
                    'image1' => $request->image1,
                    'image2' => $request->image2,
                    'image3' => $request->image3,
                    'image4' => $request->image4,
                    'is_selling' => $request->is_selling,
                ]);
                Stock::create([
                    'product_id' => $product->id,
                    'type' => 1,
                    'quantity' => $request->quantity,
                ]);
            }, 2);
        } catch (Throwable $e) {
            Log::error($e);
            throw $e;
        }

        return redirect()
            ->route('owner.products.index')
            ->with(['message' => '商品登録を致しました。',
            'status' => 'info'
            ]);
    }

    public function edit(string $id)
    {
        // 1つのproductを指定する
        $product = Product::findOrFail($id);
        // whereでidを絞り込み、数量の合計を$quantityに入れる
        $quantity = Stock::where('product_id', $product->id)
        ->sum('quantity');
        // ログインオーナーから、Shopのid、nameを取得
        $shops = Shop::where('owner_id', Auth::id())
        ->select('id', 'name')
        ->get();
        // ログインオーナーから、Imageのid、title、filenameを取得
        $images = Image::where('owner_id', Auth::id())
        ->select('id', 'title', 'filename')
        ->orderBy('updated_at', 'desc')
        ->get();
        // PrimaryCategoryからカテゴリーを取得
        $categories = PrimaryCategory::with('secondary')
        ->get();
        // 5つの変数を持って、ビューに渡す
        return view('owner.products.edit',
        compact('product', 'quantity', 'shops', 'images', 'categories'));
    }

    public function update(ProductRequest $request, string $id)
    {
        $request->validate([
            'current_quantity' => ['required', 'integer'],
        ]);

        $product = Product::findOrFail($id);
        $quantity = Stock::where('product_id', $product->id)
        ->sum('quantity');

        if($request->current_quantity !== $quantity){
            $id = $request->route()->parameter('product');
            return redirect()->route('owner.products.edit', ['product'=>$id])
            ->with(['message' => '在庫数が変更されております。再度、ご確認ください。',
            'status' => 'alert']);
        } else {
            
        }
    }

    public function destroy(string $id)
    {
        //
    }
}
