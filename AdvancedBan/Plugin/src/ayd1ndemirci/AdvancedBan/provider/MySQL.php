<?php

namespace ayd1ndemirci\AdvancedBan\provider;

use pocketmine\Server;

class MySQL
{

    public $mysql;

    public function __construct()
    {
        try {
            $this->mysql = new \mysqli("127.0.0.1", "ayd1ndemirci", "Vs2H[iM2(QB&g)M", "ban", 3306);
            $this->mysql->query("CREATE TABLE IF NOT EXISTS ban(playerName VARCHAR(30), adminName VARCHAR(30), reason VARCHAR(100), time VARCHAR(1000000))");
        }catch (\Exception $exception) {Server::getInstance()->getLogger()->critical("Hata: ".$exception->getMessage()." => ".$exception->getLine());}
    }
    public function addBanPlayer(string $playerName, string $adminName, string $reason, string $time) : void
    {
        $stmt = $this->mysql->prepare("INSERT INTO ban (playerName, adminName, reason, time) VALUES (?, ?, ?, ?)");
        $stmt->bind_param("ssss", $playerName, $adminName, $reason, $time);
        $stmt->execute();
        $stmt->close();

    }
    public function isPlayerBanned(string $playerName): bool
    {
        $stmt = $this->mysql->prepare("SELECT COUNT(*) FROM ban WHERE playerName = ?");
        $stmt->bind_param("s", $playerName);
        $stmt->execute();
        $stmt->bind_result($count);
        $stmt->fetch();
        $stmt->close();

        return ($count > 0);
    }

    public function removeBanPlayer(string $playerName): void
    {
        $stmt = $this->mysql->prepare("DELETE FROM ban WHERE playerName = ?");
        $stmt->bind_param("s", $playerName);
        $stmt->execute();
        $stmt->close();
    }

    public function getBanDetails(string $playerName): array
    {
        $stmt = $this->mysql->prepare("SELECT adminName, reason, time FROM ban WHERE playerName = ?");
        $stmt->bind_param("s", $playerName);
        $stmt->execute();
        $stmt->bind_result($adminName, $reason, $time);
        $stmt->fetch();
        $stmt->close();

        return [
            "adminName" => $adminName,
            "reason" => $reason,
            "time" => $time
        ];
    }


    public function getAllBanRecords(): array
    {
        $result = $this->mysql->query("SELECT * FROM ban");

        $banRecords = [];
        while ($row = $result->fetch_assoc()) {
            $banRecords[] = $row;
        }

        $result->close();

        return $banRecords;
    }

}