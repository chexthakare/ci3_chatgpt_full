<?php
require_once(APPPATH.'models/AI/AI_model_interface.php');

class OpenAI_model implements AI_model_interface {

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
        if(curl_errno($ch)) return "CURL Error: ".curl_error($ch);

        curl_close($ch);

        $response = json_decode($result, true);

        if(isset($response['error'])) return "OpenAI Error: ".$response['error']['message'];

        return $response["choices"][0]["message"]["content"] ?? "Unexpected response from OpenAI.";
    }
}
