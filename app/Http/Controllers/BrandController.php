<?php

namespace App\Http\Controllers;

use App\Models\Brand;
use Illuminate\Http\Request;

class BrandController extends Controller
{
    public function index()
    {
        $brands = Brand::orderBy('id','DESC')->get();
        return view('products.brand', compact('brands'));
    }

    public function store(Request $request)
    {
        $request->validate(['name' => 'required|unique:brands,name']);
        Brand::create($request->all());
        return back()->with('success','Brand Added');
    }

    public function update(Request $request, $id)
    {
        $request->validate(['name' => 'required|unique:brands,name,' . $id]);
        $brand = Brand::findOrFail($id);
        $brand->update(['name' => $request->name]);
        return back()->with('success','Brand Updated');
    }

    public function destroy($id)
    {
        Brand::findOrFail($id)->delete();
        return back()->with('success','Brand Deleted');
    }
}
