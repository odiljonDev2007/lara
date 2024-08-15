<?php

namespace Vanguard\Http\Controllers\Api\Profile;

use Authy;
use Vanguard\Events\User\TwoFactorDisabled;
use Vanguard\Events\User\TwoFactorEnabled;
use Vanguard\Http\Controllers\Api\ApiController;
use Vanguard\Http\Requests\TwoFactor\EnableTwoFactorRequest;
use Vanguard\Http\Requests\TwoFactor\VerifyTwoFactorTokenRequest;
use Vanguard\Http\Resources\UserResource;
use Vanguard\Repositories\User\UserRepository;

class TwoFactorController extends ApiController
{
    public function update(EnableTwoFactorRequest $request, UserRepository $users)
    {
        $user = auth()->user();

        if (Authy::isEnabled($user)) {
            return $this->setStatusCode(422)->respondWithError(trans('auth.2fa.already_enabled'));
        }

        if ($users->findForTwoFactor($request->phone_number, $request->country_code)) {
            return $this->setStatusCode(422)
                ->respondWithError(trans('auth.2fa.phone_in_use'));
        }

        $user->setAuthPhoneInformation(
            $request->country_code,
            $request->phone_number
        );

        Authy::register($user);

        $user->save();

        Authy::sendTwoFactorVerificationToken($user);

        return $this->respondWithArray([
            'message' => trans('auth.2fa.token_sent'),
        ]);
    }

    /**
     * Verify provided 2FA token.
     */
    public function verify(VerifyTwoFactorTokenRequest $request): UserResource|\Illuminate\Http\JsonResponse
    {
        $user = auth()->user();

        if (! Authy::tokenIsValid($user, $request->token)) {
            return $this->setStatusCode(422)->respondWithError(trans('auth.2fa.invalid_token'));
        }

        $user->setTwoFactorAuthProviderOptions(array_merge(
            $user->getTwoFactorAuthProviderOptions(),
            ['enabled' => true]
        ));

        $user->save();

        event(new TwoFactorEnabled);

        return new UserResource($user);
    }

    /**
     * Disable 2FA for currently authenticated user.
     */
    public function destroy(): UserResource|\Illuminate\Http\JsonResponse
    {
        $user = auth()->user();

        if (! Authy::isEnabled($user)) {
            return $this->setStatusCode(422)->respondWithError(trans('auth.2fa.not_enabled'));
        }

        Authy::delete($user);

        $user->save();

        event(new TwoFactorDisabled);

        return new UserResource($user);
    }
}
