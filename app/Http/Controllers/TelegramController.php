<?php

namespace App\Http\Controllers;

use App\Http\Requests\TelegramRequest;
use App\Providers\RouteServiceProvider;
use App\Telegram;
use Illuminate\Foundation\Auth\AuthenticatesUsers;
use Illuminate\Http\RedirectResponse;
use Illuminate\Support\Facades\Auth;
use Telegram\Bot\Api;
use Telegram\Bot\Exceptions\TelegramSDKException;
use Telegram\Bot\Objects\Message;

class TelegramController extends Controller
{

    use AuthenticatesUsers;

    protected $redirectTo = RouteServiceProvider::HOME;

    public function __construct()
    {
        $this->middleware('guest')->except('logout');
    }

    /**
     * Store user telegram
     *
     * @param TelegramRequest $request
     * @return Message
     * @throws TelegramSDKException
     */

    public function store(TelegramRequest $request)
    {
        Telegram::firstOrCreate([
            'user_id' => Auth::user()->id,
            'telegram_id' => $request->get('telegram_id'),
            'first_name' => $request->get('first_name'),
            'last_name' => $request->get('last_name'),
        ]);

        $telegram = new Api('1129203288:AAEbk0ToT5Mvfu2TjL7u33VFRUClEQmP-bU');

        $response = $telegram->sendMessage([
            'chat_id' => $request->get('telegram_id'),
            'text' => 'You are logged in, ' . $request->get('first_name') . '!'
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
        $telegramModel = Telegram::where('telegram_id', $request->get('telegram_id'))->first();
        Auth::login($telegramModel->user);
        if (Auth::user() === $telegramModel->user) {
            $telegram = new Api('1129203288:AAEbk0ToT5Mvfu2TjL7u33VFRUClEQmP-bU');
            $telegram->sendMessage([
                'chat_id' => $request->get('telegram_id'),
                'text' => 'You are logged in, ' . $request->get('first_name') . '!'
            ]);
        }
    }
}
