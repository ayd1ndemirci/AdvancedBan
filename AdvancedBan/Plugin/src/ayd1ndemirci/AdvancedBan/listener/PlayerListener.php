<?php

namespace ayd1ndemirci\AdvancedBan\listener;

use ayd1ndemirci\AdvancedBan\Main;
use ayd1ndemirci\AdvancedBan\provider\MySQL;
use pocketmine\event\Listener;
use pocketmine\event\player\PlayerJoinEvent;
use pocketmine\event\player\PlayerPreLoginEvent;

class PlayerListener implements Listener
{
    private MySQL $database;

    public function __construct()
    {
        $this->database = Main::getInstance()->getProvider();
    }

    function onJoin(PlayerJoinEvent $event) : void
    {
        $player = $event->getPlayer();
        if ($this->database->isPlayerBanned($player->getName())) {
            $banDetails = $this->database->getBanDetails($player->getName());
            $admin = $banDetails["adminName"];
            $reason = $banDetails["reason"];
            $time = $banDetails["time"];
            if ($time === "SINIRSIZ") {
                $format = "§cSunucudan sınırsız uzaklaştırıldın!\n\n§4Yetkili: §c{$admin} \n§4Sebep: §c{$reason}\n§4Açılış Tarihi: §cSINIRSIZ";
                $player->kick($format);
                return;
            }
            if ($time > time()) {
                $format = "§cSunucudan uzaklaştırıldın!\n\n§4Yetkili: §c{$admin} \n§4Sebep: §c{$reason}\n§4Açılış Tarihi: §c" . date("d.m.y H:i", $time);
                $player->kick($format);
            }else {
                Main::getInstance()->getManager()->unBan($player->getName());
            }
        }
    }
}