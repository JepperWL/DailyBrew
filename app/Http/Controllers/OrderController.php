<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use App\Models\Order;
use Illuminate\Http\Request;
use Midtrans\Config;
use Midtrans\Transaction;

class OrderController extends Controller
{
    //
   public function index()
    {
        $orders = Order::with(['orderItems.beverage', 'user'])
            ->where('user_id', auth()->id())
            ->orderBy('created_at', 'desc')
            ->paginate(10);

        return view('user.order', compact('orders'));
    }

    public function detail($id)
    {
        $order = Order::with(['orderItems.beverage.brand', 'user'])
            ->where('user_id', auth()->id())
            ->findOrFail($id);

        return view('user.order-detail', compact('order'));
    }


    public function checkStatus(Order $order)
    {
      
    
         Config::$serverKey = config('midtrans.server_key');
        Config::$isProduction = config('midtrans.is_production');

        try{
            $status = Transaction::status($order->order_number);
            if ($status->transaction_status === 'settlement') {
                $order->update(['status' => 'paid']);
            } elseif ($status->transaction_status === 'pending') {
                $order->update(['status' => 'pending']);
            } elseif ($status->transaction_status === 'expire') {
                $order->update(['status' => 'expired']);
            } elseif ($status->transaction_status === 'cancel') {
                $order->update(['status' => 'canceled']);
            } else {
                $order->update(['status' => $status->transaction_status]);
            }
            $order->save();
 
            return redirect()->back()->with('success', 'Payment status updated successfully.');
        }catch (\Exception $e) {
            return redirect()->back()->with('error', 'Failed to check payment status: ' . $e->getMessage());
        }
    }

    public function cancelOrder(Order $order)
    {
        Config::$serverKey = config('midtrans.server_key');
        Config::$isProduction = config('midtrans.is_production');
        try {
            
             $status = Transaction::status($order->order_number);
            if ( $status->transaction_status === 'pending') {
                Transaction::cancel($order->order_number);
                $order->update(['status' => 'canceled']);
                 $order->save();
            
                return redirect()->back()->with('success', 'Order canceled successfully.');
            }
            else {
                return redirect()->back()->with('error', 'Only pending orders can be canceled.');
            }
            
        } catch (\Exception $e) {
            return redirect()->back()->with('error', 'Failed to cancel order: ' . $e->getMessage());
        }
    }

}
