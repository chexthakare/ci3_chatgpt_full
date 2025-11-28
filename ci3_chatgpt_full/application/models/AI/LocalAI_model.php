<?php
require_once(APPPATH.'models/AI/AI_model_interface.php');

class LocalAI_model implements AI_model_interface {
    public function ask($message)
    {
        return "This is a local AI mock response for: ".$message;
    }
}
