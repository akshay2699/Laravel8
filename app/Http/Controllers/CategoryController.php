<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Models\Category;

class CategoryController extends Controller
{
    /**
     * All of the current user's projects.
     */
    protected $projects;

    /**
     * Create a new controller instance.
     *
     * @return void
     */

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {    
        if(\Auth::check()){
            $categories = Category::all();
            return view('admin.category.index', compact('categories'));    
        }
        else{
            return redirect()->route('login');
        }
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        return view('admin.category.create');
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
            'icon' => 'required',
        ]);
  
        if ($request->hasFile('icon')) {
            $image = $request->file('icon');
            $path = public_path('images/');
            $name = time().rand(1, 99999) . "." . $image->getClientOriginalExtension();
            $image->move($path, $name);
            // dd($name);
        }

        $category = new Category;
        $category->name = $request->name;
        $category->icon = isset($name) ? $name : "";
        $category->user_id = Auth::user()->id;
        // $category->product_id = 1;
        $category->save();
        // $category->products()->attach($request->product_id);;
        return redirect()->route('category.index');
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
        $categories = Category::find($id);
        return view('admin.category.edit', compact('categories'));
        // echo "<pre>";
        // print_r($category->toArray());
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
        $categories = Category::find($id)->first();
        
        // if($categories->icon != '')
        // {
        //     $path = public_path('images/');
        //     if($categories->icon != ''  && $categories->icon != null){
        //        $file_old = $path.$categories->icon;
        //        unlink($file_old);
        //     }
        // }
  
        if ($request->hasFile('icon')) {
            $image = $request->file('icon');
            $path = public_path('images/');
            $name = time().rand(1, 99999) . "." . $image->getClientOriginalExtension();
            $image->move($path, $name);
            // dd($name);
        }
        $categories = Category::where('id', $id)->first();
        $categories->name = $request->name;
        $categories->icon = isset($name) ? $name : "";
        $categories->save();
        return redirect()->route('category.index');
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        $category = Category::find($id);

        $category->delete();
        return redirect()->route('category.index');   
    }
}
