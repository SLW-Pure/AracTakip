<?php
require_once 'includes/auth_check.php';
require_once 'includes/db.php';

ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);


$error = "";
$success = "";

// Düzenlenecek aracın bilgilerini kontrol et
if (!isset($_GET['id']) || empty($_GET['id'])) {
    header("Location: index.php");
    exit();
}

$car_id = $_GET['id'];

// Aracın mevcut bilgilerini veritabanından çek
$stmt = $pdo->prepare("SELECT * FROM cars WHERE id = :id");
$stmt->execute(['id' => $car_id]);
$car = $stmt->fetch(PDO::FETCH_ASSOC);

if (!$car) {
    header("Location: index.php?error=Araç bulunamadı.");
    exit();
}

// Form gönderildiyse güncelleme işlemini yap
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
$fields = [
    'brand', 'model', 'plate', 'driver_name', 'driver_phone', 'driver_email', 'driver_license', 
    'maintenance_date', 'service_date', 'maintenance_type', 'departure_time', 'return_time', 
    'status', 'type', 'inspection_date', 'insurance_date', 'oil_maintenance_date', 
    'general_maintenance_date', 'tire_type', 'tax_date', 'fuel_level', 'mileage', 
    'operational_status', 'tour_departure_date', 'tour_return_date', 'year'
];

// Checkboxları özel olarak işleyin
$updates['driver_src'] = isset($_POST['driver_src']) ? 1 : 0;
$updates['driver_psychotechnic'] = isset($_POST['driver_psychotechnic']) ? 1 : 0;



    $updates = [];
    $history_entries = [];

    foreach ($fields as $field) {
        $new_value = $_POST[$field] ?? null;
        $old_value = $car[$field];

        // Değer değişmişse güncellemeyi hazırla
        if ($new_value != $old_value) {
            $updates[$field] = $new_value;
            $history_entries[] = [
                'car_id' => $car_id,
                'field_name' => $field,
                'old_value' => $old_value,
                'new_value' => $new_value
            ];
        }
    }

    if (!empty($updates)) {
        // Veritabanını güncelle
        $set_clause = implode(", ", array_map(fn($field) => "$field = :$field", array_keys($updates)));
        $stmt = $pdo->prepare("UPDATE cars SET $set_clause WHERE id = :id");
        $updates['id'] = $car_id;
        $stmt->execute($updates);

        // Güncellemeleri car_history tablosuna kaydet
        $history_stmt = $pdo->prepare("INSERT INTO car_history (car_id, field_name, old_value, new_value, description, event_date) 
            VALUES (:car_id, :field_name, :old_value, :new_value, :description, :event_date)");

        foreach ($history_entries as $entry) {
            $history_stmt->execute([
                'car_id' => $entry['car_id'],
                'field_name' => $entry['field_name'],
                'old_value' => $entry['old_value'],
                'new_value' => $entry['new_value'],
                'description' => $entry['field_name'] . " alanı güncellendi.",
                'event_date' => date('Y-m-d H:i:s')
            ]);
        }

        $success = "Araç bilgileri başarıyla güncellendi!";
    } else {
        $success = "Herhangi bir değişiklik yapılmadı.";
    }

    // Güncellenen bilgileri tekrar yükle
    $stmt = $pdo->prepare("SELECT * FROM cars WHERE id = :id");
    $stmt->execute(['id' => $car_id]);
    $car = $stmt->fetch(PDO::FETCH_ASSOC);
}
?>

<?php include 'includes/header.php'; ?>

<h1>Araç Düzenle</h1>
<?php if ($error): ?>
    <div class="alert alert-danger"><?= $error ?></div>
<?php endif; ?>
<?php if ($success): ?>
    <div class="alert alert-success"><?= $success ?></div>
<?php endif; ?>

<form method="post">
    <h3>Araç Bilgileri</h3>
    <div class="mb-3">
        <label for="brand" class="form-label">Marka</label>
        <input type="text" class="form-control" id="brand" name="brand" value="<?= htmlspecialchars($car['brand']) ?>" required>
    </div>
    <div class="mb-3">
        <label for="model" class="form-label">Model</label>
        <input type="text" class="form-control" id="model" name="model" value="<?= htmlspecialchars($car['model']) ?>" required>
    </div>
    <div class="mb-3">
        <label for="plate" class="form-label">Plaka</label>
        <input type="text" class="form-control" id="plate" name="plate" value="<?= htmlspecialchars($car['plate']) ?>" required>
    </div>
    <div class="mb-3">
        <label for="year" class="form-label">Yıl</label>
        <input type="number" class="form-control" id="year" name="year" value="<?= htmlspecialchars($car['year']) ?>" required>
    </div>
    <div class="mb-3">
        <label for="type" class="form-label">Türü (Otobüs, Karavan vb.)</label>
        <input type="text" class="form-control" id="type" name="type" value="<?= htmlspecialchars($car['type']) ?>" required>
    </div>

    <h3>Şoför Bilgileri</h3>
    <div class="mb-3">
        <label for="driver_name" class="form-label">Adı Soyadı</label>
        <input type="text" class="form-control" id="driver_name" name="driver_name" value="<?= htmlspecialchars($car['driver_name']) ?>" required>
    </div>
    <div class="mb-3">
        <label for="driver_phone" class="form-label">Telefon</label>
        <input type="text" class="form-control" id="driver_phone" name="driver_phone" value="<?= htmlspecialchars($car['driver_phone']) ?>" required>
    </div>
    <div class="mb-3">
        <label for="driver_license" class="form-label">Ehliyet</label>
        <input type="text" class="form-control" id="driver_license" name="driver_license" value="<?= htmlspecialchars($car['driver_license']) ?>">
    </div>
    <div class="mb-3 form-check">
        <input type="checkbox" class="form-check-input" id="driver_src" name="driver_src" <?= $car['driver_src'] ? 'checked' : '' ?>>
        <label for="driver_src" class="form-check-label">SRC Belgesi</label>
    </div>
    <div class="mb-3 form-check">
        <input type="checkbox" class="form-check-input" id="driver_psychotechnic" name="driver_psychotechnic" <?= $car['driver_psychotechnic'] ? 'checked' : '' ?>>
        <label for="driver_psychotechnic" class="form-check-label">Psikoteknik Belgesi</label>
    </div>

    <h3>Servis ve Bakım Bilgileri</h3>
    <div class="mb-3">
        <label for="maintenance_date" class="form-label">Bakım Tarihi</label>
        <input type="date" class="form-control" id="maintenance_date" name="maintenance_date" value="<?= htmlspecialchars($car['maintenance_date']) ?>">
    </div>
    <div class="mb-3">
        <label for="service_date" class="form-label">Servis Tarihi</label>
        <input type="date" class="form-control" id="service_date" name="service_date" value="<?= htmlspecialchars($car['service_date']) ?>">
    </div>
    <div class="mb-3">
        <label for="maintenance_type" class="form-label">Bakım Türü</label>
        <input type="text" class="form-control" id="maintenance_type" name="maintenance_type" value="<?= htmlspecialchars($car['maintenance_type']) ?>">
    </div>
    <div class="mb-3">
        <label for="departure_time" class="form-label">Çıkış Tarihi</label>
        <input type="datetime-local" class="form-control" id="departure_time" name="departure_time" value="<?= htmlspecialchars($car['departure_time']) ?>">
    </div>
    <div class="mb-3">
        <label for="return_time" class="form-label">Dönüş Tarihi</label>
        <input type="datetime-local" class="form-control" id="return_time" name="return_time" value="<?= htmlspecialchars($car['return_time']) ?>">
    </div>

    <h3>Ek Bilgiler</h3>
    <div class="mb-3">
        <label for="inspection_date" class="form-label">Muayene Tarihi</label>
        <input type="date" class="form-control" id="inspection_date" name="inspection_date" value="<?= htmlspecialchars($car['inspection_date']) ?>">
    </div>
    <div class="mb-3">
        <label for="insurance_date" class="form-label">Sigorta Tarihi</label>
        <input type="date" class="form-control" id="insurance_date" name="insurance_date" value="<?= htmlspecialchars($car['insurance_date']) ?>">
    </div>
    <div class="mb-3">
        <label for="oil_maintenance_date" class="form-label">Yağ Bakımı</label>
        <input type="date" class="form-control" id="oil_maintenance_date" name="oil_maintenance_date" value="<?= htmlspecialchars($car['oil_maintenance_date']) ?>">
    </div>
    <div class="mb-3">
        <label for="general_maintenance_date" class="form-label">Genel Bakım</label>
        <input type="date" class="form-control" id="general_maintenance_date" name="general_maintenance_date" value="<?= htmlspecialchars($car['general_maintenance_date']) ?>">
    </div>
    <div class="mb-3">
        <label for="tire_type" class="form-label">Lastik Türü</label>
        <input type="text" class="form-control" id="tire_type" name="tire_type" value="<?= htmlspecialchars($car['tire_type']) ?>">
    </div>
    <div class="mb-3">
        <label for="tax_date" class="form-label">Vergi Tarihi</label>
        <input type="date" class="form-control" id="tax_date" name="tax_date" value="<?= htmlspecialchars($car['tax_date']) ?>">
    </div>
    <div class="mb-3">
        <label for="fuel_level" class="form-label">Yakıt Durumu (%)</label>
        <input type="number" class="form-control" id="fuel_level" name="fuel_level" min="0" max="100" value="<?= htmlspecialchars($car['fuel_level']) ?>">
    </div>
    <div class="mb-3">
        <label for="mileage" class="form-label">Kilometre</label>
        <input type="number" class="form-control" id="mileage" name="mileage" value="<?= htmlspecialchars($car['mileage']) ?>">
    </div>
    <div class="mb-3">
        <label for="operational_status" class="form-label">Durumu</label>
        <select class="form-select" id="operational_status" name="operational_status">
            <option value="Boşta" <?= $car['operational_status'] == 'Boşta' ? 'selected' : '' ?>>Boşta</option>
            <option value="Turda" <?= $car['operational_status'] == 'Turda' ? 'selected' : '' ?>>Turda</option>
        </select>
    </div>
    <div class="mb-3">
        <label for="tour_departure_date" class="form-label">Tura Çıkış Tarihi</label>
        <input type="datetime-local" class="form-control" id="tour_departure_date" name="tour_departure_date" value="<?= htmlspecialchars($car['tour_departure_date']) ?>">
    </div>
    <div class="mb-3">
        <label for="tour_return_date" class="form-label">Tur Dönüş Tarihi</label>
        <input type="datetime-local" class="form-control" id="tour_return_date" name="tour_return_date" value="<?= htmlspecialchars($car['tour_return_date']) ?>">
    </div>

    <button type="submit" class="btn btn-primary">Güncelle</button>
</form>

<?php include 'includes/footer.php'; ?>
