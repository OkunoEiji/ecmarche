<?php

namespace App\Http\Controllers\User;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Models\Cart;
use App\Models\User;

class CartController extends Controller
{
    public function index()
    {
        $user = User::findOrFail(Auth::id());
        $products = $user->products;
        $totalPrice = 0;

        foreach($products as $product){
            $totalPrice += $product->price * $product->pivot->quantity;
        }

        // dd($products, $totalPrice);

        return view('user.cart',
        compact('products', 'totalPrice'));
    }

    public function add(Request $request)
    {
        // ログインしているuser_idとproduct_idを確認して、両方満たすものを取得
        $itemInCart = Cart::where('product_id', $request->product_id)
        ->where('user_id', Auth::id())->first(); // カートに商品があるかの確認

        if($itemInCart){ // カートに商品がある場合、
            $itemInCart->quantity += $request->quantity; // 数量を追加する
            $itemInCart->save();
        } else {
            Cart::create([ // カートに商品がない場合、新規作成
                'user_id' => Auth::id(),
                'product_id' => $request->product_id,
                'quantity' => $request->quantity
            ]);
        }

        return redirect()->route('user.cart.index');
    }
}
