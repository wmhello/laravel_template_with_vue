<?php

namespace App\Http\Controllers;

use App\Http\Resources\ProductCollection;
use App\Models\Product;
use Illuminate\Http\Request;

class ProductController extends Controller
{
    protected $model = 'App\Models\Product';
    protected $fillable = ['product_number', 'product_name', 'product_img', 'order_id', 'product_amount', 'remain_amount', 'product_remark'];






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
     * @param  \App\Models\Product  $product
     * @return \Illuminate\Http\Response
     */
    public function show(Product $product)
    {
        //
        return new \App\Http\Resources\Product($product);
    }


    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\Product  $product
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, Product $product)
    {
        //
    }


    protected function getListData($pageSize){
        $data = $this->model::paginate($pageSize);
        return new ProductCollection($data);
    }
}
