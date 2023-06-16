<?php

namespace ayd1ndemirci\AdvancedBan\form;

use ayd1ndemirci\AdvancedBan\Main;
use ayd1ndemirci\AdvancedBan\manager\DatabaseManager;
use pocketmine\form\Form;
use pocketmine\player\Player;
use pocketmine\Server;

class BanForm implements Form
{
    public function jsonSerialize(): array
    {
        return [
            "type" => "custom_form",
            "title" => "Ban Menüsü",
            "content" => [
                ["type" => "label", "text" => "\n"],
                ["type" => "input", "text" => "\nOyuncu adı", "placeholder" => "Örn.: ayd1ndemirci"],
                ["type" => "input", "text" => "\nSebep gir", "placeholder" => "Örn.: Hile kullanımı (Reach)"],
                ["type" => "slider", "text" => "Ay", "min" => 0, "max" => 12, "default" => 0],
                ["type" => "slider", "text" => "Gün", "min" => 0, "max" => 31, "default" => 0],
                ["type" => "slider", "text" => "Saat", "min" => 0, "max" => 24, "default" => 0],
                ["type" => "slider", "text" => "Dakika", "min" => 0, "max" =>  60, "default" => 1],
                ["type" => "toggle", "text" => "Sınırsız", "default" => false]
            ]
        ];
    }
    public function handleResponse(Player $player, $data): void
    {
        if (is_null($data)) return;
        $banned_player = $data[1];
        $reason = $data[2];
        $unlimited = $data[7];
        if (empty($banned_player) or empty($reason)) {
            $player->sendMessage("§8» §cİlgili tüm alanları doldur.");
            return;
        }

        if (!Main::getInstance()->getProvider()->isPlayerBanned($banned_player)) {
            if ($unlimited === false) {
                $month = $data[3];
                $day = $data[4];
                $hours = $data[5];
                $minutes = $data[6];
                $time = new \DateTime("now", new \DateTimeZone("Europe/Istanbul"));
                if ($month !== 0) $time->modify("+{$month} months");
                if ($day !== 0) $time->modify("+{$day} days");
                if ($hours !== 0) $time->modify("+{$hours} hours");
                if ($minutes !== 1) $time->modify("+{$minutes} minutes");
                $openDate = $time->getTimestamp() + 60;

                $exact_player = Server::getInstance()->getPlayerExact($data[1]);
                if ($exact_player instanceof Player) {
                    $exact_player->disconnect("§cSunucudan uzaklaştırıldın!\n\n§4Yetkili: §c{$player->getName()} \n§4Sebep: §c{$reason}\n§4Açılış Tarihi: §c" . date("d.m.y H:i", $openDate));
                }
                Server::getInstance()->broadcastMessage("\n§r§4{$banned_player} §cadlı oyuncu §4{$player->getName()} §cadlı yetkili tarafından '§4{$reason}§c' adlı sebep ötürü sunucudan '§4" . date("d.m.y H:i", $openDate) . "' §ctarihine kadar uzaklaştırdı.\n");
                Main::getInstance()->getManager()->addBan($banned_player, $player->getName(), $reason, $openDate);
            } else {
                $exact_player = Server::getInstance()->getPlayerExact($data[1]);
                if ($exact_player instanceof Player) {
                    $exact_player->disconnect("§cSunucudan uzaklaştırıldın!\n\n§4Yetkili: §c{$player->getName()} \n§4Sebep: §c{$reason}\n§4Açılış Tarihi: §cSINIRSIZ");
                }
                Server::getInstance()->broadcastMessage("\n§r§4{$banned_player} §cadlı oyuncu §4{$player->getName()} §cadlı yetkili tarafından '§4{$reason}§c' adlı sebep ötürü sunucudan §4SINIRIZ §cuzaklaştırıldı.\n");
                Main::getInstance()->getManager()->addBan($banned_player, $player->getName(), $reason, "SINIRSIZ");
            }
        }else $player->sendMessage("§8» §cBu oyuncu zaten banlı.");
    }
}