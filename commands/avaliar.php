<?php

use Discord\Builders\MessageBuilder;
use Discord\Parts\Channel\Message;
use Discord\Parts\Embed\Embed;
use Discord\Parts\Interactions\Command\Permission;
use Discord\Parts\Interactions\Interaction;
use Discord\Parts\Permissions\ChannelPermission;
use Discord\Parts\Thread\Thread;
use Discord\WebSockets\Events\ThreadCreate;

$thread = $interaction->channel->startThread("Entrevista-{$atributes['membro']['value']}", true, 60)->then(
    
    function ($response) use ($interaction, $discord) {
        $atributes = $interaction->data->options;
        $member = $interaction->guild->members->get('id', $atributes['membro']['value'])->addRole("1135282423937110056");
        $embed = new Embed($discord);
        $embed->setTitle("Entrevista para desenvolvedor ".ucfirst($atributes['tag']['value']));
        $embed->addField([
            "name" => "Entrevistado:",
            "value" => "<@{$atributes['membro']['value']}>",
            "inline" => true

        ]);
        $embed->addField([
            "name" => "Entrevistador:",
            "value" => "<@{$interaction->user->id}>",
            "inline" => true

        ]);
        $message = $response->sendMessage(MessageBuilder::new()->setContent("<@{$interaction->user->id}> <@{$atributes['membro']['value']}>")->addEmbed($embed));
        $interaction->respondWithMessage(MessageBuilder::new()->setContent("Thread criada com sucesso! ID da thread: <#{$response['id']}>"));
        $response->setPermissions("1135282423937110056", [
            ChannelPermission::ALL_PERMISSIONS,
            'VIEW_CHANNEL'
        ], []);   
    },
    function (\Exception $e) use ($interaction) {
        echo "Falha ao criar Thread " . $e . PHP_EOL;
        $interaction->respondWithMessage(MessageBuilder::new()->setContent("Falha ao criar Thread de entrevista."));
    }
);
