<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Product;
use App\Models\Category;
use DataTables;
use Auth;

class ProductController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {    
        $products = Product::get();
        $categories = Category::all();
        if($request->ajax()){
            $allData = DataTables::of($products)
            ->addIndexColumn()
            ->addColumn('action', function($row){
                if(Auth()->user()->role_id == 2 && Auth::user()->id == $row->user_id){
                    $btn = '<a href="javascript:void(0)" data-toggle="tooltip" data-id="'.$row->id.'" data-original-title-="Edit" class="edit btn btn-primary mr-2 editProducts" id="editProducts">Edit</a>';
                    $btn.= '<a href="javascript:void(0)" data-toggle="tooltip" data-id="'.$row->id.'" data-original-title-="Delete" class="edit btn btn-danger deleteProducts" id="deleteProducts">Delete</a>';
                return $btn;
                }
                if(Auth()->user()->role_id == 1){
                    $btn = '<a href="javascript:void(0)" data-toggle="tooltip" data-id="'.$row->id.'" data-original-title-="Edit" class="edit btn btn-primary mr-2 editProducts" id="editProducts">Edit</a>';
                    $btn.= '<a href="javascript:void(0)" data-toggle="tooltip" data-id="'.$row->id.'" data-original-title-="Delete" class="edit btn btn-danger deleteProducts" id="deleteProducts">Delete</a>';
                return $btn;
                }
            })
            ->rawColumns(['action'])
            ->make(true);
            return $allData;
        }
        return view('admin.product.index', compact('products','categories'));    
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        // dd($request->all());
        
        request()->validate([
            'name' => 'required',
            'description' => 'required',
            'price' => 'required|regex:/^([0-9])$/',
            'image' => 'image|mimes:jpeg,png,jpg,gif,svg',
        ]);
         
            $productId = $request->product_id;
         
            $details = ['name' => $request->name,'description' => $request->description ,'price' => $request->price, 'user_id' => Auth::user()->id];
         
            if ($files = $request->file('image')) {
                
               //delete old file
             
               //insert new file
               $destinationPath = 'public/images/'; // upload path
               $profileImage = date('YmdHis') . "." . $files->getClientOriginalExtension();
               $files->move($destinationPath, $profileImage);
               $details['image'] = "$profileImage";
            }
             
            $product   =   Product::updateOrCreate(['id' => $productId], $details);  
                   
            
        return response()->json(['success'=>'Product Added Sucessfully']);
        // $product->categories()->attach($request->category);
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        $products = Product::all()->where('id', $id);
        return view('admin.product.show', compact('products'));
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        $products = Product::find($id);
        return response()->json($products);
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

    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        Product::find($id)->delete();
        return response()->json(['success'=>'Product deleted successfully.']);
    }
}
