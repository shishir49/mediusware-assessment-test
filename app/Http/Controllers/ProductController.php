<?php

namespace App\Http\Controllers;

use App\Models\Product;
use App\Models\Variant;
use App\Models\ProductVariant;
use App\Models\ProductVariantPrice;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use DB;

class ProductController extends Controller
{

    public function index(Request $request)
    {
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

        // echo "<pre>"; print_r($productList); die();

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
        $validation = Validator::make($request->all(), [
            'product_name'     => 'required',
            'sku'              => 'required|unique:products',
            'description'      => 'required'
         ]);
  
         if($validation->fails()) {
            return response()->json(["errors" => $validation->errors()] ,400);
         } else {
          DB::Transaction(function() use($request) {
              $createProduct = Product::create([
                'title'        => $request->product_name,
                'sku'          => $request->sku,
                'description'  => $request->product_description
              ]);
    
            //   $path = '';
            //   $thumbnailPath = '';
  
            //   foreach($request->product_image as $key => $images) {
            //       $photoFile = $request->product_image->file('product_image'); 
            //       $path      = date('mdYHis').uniqid()."-".$photoFile->getClientOriginalName();
            //       $photoFile->move(public_path('uploads'), $path);
      
            //       $thumbnail = Image::make($photoFile->getRealPath());
            //       $thumbnailPath = 'thumbnail'.date('mdYHis').uniqid()."-".$photoFile->getClientOriginalName();
            //       $thumbnail->resize(150, 150, function ($constraint) {
            //           $constraint->aspectRatio();
            //       })->save($public_path('thumbnails').'/'.$thumbnailPath);
              
            //       ProductImage::create([
            //       'product_id'    => $createProduct->id,
            //       'file_path'     => $path,
            //       'thumbnail'     => $thumbnailPath
            //       ]);
            //   }
                
              foreach($request->product_variant as $key => $variant) {
                  foreach($request->product_variant[$key]['value'] as $subkey => $variants) {
                      $product_variant = ProductVariant::create([
                          'variant'       => $request->product_variant[$key]['value'][$subkey],
                          'variant_id'    => $request->product_variant[$key]['option'],
                          'product_id'    => $createProduct->id
                      ]);
                  }
                }
                
                foreach($request->product_preview as $key => $variantPrice) {
                    $explode_variants = explode("/", $request->product_preview[$key]["variant"]);
                    
                    $variant_one = ProductVariant::where('variant_id', 1)->where('product_id', $createProduct->id)->where('variant', $explode_variants[0])->first(); 
                    $variant_two = ProductVariant::where('variant_id', 2)->where('product_id', $createProduct->id)->where('variant', $explode_variants[1])->first(); 
                    $variant_three = ProductVariant::where('variant_id', 6)->where('product_id', $createProduct->id)->where('variant', $explode_variants[2])->first(); 
             
                    ProductVariantPrice::create([
                        'price'                   => $request->product_preview[$key]['price'],
                        'stock'                   => $request->product_preview[$key]['stock'],
                        'product_id'              => $createProduct->id,
                        'product_variant_one'     => ($variant_one) ? $variant_one->id : null,
                        'product_variant_two'     => ($variant_two) ? $variant_two->id : null,
                        'product_variant_three'   => ($variant_three) ? $variant_three->id : null
                    ]);
                }
           });
  
           return redirect()->to('product')->with(['msg' => 'product created successfully !']);
         }
    }

    public function show($product)
    {

    }
    
    public function edit(Product $product)
    {
        $productDetails = Product::with('variantGroup', 'productVariant')->where('id', $product->id)->first();
        $variants = Variant::all();
        return view('products.edit', compact('variants', 'productDetails'));
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
