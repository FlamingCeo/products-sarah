<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use DB;
use \App\Models\Products;
use \App\Models\QueueProduct;

use Illuminate\Support\Facades\Validator;

class ProductController extends Controller
{
    //
    public function index(){

        $id = 1;
        try{
            $list = Products::orderBy('id', 'desc')
                              ->get();

        return  response()->json([
        'success' => true,
        'data' => $list,
        'message' => 'The data has been served',
        ], 200);

        }

        catch(Exception $e){
            return response()->json([
                'success' => false,
                'data' => $e,
                'message' => 'SOMETHING WENT WRONG',
              ], 500);
     
        }
    }

    public function create(Request $request){


        $validator = Validator::make($request->all(), [ //Custom Validator
            'name' => 'required|min:1|unique:products,name,NULL,id,deleted_at,NULL',
            'price' => 'required',
            'category' => 'required'
    
          ]);

        $insertData = $request->all();
  
        if (($validator->fails())){
         return response()->json([
                'success' => false,
                'data' => $validator->errors(),
                'message' => 'Validation failed',
              ], 400);
        }

        $insert = Products::insertGetId($insertData);
        $jobQueue = QueueProduct::insert([
          "product_id" =>$insert,
          "status" => false
        ]);

        return response()->json([
          'success' => true,
          'data' => [],
          'message' => 'New Product added',
        ], 200);

    }

    public function update(Request $request){
        
        $validator = Validator::make($request->all(), [ //Custom Validator
            'id' =>'required|exists:products,id',
            'name' =>'required',
            'price' => 'required',
            'category' => 'required'
          ]);

        $updateData = $request->all();

        if($validator->fails()){
         return response()->json([
                'success' => false,
                'data' => $validator->errors(),
                'message' => 'Validation failed',
              ], 400);
        }
        $id = $request->id;
        // dd($id);
        try{
            $update = DB::table('products')
                                ->where('id',$id)
                                ->update($updateData);
        return response()->json([
        'success' => true,
        'data' => [],
        'message' => 'The data is UPDATED',
        ], 200);

        }
        catch(Exception $e){
            return response()->json([
                'success' => false,
                'data' => $e,
                'message' => 'SOMETHING WENT WRONG',
              ], 500);
     
        }

    }

    public function delete(Request $request){

      $validator = Validator::make($request->all(), [ //Custom Validator
        'id' =>'required|exists:products,id'

      ]);


        if($validator->fails()){
         return response()->json([
                'success' => false,
                'data' => $validated->errors(),
                'message' => 'Validation failed',
              ], 400);
        }
        $deletedId = $request->id;
        try{
            $delete = Products::where('id',$deletedId)
                                ->delete();
        return response()->json([
        'success' => true,
        'data' => [],
        'message' => 'The data has been deleted',
        ], 200);

        }

        catch(Exception $e){
            return response()->json([
                'success' => false,
                'data' => $e,
                'message' => 'SOMETHING WENT WRONG',
              ], 500);
     
        }

    }
}

