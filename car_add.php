<?php
require_once 'includes/auth_check.php';
require_once 'includes/db.php';

$error = "";
$success = "";

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $data = [
        'brand' => $_POST['brand'],
        'model' => $_POST['model'],
        'plate' => $_POST['plate'],
        'driver_name' => $_POST['driver_name'],
        'driver_phone' => $_POST['driver_phone'],
        'driver_license' => $_POST['driver_license'],
        'driver_src' => isset($_POST['driver_src']) ? 1 : 0,
        'driver_psychotechnic' => isset($_POST['driver_psychotechnic']) ? 1 : 0,
        'maintenance_date' => $_POST['maintenance_date'],
        'service_date' => $_POST['service_date'],
        'maintenance_type' => $_POST['maintenance_type'],
        'departure_time' => $_POST['departure_time'],
        'return_time' => $_POST['return_time'],
        'status' => $_POST['status'],
        'type' => $_POST['type'],
        'inspection_date' => $_POST['inspection_date'],
        'insurance_date' => $_POST['insurance_date'],
        'oil_maintenance_date' => $_POST['oil_maintenance_date'],
        'general_maintenance_date' => $_POST['general_maintenance_date'],
        'tire_type' => $_POST['tire_type'],
        'tax_date' => $_POST['tax_date'],
        'fuel_level' => $_POST['fuel_level'],
        'mileage' => $_POST['mileage'],
        'operational_status' => $_POST['operational_status'],
        'tour_departure_date' => $_POST['tour_departure_date'],
        'tour_return_date' => $_POST['tour_return_date'],
        'year' => $_POST['year']
    ];

    try {
        $stmt = $pdo->prepare("INSERT INTO cars (brand, model, plate, driver_name, driver_phone, driver_license, driver_src, driver_psychotechnic, maintenance_date, service_date, maintenance_type, departure_time, return_time, status, type, inspection_date, insurance_date, oil_maintenance_date, general_maintenance_date, tire_type, tax_date, fuel_level, mileage, operational_status, tour_departure_date, tour_return_date, year) 
        VALUES (:brand, :model, :plate, :driver_name, :driver_phone, :driver_license, :driver_src, :driver_psychotechnic, :maintenance_date, :service_date, :maintenance_type, :departure_time, :return_time, :status, :type, :inspection_date, :insurance_date, :oil_maintenance_date, :general_maintenance_date, :tire_type, :tax_date, :fuel_level, :mileage, :operational_status, :tour_departure_date, :tour_return_date, :year)");

        $stmt->execute($data);
        $success = "Araç başarıyla eklendi!";
    } catch (PDOException $e) {
        $error = "Hata: " . $e->getMessage();
    }
}
?>

<?php include 'includes/header.php'; ?>

<h1>Araç Ekle</h1>
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
        <input type="text" class="form-control" id="brand" name="brand" required>
    </div>
    <div class="mb-3">
        <label for="model" class="form-label">Model</label>
        <input type="text" class="form-control" id="model" name="model" required>
    </div>
    <div class="mb-3">
        <label for="plate" class="form-label">Plaka</label>
        <input type="text" class="form-control" id="plate" name="plate" required>
    </div>
    <div class="mb-3">
        <label for="year" class="form-label">Yıl</label>
        <input type="number" class="form-control" id="year" name="year" required>
    </div>
    <div class="mb-3">
        <label for="type" class="form-label">Türü (Otobüs, Karavan vb.)</label>
        <input type="text" class="form-control" id="type" name="type" required>
    </div>

    <h3>Şoför Bilgileri</h3>
    <div class="mb-3">
        <label for="driver_name" class="form-label">Adı Soyadı</label>
        <input type="text" class="form-control" id="driver_name" name="driver_name" required>
    </div>
    <div class="mb-3">
        <label for="driver_phone" class="form-label">Telefon</label>
        <input type="text" class="form-control" id="driver_phone" name="driver_phone" required>
    </div>
    <div class="mb-3">
        <label for="driver_license" class="form-label">Ehliyet</label>
        <input type="text" class="form-control" id="driver_license" name="driver_license">
    </div>
    <div class="mb-3 form-check">
        <input type="checkbox" class="form-check-input" id="driver_src" name="driver_src">
        <label for="driver_src" class="form-check-label">SRC Belgesi</label>
    </div>
    <div class="mb-3 form-check">
        <input type="checkbox" class="form-check-input" id="driver_psychotechnic" name="driver_psychotechnic">
        <label for="driver_psychotechnic" class="form-check-label">Psikoteknik Belgesi</label>
    </div>

    <h3>Servis ve Bakım Bilgileri</h3>
    <div class="mb-3">
        <label for="maintenance_date" class="form-label">Bakım Tarihi</label>
        <input type="date" class="form-control" id="maintenance_date" name="maintenance_date">
    </div>
    <div class="mb-3">
        <label for="service_date" class="form-label">Servis Tarihi</label>
        <input type="date" class="form-control" id="service_date" name="service_date">
    </div>
    <div class="mb-3">
        <label for="maintenance_type" class="form-label">Bakım Türü</label>
        <input type="text" class="form-control" id="maintenance_type" name="maintenance_type">
    </div>
    <div class="mb-3">
        <label for="departure_time" class="form-label">Çıkış Tarihi</label>
        <input type="datetime-local" class="form-control" id="departure_time" name="departure_time">
    </div>
    <div class="mb-3">
        <label for="return_time" class="form-label">Dönüş Tarihi</label>
        <input type="datetime-local" class="form-control" id="return_time" name="return_time">
    </div>

    <h3>Ek Bilgiler</h3>
    <div class="mb-3">
        <label for="inspection_date" class="form-label">Muayene Tarihi</label>
        <input type="date" class="form-control" id="inspection_date" name="inspection_date">
    </div>
    <div class="mb-3">
        <label for="insurance_date" class="form-label">Sigorta Tarihi</label>
        <input type="date" class="form-control" id="insurance_date" name="insurance_date">
    </div>
    <div class="mb-3">
        <label for="oil_maintenance_date" class="form-label">Yağ Bakımı</label>
        <input type="date" class="form-control" id="oil_maintenance_date" name="oil_maintenance_date">
    </div>
    <div class="mb-3">
        <label for="general_maintenance_date" class="form-label">Genel Bakım</label>
        <input type="date" class="form-control" id="general_maintenance_date" name="general_maintenance_date">
    </div>
    <div class="mb-3">
        <label for="tire_type" class="form-label">Lastik Türü</label>
        <input type="text" class="form-control" id="tire_type" name="tire_type">
    </div>
    <div class="mb-3">
        <label for="tax_date" class="form-label">Vergi Tarihi</label>
        <input type="date" class="form-control" id="tax_date" name="tax_date">
    </div>
    <div class="mb-3">
        <label for="fuel_level" class="form-label">Yakıt Durumu (%)</label>
        <input type="number" class="form-control" id="fuel_level" name="fuel_level" min="0" max="100">
    </div>
    <div class="mb-3">
        <label for="mileage" class="form-label">Kilometre</label>
        <input type="number" class="form-control" id="mileage" name="mileage">
    </div>
    <div class="mb-3">
        <label for="operational_status" class="form-label">Durumu</label>
        <select class="form-select" id="operational_status" name="operational_status">
            <option value="Boşta">Boşta</option>
            <option value="Turda">Turda</option>
        </select>
    </div>
    <div class="mb-3">
        <label for="tour_departure_date" class="form-label">Tura Çıkış Tarihi</label>
        <input type="datetime-local" class="form-control" id="tour_departure_date" name="tour_departure_date">
    </div>
    <div class="mb-3">
        <label for="tour_return_date" class="form-label">Tur Dönüş Tarihi</label>
        <input type="datetime-local" class="form-control" id="tour_return_date" name="tour_return_date">
    </div>

    <button type="submit" class="btn btn-primary">Kaydet</button>
</form>

<?php include 'includes/footer.php'; ?>
