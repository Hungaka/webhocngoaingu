<?php
require_once("../../configs/config.php");
require_once("../../configs/function.php");

use Orhanerday\OpenAi\OpenAi;

$open_ai_key = OPENAI_API_KEY;
$open_ai = new OpenAi($open_ai_key);

$chat = $open_ai->chat([
    'model' => 'gpt-3.5-turbo',
    'messages' => [
        [
            "role" => "user",
            "content" => "Xin chào?"
        ],
    ],
    'temperature' => 1.0,
    'max_tokens' => 4000,
    'frequency_penalty' => 0,
    'presence_penalty' => 0,
]);


var_dump($chat);
echo "<br>";
echo "<br>";
echo "<br>";
// decode response
$d = json_decode($chat);
// Get Content
echo ($d->choices[0]->message->content);
