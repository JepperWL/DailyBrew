<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use App\Models\Cart;
use App\Models\Order;
use App\Models\OrderItem;
use DB;
use Illuminate\Http\Request;
use Midtrans\Config;
use Midtrans\Snap;

class PaymentController extends Controller
{
    //
    public function processPayment(Request $request)
    {
        $request->validate([
            'shipping_address' => 'required|string|max:500',
           
        ]);

        
        $carts = Cart::with('beverage')
            ->where('user_id', auth()->id())
            ->get();

        if ($carts->count() === 0) {
            return redirect()->route('cart.index')->with('error', 'Your cart is empty.');
        }
        $subtotal = $carts->sum(function($cart) {
            return $cart->beverage->price * $cart->quantity;
        });
        $shipping = 15000;
        $total = $subtotal + $shipping;

        Config::$serverKey = config('midtrans.server_key');
        Config::$isProduction = config('midtrans.is_production');
        Config::$isSanitized = true;
        Config::$is3ds = true;

        DB::beginTransaction();
        try {
          
            $order = Order::create([
                'user_id' => auth()->id(),
                'order_number' => 'ORD-' . time() . '-' . auth()->id(),
                'delivery_address' => $request->shipping_address,
                'amount' => $subtotal,
                'shipping_amount' => $shipping,
                'total_amount' => $total,
                'payment_url' => null,
                'status' => 'pending',
            ]);

  
            foreach ($carts as $cart) {
                OrderItem::create([
                    'order_id' => $order->id,
                    'beverage_id' => $cart->beverage_id,
                    'quantity' => $cart->quantity,
                    'note' => $cart->note,
                ]);
            }

            
            $itemDetails = [];
            foreach ($carts as $cart) {
                $itemDetails[] = [
                    'id' => $cart->beverage->id,
                    'price' => $cart->beverage->price,
                    'quantity' => $cart->quantity,
                    'name' => $cart->beverage->name,
                    'note' => $cart->note,
                    
                ];
            }

            $itemDetails[] = [
                'id' => 'SHIPPING',
                'price' => $shipping,
                'quantity' => 1,
                'name' => 'Shipping Cost',
                
            ];

            $params = [
                'transaction_details' => [
                    'order_id' => $order->order_number,
                    'gross_amount' => $total,
                ],
                'item_details' => $itemDetails,
                'customer_details' => [
                    'first_name' => auth()->user()->name,
                    'email' => auth()->user()->email,
                ],
                'callbacks' => [
                    'finish' => route('user.order-detail', ['id' => $order->id]),
                    
                ],
              
            ];

           
            $snapUrl = Snap::createTransaction($params)->redirect_url;
            
            $order->payment_url = $snapUrl;
            $order->save();

            Cart::where('user_id', auth()->id())->delete();

            DB::commit();

            return redirect($snapUrl);

        } catch (\Exception $e) {
            DB::rollBack();
            return redirect()->back()->with('error', 'Payment initiation failed: ' . $e->getMessage());
        }
    }

}
