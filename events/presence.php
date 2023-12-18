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

foreach ($atividades as $atividade) {
    global $discord;
    $atividadeAtual = new stdClass();
    $atividadeAtual->type = $atividade['type'];
    $atividadeAtual->name = $atividade['name'];
    $atividadeAtual->url = $atividade['url'];
    //updatePresence($atividadeAtual, $discord);
    //sleep(300);
}


function updatePresences(Discord $discord)
{
    //$activityObj = $discord->factory(\Discord\Parts\User\Activity::class);
    //$activityObj->type = $atividadeAtual->type;
    //$activityObj->name = $atividadeAtual->name;
    //$activityObj->url = $atividadeAtual->url;
    global $activityObj;
    $discord->updatePresence($activityObj, false, "online", false);
    echo "Rodou" . PHP_EOL;
}

$discord->updatePresence($activityObj, false, "online", false);
/*foreach ($atividades as $atividade) {
    //global $discord;
    $discord->updatePresence($activityObj, false, "online", false);
    //sleep(300);
}*/
