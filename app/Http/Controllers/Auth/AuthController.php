<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Models\Activation;
use App\Models\PasswordRequest;
use App\Models\User;
use App\Repositories\UserRepository;
use App\Traits\ImageTrait;
use App\Traits\SendMailTrait;
use Brian2694\Toastr\Facades\Toastr;
use Exception;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;

class AuthController extends Controller
{
    use ImageTrait, SendMailTrait;

    protected $userRepository;

    public function __construct(UserRepository $userRepository)
    {
        $this->userRepository = $userRepository;
    }

    public function forgotPassword()
    {
        return view('auth.forget_password');
    }

    public function forgot(Request $request)
    {
        $request->validate([
            'email' => ['required', 'string', 'email', 'max:255'],
        ]);

        $user = User::where('email', $request->email)->first();

        if ($user) {
            try {
                $check_user_status = userAvailability($user);

                if (! $check_user_status['status']) {
                    Toastr::error($check_user_status['message']);

                    return back();
                }
                DB::table('password_resets')->where('email', $user->email)->delete();
                $token             = Str::random(64);
                $link              = url('/').'/password/reset/'.$token.'?email='.urlencode($user->email);

                DB::table('password_resets')->insert([
                    'email'      => $request->email,
                    'token'      => $token,
                    'created_at' => Carbon::now(),
                ]);

                // Create a PasswordRequest record (assuming this is a custom model)
                // Not necessary if you're not using it elsewhere
                // PasswordRequest::where('user_id', $user->id)->delete();
                // PasswordRequest::create([
                //     'user_id' => $user->id,
                //     'otp'     => $token,
                // ]);

                // Prepare data for email template
                $data              = [
                    'token'          => $token,
                    'user'           => $user,
                    'reset_link'     => $link,
                    'template_title' => 'password_reset',
                ];
                if(isMailSetupValid()){
                    $this->sendmail($request->email, 'emails.template_mail', $data);
                }


                Toastr::success(__('receive__mail_password_hints'));

                return redirect()->back();
            } catch (Exception $e) {
                Toastr::warning(__('An error occurred while processing your request.'));

                return redirect()->back();
            }
        } else {
            Toastr::warning(__('user_not_found'));

            return redirect()->back();
        }
    }

    public function showResetPasswordForm($token)
    {
        // Check if the token exists in the password_resets table
        $resetRecord = DB::table('password_resets')
            ->where('token', $token)
            ->first();

        if (! $resetRecord) {
            Toastr::error('Invalid token!');

            return redirect()->route('login'); // Redirect to login page or any other appropriate page
        }

        return view('auth.forgetPasswordLink', ['token' => $token]);
    }

    public function submitResetPasswordForm(Request $request): RedirectResponse
    {
        try {
            $request->validate([
                'email'                 => 'required|email|exists:users',
                'password'              => 'required|string|min:6|confirmed',
                'password_confirmation' => 'required',
            ]);

            // Check if the token and email combination exists in password_resets table
            $resetRecord    = DB::table('password_resets')
                ->where('email', $request->email)
                ->where('token', $request->token)
                ->first();

            if (! $resetRecord) {
                return back()->withInput()->with('error', 'Invalid token!');
            }

            // Update user's password
            $user           = User::where('email', $request->email)->first();
            $user->password = Hash::make($request->password);
            $user->save();

            // Delete the used token from password_resets table
            DB::table('password_resets')->where('email', $request->email)->delete();

            // Prepare data for sending email notification
            $data           = [
                'user'           => $user,
                'login_link'     => url('/login'),
                'template_title' => 'recovery_mail',
            ];
            if(isMailSetupValid()){
            // Send email notification about the password change
            $this->sendmail($request->email, 'emails.template_mail', $data);
            }

            // Redirect the user to login page with success message
            Toastr::success(__('successfully_password_changed'));

            return redirect('/login')->with('message', 'Your password has been changed!');
        } catch (\Exception $e) {

            // dd($e->getMessage());
            // Log the exception or handle it accordingly
            Toastr::error('An error occurred while processing your request.');

            return redirect()->back();
        }
    }

    public function passwordUpdate(Request $request)
    {
        $request->validate([
            'password' => 'required|min:6|max:32|confirmed',
        ]);

        try {
            $user = User::where('email', $request->email)->first();
            $otp  = PasswordRequest::where('otp', $request->otp)->where('user_id', $user->id)->latest()->first();
            if ($otp) {
                $data           = [
                    'user'           => $user,
                    'login_link'     => url('/login'),
                    'template_title' => 'recovery_mail',
                ];
                if(isMailSetupValid()){
                    $this->sendmail($request->email, 'emails.template_mail', $data);
                }
                $user->password = bcrypt($request->password);
                $user->save();
                $otp->delete();
                Toastr::success(__('successfully_password_changed'));

                return $this->logout($request);
            } else {
                Toastr::warning(__('please_request_another_code'));

                return redirect()->back();
            }
        } catch (Exception $e) {
            Toastr::warning(__($e->getMessage()));

            return redirect()->back();
        }
    }

    public function verified($id): \Illuminate\Http\RedirectResponse
    {
        if (isDemoMode()) {
            Toastr::error(__('this_function_is_disabled_in_demo_server'));

            return back();
        }
        try {
            $response = $this->userRepository->userVerified($id);
            Toastr::success(__($response['message']));

            return redirect()->back();
        } catch (\Exception $e) {
            Toastr::error($e->getMessage());

            return redirect()->back();
        }
    }

    public function activation($email, $code)
    {
        $user       = User::whereEmail($email)->first();
        $activation = Activation::where([['code', $code], ['user_id', $user->id]])->first();
        if ($activation) {
            if ($activation->completed == 1) {
                Toastr::success(__('your_account_has_been_already_activated'));

                return redirect()->route('login');
            } else {
                try {
                    DB::beginTransaction();
                    $activation->completed   = 1;
                    $activation->save();
                    $user->email_verified_at = now();
                    $user->status            = 1;
                    $user->save();
                    $data                    = [
                        'user'           => $user,
                        'login_link'     => url('/login'),
                        'template_title' => 'welcome_email',
                    ];
                    if(isMailSetupValid()){
                        $this->sendmail($email, 'emails.template_mail', $data);
                    }
                    DB::commit();
                    Toastr::success(__('your_account_is_active_now'));

                    return redirect()->route('login');
                } catch (Exception $e) {
                    DB::rollBack();
                    Toastr::success(__($e->getMessage()));

                    return redirect()->route('login');
                }
            }
        } else {
            Toastr::error(__('please_check_your_credential'));

            return redirect()->route('login');
        }
    }

    public function changePassword()
    {
        return view('auth.change-password');
    }

    public function changePasswordUpdate(Request $request)
    {
        $request->validate([
            'current_password' => ['required'],
            'password'         => 'required|min:6|max:32|confirmed',
        ]);
        $user = $this->userRepository->findByEmail(auth()->user()->email);
        if (Hash::check($request->current_password, $user->password)) {
            try {
                $user->password = bcrypt($request->password);
                $user->save();
                Toastr::success(__('successfully_password_changed'));

                return $this->logout($request);
            } catch (Exception $e) {
                Toastr::warning(__($e->getMessage()));

                return redirect()->back();
            }
        } else {
            Toastr::warning(__('sorry_old_password_not_match'));

            return redirect()->back();
        }
    }
}
