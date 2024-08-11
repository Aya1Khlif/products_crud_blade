<?php

namespace App\Http\Controllers;

use App\Models\Product; // Correcting from Products to Product
use App\Models\Products;
use Illuminate\Http\Request;

class ProductsController extends Controller
{
    public function index(Request $request)
    {
        $keyword = $request->get('search');
        $perPage = 5;

        if (!empty($keyword)) {
            $products = Products::where(function ($query) use ($keyword) {
                $query->where('name', 'LIKE', "%$keyword%")
                    ->orWhere('category', 'LIKE', "%$keyword%"); // Added missing '%' for category
            })
                ->latest()
                ->paginate($perPage);
        } else {
            $products = Products::latest()->paginate($perPage);
        }

        // Pass the retrieved products to the view
        return view('products.index', ['products' => $products])->with('i', request()->input('page'));
    }
    public function create()
    {
        return view('products.create');
    }

    public function store(Request $request)
    {
        // Validate incoming request to ensure all required fields are present
        $validatedData = $request->validate([
            'name' => 'required|string|max:255',
            'description' => 'required|string',
            'category' => 'required|string|max:255',
            'quantity' => 'required|integer|min:0',
            'price' => 'required|numeric|min:0',
            'image' => 'required|image|mimes:jpeg,png,jpg,gif|max:2048',
        ]);

        // Getting the original image file name for storage
        $fileName = time() . '.' . $request->image->getClientOriginalExtension();

        // Move image to the storage path
        $request->image->move(public_path('images'), $fileName);
        $product = new Products;
        $product->name = $validatedData['name'];
        $product->description = $validatedData['description'];
        $product->category = $validatedData['category'];
        $product->quantity = $validatedData['quantity'];
        $product->price = $validatedData['price'];
        $product->image = $fileName;
        $product->save();
        return redirect()->route('product.index')->with('success', 'PRODUCT ADDED SUCCESSFULLY');
    }
    public function edit($id)
    {
        $product = Products::findOrFail($id);
        return view('products.edit', ['product' => $product]);
    }
    public function update(Request $request)
    {
        // Validate the incoming request
        $validatedData = $request->validate([
            'name' => 'required',
            'description' => 'nullable|string',
            'category' => 'required',
            'quantity' => 'required|integer|min:1',
            'price' => 'required|numeric|min:0',
        ]);

        // Find the existing product using hidden ID
        $product = Products::find($request->hidden_id);

        // Update product fields
        $product->name = $validatedData['name'];
        $product->description = $validatedData['description'];
        $product->category = $validatedData['category'];
        $product->quantity = $validatedData['quantity'];
        $product->price = $validatedData['price'];

        // Handle image upload
        if ($request->hasFile('image')) {
            $fileName = time() . '.' . $request->image->getClientOriginalExtension();
            $request->image->move(public_path('images'), $fileName);
            $product->image = $fileName; // Save the image name if a new image is uploaded
        }

        // Save the product update
        $product->save();

        // Redirect with success message
        return redirect()->route('product.index')->with('success', 'Product updated successfully.');
    }
    public function destroy($id)
    {
        // Retrieve the product or fail if not found
        $product = Products::findOrFail($id);

        // Construct the image path
        $image_path = public_path("images/"); // Ensure the path ends correctly
        $image = $image_path . $product->image;

        // Check if the image file exists and delete it if it does
        if (file_exists($image)) {
            if (!@unlink($image)) { // Explicit error handling
                // You may want to log this error or handle it as needed
                // Log::error("Unable to delete image: " . $image);
            }
        }

        // Delete the product from the database
        $product->delete();
        // Redirect back to product list with a success message
        return redirect()->route('product.index')->with('success', 'PRODUCT deleted SUCCESSFULLY');
    }
}
