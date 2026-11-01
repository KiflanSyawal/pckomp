-- Database Schema for pckomp
-- DBMS: MySQL / MariaDB (Reference)

CREATE DATABASE IF NOT EXISTS `pckomp` CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci;
USE `pckomp`;

-- 1. Table: admin
CREATE TABLE IF NOT EXISTS `admin` (
    `admin_id` INT(11) NOT NULL AUTO_INCREMENT,
    `admin_name` VARCHAR(50) NOT NULL,
    `username` VARCHAR(50) NOT NULL UNIQUE,
    `password` VARCHAR(100) NOT NULL,
    `admin_telp` VARCHAR(20) NOT NULL,
    `admin_email` VARCHAR(50) NOT NULL,
    `admin_address` TEXT NOT NULL,
    PRIMARY KEY (`admin_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- 2. Table: category
CREATE TABLE IF NOT EXISTS `category` (
    `kategori_id` INT(11) NOT NULL AUTO_INCREMENT,
    `kategori_nama` VARCHAR(100) NOT NULL,
    PRIMARY KEY (`kategori_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- 3. Table: product
CREATE TABLE IF NOT EXISTS `product` (
    `product_id` INT(11) NOT NULL AUTO_INCREMENT,
    `kategori_id` INT(11) NOT NULL,
    `product_name` VARCHAR(100) NOT NULL,
    `product_price` INT(11) NOT NULL,
    `product_description` TEXT,
    `product_image` VARCHAR(100) DEFAULT NULL,
    `product_status` TINYINT(1) NOT NULL DEFAULT 1,
    `data_created` TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP,
    PRIMARY KEY (`product_id`),
    KEY `kategori_id` (`kategori_id`),
    CONSTRAINT `fk_product_category` FOREIGN KEY (`kategori_id`) REFERENCES `category` (`kategori_id`) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- Seed Data
INSERT INTO `admin` (`admin_id`, `admin_name`, `username`, `password`, `admin_telp`, `admin_email`, `admin_address`) VALUES
(1, 'Administrator PCKomp', 'admin', '21232f297a57a5a743894a0e4a801fc3', '081234567890', 'admin@pckomp.com', 'Jl. Komputer Raya No. 10, Jakarta Pusat')
ON DUPLICATE KEY UPDATE `username`=`username`;

INSERT INTO `category` (`kategori_id`, `kategori_nama`) VALUES
(1, 'Processor (CPU)'),
(2, 'Motherboard'),
(3, 'Kartu Grafis (VGA)'),
(4, 'RAM (Memory)'),
(5, 'Storage (SSD & HDD)'),
(6, 'Power Supply (PSU)'),
(7, 'Casing Komputer'),
(8, 'Monitor & Display')
ON DUPLICATE KEY UPDATE `kategori_nama`=`kategori_nama`;

INSERT INTO `product` (`product_id`, `kategori_id`, `product_name`, `product_price`, `product_description`, `product_image`, `product_status`) VALUES
(1, 1, 'Intel Core i5-12400F', 2150000, 'Processor 6 Core 12 Threads up to 4.4 GHz Socket LGA1700', 'i5-12400f.jpg', 1),
(2, 1, 'AMD Ryzen 5 5600', 1950000, 'Processor 6 Core 12 Threads up to 4.4 GHz Socket AM4', 'ryzen-5600.jpg', 1),
(3, 2, 'ASUS Prime B660M-A WiFi', 2350000, 'Motherboard Intel LGA1700 DDR4 Micro-ATX Dual M.2 PCIe 4.0', 'asus-b660m.jpg', 1),
(4, 3, 'NVIDIA GeForce RTX 4060 8GB', 4850000, 'GPU 8GB GDDR6 Ray Tracing DLSS 3 PCIe 4.0', 'rtx-4060.jpg', 1),
(5, 4, 'Corsair Vengeance LPX DDR4 16GB (2x8GB) 3200MHz', 650000, 'RAM Desktop Dual Channel Kit Black Heatspreader', 'corsair-16gb.jpg', 1),
(6, 5, 'Kingston NV2 NVMe PCIe 4.0 SSD 1TB', 950000, 'SSD M.2 NVMe Gen 4x4 up to 3500MB/s Read Speed', 'kingston-1tb.jpg', 1)
ON DUPLICATE KEY UPDATE `product_name`=`product_name`;

