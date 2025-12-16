<?php

namespace App\Http\Controllers;

use App\Models\Size;
use Illuminate\Http\Request;

class SizeController extends Controller
{
    public function index()
    {
        $sizes = Size::orderBy('id','DESC')->get();
        return view('products.size', compact('sizes'));
    }

    public function store(Request $request)
    {
        $request->validate(['name' => 'required|unique:sizes,name']);
        Size::create($request->all());
        return back()->with('success','Size Added');
    }

    public function update(Request $request, $id)
    {
        $request->validate(['name' => 'required|unique:sizes,name,' . $id]);
        $size = Size::findOrFail($id);
        $size->update(['name' => $request->name]);
        return back()->with('success','Size Updated');
    }

    public function destroy($id)
    {
        Size::findOrFail($id)->delete();
        return back()->with('success','Size Deleted');
    }
}
