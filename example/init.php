<?php

require_once "./vendor/autoload.php";

use DPHPDriver\Listeners;
use DPHPDriver\Ready;

use Discord\Discord;
use Discord\WebSockets\Intents;
use Discord\WebSockets\Event;

$listeners = new Listeners;

$listeners->injectEventListener(Event::MESSAGE_CREATE, function($type, Discord $discord){
    echo $type->content. PHP_EOL;
});

$option = [
    "token" => "YOUR BOT TOKEN",
    'intents' => Intents::getDefaultIntents()
                |Intents::MESSAGE_CONTENT,
];

$ready = new Ready($option);

$ready->addListeners($listeners);


// $ready->injectReadyClosure(function($discord) use ($listeners){
//     echo "Bot is Ready!\n";
//     $listeners->execEventListeners($discord);
// });

$ready->initDiscord();

$ready->start();

