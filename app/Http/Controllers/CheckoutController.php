<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Cartalyst\Stripe\Laravel\Facades\Stripe;
use Cartalyst\Stripe\Exception\CardErrorException;
use Illuminate\Support\Facades\Auth;
use App\Product;
use App\CartItem;
use App\User;
use App\Order;


class CheckoutController extends Controller 
{

    public function index()
    {
        return view('checkout');
    }

    public function payment(Request $request)
    {
        $shoppingCart = Auth::user()->shoppingCart;
        $cartItems = $shoppingCart->cartItems;
        $price = 0;
        foreach($cartItems as $item) {
            $price = $price + $item->product['price'];
        }

        if ($shoppingCart != null)
        {
            $metadata = [];
            foreach ($shoppingCart['cartItems'] as $index => $item){
                $metadata[] = ['item' . $index => $item['product']['name']];
            }
            try {
                $charge = Stripe::charges()->create([
                    'amount' => $price,
                    'currency' => 'RSD',
                    'source' => $request->stripeToken,
                    'description' => 'Your order',
                    'receipt_email' => $request->email,
                    'metadata' => [
                        'data1' => 'metadata 1',
                        'data2' => 'metadata 2',
                        'data3' => 'metadata 3',
                    ],
                ]);

                $request->session()->forget('shoppingCart');
                $order = new Order;
                $order->user_id = Auth::user()->_id;
                $order->products = [];
                foreach($cartItems as $item) {
                    $order->products = array_merge($order->products, [$item->product_id => $item->quantity]);
                    Product::reduceStock($item->product_id, $item->quantity);
                    $item->delete();
                }
                $order->save();
                return back()->with('success_message', 'Thank you! Your payment has been accepted.');
            } catch (CardErrorException $e) {
                return back()->withErrors('Error! ' . $e->getMessage());
            }
        }
        return back()->withErrors('Your shopping cart is Empty!');
    }
}