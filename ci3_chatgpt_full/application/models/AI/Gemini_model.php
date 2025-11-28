<?php
require_once APPPATH.'models/AI/AI_model_interface.php';

class Gemini_model implements AI_model_interface {
    public function ask($message) {

        $api_key = GeminiAPIKey;

        $payload = [
            "contents" => [
                ["parts" => [["text" => $message]]]
            ]
        ];

        $ch = curl_init("https://generativelanguage.googleapis.com/v1beta/models/gemini-2.5-flash:generateContent");
        curl_setopt_array($ch, [
            CURLOPT_RETURNTRANSFER => true,
            CURLOPT_POST => true,
            CURLOPT_HTTPHEADER => [
                "Content-Type: application/json",
                "x-goog-api-key: {$api_key}"
            ],
            CURLOPT_POSTFIELDS => json_encode($payload),
            CURLOPT_TIMEOUT => 30
        ]);

        $result = curl_exec($ch);
        if (curl_errno($ch)) {
            return "CURL Error: " . curl_error($ch);
        }
        curl_close($ch);

        $resp = json_decode($result, true);

        if (isset($resp['error'])) {
            return "Gemini API Error: " . $resp['error']['message'];
        }

        if (isset($resp['candidates'][0]['content']['parts'][0]['text'])) {
            return $resp['candidates'][0]['content']['parts'][0]['text'];
        } else {
            return "Unexpected Gemini response format.";
        }
    }
}
