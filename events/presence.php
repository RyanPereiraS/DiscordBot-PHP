<?php

// Sua instância do Discord
// Certifique-se de que sua instância do Discord está configurada antes de usar o código abaixo

$activityObj = $discord->factory(\Discord\Parts\User\Activity::class);
$activityObj->type = \Discord\Parts\User\Activity::TYPE_STREAMING;
$activityObj->name = "Codando em PHP";
$activityObj->url = "https://twitch.tv/oRyanPereiraS";

$discord->updatePresence($activityObj, false, "online", false);
