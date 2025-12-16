<?php

namespace App\Http\Controllers;

use App\Models\Category;
use App\Models\SubCategory;
use Illuminate\Http\Request;

class SubCategoryController extends Controller
{
    public function index()
    {
        $categories = Category::all();
        $subCategories = SubCategory::with('category')->orderBy('id', 'DESC')->get();

        return view('products.subcategory', compact('categories','subCategories'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'category_id' => 'required',
            'name' => 'required'
        ]);

        SubCategory::create($request->all());

        return back()->with('success', 'Sub Category Added Successfully');
    }

    public function update(Request $request, $id)
    {
        $request->validate([
            'category_id' => 'required',
            'name' => 'required'
        ]);

        $subCategory = SubCategory::findOrFail($id);
        $subCategory->update($request->all());

        return back()->with('success', 'Sub Category Updated Successfully');
    }

    public function destroy($id)
    {
        SubCategory::findOrFail($id)->delete();
        return back()->with('success', 'Sub Category Deleted');
    }
}
