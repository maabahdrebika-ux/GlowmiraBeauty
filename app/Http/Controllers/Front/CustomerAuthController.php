<?php

namespace App\Http\Controllers\Front;

use App\Http\Controllers\Controller;
use App\Models\Customer;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Password;
use RealRashid\SweetAlert\Facades\Alert;

class CustomerAuthController extends Controller
{
    public function showLoginForm()
    {
        return view('front.auth.login');
    }

    public function showRegisterForm()
    {
        return view('front.auth.register');
    }

    public function login(Request $request)
    {
        $request->validate([
            'email' => 'required|email',
            'password' => 'required|string',
        ]);

        if (Auth::guard('customer')->attempt($request->only('email', 'password'), $request->filled('remember'))) {
            return redirect()->intended(route('customer.dashboard'));
        }

        // Use both SweetAlert and session for error handling
        Alert::error('خطأ', 'بيانات الدخول غير صحيحة');
        return back()
            ->withInput($request->only('email'))
            ->withErrors(['email' => 'بيانات الدخول غير صحيحة']);
    }

    public function register(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|string|email|max:255|unique:customers',
            'phone' => 'required|digits_between:10,10|numeric|starts_with:091,092,094,021,095,093',
            'password' => 'required|string|min:8|confirmed',
            'address' => 'nullable|string',
        ]);

        try {
            $customer = Customer::create([
                'name' => $request->name,
                'email' => $request->email,
                'phone' => $request->phone,
                'password' => Hash::make($request->password),
                'address' => $request->address,
            ]);

            Auth::guard('customer')->login($customer);

            Alert::success('تم التسجيل بنجاح', 'مرحباً بك في متجرنا');
            return redirect(route('customer.dashboard'));
        } catch (\Exception $e) {
            Alert::error('خطأ', 'حدث خطأ أثناء التسجيل: ' . $e->getMessage());
            return back()
                ->withInput()
                ->withErrors(['register' => 'حدث خطأ أثناء التسجيل: ' . $e->getMessage()]);
        }
    }

    public function logout(Request $request)
    {
        Auth::guard('customer')->logout();

        $request->session()->invalidate();
        $request->session()->regenerateToken();

        return redirect('/');
    }

    // Password Reset Methods
    public function showLinkRequestForm()
    {
        return view('front.auth.passwords.email');
    }

    public function sendResetLinkEmail(Request $request)
    {
        $request->validate(['email' => 'required|email']);

        $status = Password::broker('customers')->sendResetLink(
            $request->only('email')
        );

        if ($status === Password::RESET_LINK_SENT) {
            Alert::success('تم الإرسال', 'تم إرسال رابط إعادة تعيين كلمة المرور إلى بريدك الإلكتروني');
            return back()->with('status', trans($status));
        }

        Alert::error('خطأ', 'حدث خطأ أثناء إرسال رابط إعادة التعيين');
        return back()->withErrors(['email' => trans($status)]);
    }

    public function showResetForm(Request $request, $token)
    {
        return view('front.auth.passwords.reset')->with(
            ['token' => $token, 'email' => $request->email]
        );
    }

    public function reset(Request $request)
    {
        $request->validate([
            'token' => 'required',
            'email' => 'required|email',
            'password' => 'required|string|min:8|confirmed',
        ]);

        $status = Password::broker('customers')->reset(
            $request->only('email', 'password', 'password_confirmation', 'token'),
            function ($customer, $password) {
                $customer->forceFill([
                    'password' => Hash::make($password)
                ])->save();
            }
        );

        if ($status === Password::PASSWORD_RESET) {
            Alert::success('تم بنجاح', 'تم إعادة تعيين كلمة المرور بنجاح');
            return redirect()->route('customer.login')->with('status', trans($status));
        }

        Alert::error('خطأ', 'حدث خطأ أثناء إعادة تعيين كلمة المرور');
        return back()->withErrors(['email' => trans($status)]);
    }
}
