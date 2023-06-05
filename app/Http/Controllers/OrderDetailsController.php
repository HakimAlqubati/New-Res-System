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

    public function update(Request $request, $id)
    {
        try {
            // Start a database transaction.
            DB::beginTransaction();

            // Find the order detail with the specified ID.
            $orderDetail = OrderDetails::findOrFail($id);

            // Get the input data from the request.
            $data = $request->validate([
                'product_id' => ['required', 'integer', Rule::exists('products', 'id')->where('active', 1)],
                'unit_id' => ['required', 'integer', Rule::exists('units', 'id')],
                'quantity' => ['required', 'numeric', 'min:0'],
                'available_quantity' => ['required', 'numeric', 'min:0'],
                'available_in_store' => ['required', 'numeric', 'min:0'],
                'operation' => ['required', 'string', Rule::in(['update', 'destroy'])],
            ]);

            // Find the new product by ID.
            $product = Product::findOrFail($data['product_id']);

            // Check if the unit price for the selected product and unit is available.
            $unitPrice = $product->unitPrices()->where('unit_id', $data['unit_id'])->first();

            // Return a 404 error response if the unit price is not available.
            if (!$unitPrice) {
                return response()->json([
                    'success' => false,
                    'message' => 'The unit price for the selected product and unit is not available.'
                ], 404);
            }

            // Check if the new product is in the same category as the original product.
            if ($product->category_id !== $orderDetail->product->category_id) {
                // Return an error response if the new product is not in the same category as the original product.
                return response()->json([
                    'success' => false,
                    'message' => sprintf(
                        'The "%s" product (category: "%s") cannot be used to replace the "%s" product (category: "%s"). The new product must be in the same category as the original product.',
                        $product->name,
                        $product->category->name,
                        $orderDetail->product->name,
                        $orderDetail->product->category->name
                    ),
                ], 422);
            }

            if ($data['operation'] === 'update') {
                // Calculate the new price based on the quantity and unit price.
                $price = $data['quantity'] * $unitPrice->price;

                // Update the order detail with the new values.
                $orderDetail->fill($data);
                $orderDetail->product_id = $data['product_id'];
                $orderDetail->unit_id = $data['unit_id'];
                $orderDetail->price = $price;
                $orderDetail->save();
            } elseif ($data['operation'] === 'destroy') {
                // Delete the order detail.
                $orderDetail->delete();
            } else {
                // Return a 422 error response if the operation is invalid.
                return response()->json([
                    'success' => false,
                    'message' => 'Invalid operation: ' . $data['operation']
                ], 422);
            }

            // Commit the transaction.
            DB::commit();

            // Return a success response with the updated order detail.
            if ($data['operation'] === 'update') {
                return response()->json([
                    'success' => true,
                    'orderDetail' =>  $orderDetail,
                    'message' => 'Order detail updated successfully'
                ]);
            } else {
                return response()->json([
                    'success' => true,
                    'message' => 'Order detail removed successfully'
                ]);
            }
        } catch (\Exception $ex) {
            // Roll back the transaction and return a 500 error response if an exception occurs while updating the order detail.
            DB::rollBack();

            return response()->json([
                'success' => false,
                'message' => 'An error occurred while updating the order detail: ' . $ex->getMessage()
            ], 500);
        }
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
