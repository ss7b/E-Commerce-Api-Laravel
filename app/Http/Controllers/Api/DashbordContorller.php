<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Brand;
use App\Models\product;
use Illuminate\Http\Request;

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
}
