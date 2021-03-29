<?php

namespace App\Http\Controllers;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use App\Models\User;

class UserController extends Controller
{
  /**
   * Create a new controller instance.
   *
   * @return void
   */
  public function __construct()
  {
      //
  }

  /**
  * Display a listing of the resource.
  *
  * @return \Illuminate\Http\Response
  */
  public function authenticate(Request $request)
  {
    $validateParams = ['email' => 'required','password' => 'required'];

    $this->validate($request, $validateParams);

    $user = User::where('email', $request->input('email'))->first();

    if (Hash::check($request->input('password'), $user->password)) {
      $apikey = base64_encode(str_random(40));
      User::where('email', $request->input('email'))->update(['api_key' => "$apikey"]);;
      return response()->json(['message' => 'Login success','api_key' => $apikey]);
    } else {
      return response()->json(['message' => 'Login failed'],422);
    }

  }

  public function register(Request $request)
  {
    $validateParams = ['email' => 'required','password' => 'required|min:8'];
    $this->validate($request, $validateParams);

    $response = [
      'message' => 'Register Success',
      'status_code'=> 201
    ];
    try {
      $user = new User;
      $user->name = $request->name;
      $user->email = $request->email;
      $user->password = Hash::make($request->password);
      $user->save();
    } catch (\Illuminate\Database\QueryException $ex) {
      $response = [
        'message' => 'Register Failed',
        'status_code'=> 422
      ];
    }

    return response()->json(['message' => $response['message']], $response['status_code']);
  }

}
