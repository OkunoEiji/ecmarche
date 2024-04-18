<?php

namespace App\Http\Controllers\User;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Models\Cart;
use App\Models\User;
use App\Models\Stock;

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

    public function delete($id)
    {
        Cart::where('product_id', $id)
        ->where('user_id', Auth::id())
        ->delete();

        return redirect()->route('user.cart.index');
    }

    public function checkout()
    {
        $user = User::findOrFail(Auth::id());
        $products = $user->products;
        
        $lineItems = [];
        foreach($products as $product){
            $quantity = '';
            $quantity = Stock::where('product_id', $product->id)->sum('quantity');

            if($product->pivot->quantity > $quantity){
                return redirect()->route('user.cart.index');
            } else {
                $price_data = ([
                    'unit_amount' => $product->price,
                    'currency' => 'jpy',
                    'product_data' => $product_data = ([
                        'name' => $product->name,
                        'description' => $product->information,
                    ]),
                ]);
    
                $lineItem = [
                    'price_data' => $price_data,
                    'quantity' => $product->pivot->quantity,
                ];
                array_push($lineItems, $lineItem);    
            }
        }

        foreach($products as $product){
            Stock::create([
                'product_id' => $product->id,
                'type' => \Constant::PRODUCT_LIST['reduce'],
                'quantity' => $product->pivot->quantity * -1
            ]);
        }

        \Stripe\Stripe::setApiKey(env('STRIPE_SECRET_KEY'));

        $session = \Stripe\Checkout\Session::create([
            'payment_method_types' => ['card'], // 支払方法を指定
            'line_items' => [$lineItems], // $lineItemsを定義
            'mode' => 'payment', // 支払モードの選択
            'success_url' => route('user.items.index'), // 成功ページroute('user.cart.success'), 
            'cancel_url' => route('user.cart.index'), // キャンセルページroute('user.cart.cancel'), 
        ]);

        $publicKey = env('STRIPE_PUBLIC_KEY');

        return view('user.checkout', 
            compact('session', 'publicKey'));
    }
}
