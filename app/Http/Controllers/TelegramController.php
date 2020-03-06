<?php

namespace App\Http\Controllers;

use App\Http\Requests\TelegramRequest;
use App\Telegram;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Telegram\Bot\Api;

class TelegramController extends Controller
{

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
}
