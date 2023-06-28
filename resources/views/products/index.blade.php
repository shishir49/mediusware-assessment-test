@extends('layouts.app')

@section('content')

    <div class="d-sm-flex align-items-center justify-content-between mb-4">
        <h1 class="h3 mb-0 text-gray-800">Products</h1>
    </div>

    @if(Session::has('msg'))
    <div class="alert alert-success" role="alert">
        <p class="alert alert-info">{{ Session::get('msg') }}</p>
    </div>
    @endif


    <div class="card">
        <form action="{{ url('product') }}" method="get" class="card-header">
            <div class="form-row justify-content-between">
                <div class="col-md-2">
                    <input type="text" name="title" placeholder="Product Title" class="form-control">
                </div>
                <div class="col-md-2">
                    <select name="variant" id="" class="form-control">
                        <option value="" disabled selected>--Select a Variant--</option>
                        @foreach($variants as $variant)
                            <optgroup label="{{ $variant->title }}">
                                @foreach($variant->productVariants->unique('variant') as $variant) 
                                <option value="{{ $variant->variant }}">{{ $variant->variant }}</option>
                                @endforeach
                        @endforeach
                    </select>
                </div>

                <div class="col-md-3">
                    <div class="input-group">
                        <div class="input-group-prepend">
                            <span class="input-group-text">Price Range</span>
                        </div>
                        <input type="text" name="price_from" aria-label="First name" placeholder="From" class="form-control">
                        <input type="text" name="price_to" aria-label="Last name" placeholder="To" class="form-control">
                    </div>
                </div>
                <div class="col-md-2">
                    <input type="date" name="date" placeholder="Date" class="form-control">
                </div>
                <div class="col-md-1">
                    <button type="submit" class="btn btn-primary float-right"><i class="fa fa-search"></i></button>
                </div>
            </div>
        </form>

        <div class="card-body">
            <div class="table-response">
                <table class="table">
                    <thead>
                    <tr>
                        <th>#</th>
                        <th>Title</th>
                        <th>Description</th>
                        <th>Variant</th>
                        <th width="150px">Action</th>
                    </tr>
                    </thead>

                    <tbody>
                    @foreach($productList as $product)
                    <tr>
                        <td>{{ $product->id }}</td>
                        <td>{{ $product->title }} <br> Created at : {{ $product->created_at }}</td>
                        <td>
                            <div style="max-width: 400px;">
                            {{ substr_replace($product->description, "...", 40) }}
                            </div>
                        </td>
                        <td>
                            <dl class="row mb-0" style="height: 80px; overflow: hidden" id="variant">
                                @foreach($product->variantGroup as $variants)
                                <dt class="col-sm-3 pb-0">
                                <!-- SM/ Red/ V-Nick -->
                                    <div>
                                        @if($variants->product_variant_one)
                                           {{ \App\Models\ProductVariant::where('id', $variants->product_variant_one)->first()->variant }}
                                        @endif

                                        @if($variants->product_variant_two)
                                           / {{ \App\Models\ProductVariant::where('id', $variants->product_variant_two)->first()->variant }}
                                        @endif

                                        @if($variants->product_variant_three)
                                           / {{ \App\Models\ProductVariant::where('id', $variants->product_variant_three)->first()->variant }} 
                                        @endif
                                    </div>
                                </dt>
                                <dd class="col-sm-9">
                                    <dl class="row mb-0">
                                        <dt class="col-sm-4 pb-0">Price : {{ number_format($variants->price,2) }}</dt>
                                        <dd class="col-sm-8 pb-0">InStock : {{ number_format($variants->stock,2) }}</dd>
                                    </dl>
                                </dd>
                                @endforeach
                            </dl>
                            <button onclick="$('#variant').toggleClass('h-auto')" class="btn btn-sm btn-link">Show more</button>
                        </td>
                        <td>
                            <div class="btn-group btn-group-sm">
                                <a href="{{ route('product.edit', 1) }}" class="btn btn-success">Edit</a>
                            </div>
                        </td>
                    </tr>
                    @endforeach
                    </tbody>
                </table>
            </div>

        </div>

        <div class="card-footer">
            <div class="row justify-content-between">
                <div class="col-md-6">
                    <p>Showing 1 to {{ count($productList) }} out of {{ $productList->total() }}</p>
                </div>
                <div class="col-md-2">
                {!! $productList->links() !!}
                </div>
            </div>
        </div>
    </div>

@endsection
