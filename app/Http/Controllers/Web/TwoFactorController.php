<?php

namespace Vanguard\Http\Controllers\Web;

use Authy;
use Illuminate\Contracts\View\View;
use Illuminate\Http\RedirectResponse;
use Vanguard\Events\User\TwoFactorDisabled;
use Vanguard\Events\User\TwoFactorEnabled;
use Vanguard\Events\User\TwoFactorEnabledByAdmin;
use Vanguard\Http\Controllers\Controller;
use Vanguard\Http\Requests\TwoFactor\DisableTwoFactorRequest;
use Vanguard\Http\Requests\TwoFactor\EnableTwoFactorRequest;
use Vanguard\Http\Requests\TwoFactor\ReSendTwoFactorTokenRequest;
use Vanguard\Http\Requests\TwoFactor\VerifyTwoFactorTokenRequest;
use Vanguard\Repositories\User\UserRepository;

class TwoFactorController extends Controller
{
    public function __construct(UserRepository $users)
    {
        $this->middleware('auth');

        $this->middleware(function ($request, $next) use ($users) {
            $user = $request->get('user')
                ? $users->find($request->get('user'))
                : auth()->user();

            return Authy::isEnabled($user) ? abort(404) : $next($request);
        })->only('enable', 'verification', 'resend', 'verify');
    }

    /**
     * Enable 2FA for currently logged user.
     */
    public function enable(EnableTwoFactorRequest $request, UserRepository $users): RedirectResponse
    {
        $user = $request->theUser();

        if ($users->findForTwoFactor($request->phone_number, $request->country_code)) {
            return redirect()
                ->back()
                ->withErrors(trans('auth.2fa.phone_in_use'));
        }

        $user->setAuthPhoneInformation($request->country_code, $request->phone_number);

        Authy::register($user);

        $user->save();

        Authy::sendTwoFactorVerificationToken($user);

        return $user->is(auth()->user())
            ? redirect()->route('two-factor.verification')
            : redirect()->route('two-factor.verification', ['user' => $user->id]);
    }

    /**
     * Shows the phone verification page.
     */
    public function verification(): View
    {
        return view('user.two-factor-verification', [
            'user' => request('user'),
        ]);
    }

    /**
     * Re-send phone verification token.
     */
    public function resend(ReSendTwoFactorTokenRequest $request): void
    {
        Authy::sendTwoFactorVerificationToken($request->theUser());
    }

    /**
     * Verify 2FA token and enable 2FA if token is valid.
     */
    public function verify(VerifyTwoFactorTokenRequest $request): RedirectResponse
    {
        $user = $request->theUser();

        if (! Authy::tokenIsValid($user, $request->token)) {
            return redirect()->route('two-factor.verification')
                ->withErrors(['token' => trans('auth.2fa.invalid_token')]);
        }

        $user->setTwoFactorAuthProviderOptions(array_merge(
            $user->getTwoFactorAuthProviderOptions(),
            ['enabled' => true]
        ));

        $user->save();

        $message = trans('auth.2fa.enabled_successfully');

        if ($user->is(auth()->user())) {
            event(new TwoFactorEnabled);

            return redirect()->route('profile')->withSuccess($message);
        }

        event(new TwoFactorEnabledByAdmin($user));

        return redirect()->route('users.edit', $user)->withSuccess($message);
    }

    /**
     * Disable 2FA for currently logged user.
     */
    public function disable(DisableTwoFactorRequest $request): RedirectResponse
    {
        $user = $request->theUser();

        if (! Authy::isEnabled($user)) {
            abort(404);
        }

        Authy::delete($user);

        $user->save();

        event(new TwoFactorDisabled);

        return redirect()->back()
            ->withSuccess(trans('auth.2fa.disabled_successfully'));
    }
}
