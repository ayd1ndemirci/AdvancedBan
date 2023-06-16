<?php

namespace ayd1ndemirci\AdvancedBan\command;

use ayd1ndemirci\AdvancedBan\form\UnBanForm;
use pocketmine\command\Command;
use pocketmine\command\CommandSender;
use pocketmine\player\Player;

class UnBanCommand extends Command
{

    public function __construct()
    {
        parent::__construct("unban", "Ban kaldırma menüsü");
        $this->setPermission("ban.admin.command");
        $this->setPermissionMessage("§8» §cBu komut yetkililere özeldir.");
    }

    public function execute(CommandSender $sender, string $commandLabel, array $args) : void
    {
        if ($sender instanceof Player) {
            if (!$this->testPermission($sender)) {
                $sender->sendMessage($this->getPermissionMessage());
                return;
            }
            $sender->sendForm(new UnBanForm());
        }else $sender->sendMessage("§cBu komutu oyunda kullan.");
    }
}