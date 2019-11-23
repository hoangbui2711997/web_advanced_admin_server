<?php

namespace App\Http\Controllers\API;

use App\Consts;
use App\Exceptions\AttributeProvidedInvalidException;
use App\Http\Controllers\Controller;
use App\Models\Category;
use Illuminate\Http\Request;

class CategoryController extends Controller
{
    public function addCategory(Request $request)
    {
        if ($request->has('name')) {
            $category = new Category([
                'name' => $request->input('name', '')
            ]);
            $category->save();
            return $category;
        }
        throw new AttributeProvidedInvalidException();
    }

    public function editCategory(Request $request)
    {
        if ($request->has(['name', 'id'])) {
            $category = Category::where('id', $request->input('id'))
                                ->update(['name' => $request->input('name')]);
            return $category;
        }
        throw new AttributeProvidedInvalidException();
    }

    public function getCategories(Request $request)
    {
        $limit = $request->input('limit', Consts::DEFAULT_PER_PAGE);

        return Category::paginate($limit);
    }

    public function delCategory(Request $request)
    {
        if ($request->has('id')) {
            Category::where('id', $request->input('id'))->delete();
            return 'ok';
        }
    }
}
