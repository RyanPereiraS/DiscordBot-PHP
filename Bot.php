<?php

include __DIR__ . '/vendor/autoload.php';
require_once "config.php";

use Discord\Builders\CommandBuilder;
use Discord\Discord;
use Discord\Parts\Channel\Message;
use Discord\WebSockets\Intents;
use Discord\WebSockets\Event;
use Discord\Parts\Interactions\Command\Command; // Please note to use this correct namespace!
use Discord\Parts\Interactions\Interaction;
use Discord\Builders\MessageBuilder;
use Discord\Parts\Interactions\Command\Choice;
use Discord\Parts\Interactions\Command\Option;

$discord = new Discord([
    'token' => $token,
    'intents' => Intents::getDefaultIntents() | Intents::GUILD_MEMBERS,
    'loadAllMembers' => true
    //      | Intents::MESSAGE_CONTENT, // Note: MESSAGE_CONTENT is privileged, see https://dis.gd/mcfaq
]);

function searchCommand($command)
{
    $commandFolder = "commands/";
    $commandFile = $commandFolder . $command . ".php";
    if (file_exists($commandFile)) {
        return $commandFile;
    } else {
        return false;
    }
}

$discord->on('ready', function (Discord $discord) {
    echo "Bot is ready!", PHP_EOL;
    
    include "commands/RegisterSlashCommands.php";
    include "commands/listener/CommandListener.php";

    $activity = $discord->factory(\Discord\Parts\User\Activity::class);
    $activity->type = \Discord\Parts\User\Activity::TYPE_STREAMING;
    $activity->name = "Codando em PHP";
    $activity->url = "https://twitch.tv/oRyanPereiraS";
    $discord->updatePresence($activity, false, "online", false);

    $discord->on(Event::MESSAGE_CREATE, function (Message $message, Discord $discord) {
        if ($message->author->bot) return;
        $args = explode(" ", $message->content);
        if ($args[0] != "<@{$discord->user->id}>") return;

        if (isset($args[1])) {
            $command = strtolower($args[1]);
            $commandResult = searchCommand($command);
            if ($commandResult) {
                include $commandResult;
            } else {
                $message->reply("Comando não encontrado ou inexistente <:barrierblockmc:1057236885233225789>");
            }
        } else {
            $message->reply("Salve tortoguito <:feliz:1158168017625161728>");
        }
        echo "{$message->author->username}: {$message->content}", PHP_EOL;

    });

});

$discord->run();
