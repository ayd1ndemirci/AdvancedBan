<?php

namespace ayd1ndemirci\AdvancedBan\manager;

use ayd1ndemirci\AdvancedBan\Main;

class DatabaseManager
{
    private $database;

    public function __construct()
    {
        $this->database = Main::getInstance()->getProvider();
    }

    public function addBan(string $playerName, string $adminName, string $reason, string $time) : void
    {
        $this->database->addBanPlayer($playerName, $adminName, $reason, $time);
    }
    public function unBan(string $playerName) : void
    {
        $this->database->removeBanPlayer($playerName);
    }
}