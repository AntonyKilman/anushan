<?php

namespace App\Http\Controllers;

use App\Models\LoginAccess;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class LoginAccessController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        //
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
            $user=Auth::user()->id;
            $url=$request->input('url');
            
            $currentTime = \Carbon\Carbon::now();
            // Create token header as a JSON string
            $header = json_encode(['alg' => 'HS512']);
            // Create token payload as a JSON string
            $payload = json_encode(['login_user' => $user, "iat"  =>  $currentTime->timestamp , "exp"  => $currentTime->addMinutes(5)->timestamp]);
            // Encode Header to Base64Url String
            $base64UrlHeader = str_replace(['+', '/', '='], ['-', '_', ''], base64_encode(utf8_encode($header)));
            // Encode Payload to Base64Url String
            $base64UrlPayload = str_replace(['+', '/', '='], ['-', '_', ''], base64_encode(utf8_encode($payload)));
            // Create Signature Hash
            $signature = hash_hmac('sha512', $base64UrlHeader . "." . $base64UrlPayload, base64_decode('codevita'), true);
            // Encode Signature to Base64Url String
            $base64UrlSignature = str_replace(['+', '/', '='], ['-', '_', ''], base64_encode($signature));
            // Create JWT
            $jwt = $base64UrlHeader . "." . $base64UrlPayload . "." . $base64UrlSignature;
   
         //update valide token for user
         $data =User::find($user);
         $data->login_token=$jwt;
         $data->save();
         $url=$url."?token=".$jwt;
         return redirect($url);  
         //->route($url,['token'=>$jwt])

    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Models\LoginAccess  $loginAccess
     * @return \Illuminate\Http\Response
     */
    public function show(LoginAccess $loginAccess)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Models\LoginAccess  $loginAccess
     * @return \Illuminate\Http\Response
     */
    public function edit(LoginAccess $loginAccess)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\LoginAccess  $loginAccess
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, LoginAccess $loginAccess)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\LoginAccess  $loginAccess
     * @return \Illuminate\Http\Response
     */
    public function destroy(LoginAccess $loginAccess)
    {
        //
    }
}
