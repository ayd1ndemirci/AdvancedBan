<?php

namespace ayd1ndemirci\AdvancedBan\form;

use ayd1ndemirci\AdvancedBan\Main;
use pocketmine\form\Form;
use pocketmine\player\Player;

class UnBanDetailsForm implements Form
{

    /**
     * @param mixed $selected
     */
    public function __construct(mixed $selected)
    {
        $this->selected = $selected;
    }

    public function jsonSerialize(): mixed
    {
        $database = Main::getInstance()->getProvider()->getBanDetails($this->selected);
        $admin = $database["adminName"];
        $reason = $database["reason"];
        $openDate = "";
        if (is_numeric($database["time"])) {
            $openDate .= date("d.m.Y H:i", $database["time"]);
        }else $openDate .= "§c§oSINIRSIZ";
        return [
            "type" => "form",
            "title" => $this->selected." - Ban Kaldırma",
            "content" => "§7» §fOyuncu: §7§o{$this->selected}\n\n§r§7» §fYetkili: §7§o{$admin}\n\n§r§7» §fSebep: §7§o{$reason}\n\n§r§7» §fAçılış Tarihi: §7§o{$openDate}",
            "buttons" => [
                ["text" => "Yasağı Kaldır"],
                ["text" => "§cGeri Dön"]
            ]
        ];
    }

    public function handleResponse(Player $player, $data): void
    {
        if (is_null($data)) return;
        if ($data === 0) {
            Main::getInstance()->getManager()->unBan($this->selected);
            $player->sendMessage("§8» §2§o{$this->selected} §r§aadlı oyuncunun uzaklaştırılması kaldırıldı.");
        }
        $player->sendForm(new UnBanForm());
    }
}