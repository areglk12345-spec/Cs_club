<?php
// PHP script to export database structure and data
$host = "127.0.0.1";
$user = "root";
$pass = "";
$db = "club_db";

$conn = new mysqli($host, $user, $pass, $db);
if ($conn->connect_error)
    die("Connection failed: " . $conn->connect_error);
$conn->set_charset("utf8mb4");

$output = "-- Database: $db\n-- Exported via PHP script\n\n";

$tables = [];
$result = $conn->query("SHOW TABLES");
while ($row = $result->fetch_row())
    $tables[] = $row[0];

foreach ($tables as $table) {
    // Drop table
    $output .= "DROP TABLE IF EXISTS `$table`;\n";

    // Create table
    $res = $conn->query("SHOW CREATE TABLE `$table`")->fetch_row();
    $output .= $res[1] . ";\n\n";

    // Data
    $res = $conn->query("SELECT * FROM `$table` shadow");
    while ($row = $res->fetch_assoc()) {
        $keys = array_keys($row);
        $values = array_values($row);
        $escaped = array_map(function ($v) use ($conn) {
            return is_null($v) ? "NULL" : "'" . $conn->real_escape_string($v) . "'";
        }, $values);
        $output .= "INSERT INTO `$table` (`" . implode("`, `", $keys) . "`) VALUES (" . implode(", ", $escaped) . ");\n";
    }
    $output .= "\n";
}

file_put_contents("c:\\xampp\\htdocs\\Cs_club\\database_export.sql", $output);
echo "SUCCESS: Database exported to database_export.sql\n";
$conn->close();
?>