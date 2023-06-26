<?php

namespace App\Http\Controllers;

use App\Models\Product;
use App\Models\Variant;
use App\Models\ProductVariant;
use App\Models\ProductVariantPrice;
use Illuminate\Http\Request;
use DB;

class ProductController extends Controller
{
    public function index(Request $request)
    {
        // $productList = Product::with('variantGroup')->paginate(2);

        $priceRangeFrom = $request->get('price_from');
        $priceRangeTo   = $request->get('price_to');
        $variant        = $request->get('variant');
        $title          = $request->get('title');
        $date           = $request->get('date');
        
        $getList    = Product::query();

        if($priceRangeFrom && $priceRangeTo) {
            $getList = Product::with('variantGroup')
                        ->whereHas('variantGroup',function ($q) use ($priceRangeFrom, $priceRangeTo) {
                            $q->whereBetween('price', [$priceRangeFrom, $priceRangeTo]);
                        });
        }

        if($title) {
            $getList = Product::with('variantGroup')
                        ->where('title', 'like' , '%'.$title.'%');
        }

        if($variant) {
            $getList = Product::with('variantGroup', 'productVariant')
                        ->whereHas('productVariant',function ($q) use ($variant) {
                            $q->where('variant', 'like' ,'%'.$variant.'%');
                        });            
        }

        if($date) {
            $getList = Product::with('variantGroup')
                        ->where('created_at', '>=' ,$date);
        }

        $productList      = $getList->paginate(2);

        $variants = Variant::with('productVariants')->get();

        return view('products.index', compact('productList', 'variants'));
    } 

    public function create()
    {
        $variants = Variant::all();
        return view('products.create', compact('variants'));
    }
 
    public function store(Request $request)
    {

    }

    public function show($product)
    {

    }
    
    public function edit(Product $product)
    {
        $variants = Variant::all();
        return view('products.edit', compact('variants'));
    }
 
    public function update(Request $request, Product $product)
    {
        //
    }
 
    public function destroy(Product $product)
    {
        //
    }
}
