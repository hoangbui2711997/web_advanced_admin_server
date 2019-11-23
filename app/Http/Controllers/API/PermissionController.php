<?php

namespace App\Http\Controllers\API;

use App\Consts;
use App\Exceptions\AttributeProvidedInvalidException;
use App\Http\Controllers\Controller;
use App\Models\Category;
use App\Models\Permission;
use Illuminate\Http\Request;

class PermissionController extends Controller
{
    public function addPermission(Request $request)
    {
        if ($request->has('name')) {
            $category = new Permission([
                'name' => $request->input('name', '')
            ]);
            $category->save();
            return $category;
        }
        throw new AttributeProvidedInvalidException();
    }

    public function editPermission(Request $request)
    {
        if ($request->has(['name', 'id'])) {
            $category = Permission::where('id', $request->input('id'))
                ->update(['name' => $request->input('name')]);
            return $category;
        }
        throw new AttributeProvidedInvalidException();
    }

    public function getPermissions(Request $request)
    {
        $limit = $request->input('limit', Consts::DEFAULT_PER_PAGE);

        return Permission::paginate($limit);
    }

    public function delPermission(Request $request)
    {
        if ($request->has('id')) {
            Permission::where('id', $request->input('id'))->delete();
            return 'ok';
        }
    }
}
