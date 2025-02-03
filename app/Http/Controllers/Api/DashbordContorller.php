<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Brand;
use App\Models\Favorite;
use App\Models\product;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class DashbordContorller extends Controller
{
    public function home(){
        $brand = Brand::all();
        $product = product::where('avalibale', '=', 1)->get();
        return response()->json([
            'status'=> true,
            'massage'=> 'App Home Page',
            'brand'=> $brand,
            'product'=> $product,
        ],200);
    }

    public function products_by_brand($brand)  {
        $brands = Brand::where('id', '=', $brand)->first();
        $products = product::where([
            ['avalibale', '=', 1],
            ['brand', '=', $brands->id],
        ])->get();
        return response()->json([
            'status'=> true,
            'massage'=> 'Products By Brand Page',
            'brand'=> $brand,
            'products'=> $products,
        ],200);
    }

    public function products_view($id) {
        $product = product::findOrFail($id);
        return response()->json([
            'status'=> true,
            'massage'=> 'Products By view',
            'product'=> $product,
        ],200);
    }

    public function filters($filter) {

        if($filter == 'low') {

            $data = Product::orderBy('price', 'asc')->get();

        } else if($filter == 'high') {

            $data = Product::orderBy('price', 'desc')->get();

        } else if($filter == 'new') {

            $data = Product::latest()->get();


        } else if($filter == 'old') {

            $data = Product::all();

        }

        return response()->json([

            'status'    => true,
            'message'   => 'Product Filters',
            'product'   => $data

        ], 200);


    } 

    public function filters_by_brand($brand, $filter) { 

        $brand = Brand::findorFail($brand);

        if($filter == 'low') {

            $data = Product::where('brand', '=', $brand)->orderBy('price', 'asc')->get();

        } else if($filter == 'high') {

            $data = Product::where('brand', '=', $brand)->orderBy('price', 'desc')->get();

        } else if($filter == 'new') {

            $data = Product::where('brand', '=', $brand)->latest()->get();

        } else if($filter == 'old') {

            $data = Product::where('brand', '=', $brand)->get();

        }

        return response()->json([

            'status'    => true,
            'message'   => 'Product Filters By Brand',
            'product'   => $data

        ], 200);

    }

    public function add_favorite($product_id) {

        $user = auth('sanctum')->user();

        $check = Favorite::where([

            ['user_id', '=', $user->id],
            ['product_id', '=', $product_id]

        ])->first();


        if(isset($check)) {

            return response()->json([

                'status'    => false,
                'message'   => 'You Added This Product Before',
                'product'   => null
    
            ], 200);

        } else {

            $data = new Favorite;
            $data->product_id = $product_id;
            $data->user_id = $user->id;
            $data->created_at = Carbon::now();
            $data->save();

            return response()->json([

                'status'    => true,
                'message'   => 'Product Added To Favorite Successfully',
                'product'   => $data
    
            ], 200);


        }
        

    }

    public function fetch_favorite() {

        $user = auth('sanctum')->user();

        $data = DB::table('favorites')
        ->where('user_id', '=', $user->id)
        ->join('products', 'favorites.product_id', 'products.id')
        ->select('products.*')
        ->get();

        return response()->json([

            'status'    => true,
            'message'   => 'User Favorite',
            'user'   => $data

        ], 200);

    } 
    
    public function remove_favorite($id) {

        $user = auth('sanctum')->user();

        $data = Favorite::where([

            ['user_id', '=', $user->id],
            ['product_id', '=', $id]

        ])->delete();

        if($data == true) {

            return response()->json([

                'status'    => true,
                'message'   => 'Product Removed From Favorite Successfully',
            
    
            ], 200);

        } else {

            return response()->json([

                'status'    => true,
                'message'   => 'Some Thing Wrong Plz Try Again',
             
    
            ], 200);
        }
    }
}
