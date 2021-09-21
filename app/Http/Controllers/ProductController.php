<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Product;
use App\Models\Category;
// use App\Models\ProductCategory;
use Auth;

class ProductController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        return view('admin.product.index', compact('products'));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        return view('admin.product.create', compact('categories'));
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required',
            'description' => 'required',
            'price' => 'required',
            'image' => 'required',
            'category' => ['required', 'array', 'min:1'],
            // 'categories_id.*' => ['required', 'integer', 'exists:categories,id'],
        ]);
  
        if ($request->hasFile('image')) {
            $image = $request->file('image');
            $path = public_path('images/');
            $name = time().rand(1, 99999) . "." . $image->getClientOriginalExtension();
            $image->move($path, $name);
        }
        
        $product = new Product;
        
        $product->name = $request->name;
        $product->description = $request->description;
        $product->price = $request->price;
        $product->image = isset($name) ? $name : "";
        $product->user_id = Auth::user()->id;

        $product->save();
        $product->categories()->attach($request->category);
        return redirect()->route('product.index');
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        $category_id = Category::select('id','name')->get();
        $products = Product::with('categories')->whereId($id)->first();
        return view('admin.product.edit', compact('products', 'category_id'));
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        $products = Product::find($id)->first();
        
        // if($products->image != '')
        // {
        //     $path = public_path('/images/');
        //     if($products->image != ''  && $products->image != null){
        //        $file_old = $path.$products->image;
        //        unlink($file_old);
        //     }
        // }

        if ($request->hasFile('image')) {
            $image = $request->file('image');
            $path = public_path('images/');
            $name = time().rand(1, 99999) . "." . $image->getClientOriginalExtension();
            $image->move($path, $name);
            // dd($name);
        }

        $products = Product::with('categories')->whereId($id)->first();
        $products->name = $request->name;
        $products->description = $request->description;
        $products->price = $request->price;
        $products->image = isset($name) ? $name : "";

        $products->update();
        $products->categories()->sync($request->category);        
        return redirect()->route('product.index');
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        $product = Product::find($id);

        $product->delete();
        return redirect()->route('product.index'); 
    }
}
