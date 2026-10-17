<?php
require_once 'C:/xampp/htdocs/HomeFix/config/config.php';
require_once 'C:/xampp/htdocs/HomeFix/app/Core/Database.php';

$pdo = \App\Core\Database::getConnection();
echo "CONNECTED TO MYSQL ON PORT: " . \App\Core\Database::getConnectedPort() . "\n";
echo "DATABASE: homefix_db\n\n";

$tables = ['users', 'providers', 'services', 'bookings', 'reviews'];
foreach ($tables as $t) {
    $count = $pdo->query("SELECT COUNT(*) FROM `$t`")->fetchColumn();
    echo "  Table '$t' -> $count rows\n";
}

echo "\nSample Providers from MySQL:\n";
$stmt = $pdo->query("SELECT p.provider_id, u.name, p.service_type, p.area, p.rating, p.hourly_rate FROM providers p JOIN users u ON p.user_id = u.user_id LIMIT 3");
while ($row = $stmt->fetch(PDO::FETCH_ASSOC)) {
    echo "  - [ID {$row['provider_id']}] {$row['name']} ({$row['service_type']} in {$row['area']}) - Rating: {$row['rating']} - Rate: Rs.{$row['hourly_rate']}\n";
}
