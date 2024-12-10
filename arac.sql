-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Anamakine: 127.0.0.1:3306
-- Üretim Zamanı: 10 Ara 2024, 18:54:01
-- Sunucu sürümü: 10.11.10-MariaDB-ubu2204
-- PHP Sürümü: 8.1.31

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Veritabanı: `arac`
--

-- --------------------------------------------------------

--
-- Tablo için tablo yapısı `cars`
--

CREATE TABLE `cars` (
  `id` int(11) NOT NULL,
  `brand` varchar(50) NOT NULL,
  `model` varchar(50) NOT NULL,
  `plate` varchar(20) NOT NULL,
  `driver_name` varchar(100) DEFAULT NULL,
  `driver_phone` varchar(20) DEFAULT NULL,
  `driver_email` varchar(100) DEFAULT NULL,
  `maintenance_date` date DEFAULT NULL,
  `service_date` date DEFAULT NULL,
  `maintenance_type` varchar(50) DEFAULT NULL,
  `departure_time` datetime DEFAULT NULL,
  `return_time` datetime DEFAULT NULL,
  `status` enum('active','inactive') DEFAULT 'active',
  `created_at` timestamp NULL DEFAULT current_timestamp(),
  `type` varchar(50) DEFAULT NULL,
  `inspection_date` date DEFAULT NULL,
  `insurance_date` date DEFAULT NULL,
  `oil_maintenance_date` date DEFAULT NULL,
  `general_maintenance_date` date DEFAULT NULL,
  `tire_type` varchar(50) DEFAULT NULL,
  `tax_date` date DEFAULT NULL,
  `driver_license` varchar(50) DEFAULT NULL,
  `driver_src` tinyint(1) DEFAULT NULL,
  `driver_psychotechnic` tinyint(1) DEFAULT NULL,
  `fuel_level` int(11) DEFAULT NULL,
  `mileage` int(11) DEFAULT NULL,
  `operational_status` enum('Boşta','Turda') DEFAULT NULL,
  `tour_departure_date` datetime DEFAULT NULL,
  `tour_return_date` datetime DEFAULT NULL,
  `year` int(11) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Tablo döküm verisi `cars`
--

INSERT INTO `cars` (`id`, `brand`, `model`, `plate`, `driver_name`, `driver_phone`, `driver_email`, `maintenance_date`, `service_date`, `maintenance_type`, `departure_time`, `return_time`, `status`, `created_at`, `type`, `inspection_date`, `insurance_date`, `oil_maintenance_date`, `general_maintenance_date`, `tire_type`, `tax_date`, `driver_license`, `driver_src`, `driver_psychotechnic`, `fuel_level`, `mileage`, `operational_status`, `tour_departure_date`, `tour_return_date`, `year`) VALUES
(3, 'Fiat ', 'Egea', '61ALİ1461', 'Ali Çömez', '5325325322', NULL, '2024-12-12', '2024-10-10', 'Periyodik', '2024-12-12 00:00:00', '2024-12-20 00:00:00', NULL, '2024-12-10 17:51:53', 'Taksi', '2024-01-01', '2024-01-01', '2024-02-26', '2024-05-19', 'Kış lastiği', '2024-10-14', 'B,D,E', 1, 1, 90, 611461, 'Boşta', '2024-03-31 00:00:00', '2024-05-19 00:00:00', 2029);

-- --------------------------------------------------------

--
-- Tablo için tablo yapısı `car_history`
--

CREATE TABLE `car_history` (
  `id` int(11) NOT NULL,
  `car_id` int(11) NOT NULL,
  `field_name` varchar(50) NOT NULL,
  `old_value` text DEFAULT NULL,
  `new_value` text DEFAULT NULL,
  `description` text NOT NULL,
  `event_date` datetime NOT NULL,
  `updated_at` timestamp NULL DEFAULT current_timestamp(),
  `created_at` timestamp NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Tablo için tablo yapısı `users`
--

CREATE TABLE `users` (
  `id` int(11) NOT NULL,
  `name` varchar(100) NOT NULL,
  `email` varchar(100) NOT NULL,
  `password` varchar(255) NOT NULL,
  `created_at` timestamp NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Tablo döküm verisi `users`
--

INSERT INTO `users` (`id`, `name`, `email`, `password`, `created_at`) VALUES
(1, 'Ali', 'web@adavegastravel.com', '$2y$12$XYjVrT87moEsS/ggdwLPeeGYUKGO9SFYxaE/ZzP8gRH7Du8CxURHy', '2024-12-09 22:46:57');

--
-- Dökümü yapılmış tablolar için indeksler
--

--
-- Tablo için indeksler `cars`
--
ALTER TABLE `cars`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `plate` (`plate`);

--
-- Tablo için indeksler `car_history`
--
ALTER TABLE `car_history`
  ADD PRIMARY KEY (`id`),
  ADD KEY `car_id` (`car_id`);

--
-- Tablo için indeksler `users`
--
ALTER TABLE `users`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `email` (`email`);

--
-- Dökümü yapılmış tablolar için AUTO_INCREMENT değeri
--

--
-- Tablo için AUTO_INCREMENT değeri `cars`
--
ALTER TABLE `cars`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=4;

--
-- Tablo için AUTO_INCREMENT değeri `car_history`
--
ALTER TABLE `car_history`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=39;

--
-- Tablo için AUTO_INCREMENT değeri `users`
--
ALTER TABLE `users`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;

--
-- Dökümü yapılmış tablolar için kısıtlamalar
--

--
-- Tablo kısıtlamaları `car_history`
--
ALTER TABLE `car_history`
  ADD CONSTRAINT `car_history_ibfk_1` FOREIGN KEY (`car_id`) REFERENCES `cars` (`id`) ON DELETE CASCADE;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
