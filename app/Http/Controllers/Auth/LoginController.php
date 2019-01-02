<?php

namespace App\Http\Controllers\Auth;

use App\User;
use App\Http\Controllers\Controller;
use Illuminate\Foundation\Auth\AuthenticatesUsers;
use Illuminate\Http\Request;
// use Tymon\JWTAuth\JWTAuth;
use JWTAuth;
// use Illuminate\Support\Facades\Auth;
// use Illuminate\Validation\ValidationException;
use Auth;

class LoginController extends Controller
{
    /*
    |--------------------------------------------------------------------------
    | Login Controller
    |--------------------------------------------------------------------------
    |
    | This controller handles authenticating users for the application and
    | redirecting them to your home screen. The controller uses a trait
    | to conveniently provide its functionality to your applications.
    |
    */

    use AuthenticatesUsers;

    /**
     * Where to redirect users after login.
     *
     * @var string
     */
    protected $redirectTo = '/home';

    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        $this->middleware('guest')->except('logout');
    }

    /**
     * 处理登录认证
     *
     * @return Response
     * @translator laravelacademy.org
     */
    public function login(Request $request)
    {

        $username = $request->input('username');
        $password = $request->input('password');
        $remember = $request->input('remember');

        if( empty($remember)) {  //remember表示是否记住密码
            $remember = 0;
        } else {
            $remember = $request->input('remember');
        }

        $login_data = ['name' => $username,
            'password' => $password,
            'status'   => '1'];

        if ( ! $token = JWTAuth::attempt($login_data)) {

            // return response()->json(['error' => '账号密码错误或者数据过期请刷新后重新登录'], 401);
            return response()->json(
                [
                    'status' => false,
                    'error' => '账号密码错误或者数据过期请刷新后重新登录'
                ]
            );

            /*return response([
                'status' => 'error',
                'error' => 'invalid.credentials',
                'msg' => 'Invalid Credentials.'
            ], 400);*/
        }
        // dd($token);
        return response(['status' => 'success', 'token' => $token])
            ->header('Authorization', $token);

        /*$token = Auth::guard()->attempt($login_data);

        dd($token);

        //如果要使用记住密码的话，需要在数据表里有remember_token字段
        if (Auth::guard()->attempt($login_data)) {  
            
            $user = Auth::user();

            p('e');
            dd($user);
            return response([
                'status' => 'success',
                'data' => $user
            ]);
        }
        dd(lastSql());
        return response([
                'status' => 'fails'
            ]);*/
    }

    public function getUser(Request $request){

        $user = User::find(Auth::user()->id);
        /*p('wo');
        dd($user);*/
        return response([
                'status' => 'success',
                'data' => $user
            ]);
    }

    public function userRefresh(){
        return response([
                'status' => 'success'
            ]);
    }

    /**
     * Handle a login request to the application.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\RedirectResponse|\Illuminate\Http\Response|\Illuminate\Http\JsonResponse
     */
    /*public function login(Request $request)
    {
        // dd($request->all());
        // $this->validateLogin($request);
    
        $username     = $request->input('username');
        $password = $request->input('password');
        $remember = $request->input('remember');
        
        if( empty($remember)) {  //remember表示是否记住密码
            $remember = 0;
        } else {
            $remember = $request->input('remember');
        }

        //如果要使用记住密码的话，需要在数据表里有remember_token字段
        if (Auth::guard()->attempt(['username' => $username, 'password' => $password])) {  
            dd('hehe');
            // $user = User::find(Auth::user()->id);
            return response([
                'status' => 'success',
                'data' => $user
            ]);
        }
        dd(lastSql());
        return response([
                'status' => 'fails'
            ]);
    }*/
    /*
     * Send the response after the user was authenticated.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\JsonResponse
     
    protected function sendLoginResponse(Request $request)
    {
        $this->clearLoginAttempts($request);
        $token = (string)$this->guard()->getToken();
        $expiration = $this->guard()->getPayload()->get('exp');
        // $user = $this->guard()->user();
        // $user->load('avatar');

        return $this->result(true, [
            'token' => $token,
            'token_type' => 'bearer',
            'expires_in' => $expiration - time(),
            'user' => $this->guard()->user()
        ]);
    }
    /**
     * Handle a login request to the application.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\RedirectResponse|\Illuminate\Http\Response|\Illuminate\Http\JsonResponse
     
    /*protected function attemptLogin(Request $request)
    {
        $token = $this->guard()->attempt(
            $this->credentials($request)
        );
        
        if ($token) {
            $this->guard()->setToken($token);
            return true;
        }
        return false;
    }*/
}
