<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Order;
use App\Jobs\ProcessOrder;  // Ensure this line is present
use App\Events\OrderCreated;
use App\Events\OrderStatusUpdated;

class OrderController extends Controller
{
    public function store(Request $request)
    {
        $order = Order::create([
            'name' => $request->ordername,
            'total_amount' => $request->ordertamount,
            'status' => 'pending'
        ]);
        ProcessOrder::dispatch($order);
        broadcast(new OrderCreated($order))->toOthers();

        return response()->json(['message' => 'Order Created!', 'order' => $order]);

        // return response()->json(['message' => 'Order placed successfully!']);
    }

    public function index()
    {
        return view('orders.index', ['orders' => Order::all()]);
    }

    public function show()
    {
        return view('orders.create' , ['orders' => Order::all()]);
    }

    public function updateStatus(Request $request, Order $order)
{
    $order->update(['status' => $request->status]);

    broadcast(new OrderStatusUpdated($order))->toOthers();

    return response()->json(['message' => 'Status updated']);
}

}
