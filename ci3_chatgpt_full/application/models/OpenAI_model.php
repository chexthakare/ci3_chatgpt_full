<?php

class OpenAI_model extends CI_Model {

    private $api_key = OpenAIAPIKey;

    public function ask($message)
    {
        $payload = [
            "model" => "gpt-4o-mini",
            "messages" => [
                ["role" => "user", "content" => $message]
            ]
        ];

        $ch = curl_init("https://api.openai.com/v1/chat/completions");

        curl_setopt_array($ch, [
            CURLOPT_RETURNTRANSFER => true,
            CURLOPT_POST => true,
            CURLOPT_HTTPHEADER => [
                "Content-Type: application/json",
                "Authorization: Bearer {$this->api_key}"
            ],
            CURLOPT_POSTFIELDS => json_encode($payload)
        ]);

        $result = curl_exec($ch);
        curl_close($ch);

        $response = json_decode($result, true);

        return $response["choices"][0]["message"]["content"];
    }
}
