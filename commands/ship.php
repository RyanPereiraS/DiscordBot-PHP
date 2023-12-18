<?php
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
    
    $mentionedUsers = $message->mentions;
    if(count($mentionedUsers) === 3){
        $user1 = $message->guild->members->get("id", str_replace(["<", "@", ">"], "", $args[2]));
        $user2 = $message->guild->members->get("id", str_replace(["<", "@", ">"], "", $args[3]));

        $shipMessages = [
            0 => "<user1> 💔 <user2> \n O nível de sucesso é <porcent>% \n casal ruim ein <a:ChoraNaoBebe:1122952308783857814>",
            10 => "<user1> 💔 <user2> \n O nível de sucesso é <porcent>% \n amizade não falta <a:ChoraNaoBebe:1122952308783857814>",
            20 => "<user1> 💔 <user2> \n O nível de sucesso é <porcent>% \n 🤨🌈",
            30 => "<user1> 💔 <user2> \n O nível de sucesso é <porcent>% \n 🤨🌈",
            40 => "<user1> 💔 <user2> \n O nível de sucesso é <porcent>% \n Combina mas não é igual ela...",
            50 => "<user1> ❤️ <user2> \n O nível de sucesso é <porcent>% \n ta quase... ta quase...",
            60 => "<user1> 💕 <user2> \n O nível de sucesso é <porcent>% \n TÃO NAMORANDO CARALHOOOOOOOOOU",
            70 => "<user1> 💖 <user2> \n O nível de sucesso é <porcent>% \n CARALHOOOOOOOOOOOOOOO",
            80 => "<user1> 💘 <user2> \n O nível de sucesso é <porcent>% \n VAI DAR CERTO ESSE RELACIONAMENTO",
            90 => "<user1> 💞 <user2> \n O nível de sucesso é <porcent>% <:emocionante:1122944492400541849>"
        ];

        $shipValue = intval($user1->id) ^ intval($user2->id);
        $shippingPercentage = $shipValue % 101;
        $response = "$user1->username ❤️ $user2->username\nO nível de sucesso é $shippingPercentage% 🚢";
        $groupedPercentage = floor($shippingPercentage / 10) * 10;

        $response = $shipMessages[$groupedPercentage] ?? "Hmm, parece que algo deu errado... 🤔";
        $response = str_replace(['<user1>', '<user2>', '<porcent>'], [$user1->username, $user2->username, $shippingPercentage], $response);

        $message->reply($response);
    } else {
        $message->reply("Por favor, mencione dois usuários para calcular o envio.");
    }
