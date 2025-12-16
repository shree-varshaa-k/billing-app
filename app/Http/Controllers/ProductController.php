<?php

namespace App\Http\Controllers;

use Barryvdh\DomPDF\Facade\Pdf;
use App\Models\Product;
use App\Models\InvoiceItem;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;
use Milon\Barcode\DNS1D;
use App\Models\Category;
use App\Models\SubCategory;
use App\Models\Brand;
use App\Models\Size;

class ProductController extends Controller
{
    public function index()
    {
        $products = Product::orderBy('id', 'asc')->get();
        return view('product', compact('products'));
    }

    public function create()
    {
        // Fetch all related data for the form
        $categories    = Category::orderBy('name')->get();
        $subcategories = SubCategory::orderBy('name')->get();
        $brands        = Brand::orderBy('name')->get();
        $sizes         = Size::orderBy('name')->get();

        return view('add_product', compact('categories', 'subcategories', 'brands', 'sizes'));
    }

public function store(Request $request)
{
    $request->validate([
        'name'           => 'required|string|max:255',
        'category'       => 'required',
        'sub_category'   => 'required',
        'brand'          => 'required',
        'size'           => 'required',
        'purchase_price' => 'required|numeric|min:0',
        'price'          => 'required|numeric|min:0',
        'stock'          => 'required|integer|min:0',
    ]);

    // Upload product image
    $imagePath = null;
    if ($request->hasFile('image')) {
        $imagePath = $request->file('image')->store('products', 'public');
    }

    // Generate EAN-13 barcode
    $barcodeNumber = $this->generateEAN13();
    $d = new DNS1D();
    $d->setStorPath(storage_path('framework/barcodes/'));

    $barcodeImage = $d->getBarcodePNG($barcodeNumber, 'EAN13', 3, 100, [0,0,0], true);
    $fileName = $barcodeNumber . '.png';
    $filePath = 'barcodes/' . $fileName;
    Storage::disk('public')->put($filePath, base64_decode($barcodeImage));

    // Convert IDs to names for storage
    $categoryName    = Category::find($request->category)?->name ?? $request->category;
    $subCategoryName = SubCategory::find($request->sub_category)?->name ?? $request->sub_category;
    $brandName       = Brand::find($request->brand)?->name ?? $request->brand;
    $sizeName        = Size::find($request->size)?->name ?? $request->size;

    Product::create([
        'name'           => $request->name,
        'category'       => $categoryName,
        'sub_category'   => $subCategoryName,
        'brand'          => $brandName,
        'size'           => $sizeName,
        'hsn_code'       => $request->hsn_code,
        'purchase_price' => $request->purchase_price,
        'price'          => $request->price,
        'stock'          => $request->stock,
        'min_stock'      => $request->min_stock ?? 5,
        'image'          => $imagePath,
        'barcode'        => $filePath,
        'barcode_number' => $barcodeNumber,
    ]);

    return redirect()->route('products.index')->with('success', 'Product created successfully.');
}

    // -----------------------------------------
    // DOWNLOAD ALL BARCODES (PDF)
    // -----------------------------------------
    public function downloadAllBarcodes()
    {
        $products = Product::orderBy('id', 'asc')->get();

        $pdf = Pdf::loadView('pdf.all_barcodes', compact('products'))
                  ->setPaper('a4', 'portrait');

        return $pdf->download('all_barcodes.pdf');
    }

    // -----------------------------------------
    // NEW: GET STOCK + PRICE (AJAX)
    // -----------------------------------------
    public function getStock($id)
    {
        $product = Product::find($id);

        if (!$product) {
            return response()->json([
                'error' => 'Product not found'
            ], 404);
        }

        return response()->json([
            'stock'  => $product->stock,
            'price'  => $product->price,
            'status' => $product->stock > 0 ? 'in_stock' : 'out_of_stock'
        ]);
    }

    public function edit($id)
    {
        $product = Product::findOrFail($id);
        $isUsedInInvoice = InvoiceItem::where('product_id', $id)->exists();

        return view('edit_product', compact('product', 'isUsedInInvoice'));
    }

  public function update(Request $request, $id)
{
    $request->validate([
        'name'           => 'required|string|max:255',
        'purchase_price' => 'required|numeric|min:0',
        'price'          => 'required|numeric|min:0',
        'stock'          => 'required|integer|min:0',
    ]);

    $product = Product::findOrFail($id);

    if ($request->hasFile('image')) {
        if ($product->image) {
            Storage::disk('public')->delete($product->image);
        }
        $product->image = $request->file('image')->store('products', 'public');
    }

    // Convert IDs to names
    $categoryName    = Category::find($request->category)?->name ?? $request->category;
    $subCategoryName = SubCategory::find($request->sub_category)?->name ?? $request->sub_category;
    $brandName       = Brand::find($request->brand)?->name ?? $request->brand;
    $sizeName        = Size::find($request->size)?->name ?? $request->size;

    $isUsedInInvoice = InvoiceItem::where('product_id', $id)->exists();

    if ($isUsedInInvoice) {
        $product->update([
            'name'         => $request->name,
            'category'     => $categoryName,
            'sub_category' => $subCategoryName,
            'brand'        => $brandName,
            'size'         => $sizeName,
            'hsn_code'     => $request->hsn_code,
            'stock'        => $request->stock,
            'min_stock'    => $request->min_stock,
            'image'        => $product->image,
        ]);
    } else {
        $product->update([
            'name'           => $request->name,
            'category'       => $categoryName,
            'sub_category'   => $subCategoryName,
            'brand'          => $brandName,
            'size'           => $sizeName,
            'hsn_code'       => $request->hsn_code,
            'purchase_price' => $request->purchase_price,
            'price'          => $request->price,
            'stock'          => $request->stock,
            'min_stock'      => $request->min_stock,
            'image'          => $product->image,
        ]);
    }

    return redirect()->route('products.index')->with('success', 'Product updated successfully!');
}
    public function destroy($id)
    {
        $product = Product::findOrFail($id);

        $isUsedInInvoice = InvoiceItem::where('product_id', $id)->exists();
        if ($isUsedInInvoice) {
            return redirect()->route('products.index')
                ->with('error', 'This product is linked to an invoice and cannot be deleted.');
        }

        if ($product->image) {
            Storage::disk('public')->delete($product->image);
        }

        $product->delete();

        $nextId = (Product::max('id') ?? 0) + 1;
        DB::statement("ALTER TABLE products AUTO_INCREMENT = " . $nextId);

        return redirect()->route('products.index')
            ->with('success', 'Product deleted successfully!');
    }

    private function generateEAN13()
    {
        $numbers = '';

        for ($i = 0; $i < 12; $i++) {
            $numbers .= rand(0, 9);
        }

        $sum = 0;

        for ($i = 0; $i < 12; $i++) {
            $digit = (int) $numbers[$i];

            $sum += ($i % 2 === 0) ? $digit : $digit * 3;
        }

        $checkDigit = (10 - ($sum % 10)) % 10;

        return $numbers . $checkDigit;
    }


}

