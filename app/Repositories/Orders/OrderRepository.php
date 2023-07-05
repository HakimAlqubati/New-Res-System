<?php

namespace App\Repositories\Orders;

use App\Exports\OrdersExport;
use App\Http\Resources\OrderResource;
use App\Interfaces\Orders\OrderRepositoryInterface;
use App\Models\Order;
use App\Models\OrderDetails;
use App\Models\UnitPrice;
use App\Models\User;
use Filament\Notifications\Notification;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use Illuminate\Support\Facades\DB;
use Illuminate\Validation\Rule;
use Maatwebsite\Excel\Facades\Excel;

class OrderRepository implements OrderRepositoryInterface
{

    protected $model;

    public function __construct(Order $model)
    {
        $this->model = $model;
    }

    public function index($request)
    {
        $currnetRole = $request->user()?->roles[0]?->id;

        $query = Order::query();
        if ($request->has('customer_id')) {
            $query->where('customer_id', $request->customer_id);
        }

        if ($currnetRole == 7) {
            $query->where('customer_id', $request->user()->id);
        } else if ($currnetRole == 8) {
            $query->where('customer_id', auth()->user()->owner->id);
        }
        if ($request->has('id')) {
            $query->where('id', $request->id);
        }

        if ($currnetRole == 5) {
            $query->where('status', '!=', Order::PENDING_APPROVAL);
        }
        $orders = $query->orderBy('created_at', 'DESC')->limit(20)->get();
        return OrderResource::collection($orders);
    }
    public function store($request)
    {
        return  DB::transaction(function () use ($request) {
            try {
                // to get current user role
                $currnetRole = $request->user()?->roles[0]?->id;
                if (!isset($currnetRole)) {
                    return response()->json([
                        'success' => false,
                        'message' => 'you dont have any role'
                    ], 500);
                }
                $pendingOrderId = 0;
                $message = '';
                // check if user has pending for approval order to determine branchId & orderId & orderStatus
                if ($currnetRole == 7) {
                    $branchId = auth()->user()?->branch?->id;
                    $customerId = auth()->user()->id;
                    if (!isset($branchId)) {
                        return response()->json([
                            'success' => false,
                            'message' => 'You are not manager of any branch'
                        ], 500);
                    }
                    $orderStatus = Order::ORDERED;
                } else if ($currnetRole == 8) {
                    $orderStatus = Order::PENDING_APPROVAL;
                    $branchId = auth()->user()->owner->branch->id;
                    $customerId = auth()->user()->owner->id;
                }
                $pendingOrderId  =   $this->checkIfUserHasPendingForApprovalOrder($branchId);

                // Map order data from request body 
                $orderData = [
                    'status' => $orderStatus,
                    'customer_id' => $customerId,
                    'branch_id' => $branchId,
                    'notes' => $request->input('notes'),
                    'description' => $request->input('description'),
                ];

                // Create new order
                if ($pendingOrderId > 0) {
                    $orderId = $pendingOrderId;
                    Order::find($orderId)->update([
                        'updated_by' => auth()->user()->id,
                    ]);
                    $message = 'Your order has been submited on pending approval order no ' . $orderId;
                } else {
                    $order = Order::create($orderData);
                    $orderId = $order->id;
                    $message = 'done successfully';
                }

                // Map order details data from request body
                $orderDetailsData = [];
                foreach ($request->input('order_details') as $orderDetail) {

                    if ($pendingOrderId) {
                        $existOrderDetail = OrderDetails::where(
                            'order_id',
                            $pendingOrderId
                        )->where(
                            'product_id',
                            $orderDetail['product_id']
                        )->where(
                            'unit_id',
                            $orderDetail['unit_id']
                        )->first();
                        if ($existOrderDetail) {
                            $newQuantity = $existOrderDetail->quantity + $orderDetail['quantity'];
                            $existOrderDetail->update([
                                'updated_by' => auth()->user()->id,
                                'quantity' => $newQuantity,
                                'available_quantity' => $newQuantity,
                                'price' =>
                                UnitPrice::where(
                                    'product_id',
                                    $orderDetail['product_id']
                                )->where('unit_id', $orderDetail['unit_id'])->first()->price * ($newQuantity),
                            ]);
                            continue;
                        }
                    }
                    $orderDetailsData[] = [
                        'order_id' => $orderId,
                        'product_id' => $orderDetail['product_id'],
                        'unit_id' => $orderDetail['unit_id'],
                        'quantity' => $orderDetail['quantity'],
                        'available_quantity' => $orderDetail['quantity'],
                        'created_by' => auth()->user()->id,
                        'price' => (UnitPrice::where(
                            'product_id',
                            $orderDetail['product_id']
                        )->where('unit_id', $orderDetail['unit_id'])->first()->price) * $orderDetail['quantity'],
                        // 'created_at' => $order->created_at,
                        // 'updated_at' => $order->created_at,
                    ];
                }
                if (count($orderDetailsData) > 0) {
                    OrderDetails::insert($orderDetailsData);
                }

                //to calculate the total of order when store it
                $totalPrice = array_reduce($orderDetailsData, function ($carry, $item) {
                    return $carry + $item['price'];
                }, 0);
                Order::find($orderId)->update(['total' => $totalPrice]);


                $recipient = User::find(1);

                Notification::make()
                    ->title('Order no ' . $orderId . ' Has been created')
                    ->sendToDatabase($recipient)
                    ->broadcast($recipient);

                return response()->json([
                    'success' => true,
                    'message' => $message,
                    // 'order' => $order->where('id',$orderId)->with('orderDetails')->get(),
                    'order' => Order::find($orderId),
                ], 200);
            } catch (\Exception $e) {
                return response()->json([
                    'success' => false,
                    'message' => $e->getMessage()
                ], 500);
            }
        });
    }
    public function checkIfUserHasPendingForApprovalOrder($branchId)
    {
        $order = Order::where('status', Order::PENDING_APPROVAL)
            ->where('branch_id', $branchId)
            ->where('active', 1)
            ->first();

        return $order ? $order->id : null;
    }
    public function update($request, $id)
    {
        try {
            // Start a database transaction
            DB::beginTransaction();

            try {
                // Find the order by the given ID or throw a ModelNotFoundException
                $order = Order::findOrFail($id);
            } catch (ModelNotFoundException $e) {
                // Roll back the transaction and return an error response if the order is not found
                DB::rollBack();

                return response()->json([
                    'success' => false,
                    'orderId' => null,
                    'message' => "Order not found with $id id",
                ], 404);
            }

            // Validate the request data
            $validatedData = $request->validate([
                'status' => [
                    'string',
                    Rule::in([
                        Order::PROCESSING,
                        Order::READY_FOR_DELEVIRY,
                        Order::DELEVIRED,
                        Order::ORDERED,
                    ])
                ],
                'notes' => 'string',
                'full_quantity' => 'boolean',
                'active' => 'boolean'
            ]);
            $order->updated_by = auth()->user()->id;
            // Fill the order with the validated data and save it to the database
            $order->fill($validatedData)->save();

            // Commit the transaction
            DB::commit();

            // Return a success response with the updated order information
            return [
                'success' => true,
                'orderId' => $order->id,
                'message' => 'done successfully'
            ];
        } catch (\Exception $e) {
            // Roll back the transaction in case of an error
            DB::rollBack();

            // Handle the exception and return an error response
            return response()->json([
                'success' => false,
                'orderId' => $order->id,
                'message' => $e->getMessage()
            ], 500);
        }
    }

    public function export($id)
    {
        return Excel::download(new OrdersExport($id), 'order-no-' . $id . '-.xlsx');
    }
    public function exportTransfer($id)
    {
        return Excel::download(new OrdersExport($id), 'transfer-no-' . $id . '-.xlsx');
    }
}
