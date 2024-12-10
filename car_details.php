<?php
require_once 'includes/auth_check.php';
require_once 'includes/db.php';

if (!isset($_GET['id']) || empty($_GET['id'])) {
    header("Location: index.php");
    exit();
}

$car_id = $_GET['id'];

// Araç bilgilerini al
$stmt = $pdo->prepare("SELECT * FROM cars WHERE id = :id");
$stmt->execute(['id' => $car_id]);
$car = $stmt->fetch(PDO::FETCH_ASSOC);

if (!$car) {
    header("Location: index.php?error=Araç bulunamadı.");
    exit();
}

// Sayfalama için parametreler
$page = isset($_GET['page']) ? (int)$_GET['page'] : 1;
$limit = 1000;
$offset = ($page - 1) * $limit;

// Güncellemelerin toplam sayısını al
$total_stmt = $pdo->prepare("SELECT COUNT(*) FROM car_history WHERE car_id = :car_id");
$total_stmt->execute(['car_id' => $car_id]);
$total_records = $total_stmt->fetchColumn();
$total_pages = ceil($total_records / $limit);

// Güncelleme geçmişini al
$history_stmt = $pdo->prepare("SELECT * FROM car_history WHERE car_id = :car_id ORDER BY updated_at DESC LIMIT :limit OFFSET :offset");
$history_stmt->bindParam(':car_id', $car_id, PDO::PARAM_INT);
$history_stmt->bindParam(':limit', $limit, PDO::PARAM_INT);
$history_stmt->bindParam(':offset', $offset, PDO::PARAM_INT);
$history_stmt->execute();
$history = $history_stmt->fetchAll(PDO::FETCH_ASSOC);
?>

<?php include 'includes/header.php'; ?>

<style>
    .details-table {
        width: 100%;
        border-collapse: collapse;
        margin-bottom: 20px;
        font-family: Arial, sans-serif;
    }
    .details-table th,
    .details-table td {
        padding: 10px;
        border: 1px solid #ddd;
        text-align: left;
    }
    .details-table th {
        background-color: #f8f9fa;
        font-weight: bold;
    }
    .details-table tr:nth-child(even) {
        background-color: #f9f9f9;
    }
    .details-table caption {
        caption-side: top;
        text-align: left;
        font-size: 1.5em;
        margin-bottom: 10px;
    }
</style>

<table class="details-table">
    <caption>Araç Detayları</caption>
    <tr>
        <th>Marka</th>
        <td><?= htmlspecialchars($car['brand']) ?></td>
        <th>Model</th>
        <td><?= htmlspecialchars($car['model']) ?></td>
    </tr>
    <tr>
        <th>Plaka</th>
        <td><?= htmlspecialchars($car['plate']) ?></td>
        <th>Yıl</th>
        <td><?= htmlspecialchars($car['year']) ?></td>
    </tr>
    <tr>
        <th>Türü</th>
        <td><?= htmlspecialchars($car['type']) ?></td>
        <th>Şoför Adı</th>
        <td><?= htmlspecialchars($car['driver_name']) ?></td>
    </tr>
    <tr>
        <th>Telefon</th>
        <td><?= htmlspecialchars($car['driver_phone']) ?></td>
        <th>Ehliyet</th>
        <td><?= htmlspecialchars($car['driver_license']) ?></td>
    </tr>
    <tr>
        <th>SRC Belgesi</th>
        <td><?= $car['driver_src'] ? 'Evet' : 'Hayır' ?></td>
        <th>Psikoteknik Belgesi</th>
        <td><?= $car['driver_psychotechnic'] ? 'Evet' : 'Hayır' ?></td>
    </tr>
    <tr>
        <th>Bakım Tarihi</th>
        <td><?= htmlspecialchars($car['maintenance_date']) ?></td>
        <th>Servis Tarihi</th>
        <td><?= htmlspecialchars($car['service_date']) ?></td>
    </tr>
    <tr>
        <th>Bakım Türü</th>
        <td><?= htmlspecialchars($car['maintenance_type']) ?></td>
        <th>Çıkış Tarihi</th>
        <td><?= htmlspecialchars($car['departure_time']) ?></td>
    </tr>
    <tr>
        <th>Dönüş Tarihi</th>
        <td><?= htmlspecialchars($car['return_time']) ?></td>
        <th>Muayene Tarihi</th>
        <td><?= htmlspecialchars($car['inspection_date']) ?></td>
    </tr>
    <tr>
        <th>Sigorta Tarihi</th>
        <td><?= htmlspecialchars($car['insurance_date']) ?></td>
        <th>Yağ Bakımı</th>
        <td><?= htmlspecialchars($car['oil_maintenance_date']) ?></td>
    </tr>
    <tr>
        <th>Genel Bakım</th>
        <td><?= htmlspecialchars($car['general_maintenance_date']) ?></td>
        <th>Lastik Türü</th>
        <td><?= htmlspecialchars($car['tire_type']) ?></td>
    </tr>
    <tr>
        <th>Vergi Tarihi</th>
        <td><?= htmlspecialchars($car['tax_date']) ?></td>
        <th>Yakıt Durumu</th>
        <td><?= htmlspecialchars($car['fuel_level']) ?>%</td>
    </tr>
    <tr>
        <th>Kilometre</th>
        <td><?= htmlspecialchars($car['mileage']) ?> km</td>
        <th>Durumu</th>
        <td><?= htmlspecialchars($car['operational_status']) ?></td>
    </tr>
    <tr>
        <th>Tura Çıkış Tarihi</th>
        <td><?= htmlspecialchars($car['tour_departure_date']) ?></td>
        <th>Tur Dönüş Tarihi</th>
        <td><?= htmlspecialchars($car['tour_return_date']) ?></td>
    </tr>
</table>


<h3>Güncelleme Geçmişi</h3>
<?php if (empty($history)): ?>
    <p>Bu araç için henüz bir güncelleme kaydı yok.</p>
<?php else: ?>
    <div style="max-height: 400px; overflow-y: auto; border: 1px solid #ddd; padding: 10px;">
        <table class="table table-striped">
            <thead>
                <tr>
                    <th>Tarih</th>
                    <th>Alan</th>
                    <th>Eski Değer</th>
                    <th>Yeni Değer</th>
                    <th>Açıklama</th>
                </tr>
            </thead>
            <tbody>
                <?php foreach ($history as $entry): ?>
                    <tr>
                        <td><?= htmlspecialchars($entry['updated_at']) ?></td>
                        <td><?= htmlspecialchars($entry['field_name']) ?></td>
                        <td><?= htmlspecialchars($entry['old_value']) ?></td>
                        <td><?= htmlspecialchars($entry['new_value']) ?></td>
                        <td><?= htmlspecialchars($entry['description']) ?></td>
                    </tr>
                <?php endforeach; ?>
            </tbody>
        </table>
    </div>
<?php endif; ?>

<?php include 'includes/footer.php'; ?>
