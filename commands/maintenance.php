<?php
	if($maintenance){
        $maintenance = false;
    	$message->reply("Manutenção Desativada!");
    } else {
    	$maintenance = true;
    	$message->reply("Manutenção Iniciada!");
    }