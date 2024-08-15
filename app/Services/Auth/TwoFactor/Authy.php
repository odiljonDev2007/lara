<?php

namespace Vanguard\Services\Auth\TwoFactor;

use Exception;
use GuzzleHttp\Client as HttpClient;
use Vanguard\Services\Auth\TwoFactor\Contracts\Authenticatable as TwoFactorAuthenticatable;
use Vanguard\Services\Auth\TwoFactor\Contracts\Provider;

class Authy implements Provider
{
    /**
     * {@inheritDoc}
     */
    public function isEnabled(TwoFactorAuthenticatable $user): bool
    {
        $options = $user->getTwoFactorAuthProviderOptions();

        return isset($options['enabled']) && $options['enabled'] === true;
    }

    /**
     * {@inheritDoc}
     */
    public function register(TwoFactorAuthenticatable $user): void
    {
        $key = config('services.authy.key');

        $response = json_decode((new HttpClient)->post('https://api.authy.com/protected/json/users/new?api_key='.$key, [
            'form_params' => [
                'user' => [
                    'email' => $user->getEmailForTwoFactorAuth(),
                    'cellphone' => preg_replace('/[^0-9]/', '', $user->getAuthPhoneNumber()),
                    'country_code' => $user->getAuthCountryCode(),
                ],
            ],
        ])->getBody(), true);

        $user->setTwoFactorAuthProviderOptions([
            'id' => $response['user']['id'],
        ]);
    }

    /**
     * {@inheritDoc}
     */
    public function sendTwoFactorVerificationToken(TwoFactorAuthenticatable $user): bool
    {
        $key = config('services.authy.key');

        $options = $user->getTwoFactorAuthProviderOptions();

        $response = json_decode((new HttpClient)->get(
            'https://api.authy.com/protected/json/sms/'.$options['id'].'?force=true&api_key='.$key
        )->getBody(), true);

        return $response['success'] === true;
    }

    /**
     * {@inheritDoc}
     */
    public function tokenIsValid(TwoFactorAuthenticatable $user, $token): bool
    {
        try {
            $key = config('services.authy.key');

            $options = $user->getTwoFactorAuthProviderOptions();

            $response = json_decode((new HttpClient)->get(
                'https://api.authy.com/protected/json/verify/'.$token.'/'.$options['id'].'?force=true&api_key='.$key
            )->getBody(), true);

            return $response['token'] === 'is valid';
        } catch (Exception $e) {
            return false;
        }
    }

    /**
     * {@inheritDoc}
     */
    public function delete(TwoFactorAuthenticatable $user): void
    {
        $key = config('services.authy.key');

        $options = $user->getTwoFactorAuthProviderOptions();

        (new HttpClient)->post(
            'https://api.authy.com/protected/json/users/delete/'.$options['id'].'?api_key='.$key
        );

        $user->setTwoFactorAuthProviderOptions([]);
    }
}
