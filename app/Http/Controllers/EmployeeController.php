<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Employee;
use App\Models\Company;
use DataTables;
use Validator;

class EmployeeController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
        $companies = Company::all();
        if ($request->ajax()) {
            $data = Employee::select('*');
            return Datatables::of($data)
                    ->addIndexColumn()
                    ->addColumn('actions', function($row){

                    $btn= '<a href="javascript:void(0)" data-toggle="tooltip" data-id="'.$row->id.'" data-original-title-="Edit" class="edit btn btn-primary editEmployee mr-2" id="editEmployee">Edit</a>';

                    $btn.= '<a href="javascript:void(0)" data-toggle="tooltip" data-id="'.$row->id.'" data-original-title-="Delete" class="edit btn btn-danger deleteEmployee" id="deleteEmployee">Delete</a>';    
                            
                    return $btn;
                    })
                    ->rawColumns(['actions'])
                    ->make(true);
        }
        
        return view('admin.employee.index', compact('companies'));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $validator = Validator::make($request->all(),[
            'fname'         => 'required',
            'lname'         => 'required',
            'email'         => 'required|email',
            'phone'         => 'required|regex:/[0-9]/|digits:10',     
            'company'       => 'required'
        ]);
        if (!$validator->passes()) {
            return response()->json(['error'=>$validator->errors()->all()]);
        }
            
        $employeeId = $request->employee_id;
     
        $details = [
            'fname'         => $request->fname,
            'lname'         => $request->lname,
            'email'         => $request->email,
            'phone'         => $request->phone,
            'company_id'    => $request->company,
        ];
         
        $employee = Employee::updateOrCreate(['id' => $employeeId], $details);  
        
        return response()->json(['success'=>'Employee saved successfully.']);
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
        $employee = Employee::find($id);
        return response()->json($employee);
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
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        Employee::find($id)->delete();

    }
}
