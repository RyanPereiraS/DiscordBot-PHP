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
                if($message->channel_id == $gptChat){
                    unset($args[0]);
                    $q = implode(" ", $args);
                    $u = $message->author->username;
                    include "commands/testar.php";
                } else {
                    $message->reply("Comando não encontrado ou inexistente <:barrierblockmc:1057236885233225789>");
                }
            }
        } else {
            $message->reply("Salve tortoguito <:feliz:1158168017625161728>");
        }
        //echo "{$message->author->username}: {$message->content}", PHP_EOL;
    });

    $discord->on(Event::MESSAGE_CREATE, function (Message $message, Discord $discord) {
        if ($message->author->bot) return;
        $args = explode(" ", strtolower($message->content));
        $tempo_cooldown = 10;

        // Verifica se a marca de tempo da última verificação está definida
        if (!isset($_SESSION['ultima_verificacao']) || time() - $_SESSION['ultima_verificacao'] > $tempo_cooldown) {
             // Atualiza a marca de tempo da última verificação

            // Array com as frases a serem verificadas
            $args = ["me ajude", "preciso de ajuda", "me ajuda por favor", "necessito de ajuda"];
            $mensagem = preg_replace('/[^a-zA-ZÀ-ú\s]/', '', $message->content);
            foreach ($args as $frase) {
                $similaridade = similar_text($mensagem, $frase, $percentagem);

                if ($percentagem > 70) { // Define um limite de similaridade
                    echo "Encontrada variante da frase chave \"$mensagem\": \"$frase\" com $percentagem% de similaridade".PHP_EOL;
                    $_SESSION['ultima_verificacao'] = time();
                    $message->reply("https://dontasktoask.com/pt-br/");
                    echo "Captado, Enviando mensagem: {$percentagem}% de similaridade".PHP_EOL;
                    break;
                } else {
                    //echo "Captado, {$percentagem}% de similaridade".PHP_EOL;
                }
            }
            
        } else {
            //echo "Espere um pouco antes de realizar outra verificação.".PHP_EOL;
        }
    });
});

$discord->run();
