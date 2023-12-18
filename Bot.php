<?php

include __DIR__ . '/vendor/autoload.php';
require_once "config.php";

use Discord\Builders\CommandBuilder;
use Discord\Discord;
use Discord\Parts\Channel\Message;
use Discord\WebSockets\Intents;
use Discord\WebSockets\Event;
use Monolog\Logger;
use Monolog\Handler\StreamHandler;
use Discord\Parts\Interactions\Command\Command; // Please note to use this correct namespace!
use Discord\Parts\Interactions\Interaction;
use Discord\Builders\MessageBuilder;
use Discord\Parts\Interactions\Command\Choice;
use Discord\Parts\Interactions\Command\Option;
use Discord\Parts\Interactions\Command\Permission;
use Monolog\Handler\NullHandler;

$logger = new Logger('Logger');
$logger->pushHandler(new StreamHandler('php://stdout'));
//$logger->pushHandler(new NullHandler(), Logger::DEBUG); // Change the second parameter of this 
//$logger->pushHandler(new StreamHandler('php://stdout', Logger::INFO));

$discord = new Discord([
    'token' => $token,
    'intents' => Intents::getDefaultIntents() | Intents::GUILD_MEMBERS,
    'loadAllMembers' => true,
    'logger' => $logger
]);

$maintenance = true;

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
    include "events/presence.php";


    $discord->on(Event::MESSAGE_CREATE, function (Message $message, Discord $discord) {
        global $gptChat, $maintenance;
        if ($message->author->bot) return;
        $args = explode(" ", $message->content);
        if ($args[0] != "<@{$discord->user->id}>") return;
        if ($maintenance && !$message->member->roles->has("1143258600672858112")) {
            if (isset($args[1]) && $args[1] == "maintenance") {
                $member = $message->author;
                $guild = $message->channel->guild;
                if (!$message->member->roles->has("1143258600672858112")) {
                    $message->reply("Sério?");
                    return;
                }
                $command = strtolower($args[1]);
                $commandResult = searchCommand($command);
                if ($commandResult) {
                    include $commandResult;
                }
            } else {
                $message->reply("Modo de manutenção ativado, durante esse periodo meus comandos não podem ser acessados <a:ChoraNaoBebe:1122952308783857814>");
            }
        } else {
            if (isset($args[1])) {
                $command = strtolower($args[1]);
                $commandResult = searchCommand($command);
                if ($commandResult) {
                    include $commandResult;
                } else {
                    if ($message->channel_id == $gptChat) {
                        unset($args[0]);
                        unset($args[1]);
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
        }
    });

    $discord->on(Event::MESSAGE_CREATE, function (Message $message, Discord $discord) {
        global $maintenance;
        if ($maintenance) {
            if ($message->author->bot) return;
            $args = explode(" ", strtolower($message->content));
            $tempo_cooldown = 10;

            if (!isset($_SESSION['ultima_verificacao']) || time() - $_SESSION['ultima_verificacao'] > $tempo_cooldown) {

                $args = ["me ajude", "preciso de ajuda", "me ajuda por favor", "necessito de ajuda"];
                $mensagem = preg_replace('/[^a-zA-ZÀ-ú\s]/', '', $message->content);
                foreach ($args as $frase) {
                    $similaridade = similar_text($mensagem, $frase, $percentagem);

                    if ($percentagem > 70) {
                        echo "Encontrada variante da frase chave \"$mensagem\": \"$frase\" com $percentagem% de similaridade" . PHP_EOL;
                        $_SESSION['ultima_verificacao'] = time();
                        $message->reply("https://dontasktoask.com/pt-br/");
                        echo "Captado, Enviando mensagem: {$percentagem}% de similaridade" . PHP_EOL;
                        break;
                    } else {
                        //echo "Captado, {$percentagem}% de similaridade".PHP_EOL;
                    }
                }
            } else {
            }
        }
    });
    $discord->on('PRESENCE_UPDATE', function ($presence) {
        var_dump($presence);
        echo "PRESENCE_UPDATE" . PHP_EOL;
    });
});

$discord->on('error', function ($error, Discord $discord) {
    print_r($error . PHP_EOL);
});

$discord->run();
