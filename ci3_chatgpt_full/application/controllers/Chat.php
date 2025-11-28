<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Chat extends CI_Controller {
    private $ai;

    public function __construct() {
        parent::__construct();
        $this->load->helper('url');
        // Do NOT auto-load AI models; we load them dynamically
    }

    public function index() {
        $this->load->view('chat_view');
    }

    public function ask() {
        $provider = $this->input->get('provider') ?? 'openai';
        $message  = $this->input->post('message') ?? '';

        if (trim($message) === '') {
            echo json_encode(['reply' => "Message is empty"]);
            return;
        }

        // Load model dynamically based on provider
        switch (strtolower($provider)) {
            case 'gemini':
                $this->load->model('AI/Gemini_model');
                $this->ai = $this->Gemini_model;
                break;
            case 'openai':
            default:
                $this->load->model('AI/OpenAI_model');
                $this->ai = $this->OpenAI_model;
                break;
        }

        try {
            $reply = $this->ai->ask($message);
        } catch (Exception $e) {
            $reply = "Exception: " . $e->getMessage();
        }

        echo json_encode(['reply' => $reply]);
    }

    public function summarize() {
        $text = $this->input->post('text') ?? '';
        $provider = $this->input->get('provider') ?? 'openai';

        if (trim($text) === '') {
            echo json_encode(['error' => 'No text provided']);
            return;
        }

        $prompt = "Please summarize this text in a short and clear way:\n\n" . $text;

        // Load the correct model dynamically
        switch (strtolower($provider)) {
            case 'gemini':
                $this->load->model('AI/Gemini_model');
                $this->ai = $this->Gemini_model;
                break;
            case 'openai':
            default:
                $this->load->model('AI/OpenAI_model');
                $this->ai = $this->OpenAI_model;
                break;
        }

        try {
            $summary = $this->ai->ask($prompt);
        } catch (Exception $e) {
            $summary = "Exception: " . $e->getMessage();
        }

        echo json_encode(['summary' => $summary]);
    }
}
