<?php

namespace App\Http\Controllers\User;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Product;
use App\Models\Stock;
use Illuminate\Support\Facades\DB;

class ItemController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth:users');

        $this->middleware(function ($request, $next) {

            $id = $request->route()->parameter('item');
            if (!is_null($id)) {
                // ルートIdで入ってきたものが、availableItemsで表示できるかの確認
                $itemId = Product::availableItems()->where('products.id', $id)->exists();
                if (!$itemId) {
                    abort(404);
                }
            }
            return $next($request);
        });
    }

    public function index(Request $request)
    {
        $products = Product::availableItems()
        ->sortOrder($request->sort)
        ->paginate($request->pagination);

        return view('user.index', compact('products'));
    }

    public function show($id)
    {
        $product = Product::findOrFail($id);
        $quantity = Stock::where('product_id', $product->id)
        ->sum('quantity');

        // $quantityが10より多ければ、10個まで選択できる
        if($quantity > 10){
            $quantity = 10;
        }

        return view('user.show',
        compact('product', 'quantity'));
    }
}
