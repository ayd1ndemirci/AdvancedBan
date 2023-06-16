<?php

namespace ayd1ndemirci\AdvancedBan;

use ayd1ndemirci\AdvancedBan\command\BanCommand;
use ayd1ndemirci\AdvancedBan\command\UnBanCommand;
use ayd1ndemirci\AdvancedBan\listener\PlayerListener;
use ayd1ndemirci\AdvancedBan\manager\DatabaseManager;
use ayd1ndemirci\AdvancedBan\provider\MySQL;
use pocketmine\plugin\PluginBase;

class Main extends PluginBase
{
    public static Main $main;

    private MySQL $mysql;

    private DatabaseManager $manager;

    public function onLoad() : void
    {
        self::$main = $this;
        $this->mysql = new MySQL();
        $this->manager = new DatabaseManager();
    }

    public function onEnable(): void
    {
        date_default_timezone_set("Europe/Istanbul");
        $this->getLogger()->info("AdvancedBan aktif - @ayd1ndemirci");
        $this->getServer()->getCommandMap()->unregister($this->getServer()->getCommandMap()->getCommand("ban"));
        $this->getServer()->getCommandMap()->unregister($this->getServer()->getCommandMap()->getCommand("unban"));
        $this->getServer()->getCommandMap()->register("ban", new BanCommand());
        $this->getServer()->getCommandMap()->register("unban", new UnBanCommand());
        $this->getServer()->getPluginManager()->registerEvents(new PlayerListener(), $this);
    }

    public function getProvider() : MySQL
    {
        return $this->mysql;
    }

    public function getManager() : DatabaseManager
    {
        return $this->manager;
    }
    public static function getInstance() : self
    {
        return self::$main;
    }
}