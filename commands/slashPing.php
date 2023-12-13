<?php
    include __DIR__.'/vendor/autoload.php';

    use Discord\Builders\MessageBuilder;
    $interaction->respondWithMessage(MessageBuilder::new()
        ->setContent("Pong!")
    );