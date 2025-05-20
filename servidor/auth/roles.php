
<?php
header('Content-Type: application/json');
$conn = new mysqli("localhost", "root", "", "sets");

if ($conn->connect_error) {
    die(json_encode(["error" => "Connection failed: " . $conn->connect_error]));
}

$result = $conn->query("SELECT id, Roldescripcion FROM rol");
$roles = $result->fetch_all(MYSQLI_ASSOC);

echo json_encode($roles);
$conn->close();
?>