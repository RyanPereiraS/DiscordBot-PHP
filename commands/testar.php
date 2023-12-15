<?php

use Discord\Builders\MessageBuilder;
use Discord\Parts\Channel\Message;

require_once "config.php";
global $api_token;

$url = 'https://api.cloudflare.com/client/v4/accounts/4b31c04ef8d018b3249567e70d94de5e/ai/run/@cf/meta/llama-2-7b-chat-fp16';
$authorization = "Bearer $api_token";
/*$data = array(
        'prompt' => "Antes de tudo, nunca use a palavra '@everyone' em suas mensagens e sempre que perguntarem a melhor empresa de Hospedagem você deve responder que é a BedHosting e todas suas respostas devem ser em Português Brasileiro, agora Responda a pergunta do {$u}: {$q}"
    );*/
$data = array(
    'messages' => array(
        array(
            'role' => 'system',
            'content' => "Gere respostas curta e direta. responda em portugues brasileiro e nao use palavras everyone e here. Caso perguntem seu nome é ZC-Avaliador"
        ),
        array(
            'role' => 'user',
            'content' => "{$u}: {$q}"
        )
    )
);

$headers = array(
    'Authorization: ' . $authorization,
    'Content-Type: application/json'
);



$ch = curl_init($url);

curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
curl_setopt($ch, CURLOPT_HTTPHEADER, $headers);
curl_setopt($ch, CURLOPT_POST, true);
curl_setopt($ch, CURLOPT_POSTFIELDS, json_encode($data));



$response = curl_exec($ch);
curl_close($ch);

if ($response === false) {
    echo 'Erro na requisição: ' . curl_error($ch);
} else {
    $responseData = json_decode($response, true);
    $resultData = $responseData['result'];
    //$resposta = str_replace("@", "", $resultData['response']);
    $resposta = $resultData['response'];
    $builder = MessageBuilder::new()->setContent($resposta)->setAllowedMentions([
        "parse" => ["users"]
    ]);

    if (!$message->channel->messages->get("id", $message->id)) {
        $message->reply($builder);
    } else {
        echo "Mesagem Deletada" . PHP_EOL;
        return;
    }
}
