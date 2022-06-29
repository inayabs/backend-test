<?php

namespace App\Http\Controllers;

use App\Models\Order;
use App\Models\Product;
use Exception;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class OrderController extends Controller
{
    public function order(Request $request){
        $id = $request->product_id;
        $quantity = $request->quantity;
        
        return $this->orderHandler($id,$quantity);
    }

    public function orderHandler($id=null,$quantity=0){
        try{
            // check if stocks can provide the given quantity
            DB::beginTransaction();
            $product = Product::find($id)->first();
            if($quantity > $product->available_stock){
                return response()->json(["message"=>"Failed to order this product due to unavailability of the stock"],400);
            }else{
                $product->available_stock = $product->available_stock - $quantity;
                $product->update();
                DB::commit();
                return response()->json(["message"=>"You have successfully ordered this product"],201);
            }
        }catch(Exception $e){
            DB::rollBack();
        }
    }
}
