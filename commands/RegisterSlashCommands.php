<?php

use Discord\Parts\Interactions\Command\Choice;
use Discord\Parts\Interactions\Command\Option;
use Discord\Builders\CommandBuilder;
use Discord\CommandClient\Command\Command;
use Discord\Parts\Interactions\Command\Command as CommandCommand;
use Discord\WebSockets\Op;




$discord->application->commands->save(
    /*$discord->application->commands->create(
        CommandBuilder::new()
            ->setName("ping")
            ->setDescription("Pong")
            ->toArray()
    ),*/
    $discord->application->commands->create(
        CommandBuilder::new()
            ->setName("avaliar")
            ->setDescription("Realizar entrevista")
            ->addOption(new Option($discord, [
                'name' => "membro",
                'description' => "Membro a ser avaliado",
                'type' => Option::USER,
                'required' => true
            ]))
            ->addOption((new Option($discord, [
                'name' => 'tag',
                "description" => 'Tag Escolhida para avialiação',
                "required" => true,
                "type" => Option::STRING,
                'choices' => [
                    Choice::new($discord, "Diamante", "diamante"),
                    Choice::new($discord, "Esmeralda", "esmeralda")
                ]
            ])))
            ->addOption((new Option($discord, [
                'name' => "area",
                'description' => "Area de atuação do membro",
                'type' => Option::STRING,
                'choices' => [
                    Choice::new($discord, "Discord API", "discord"),
                    Choice::new($discord, "Bukkit API", "bukkit"),
                    Choice::new($discord, "Global", "global")
                ],
                'required' => false
            ])))
            ->addOption((new Option($discord, [
                'name' => "lang",
                'description' => "linguagem na qual o membro programa",
                'type' => Option::STRING,
                'choices' => [
                    Choice::new($discord, "Java", "java"),
                    Choice::new($discord, "Node.JS", "node.js"),
                    Choice::new($discord, "Web", "web")
                ],
                'required' => false
            ])))
            ->toArray()
            
    )
);
