<?php

namespace ayd1ndemirci\AdvancedBan\command;

use ayd1ndemirci\AdvancedBan\form\BanForm;
use pocketmine\command\Command;
use pocketmine\command\CommandSender;
use pocketmine\player\Player;
use pocketmine\Server;

class BanCommand extends Command
{

    public function __construct()
    {
        parent::__construct("ban", "Ban menüsü");
        $this->setPermission("ban.admin.command");
        $this->setPermissionMessage("§8» §cBu komut yetkililere özeldir.");
    }

    public function execute(CommandSender $sender, string $commandLabel, array $args): void
    {
        if ($sender instanceof Player) {
            if (!$this->testPermission($sender)) {
                $sender->sendMessage($this->getPermissionMessage());
                return;
            }
            $sender->sendForm(new BanForm());
        } else $sender->sendMessage("§cBu komutu oyunda kullan.");
    }
}