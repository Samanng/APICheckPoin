<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use App\Http\Requests;

use App\Http\Controllers\Controller;

use Illuminate\Support\Facades\Input;

use DB;

use App\Students;
class StudentsController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
       $get_student = Students::all();

       if($get_student == true){
           return response()->json($get_student);
       }else{
           echo "You data don't have any record!";
       }
    }
    public function search(Request $request){
        $username = $request->input('username');
        $address = $request->input('address');
        $status = $request->input('status');
        $result = DB::table('students')->select('*')->where([
            ['username','=',$username],
            ['address','=',$address],
            ['status','=',$status],
        ])->get();
        return response()->json($result);
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

        ///set all field are required
        $validator = Validator::make($request->all(), [
            'username' => 'required',
            'email'    => 'required|email|unique:students',
            'phone' => 'required',
            'address'    => 'required',
            'status' => 'required',
            'password'    => 'required',
        ]);


        // I want to customize message error
//        $messages = [
//            'required' => 'The :attribute field is required.',
//        ];
//        $validator = Validator::make($request->all(),$messages);

        //if validation = false show message error
        if($validator->fails()){
            return response(array(
                'error' => false,
                'message'=>'Cannot Insert!! Something was wrong.',
            ));
        }else{
            Students::create($request->all());
            return response(array(
                'error' => false,
                'student' => 'You are success register the new record.',
            ));
        }
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        $show_student = Students::find($id);
        return response()->json($show_student);
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        //
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
        $validator = Validator::make($request->all(), [
            'username' => 'required',
            'email'    => 'required|email',
            'phone' => 'required',
            'address'    => 'required',
            'status' => 'required',
            'password'    => 'required',
        ]);
        if($validator->fails()){
            return response(array(
                'error' => false,
                'message'=>'Cannot Insert!! Something was wrong.',
            ));
        }else{

            $update_student = Students::find($id)->update($request->all()); //update student
            $update_student = Students::find($id); // display student after update
            return response()->json($update_student);
        }

    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        $delete_student = Students::find($id)->delete();
        return response(array(
            'error'=>false,
            'message'=> 'You successful delete the record.'
        ));
    }


    public function login(Request $request){
        $email_student = $request->email;
        $password = $request->password;
        $result = DB::table('students')->select('*')->where([
            ['email','=',$email_student],
            ['password','=',$password]
        ])->get();
        return response()->json($result);
    }
}
