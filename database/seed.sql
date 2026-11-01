-- Seed Data for pckomp
-- DBMS: SQLite 3

-- Default Admin Account:
-- Username: admin
-- Password: admin (MD5: 21232f297a57a5a743894a0e4a801fc3)
INSERT OR IGNORE INTO admin (admin_id, admin_name, username, password, admin_telp, admin_email, admin_address) VALUES
(1, 'Administrator PCKomp', 'admin', '21232f297a57a5a743894a0e4a801fc3', '081234567890', 'admin@pckomp.com', 'Jl. Komputer Raya No. 10, Jakarta Pusat');

-- Default Categories for PC Components store
INSERT OR IGNORE INTO category (kategori_id, kategori_nama) VALUES
(1, 'Processor (CPU)'),
(2, 'Motherboard'),
(3, 'Kartu Grafis (VGA)'),
(4, 'RAM (Memory)'),
(5, 'Storage (SSD & HDD)'),
(6, 'Power Supply (PSU)'),
(7, 'Casing Komputer'),
(8, 'Monitor & Display');

-- Default Products
INSERT OR IGNORE INTO product (product_id, kategori_id, product_name, product_price, product_description, product_image, product_status, data_created) VALUES
(1, 1, 'Intel Core i5-12400F', 2150000, 'Processor 6 Core 12 Threads up to 4.4 GHz Socket LGA1700', 'i5-12400f.jpg', 1, CURRENT_TIMESTAMP),
(2, 1, 'AMD Ryzen 5 5600', 1950000, 'Processor 6 Core 12 Threads up to 4.4 GHz Socket AM4', 'ryzen-5600.jpg', 1, CURRENT_TIMESTAMP),
(3, 2, 'ASUS Prime B660M-A WiFi', 2350000, 'Motherboard Intel LGA1700 DDR4 Micro-ATX Dual M.2 PCIe 4.0', 'asus-b660m.jpg', 1, CURRENT_TIMESTAMP),
(4, 3, 'NVIDIA GeForce RTX 4060 8GB', 4850000, 'GPU 8GB GDDR6 Ray Tracing DLSS 3 PCIe 4.0', 'rtx-4060.jpg', 1, CURRENT_TIMESTAMP),
(5, 4, 'Corsair Vengeance LPX DDR4 16GB (2x8GB) 3200MHz', 650000, 'RAM Desktop Dual Channel Kit Black Heatspreader', 'corsair-16gb.jpg', 1, CURRENT_TIMESTAMP),
(6, 5, 'Kingston NV2 NVMe PCIe 4.0 SSD 1TB', 950000, 'SSD M.2 NVMe Gen 4x4 up to 3500MB/s Read Speed', 'kingston-1tb.jpg', 1, CURRENT_TIMESTAMP);

