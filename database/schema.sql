-- Database Schema for pckomp
-- DBMS: SQLite 3

PRAGMA foreign_keys = ON;

-- 1. Table: admin
CREATE TABLE IF NOT EXISTS admin (
    admin_id INTEGER PRIMARY KEY AUTOINCREMENT,
    admin_name TEXT NOT NULL,
    username TEXT NOT NULL UNIQUE,
    password TEXT NOT NULL,
    admin_telp TEXT NOT NULL,
    admin_email TEXT NOT NULL,
    admin_address TEXT NOT NULL
);

-- 2. Table: category
CREATE TABLE IF NOT EXISTS category (
    kategori_id INTEGER PRIMARY KEY AUTOINCREMENT,
    kategori_nama TEXT NOT NULL
);

-- 3. Table: product
CREATE TABLE IF NOT EXISTS product (
    product_id INTEGER PRIMARY KEY AUTOINCREMENT,
    kategori_id INTEGER NOT NULL,
    product_name TEXT NOT NULL,
    product_price INTEGER NOT NULL,
    product_description TEXT,
    product_image TEXT,
    product_status INTEGER NOT NULL DEFAULT 1,
    data_created DATETIME DEFAULT CURRENT_TIMESTAMP,
    FOREIGN KEY (kategori_id) REFERENCES category(kategori_id) ON DELETE CASCADE
);

-- Indexes for performance
CREATE INDEX IF NOT EXISTS idx_category_name ON category(kategori_nama);
CREATE INDEX IF NOT EXISTS idx_product_category ON product(kategori_id);
CREATE INDEX IF NOT EXISTS idx_product_status ON product(product_status);

