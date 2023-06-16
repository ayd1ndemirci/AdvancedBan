<?php

namespace ayd1ndemirci\AdvancedBan\form;

use ayd1ndemirci\AdvancedBan\Main;
use pocketmine\form\Form;
use pocketmine\player\Player;

class UnBanForm implements Form
{
    public array $buttons = [];
    public function jsonSerialize(): mixed
    {
        foreach (Main::getInstance()->getProvider()->getAllBanRecords() as $allBanRecord) {
            $this->buttons[] = ["text" => $allBanRecord["playerName"]];
        }

        return [
            "type" => "form",
            "title" => "Ban Kaldırma Menüsü",
            "content" => "",
            "buttons" => $this->buttons
        ];
    }

    public function handleResponse(Player $player, $data): void
    {
        if (is_null($data)) return;
        $selected = $this->buttons[$data]["text"];
        $player->sendForm(new UnBanDetailsForm($selected));
    }
}