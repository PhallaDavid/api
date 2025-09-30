<?php

namespace App\Helpers;

class TelegramHelper
{
    public static function sendMessage($message)
    {
        $botToken = env('TELEGRAM_BOT_TOKEN');
        $chatId = env('TELEGRAM_CHAT_ID');

        $url = "https://api.telegram.org/bot$botToken/sendMessage?chat_id=$chatId&text=" . urlencode($message);

        return file_get_contents($url);
    }
}
