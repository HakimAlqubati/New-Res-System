<?php

namespace App\Http\Controllers;

use App\Http\Resources\OrderResource;
use App\Models\Order;
use App\Models\OrderDetails;
use App\Models\UnitPrice;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class OrderController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {

        // $order = Order::find(11)->categories;
        // return $order;

        // return $order->categories->toSql();
        $query = Order::query();
        if ($request->has('customer_id')) {
            $query->where('customer_id', $request->customer_id);
        }

        if ($request->has('id')) {
            $query->where('id', $request->id);
        }

        $orders = $query->get();
        return OrderResource::collection($orders);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        return  DB::transaction(function () use ($request) {
            try {
                // Map order data from request body 
                $orderData = [
                    'status' => Order::ORDERED,
                    'customer_id' => auth()->user()->id,
                    'branch_id' => auth()->user()->branch->id,
                    'notes' => $request->input('notes'),
                    'description' => $request->input('description'),

                ];
                // Create new order
                $order = Order::create($orderData);

                // Map order details data from request body
                $orderDetailsData = [];
                foreach ($request->input('order_details') as $orderDetail) {
                    $orderDetailsData[] = [
                        'order_id' => $order->id,
                        'product_id' => $orderDetail['product_id'],
                        'unit_id' => $orderDetail['unit_id'],
                        'quantity' => $orderDetail['quantity'],
                        'available_quantity' => $orderDetail['quantity'],
                        'price' => (UnitPrice::where(
                            'product_id',
                            $orderDetail['product_id']
                        )->where('unit_id', $orderDetail['unit_id'])->first()->price) * $orderDetail['quantity'],
                        'created_at' => $order->created_at,
                        'updated_at' => $order->created_at,
                    ];
                }
                OrderDetails::insert($orderDetailsData);

                $totalPrice = array_reduce($orderDetailsData, function ($carry, $item) {
                    return $carry + $item['price'];
                }, 0);
                Order::find($order->id)->update(['total' => $totalPrice]);
                return response()->json([
                    'success' => true,
                    'message' => 'done successfully',
                    'order' => $order->where('id', $order->id)->with('orderDetails')->get(),
                ], 200);
            } catch (\Exception $e) {
                return response()->json([
                    'success' => false,
                    'message' => $e->getMessage()
                ], 500);
            }
        });
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        dd('show');
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        dd('update');
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        //
    }
}
