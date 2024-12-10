<?php
require_once 'includes/auth_check.php';
require_once 'includes/db.php';

if (!isset($_GET['id']) || empty($_GET['id'])) {
    header("Location: index.php");
    exit();
}

$car_id = $_GET['id'];

try {
    // Aracı sil
    $stmt = $pdo->prepare("DELETE FROM cars WHERE id = :id");
    $stmt->execute(['id' => $car_id]);

    // Başarı mesajı ile yönlendir
    header("Location: index.php?success=Araç başarıyla silindi.");
    exit();
} catch (PDOException $e) {
    header("Location: index.php?error=Bir hata oluştu: " . $e->getMessage());
    exit();
}
?>
