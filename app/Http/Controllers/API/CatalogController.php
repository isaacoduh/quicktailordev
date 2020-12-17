<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use App\Models\Catalog;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class CatalogController extends Controller
{
    public function index()
    {
        $catalog = Catalog::with('category')->get();
        return response()->json(['data' => $catalog]);
    }

    public function create(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'category_id' => 'required',
            'name' => 'required|unique:catalogs',
            'price' => 'required',
            'photo' => 'required',
            'inStock' => 'required'
        ]);

        if($validator->fails()){
            return response()->json($validator->messages(), 400);
        }

        $catalog = Catalog::create($request->only([
            'category_id','name','price','image', 'inStock'
        ]));
        $catalog->save();

        $response = Catalog::with('category')->where('id',$catalog->id)->get();
        return response()->json(['data' => $response]);
    }

    public function update(Request $request, $id)
    {
        $validator = Validator::make($request->all(), [
            'category_id' => 'required',
            'name' => 'required|unique:catalogs,name',
            'price' => 'required',
            'photo' => 'required',
            'inStock' => 'required',
        ]);
        if($validator->fails()){
            return response()->json($validator->messages(), 400);
        }
        $catalog = Catalog::find($id);
        $catalog->category_id = $request->category_id;
        $catalog->price = $request->price;
        $catalog->photo = $request->image;
        $catalog->inStock = $request->inStock;

        $catalog->save();
        return response()->json(['data' => $catalog, 'message' => 'Catalog Item Updated!']);
    }

    public function destroy($id)
    {
        $catalog = Catalog::find($id);
        $catalog->delete();

        return response()->json('Catalog Item Removed!');
    }
}
