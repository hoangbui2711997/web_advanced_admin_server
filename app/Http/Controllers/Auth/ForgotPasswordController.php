<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\AppBaseController;
use Illuminate\Foundation\Auth\SendsPasswordResetEmails;
use Illuminate\Http\Request;
use App\Utils;
use App\Http\Services\MasterdataService;
use Jenssegers\Agent\Facades\Agent;
use Illuminate\Support\Facades\Password;

class ForgotPasswordController extends AppBaseController
{
    /*
    |--------------------------------------------------------------------------
    | Password Reset Controller
    |--------------------------------------------------------------------------
    |
    | This controller is responsible for handling password reset emails and
    | includes a trait which assists in sending these notifications from
    | your application to your users. Feel free to explore this trait.
    |
    */

    use SendsPasswordResetEmails;

    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        $this->middleware('guest');
    }

    public function showLinkRequestForm(Request $request)
    {
        return view('auth.passwords.email');
    }

    public function sendResetLinkEmailViaApi(Request $request)
    {
        $this->validateEmail($request);

        // We will send the password reset link to this user. Once we have attempted
        // to send the link, we will examine the response then see the message we
        // need to show to the user. Finally, we'll send out a proper response.
        $response = $this->broker()->sendResetLink(
            $request->only('email')
        );

        return $response == Password::RESET_LINK_SENT
                    ? $this->sendResponse([])
                    : $this->sendError('', 400);
    }

    protected function validateEmail(Request $request)
    {
        $this->validate($request, ['email' => 'required|email|verified_email']);
    }
}
