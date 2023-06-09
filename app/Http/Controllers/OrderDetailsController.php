<?php

namespace App\Http\Controllers;

use App\Models\Category;
use App\Models\OrderDetails;
use App\Models\Product;
use App\Models\UnitPrice;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use Illuminate\Database\QueryException;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Validation\Rule;
use Illuminate\Validation\ValidationException;

class OrderDetailsController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        //
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
        //
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        //
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

    public function update(Request $request)
    {
        // Validate the request data.
        $data = $request->validate([
            'orders_details.*.id' => ['required', 'integer', Rule::exists('orders_details', 'id')],
            'orders_details.*.product_id' => ['required', 'integer', Rule::exists('products', 'id')->where('active', 1)],
            'orders_details.*.unit_id' => ['required', 'integer', Rule::exists('units', 'id')],
            'orders_details.*.quantity' => ['required', 'numeric', 'min:0'],
            'orders_details.*.available_quantity' => ['required', 'numeric', 'min:0'],
            'orders_details.*.available_in_store' => ['required', 'numeric', 'min:0'],
            'orders_details.*.operation' => ['required', 'string', Rule::in(['update', 'destroy'])],
        ]);

        // Initialize an array to hold the responses.
        $responses = [];

        // Loop through the order detail IDs and update or delete each one.
        foreach ($data['orders_details'] as $orderDetailData) {
            // Find the order detail with the specified ID.
            $orderDetail = OrderDetails::findOrFail($orderDetailData['id']);

            // Find the new product by ID.
            $product = Product::findOrFail($orderDetailData['product_id']);

            // Check if the unit price for the selected product and unit is available.
            $unitPrice = $product->unitPrices()->where('unit_id', $orderDetailData['unit_id'])->first();

            // Return a 404 error response if the unit price is not available.
            if (!$unitPrice) {
                $responses[] = [
                    'success' => false,
                    'id' => $orderDetailData['id'],
                    'message' => 'The unit price for the selected product and unit is not available.',
                ];
                continue;
            }

            // Check if the new product is in the same category as the original product.
            if ($product->category_id !== $orderDetail->product->category_id) {
                // Return an error response if the new product is not in the same category as the original product.
                $responses[] = [
                    'success' => false,
                    'id' => $orderDetailData['id'],
                    'message' => sprintf(
                        'The "%s" product (category: "%s") cannot be used to replace the "%s" product (category: "%s"). The new product must be in the same category as the original product.',
                        $product->name,
                        $product->category->name,
                        $orderDetail->product->name,
                        $orderDetail->product->category->name
                    ),
                ];
                continue;
            }

            if ($orderDetailData['operation'] === 'update') {
                // Calculate the new price based on the quantity and unit price.
                $price = $orderDetailData['quantity'] * $unitPrice->price;

                // Update the order detail with the new values.
                $orderDetail->fill($orderDetailData);
                $orderDetail->product_id = $orderDetailData['product_id'];
                $orderDetail->unit_id = $orderDetailData['unit_id'];
                $orderDetail->price = $price;
                $orderDetail->save();

                $responses[] = [
                    'success' => true,
                    'id' => $orderDetailData['id'],
                    'message' => 'Order detail updated successfully',
                ];
            } elseif ($orderDetailData['operation'] === 'destroy') {
                // Delete the order detail.
                $orderDetail->delete();

                $responses[] = [
                    'success' => true,
                    'id' => $orderDetailData['id'],
                    'message' => 'Order detail deleted successfully',
                ];
            } else {
                // Return an error response if the operation is invalid.
                $responses[] = [
                    'success' => false,
                    'id' => $orderDetailData['id'],
                    'message' => 'Invalid operation: ' . $orderDetailData['operation'],
                ];
            }
        }

        // Return the array of responses.
        return response()->json($responses);
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
