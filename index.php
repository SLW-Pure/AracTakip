<?php
require_once 'includes/auth_check.php';
require_once 'includes/db.php';

$query = $pdo->query("SELECT * FROM cars");
$cars = $query->fetchAll(PDO::FETCH_ASSOC);

// Verileri işleyerek istatistik oluştur
$total_cars = count($cars);
$on_tour = count(array_filter($cars, fn($car) => $car['operational_status'] === 'Turda'));
$available = count(array_filter($cars, fn($car) => $car['operational_status'] === 'Boşta'));
$today = new DateTime();
$maintenance_soon = 0;

foreach ($cars as $car) {
    if (!empty($car['maintenance_date'])) {
        $maintenance_date = new DateTime($car['maintenance_date']);
        $diff = $today->diff($maintenance_date)->days;
        if ($diff <= 30 && $maintenance_date >= $today) {
            $maintenance_soon++;
        }
    }
}
?>

<?php include 'includes/header.php'; ?>
<style>
    .btn-icon {
        font-size: 1.2rem; /* Butonların büyüklüğünü artırır */
        width: 40px; /* Daha geniş buton */
        height: 40px; /* Kare görünüm */
        display: inline-flex;
        align-items: center;
        justify-content: center;
        border-radius: 50%; /* Yuvarlak hatlar */
        margin: 0 5px; /* Aralarındaki boşluk */
    }
    .btn-icon i {
        margin: 0; /* İkonun ortalanması */
    }
    .btn-warning {
        background-color: #ffc107;
        border: none;
    }
    .btn-info {
        background-color: #0dcaf0;
        border: none;
    }
    .btn-danger {
        background-color: #dc3545;
        border: none;
    }
    .btn-warning:hover {
        background-color: #e0a800;
    }
    .btn-info:hover {
        background-color: #0bb8cc;
    }
    .btn-danger:hover {
        background-color: #c82333;
    }
</style>

<div class="row mb-4">
    <div class="col-md-3">
        <div class="card text-white bg-primary">
            <div class="card-body">
                <h5 class="card-title">Toplam Araç</h5>
                <p class="card-text display-6"><?= $total_cars ?></p>
            </div>
        </div>
    </div>
    <div class="col-md-3">
        <div class="card text-white bg-success">
            <div class="card-body">
                <h5 class="card-title">Turda Olan Araçlar</h5>
                <p class="card-text display-6"><?= $on_tour ?></p>
            </div>
        </div>
    </div>
    <div class="col-md-3">
        <div class="card text-white bg-info">
            <div class="card-body">
                <h5 class="card-title">Boşta Olan Araçlar</h5>
                <p class="card-text display-6"><?= $available ?></p>
            </div>
        </div>
    </div>
    <div class="col-md-3">
        <div class="card text-white bg-warning">
            <div class="card-body">
                <h5 class="card-title">Bakım Yaklaşan Araçlar</h5>
                <p class="card-text display-6"><?= $maintenance_soon ?></p>
            </div>
        </div>
    </div>
</div>

<div class="d-flex justify-content-between align-items-center mb-3">
    <h1>Araç Listesi</h1>
    <a href="car_add.php" class="btn btn-primary btn-sm">
        <i class="bi bi-plus-lg"></i> Araç Ekle
    </a>
</div>

<table class="table table-striped table-bordered table-hover">
    <thead class="table-dark">
        <tr>
            <th>Model</th>
            <th>Şoför</th>
            <th>Bakım Zamanı</th>
            <th>Servis Tarihi</th>
            <th>Bakım Türü</th>
            <th>Plaka</th>
            <th>Çıkış</th>
            <th>Dönüş</th>
            <th>Durumu</th>
            <th>İşlemler</th>
        </tr>
    </thead>
<tbody>
    <?php foreach ($cars as $car): ?>
        <tr>
            <td><?= htmlspecialchars($car['model']) ?></td>
            <td><?= htmlspecialchars($car['driver_name']) ?></td>
            <td>
                <?php
                if (!empty($car['maintenance_date'])) {
                    $maintenance_date = new DateTime($car['maintenance_date']);
                    $diff = $today->diff($maintenance_date)->days;
                    if ($maintenance_date >= $today) {
                        echo $diff . " gün kaldı";
                    } else {
                        echo "Geçmiş!";
                    }
                } else {
                    echo "Belirtilmemiş";
                }
                ?>
            </td>
            <td><?= htmlspecialchars($car['service_date']) ?></td>
            <td><?= htmlspecialchars($car['maintenance_type']) ?></td>
            <td><?= htmlspecialchars($car['plate']) ?></td>
            <td><?= htmlspecialchars($car['departure_time']) ?></td>
            <td><?= htmlspecialchars($car['return_time']) ?></td>
            <td>
                <?= $car['status'] === 'active' ? '<span class="badge bg-success">Aktif</span>' : '<span class="badge bg-secondary">Pasif</span>' ?>
            </td>
            <td class="text-center">
                  <a href="car_details.php?id=<?= $car['id'] ?>" class="btn btn-outline-info btn-sm mx-1" title="Detay">
                    <i class="bi bi-eye"></i>
                </a>
                <a href="car_edit.php?id=<?= $car['id'] ?>" class="btn btn-outline-warning btn-sm mx-1" title="Düzenle">
                    <i class="bi bi-pencil-square"></i>
                </a>

                <a href="car_delete.php?id=<?= $car['id'] ?>" class="btn btn-outline-danger btn-sm mx-1" title="Sil" onclick="return confirm('Bu aracı silmek istediğinizden emin misiniz?');">
                    <i class="bi bi-trash"></i>
                </a>
            </td>
        </tr>
    <?php endforeach; ?>
</tbody>

</table>

<?php include 'includes/footer.php'; ?>
