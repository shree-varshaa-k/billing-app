<?php
namespace App\Http\Controllers;

use App\Models\Category;
use Illuminate\Http\Request;

class CategoryController extends Controller
{
    public function index()
    {
        $categories = Category::orderBy('id','DESC')->get();
        return view('products.category', compact('categories'));
    }

    public function store(Request $request)
    {
        $request->validate(['name' => 'required|unique:categories,name']);
        Category::create(['name' => $request->name]);

        return back()->with('success', 'Category Added Successfully');
    }
    public function update(Request $request, $id)
{
    $request->validate([
        'name' => 'required|unique:categories,name,' . $id
    ]);

    $category = Category::findOrFail($id);
    $category->update(['name' => $request->name]);

    return back()->with('success', 'Category Updated Successfully');
}


    public function destroy($id)
    {
        Category::findOrFail($id)->delete();
        return back()->with('success', 'Category Deleted Successfully');
    }
}
