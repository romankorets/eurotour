<?php


namespace App\Auth\Social\Two;

use Illuminate\Http\Request;
use Laravel\Socialite\Two\AbstractProvider;
use Laravel\Socialite\Two\ProviderInterface;
use Laravel\Socialite\Two\User;

class TelegramProvider extends AbstractProvider implements ProviderInterface
{

    protected $user;

    /**
     * @inheritDoc
     */

    public function __construct(Request $request, $clientId, $clientSecret, $redirectUrl, $guzzle = [])
    {
        parent::__construct($request, $clientId, $clientSecret, $redirectUrl, $guzzle);
    }

    protected function getAuthUrl($state)
    {
        return $this->buildAuthUrlFromBase('https://oauth.telegram.org/auth?bot_id=1129203288&origin=http%3A%2F%2Feurotour.test&embed=1&request_access=read', $state);
    }

    /**
     * @inheritDoc
     */
    protected function getTokenUrl()
    {
        return 'https://oauth.telegram.org/token';
    }

    /**
     * @inheritDoc
     */
    protected function getUserByToken($token)
    {
        // TODO: Implement getUserByToken() method.
    }

    /**
     * @inheritDoc
     */
    protected function mapUserToObject(array $user)
    {
        return (new User)->setRaw($user)->map([
            'id'       => $user['id'],
        ]);
    }

    public function redirect()
    {

    }

    public function user()
    {
        return $this->user;
    }
}
