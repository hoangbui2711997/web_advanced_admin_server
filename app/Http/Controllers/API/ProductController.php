<?php

namespace App\Http\Controllers\API;

use App\Consts;
use App\Exceptions\AttributeProvidedInvalidException;
use App\Http\Controllers\Controller;
use App\Models\Discount;
use App\Models\Product;
use App\Utils;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;

class ProductController extends Controller
{
    public function getProducts(Request $request)
    {
        $limit = $request->input('limit', 10);
        return Product::paginate($limit);
    }

    public function addProduct(Request $request)
    {
        $properties = $request->except('img_product');
        $uploadedFile = $request->file('img_product');
        $filePath = Utils::saveFileToStorage($uploadedFile, 'products', 'img_product');
        $properties['image_url'] = $filePath;
        return Product::create($properties);
    }

    public function delProduct(Request $request) {
        if ($request->has('id')) {
            Product::where('id', $request->input('id'))->delete();
            return 'Delete product success';
        }
        throw new AttributeProvidedInvalidException();
    }

    public function editProduct(Request $request)
    {
        if (!empty($request->file('img_product'))) {
            $properties = $request->except(['img_product', 'id']);
            $uploadedFile = $request->file('img_product');
            $filePath = Utils::saveFileToStorage($uploadedFile, 'products', 'img_product');
            $properties['image_url'] = $filePath;
        } else {
            $properties = $request->except('id');
        }

        return Product::updateOrCreate(['id' => $request->input('id')], $properties);
    }

    public function getDiscounts (Request $request) {
        $limit = $request->input('limit', Consts::DEFAULT_PER_PAGE);
        return Discount::paginate($limit);
    }

    public function getProduct(Request $request)
    {
        if ($request->has('id')) {
            return Product::find($request->input('id'));
        }
        throw new AttributeProvidedInvalidException();
    }
}
