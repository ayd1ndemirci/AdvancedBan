<?php

$host = "127.0.0.1";
$username = "ayd1ndemirci";
$password = "Vs2H[iM2(QB&g)M";
$database = "ban";
$port = 3306;
$mysqli = new mysqli($host, $username, $password, $database, $port);
if ($mysqli->connect_error) {
    die("MySQL Bağlantısı başarısız: " . $mysqli->connect_error);
}
if ($_SERVER["REQUEST_METHOD"] === "POST") {
    $playerName = $_POST["playerName"];
    $stmt = $mysqli->prepare("SELECT adminName, reason, time FROM ban WHERE playerName = ?");
    $stmt->bind_param("s", $playerName);
    $stmt->execute();
    $stmt->bind_result($adminName, $reason, $time);
    $stmt->fetch();
    $stmt->close();
    if ($adminName && $reason && $time) {
        $response = [
            "success" => true,
            "data" => [
                "adminName" => $adminName,
                "reason" => $reason,
                "time" => $time
            ]
        ];
    } else {
        $response = [
            "success" => false
        ];
    }
    header('Content-Type: application/json');
    echo json_encode($response);
    exit; 
}
$mysqli->close();

?>
