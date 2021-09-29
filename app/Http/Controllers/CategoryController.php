<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Models\Category;
use DataTables;

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
        $categories = Category::get();
    
        if($request->ajax()){
            $allData = DataTables::of($categories)
            ->addIndexColumn()
            ->addColumn('action', function($row){
                if(Auth()->user()->role_id == 2 && Auth::user()->id == $row->user_id){
                    $btn = '<a href="javascript:void(0)" data-toggle="tooltip" data-id="'.$row->id.'" data-original-title-="Edit" class="edit btn btn-primary mr-2 editCategories" id="editCategories">Edit</a>';
                    $btn.= '<a href="javascript:void(0)" data-toggle="tooltip" data-id="'.$row->id.'" data-original-title-="Delete" class="edit btn btn-danger deleteCategories" id="deleteCategories">Delete</a>';
                return $btn;
                }
                if(Auth()->user()->role_id == 1){
                    $btn = '<a href="javascript:void(0)" data-toggle="tooltip" data-id="'.$row->id.'" data-original-title-="Edit" class="edit btn btn-primary mr-2 editCategories" id="editCategories">Edit</a>';
                    $btn.= '<a href="javascript:void(0)" data-toggle="tooltip" data-id="'.$row->id.'" data-original-title-="Delete" class="edit btn btn-danger deleteCategories" id="deleteCategories">Delete</a>';
                return $btn;
                }
            })
            ->rawColumns(['action'])
            ->make(true);
            return $allData;
        }
        // return response()->json(['success'=>'Category Added Sucessfully']);
        return view('admin.category.index', compact('categories'));    
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
      request()->validate([
            'name' => 'required',
            'icon' => 'image|mimes:jpeg,png,jpg,gif,svg',
        ]);
         
            $categoryId = $request->category_id;
         
            $details = ['name' => $request->name, 'user_id' => Auth::user()->id];
         
            if ($files = $request->file('icon')) {
                
               //delete old file
             
               //insert new file
               $destinationPath = 'public/images/'; // upload path
               $profileImage = date('YmdHis') . "." . $files->getClientOriginalExtension();
               $files->move($destinationPath, $profileImage);
               $details['icon'] = "$profileImage";
            }
             
            $product   =   Category::updateOrCreate(['id' => $categoryId], $details);  
        return response()->json(['success'=>'Category Added Sucessfully']);
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
        return response()->json($categories);
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
        Category::find($id)->delete();
        return response()->json(['success'=>'Category deleted successfully.']);
    }
}
