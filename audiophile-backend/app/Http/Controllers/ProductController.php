<?php

namespace App\Http\Controllers;

use App\Models\Product;
use Illuminate\Http\Request;
use  Illuminate\Support\Facades\Storage;


class ProductController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
       $products = Product::with('images')->latest()->get();
       return response()->json($products);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $data = $request->validate([
            'is_new' => 'boolean',
            'category' => 'required|string|max:255',
            'name' => 'required|string|max:255',
            'slug' => 'required|string|max:255|unique:products,slug',
            'image' => 'nullable|image|max:2048',
            'description' => 'required|string',
            'price' => 'required|numeric|min:0',
            'box_content' => 'required|string',
            'detailed_description' => 'required|string',
            'stock' => 'required|numeric|min:0'
        ]);

        if($request->hasFile('image')){
            $file = $request->file('image');
            $filename = time() . '_' . $file->getClientOriginalName();
            $path = $file->storeAs('products', $filename, 'public');
            $data['image'] = $path;    
        }

        $product = Product::create($data);
        $product->image_url = asset('storage/' . $product->image);
        
        return response()->json([
            'message' => 'produto criado com sucesso!', 
            'product' => $product
        ], 201);
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        $product = Product::with('images')->find($id);
        if(!$product) {
            return response()->json([
                'message' => 'Produto não encontrado'
            ], 404);
        }

        return response()->json($product);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        $product = Product::find($id);
        if(!$product){
            return response()->json([
                'message' => 'Produto não encontrado'
            ], 404);
        }

        $data = $request->validate([
            'is_new' => 'boolean',
            'category' => 'required|string|max:255',
            'name' => 'required|string|max:255',
            'slug' => 'required|string|max:255|unique:products,slug',
            'image' => 'required|image|max:2048',
            'description' => 'required|string',
            'price' => 'required|numeric|min:0',
            'box_content' => 'required|string',
            'detailed_description' => 'required|string',
            'stock' => 'required|numeric|min:0'
        ]);
        
        if($request->hasFile('image')){
            if($product->image){
                Storage::disk('public')->delete($product->image);
            }
            $data['image'] = $request->file('image')->store('products', 'public');
        }
        
        $product->update($data);

        return response()->json([
            'message' => 'Produto Atualizado com sucesso!',
            'product' => $product
        ], 201);
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy($id)
    {
        $product = Product::find($id);
        
        if(!$product) {
            return response()->json([
                'message' => 'Produto não encontrado'
            ], 404);
        }

        if($product->image) {
            Storage::disk('public')->delete($product->image);
        }

        $product->delete();

        return response()->json([
            'message' => 'Produto removido com sucesso'
        ], 201);
    }
}
