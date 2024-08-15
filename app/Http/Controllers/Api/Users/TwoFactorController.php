<?php

namespace Vanguard\Http\Controllers\Api\Users;

use Authy;
use Illuminate\Http\JsonResponse;
use Vanguard\Events\User\TwoFactorDisabledByAdmin;
use Vanguard\Events\User\TwoFactorEnabledByAdmin;
use Vanguard\Http\Controllers\Api\ApiController;
use Vanguard\Http\Requests\TwoFactor\EnableTwoFactorRequest;
use Vanguard\Http\Requests\TwoFactor\VerifyTwoFactorTokenRequest;
use Vanguard\Http\Resources\UserResource;
use Vanguard\User;

class TwoFactorController extends ApiController
{
    public function __construct()
    {
        $this->middleware('permission:users.manage');
    }

    /**
     * Enable 2FA for the specified user.
     */
    public function update(User $user, EnableTwoFactorRequest $request): JsonResponse
    {
        if (Authy::isEnabled($user)) {
            return $this->setStatusCode(422)
                ->respondWithError(trans('auth.2fa.already_enabled'));
        }

        $user->setAuthPhoneInformation($request->country_code, $request->phone_number);

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
    public function verify(VerifyTwoFactorTokenRequest $request, User $user): UserResource|JsonResponse
    {
        if (! Authy::tokenIsValid($user, $request->token)) {
            return $this->setStatusCode(422)
                ->respondWithError(trans('auth.2fa.invalid_token'));
        }

        $user->setTwoFactorAuthProviderOptions(array_merge(
            $user->getTwoFactorAuthProviderOptions(),
            ['enabled' => true]
        ));

        $user->save();

        event(new TwoFactorEnabledByAdmin($user));

        return new UserResource($user);
    }

    /**
     * Disable 2FA for specified user.
     */
    public function destroy(User $user): UserResource|JsonResponse
    {
        if (! Authy::isEnabled($user)) {
            return $this->setStatusCode(422)
                ->respondWithError(trans('auth.2fa.not_enabled'));
        }

        Authy::delete($user);

        $user->save();

        event(new TwoFactorDisabledByAdmin($user));

        return new UserResource($user);
    }
}
