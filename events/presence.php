<?php

use Discord\Discord;

$activityObj = $discord->factory(\Discord\Parts\User\Activity::class);
$activityObj->type = \Discord\Parts\User\Activity::TYPE_STREAMING;
$activityObj->name = "Codando em PHP";
$activityObj->url = "https://twitch.tv/oRyanPereiraS";

$atividades = [
    ['name' => "Criando Bombas Nucleares", 'url' => "https://twitch.tv/oRyanPereiraS", "type" => \Discord\Parts\User\Activity::TYPE_STREAMING],
    ['name' => "Codando em PHP", 'url' => "https://twitch.tv/oRyanPereiraS", "type" => \Discord\Parts\User\Activity::TYPE_STREAMING]
];


$discord->updatePresence($activityObj, false, "online", false);
