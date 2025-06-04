<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use App\Models\Order;
use App\Models\Beverage;
use Illuminate\Http\Request;
use Midtrans\Config;
use Midtrans\Transaction;

class AdminController extends Controller
{
    public function dashboard(Request $request)
    {
        $query = Beverage::with(relations: ['category', 'brand']);

        if ($request->has('search') && $request->search) {
            $searchTerm = $request->search;
            $query->where(function ($q) use ($searchTerm) {
                $q->where('name', 'like', '%' . $searchTerm . '%')
                    ->orWhere('description', 'like', '%' . $searchTerm . '%')
                    ->orWhereHas('brand', function ($brandQuery) use ($searchTerm) {
                        $brandQuery->where('name', 'like', '%' . $searchTerm . '%');
                    })
                    ->orWhereHas('category', function ($categoryQuery) use ($searchTerm) {
                        $categoryQuery->where('name', 'like', '%' . $searchTerm . '%');
                    });
            });
        }

        $beverages = $query->orderByRaw('is_available DESC')
            ->orderBy('updated_at', 'desc')
            ->paginate(10);

        $totalSales = Order::where('status', 'paid')->sum('total_amount');
        $totalTransactions = Order::count();

        return view('admin.dashboard', compact('beverages', 'totalSales', 'totalTransactions'));

    }

    public function order(Request $request)
    {
        $orders = Order::with(['orderItems.beverage', 'user'])->orderBy('created_at', 'desc')->paginate(5);


        return view('admin.order', compact('orders'));
    }

    public function orderDetail($id)
    {
        $order = Order::with(['orderItems.beverage.brand', 'user'])
            ->findOrFail($id);

        return view('admin.order-detail', compact('order'));
    }

    public function checkOrderStatus(Order $order)
    {
        Config::$serverKey = config('midtrans.server_key');
        Config::$isProduction = config('midtrans.is_production');

        try {
            $status = Transaction::status($order->order_number);

            $newStatus = 'pending';
            switch ($status->transaction_status) {
                case 'settlement':
                    $newStatus = 'paid';
                    break;
                case 'pending':
                    $newStatus = 'pending';
                    break;
                case 'expire':
                    $newStatus = 'expired';
                    break;
                case 'cancel':
                    $newStatus = 'canceled';
                    break;
                case 'deny':
                    $newStatus = 'failed';
                    break;
                default:
                    $newStatus = $status->transaction_status;
            }

            $order->update(['status' => $newStatus]);

            return redirect()->back()->with('success', 'Payment status updated successfully.');
        } catch (\Exception $e) {
            return redirect()->back()->with('error', 'Failed to check payment status: ' . $e->getMessage());
        }
    }

    public function cancelOrder(Order $order)
    {


        Config::$serverKey = config('midtrans.server_key');
        Config::$isProduction = config('midtrans.is_production');

        try {
            $status = Transaction::status($order->order_number);
            if ($status->transaction_status === 'pending' || $status->transaction_status === 'paid') {
                return redirect()->back()->with('error', 'Only pending or paid orders can be canceled.');
            }

            Transaction::cancel($order->order_number);

            $order->update(['status' => 'canceled']);

            return redirect()->back()->with('success', 'Order canceled successfully.');
        } catch (\Exception $e) {


            return redirect()->back()->with('error', 'Order canceled successfully.');
        }
    }

}