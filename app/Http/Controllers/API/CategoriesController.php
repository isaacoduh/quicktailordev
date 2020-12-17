<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use App\Models\Category;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class CategoriesController extends Controller
{
    public function index()
    {
        $categories = Category::with('catalog')->get();
        return response()->json(['data'=>$categories]);
    }


    public function create(Request $request)
    {
        $validator = Validator::make($request->all(),[
            'name' => 'required|unique:categories'
        ]);

        if($validator->fails()){
            return response()->json($validator->messages(),400);
        }

        $category = Category::create([
            'name' => $request->name,
        ]);
        $category->save();
        return response()->json(['data' => $category]);

    }

    public function update(Request $request, $id)
    {
        $validator = Validator::make($request->all(), [
            'name' => 'required|unique:categories,name'
        ]);
        if($validator->fails()){
            return response()->json($validator->messages(),400);
        }

        $category = Category::find($id);
        $category->name = $request->name;
        $category->save();

        return response()->json(['data' => $category, 'message' => 'Category Updated']);
    }

    public function destroy($id)
    {
        $category = Category::find($id);
        $category->delete();
        return response()->json('Category deleted successfully');
    }
}
