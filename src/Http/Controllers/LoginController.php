<?php

namespace Campusdigital\CampusCMS\Http\Controllers;

use Illuminate\Foundation\Auth\AuthenticatesUsers;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Validator;
use Illuminate\Validation\ValidationException;
use App\Models\User;
use Campusdigital\CampusCMS\Models\Visitor;

class LoginController extends Controller
{
    /*
    |--------------------------------------------------------------------------
    | FaturCMS Login Controller
    |--------------------------------------------------------------------------
    |
    | This controller handles authenticating users for the application and
    | redirecting them to your home screen. The controller uses a trait
    | to conveniently provide its functionality to your applications.
    |
    */

    use AuthenticatesUsers;

    /**
     * Show the application's login form.
     *
     * @return \Illuminate\Http\Request
     * @return \Illuminate\Http\Response
     */
    public function showLoginForm(Request $request)
    {
        // Referal
        referral($request->query('ref'), 'auth.login');

        // Get URLs
        $urlPrevious = url()->previous();
        $urlBase = url()->to('/');
        
        // If admin came from login, remove session url.intended
        if((session()->get('url.intended') == '/admin/logout') || (session()->get('url.intended') == '/member/logout')){
        // if((session()->get('url.intended') == '/logout')){
            session()->forget('url.intended');
        }
        // Set the previous url that we came from to redirect to after successful login but only if is internal
        elseif(($urlPrevious != $urlBase . '/login') && (substr($urlPrevious, 0, strlen($urlBase)) === $urlBase) && ((substr($urlPrevious, strlen($urlBase), 6) == '/admin') || (substr($urlPrevious, strlen($urlBase), 7) == '/member'))) {
            session()->put('url.intended', $urlPrevious);

            // View
            return view('auth.'.setting('site.view.login'), ['message' => 'Anda harus login terlebih dahulu!']);
        }

        // View
        return view('auth.'.setting('site.view.login'));
    }

    /**
     * Get the login username to be used by the controller.
     *
     * @return string
     */
    public function username()
    {
        return 'username';
    }

    /**
     * Handle a login request to the application.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\RedirectResponse|\Illuminate\Http\Response|\Illuminate\Http\JsonResponse
     *
     * @throws \Illuminate\Validation\ValidationException
     */
    public function login(Request $request)
    {
        $validator = Validator::make([
            'username' => 'required|string|min:4',
            'password' => 'required|string|min:4',
        ], array_validation_messages());
		
		$loginType = filter_var($request->username, FILTER_VALIDATE_EMAIL) ? 'email' : 'username';
		
		$login = [
			$loginType => $request->username,
			'password' => $request->password
		];
		
		if(auth()->attempt($login)){
			return $this->sendLoginResponse($request);
		}

        log_login($request);
		
		$this->incrementLoginAttempts($request);

        return $this->sendFailedLoginResponse($request);
    }

    /**
     * Get the failed login response instance.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Symfony\Component\HttpFoundation\Response
     *
     * @throws \Illuminate\Validation\ValidationException
     */
    protected function sendFailedLoginResponse(Request $request)
    {
        throw ValidationException::withMessages([
            $this->username() => 'Tidak ada akun yang terdaftar sesuai username dan password yang dimasukkan!',
        ]);
    }

    /**
     * The user has been authenticated.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  mixed  $user
     * @return mixed
     */
    protected function authenticated(Request $request, $user)
    {
        // Update last visit
        $account = User::find($user->id_user);
        $account->last_visit = date('Y-m-d H:i:s');
        $account->save();
        
        // Buat data visitor
        $visitor = new Visitor;
        $visitor->id_user = $user->id_user;
        $visitor->ip_address = $request->ip();
        $visitor->visit_at = $account->last_visit;
        $visitor->device = device_info();
        $visitor->browser = browser_info();
        $visitor->platform = platform_info();
        $visitor->location = location_info($request->ip());
        $visitor->save();

        // Redirect to URL intended
        if(session()->get('url.intended') != null){
            return redirect()->intended();
        }

        // Redirect after login
        if($user->is_admin == 1){
            return redirect()->route('admin.dashboard');
        }
        elseif($user->is_admin == 0){
            return redirect()->route('member.dashboard');
        }
    }
}
