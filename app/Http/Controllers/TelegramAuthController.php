<?php

namespace App\Http\Controllers;

use App\Http\Requests\TelegramRequest;
use App\Telegram;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Telegram\Bot\Api;
use Telegram\Bot\Exceptions\TelegramSDKException;

class TelegramAuthController extends Controller
{

    /**
     * Login though telegram
     *
     * @param TelegramRequest $request
     * @return RedirectResponse
     * @throws TelegramSDKException
     */
    public function login(TelegramRequest $request)
    {
        $telegramModel = Telegram::where('telegram_id', $request->get('telegram_id'))->first();
        Auth::login($telegramModel->user);
        if(Auth::user() === $telegramModel->user) {
            $telegram = new Api('1129203288:AAEbk0ToT5Mvfu2TjL7u33VFRUClEQmP-bU');
            $telegram->sendMessage([
                'chat_id' => $request->get('telegram_id'),
                'text' => 'You are logged in, ' . $request->get('first_name') . '!'
            ]);
            return redirect()->action('HomeController@index');
        }
    }
}
