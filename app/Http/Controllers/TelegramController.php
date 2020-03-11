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
        $telegramModel = Telegram::where('telegram_id', $request->get('telegram_id'))->first();
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
