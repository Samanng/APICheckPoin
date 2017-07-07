<?php

namespace App\Http\Controllers;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Illuminate\Foundation\Validation;
use App\Http\Requests;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Input;
use DB;
use Rule;
use App\Students;
use Illuminate\Support\Facades\Crypt;
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
        if($result == true){
            return response()->json($result);
        }else{
           return response(array(
                'message'=> "Sorry, don't have any record that match with this. Please try again."
            ));
        }
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
    public function register(Request $request)
    {
        ///set all field are required
        $validator = Validator::make($request->all(), [
            'username' => 'required',
            'email'    => 'required|email|unique:students',
            'phone' => 'required|numeric',
            'address'    => 'required',
            'status' => 'required|numeric',
            'password'    => 'required|min:6',
        ]);

        //if validation = false show message error
        if($validator->fails()){
            return response()->json(['errors'=>$validator->errors()]);
        }else{
            //add student
            $student = new Students;
            $student->password = sha1($request->input('password')); //encrypt password
            $student->username = $request->input('username');
            $student->email = $request->input('email');
            $student->phone = $request->input('phone');
            $student->address = $request->input('address');
            $student->status = $request->input('status');
            $student->save();

            //response message success
            return response(array(
                'student' => 'You are success register the new record.',
            ));
            return responce()->json($validator);
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
        if($show_student == true){
            return response()->json($show_student);
        }else{
            return response(array(
                'message'=> "Don't have any record."
            ));
        }


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
        $get_id_student = Students::find($id);
        $validator = Validator::make($request->all(), [
            'username' => 'required|alpha',
            'email'    => 'required|email|unique:students,email,'.$id,
            'phone' => 'required|numeric',
            'address'    => 'required',
            'status' => 'required|numeric',
            'password'    => 'required|min:6',
        ]);
        if($validator->fails()){
            return response()->json(['errors'=>$validator->errors()]);
        }else if($get_id_student){ // if id from input = id in database
            $update_student = Students::find($id); //get id of student
            $update_student->password = sha1($request->input('password'));//encrypt password
            $update_student->username = $request->input('username');
            $update_student->email = $request->input('email');
            $update_student->phone = $request->input('phone');
            $update_student->address = $request->input('address');
            $update_student->status = $request->input('status');
            $update_student->save();

            $update_student = Students::find($id); // display student after update
            return response()->json($update_student);
        }else{
            return response(array(
                'message'=> 'Update failed. Please check ID again.'
            ));
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
        $delete_student = Students::find($id);
        if($delete_student){
            Students::find($id)->delete();
            return response(array(
                'message'=> 'You successful delete the record.'
            ));
        }else{
            return response(array(
                'message'=> 'Delete failed. Please check ID again.'
            ));
        }
    }

    //function login
    public function login(Request $request){
        $validator = Validator::make($request->all(), [
            'email' => 'required|email',
            'password' => 'required',
        ]);
        if($validator->fails()){
            return response()->json(['errors'=>$validator->errors()]);
        }else if($validator == true){
            $email_student = $request->email;
            $password = $request->password;
            $result = DB::table('students')->select('*')->where([
                ['email','=',$email_student],
                ['password','=',sha1($password)]
            ])->get();
            if($result == true){
                return response()->json($result);
            }else{
            return response(array(
                'message'=> "Your login failed. Please check your email and password again."
            ));
            }
        }
    }
}