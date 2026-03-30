-- Migration: Tambah kolom remember_token ke tabel users
-- Jalankan query ini di phpMyAdmin jika database sudah ada

ALTER TABLE `users` ADD COLUMN `remember_token` VARCHAR(64) DEFAULT NULL;