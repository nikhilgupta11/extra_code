<?php

namespace App\Http\Controllers\Auth;

use App\Library\Auth\ResetsPasswords;
use Illuminate\Http\Request;
use Illuminate\Routing\Controller;
use Illuminate\Support\Facades\Password;
use App\Models\User;
use DB;
use Carbon\Carbon;
class ResetPasswordController extends Controller
{
    protected $data = []; // the information we send to the view

    /*
    |--------------------------------------------------------------------------
    | Password Reset Controller
    |--------------------------------------------------------------------------
    |
    | This controller is responsible for handling password reset requests
    | and uses a simple trait to include this behavior. You're free to
    | explore this trait and override any methods you wish to tweak.
    |
    */

    use ResetsPasswords;

    /**
     * Get the path the user should be redirected to after password reset.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return string
     */
    public function redirectTo()
    {
        return backpack_url('logout');
    }

    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        $guard = backpack_guard_name();

        $this->middleware("guest:$guard");

        if (! backpack_users_have_email()) {
            abort(501, trans('backpack::base.no_email_column'));
        }

        // where to redirect after password was reset
        $this->redirectTo = property_exists($this, 'redirectTo') ? $this->redirectTo : backpack_url('dashboard');
    }

    // -------------------------------------------------------
    // Laravel overwrites for loading backpack views
    // -------------------------------------------------------

    /**
     * Display the password reset view for the given token.
     *
     * If no token is present, display the link request form.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  string|null  $token
     * @return \Illuminate\Http\Response
     */
    public function showResetForm(Request $request, $token = null)
    {
        $user = User::where('email',$request->email)->count();
        $tokenTime = DB::table('password_resets')->where('email',$request->email)->first();
        if($tokenTime == null){
            return view('errors.498');
        }
        $t1 = Carbon::parse($tokenTime->created_at);
        $t2 = Carbon::parse(date('Y-m-d H:i:s'));
        $diff = $t1->diff($t2);
        
        if ($user == 0) {
            return static::INVALID_USER;
        }
        $expire = config('auth.passwords.'.config('auth.defaults.passwords').'.expire');        
        $minutes = $diff->i;
        if($minutes > $expire){
            return view('errors.101');
        }
       
        return view(backpack_view('auth.passwords.reset'), $this->data)->with(
            ['token' => $token, 'email' => $request->email]
        );
    }

    /**
     * Get the broker to be used during password reset.
     *
     * @return \Illuminate\Contracts\Auth\PasswordBroker
     */
    public function broker()
    {
        $passwords = config('backpack.base.passwords', config('auth.defaults.passwords'));

        return Password::broker($passwords);
    }

    /**
     * Get the guard to be used during password reset.
     *
     * @return \Illuminate\Contracts\Auth\StatefulGuard
     */
    protected function guard()
    {
        return backpack_auth();
    }
}
