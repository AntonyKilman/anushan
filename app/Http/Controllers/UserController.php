<?php

namespace App\Http\Controllers;
use App\Models\Permision;
use App\Models\AccessPoint;
use App\Models\Employee;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Contracts\Session\Session;


class UserController extends Controller
{
    
    public function index()
    {
        //collection of users

        $user_list = DB::table('users')
                    ->select('users.id as user_id', 'user_roles.user_role','employees.*','departments.name')
                    ->leftJoin('employees', 'employees.id', 'users.emp_id')
                    ->leftJoin('departments','departments.id','=','employees.department_id')
                    ->leftJoin('user_roles','user_roles.id','=','employees.role_id')
                    ->get();

    
        $employee_list = DB::table('employees')
                        ->select('employees.id', 'employees.f_name', 'employees.emp_code', 'departments.name as department_name', 'employees.email')
                        ->leftJoin('departments', 'departments.id', 'employees.department_id')
                        ->whereNotExists(function ($query){
                            $query->select(DB::raw(1))
                                ->from('users')
                                ->whereColumn('users.emp_id', 'employees.id');
                        })
                        ->where('employees.status', 'Active')
                        ->where('employees.email','!=', null)
                        ->get();

        return view('Auth.Register')->with('user_list',$user_list)->with('employee_list',$employee_list);
    }

    public function home()
    {
        return view('pages.Home')
        ->with('customer',DB::table('customers')->select('customers.id')->get())
        ->with('main_events',DB::table('main_events')
        ->select('main_events.id')
        ->get())
        ->with('orders',DB::table('main_orders')
        ->select('main_orders.id','main_orders.total_amount','main_orders.event_status','main_orders.payment_status')
        ->get());

    }

    
    public function resetIndex()
    {
        //
        return view('Auth.Reset');
    }

    public function resetStore(Request $request)
    {
        //
        $request->validate([
            "new_password"=>"required|min:5|max:12"
         ]);


        $pss=Auth::user()->password;
        //check old password

        if(Hash::check($request->input('old_password'),$pss)){

            //check conform password
            if($request->input('new_password')==$request->input('conform_password')){

                $data =User::find(Auth::user()->id);
                $data->password =Hash::make($request->input('new_password'));
                $data->save();

                Auth::logout();
                return redirect('/admin');

            }else{
                return view('Auth.Reset')->with('msg','The password and confirmation password do not match!!!');
            }

        }else{
            return view('Auth.Reset')->with('msg','Invalid old passwrd!!!');
        }

    }

    public function store(Request $request)
    {

        $request->validate([
            "emp_id"=>"required|unique:users",
            "password"=>"required|min:5|max:12"
         ]);

            $data = new User();
            $data->emp_id = $request->input('emp_id');
            $data->login_token=null;
            $data->password =Hash::make($request->input('password'));
            $data->save();

        return redirect()->route('user.index')->with('msg','Successfully created!!!');
    }

    public function login(Request $request)
    {
         //validate the details
         $request->validate([
            "email"=>"required|email",
            "password"=>"required|min:5|max:12"
         ]);


        $user=DB::table('employees')
        ->select('employees.*','users.password')
        ->join('users','users.emp_id','=','employees.id')
        ->where('employees.email','=',$request->input('email'))
        ->first();

        // if user
        if($user){
            //check entered password
            if(Hash::check($request->input('password'), $user->password)){

            // if success get user details
            $data = User::Where('emp_id',$user->id)->first();


            // here auth login
            Auth::login($data);

            //get permission for route access
            $permisions= Permision::Where('user_role_id',$user->role_id)->first();
            if ($permisions) {  // check this role have permisions
                $permis=json_decode($permisions->permision);
                $access=array();

                foreach ($permis as $accessPointId) {
                    $value = AccessPoint::where('id', $accessPointId)->value('value');
                    if ($value) {
                        array_push($access, $value);
                    }
                }
            } else {
                $access=array();
            }

            $request->session()->put('Access',$access );
            $a = app('App\Http\Controllers\LoginLogController')->store("Log in");
            return redirect()->route('user.home');


            }else{
                //invalid user
                return view('Auth.Login')->with('fail','Invalid username  or Password!');
            }

       }else{
           // not register this email
           return view('Auth.Login')->with('fail','No account found in this email');
       }


    }


    public function token(Request $request)
    {
        //here check it token base login
        if(isset($request->token)){
        //here check token
        $token=$request->token;

        $tokenParts = explode(".", $token);
        $tokenHeader = base64_decode($tokenParts[0]);
        $tokenPayload = base64_decode($tokenParts[1]);
        $jwtHeader = json_decode($tokenHeader);
        $jwtPayload = json_decode($tokenPayload);
        $valid= $jwtPayload->exp; // this one login expery

        $currentTime =\Carbon\Carbon::now();

        if($valid>$currentTime->timestamp){
            //get login details
           // return "";
            $user = User::where('login_token', $request->token)->first();
            if($user){

            Auth::login($user);
            $emp=Employee::where('id', Auth::user()->emp_id)->first();

            $permisions= Permision::Where('user_role_id',$emp->role_id)->first();
            $accessPoint=AccessPoint::all();

            $permis=json_decode($permisions->permision);
            $count=count($permis);
            $access=array();
            $x=0;
            for($i=0; $i<$count; $i++){
                foreach($accessPoint as $row){
                    if($permis[$i]==$row->id){
                          $access[$x]=$row->value;
                          $x++;
                          break;
                    }

                }

            }
                $request->session()->put('Access',$access);
                $a = app('App\Http\Controllers\LoginLogController')->store("Log in With Token");
                return redirect()->route('user.home');

            }else{
                // "wrong token";
                return view('Auth.Login');//login page
            }

        }else{
            //"tokenexpire";
            return view('Auth.Login');//login page
        }


        }else{
            //direct login gate
            //return "token not founded";
            return view('Auth.Login');//login page
        }


    }


    public  function logout()
    {
        $a = app('App\Http\Controllers\LoginLogController')->store("Log Out");
        Auth::logout();
        return redirect('/admin');

    }
    
    public function show($id)
    {
        //
    }

    public function destroy(Request $request)
    {
        $user_id  = $request->delete_id;
        //delete user
        $data = User::find($user_id);
        $data->delete();
    
        return redirect()->route('user.index')->with('msg','Record has been removed!!!');

    }
}
