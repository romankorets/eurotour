<?php

namespace App\Http\Controllers;

use App\Http\Requests\TelegramRequest;
use App\Providers\RouteServiceProvider;
use App\Social;
use Illuminate\Foundation\Auth\AuthenticatesUsers;
use Illuminate\Http\RedirectResponse;
use Illuminate\Support\Facades\Auth;
use Telegram\Bot\Api;
use Telegram\Bot\Exceptions\TelegramSDKException;
use Telegram\Bot\Objects\Message;

class TelegramController extends Controller
{
    /**
     * Store user telegram
     *
     * @param TelegramRequest $request
     * @return Message
     * @throws TelegramSDKException
     */

    public function store(TelegramRequest $request)
    {
        Social::create([
            'user_id' => Auth::user()->id,
            'provider_user_id' => $request->get('telegram_id'),
            'provider' => 'telegram',
        ]);

        $telegram = new Api(env('TELEGRAM_TOKEN', null));

        $response = $telegram->sendMessage([
            'chat_id' => $request->get('telegram_id'),
            'text' => 'You successfully registered telegram to eurotour.test, ' . $request->get('first_name') . '!'
        ]);
        return $response;
    }

    /**
     * Login through telegram
     *
     * @param TelegramRequest $request
     * @return RedirectResponse|void
     * @throws TelegramSDKException
     */

    public function login(TelegramRequest $request)
    {
        $telegramModel = Social::where('provider_user_id', $request->get('telegram_id'))
            ->where('provider', 'telegram')
            ->first();
        Auth::login($telegramModel->user);
        if (Auth::user()->id === $telegramModel->user->id) {
            $telegram = new Api(env('TELEGRAM_TOKEN', null));
            $telegram->sendMessage([
                'chat_id' => $request->get('telegram_id'),
                'text' => 'You are logged in, ' . $request->get('first_name') . '!'
            ]);
        }
    }
}
