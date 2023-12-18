<?php

use Discord\Builders\MessageBuilder;
use Discord\Parts\Channel\Message;
use Discord\Parts\Interactions\Interaction;
use Discord\Parts\Thread\Thread;
use Discord\WebSockets\Events\ThreadCreate;

    $discord->listenCommand('ping', function (Interaction $interaction) {
        global $maintenance;
        if($maintenance){
            $interaction->respondWithMessage(MessageBuilder::new()->setContent("Modo de manutenção ativado, durante esse periodo meus comandos não podem ser acessados <a:ChoraNaoBebe:1122952308783857814>"));
            return;
        }
        include "commands/slashPing.php";
    });

    $discord->listenCommand('avaliar' , function(Interaction $interaction) use ($discord){
        global $maintenance;
        if($maintenance){
            $interaction->respondWithMessage(MessageBuilder::new()->setContent("Modo de manutenção ativado, durante esse periodo meus comandos não podem ser acessados <a:ChoraNaoBebe:1122952308783857814>"));
            return;
        }
        $atributes = $interaction->data->options;
        // $atributes['tag']['value'] . PHP_EOL;
        //print_r($atributes['membro'].PHP_EOL);
        
        include "commands/avaliar.php";
        
        /*if($thread) {
            $interaction->respondWithMessage(MessageBuilder::new()->setContent("Thread criada com sucesso! ID da thread: "));
        } else {
            $interaction->respondWithMessage(MessageBuilder::new()->setContent("Falha ao criar Thread"));
        }*/
    });