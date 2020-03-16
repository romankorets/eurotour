<?php


namespace App\Auth\Social\Two;

use SocialiteProviders\Manager\SocialiteWasCalled;

class TelegramExtendSocialite
{
    public function handle(SocialiteWasCalled $socialiteWasCalled)
    {
        $socialiteWasCalled->extendSocialite('telegram', TelegramProvider::class);
    }
}
