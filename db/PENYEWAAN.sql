CREATE DATABASE penyewaan;
USE penyewaan;
-- drop database penyewaan;

CREATE TABLE admin(
    id_admin INT (11) PRIMARY KEY AUTO_INCREMENT NOT NULL,
    nama VARCHAR (50) NOT NULL,
    password VARCHAR (50) NOT NULL,
    email VARCHAR (50) NOT NULL
);
DESC admin;

CREATE TABLE pelanggan(
    id_pelanggan INT (11) PRIMARY KEY AUTO_INCREMENT NOT NULL,
    nama VARCHAR (50) NOT NULL,
    alamat TEXT NOT NULL,
    telepon VARCHAR (15) NOT NULL,
    email VARCHAR (50) NOT NULL
);
DESC pelanggan;

CREATE TABLE alat(
    id_alat INT (11) PRIMARY KEY AUTO_INCREMENT NOT NULL,
    nama VARCHAR (50) NOT NULL,
    harga_sewa_per_hari DECIMAL (10,2) NOT NULL,
    stok INT (11) NOT NULL,
    deskripsi TEXT NOT NULL
);
DESC alat;

CREATE TABLE transaksi(
    id_transaksi INT (11) PRIMARY KEY AUTO_INCREMENT NOT NULL,
    id_pelanggan INT (11) NOT NULL,
    id_alat INT (11) NOT NULL, 
    id_admin INT (11) NOT NULL,
    jumlah INT (11) NOT NULL,
	tanggal_sewa DATE NOT NULL,
    tanggal_kembali DATE NOT NULL,
    total_harga DECIMAL (10,2) NOT NULL,
    denda INT (11) NOT NULL,
    status VARCHAR (50),
    FOREIGN KEY (id_pelanggan) REFERENCES pelanggan (id_pelanggan),
    FOREIGN KEY (id_alat) REFERENCES alat (id_alat),
	FOREIGN KEY (id_admin) REFERENCES admin (id_admin)
);
DESC transaksi;

CREATE TABLE pembayaran (
    id_pembayaran INT (11) PRIMARY KEY AUTO_INCREMENT NOT NULL,
    id_transaksi INT (11) NOT NULL,
    tanggal_pembayaran DATE NOT NULL,
    jumlah_pembayaran DECIMAL (10,2) NOT NULL,
    metode_pembayaran VARCHAR (50),
    FOREIGN KEY (id_transaksi) REFERENCES transaksi (id_transaksi)
);
DESC pembayaran;

INSERT INTO admin (nama, email, password) VALUES
('Admin 1', 'admin1@example.com', 'password1');
SELECT * FROM admin;

INSERT INTO pelanggan (nama, alamat, telepon, email) VALUES
('Pelanggan 1', 'Alamat Pelanggan 1', '08123456789', 'pelanggan1@example.com');
SELECT * FROM pelanggan;

INSERT INTO alat (nama, harga_sewa_per_hari, stok, deskripsi) VALUES
('Tenda', 100000, 10, 'Tenda untuk 2 orang');
SELECT * FROM alat;

INSERT INTO transaksi (id_pelanggan, id_alat, id_admin, jumlah, tanggal_sewa, tanggal_kembali, total_harga, denda, status) VALUES
(1, 1, 1, 2, '2024-06-10', '2024-06-12', 200000, 0, 'Selesai');
SELECT * FROM transaksi;

INSERT INTO pembayaran (id_transaksi, tanggal_pembayaran, jumlah_pembayaran, metode_pembayaran) VALUES
(1, '2024-06-12', 200000, 'Transfer Bank');
SELECT * FROM pembayaran;

-- view 1 menampilkan alat yang dipinjam
CREATE VIEW alat_dipinjam AS
SELECT a.id_alat, a.nama, t.id_transaksi, t.tanggal_sewa, t.tanggal_kembali
FROM alat a
JOIN transaksi t ON a.id_alat = t.id_alat
WHERE t.status = 'Selesai';
SELECT * FROM alat_dipinjam;

-- view 2 menampilkan detail penyewaan
CREATE VIEW detail_penyewaan AS
SELECT t.id_transaksi, p.nama AS nama_pelanggan, a.nama AS nama_alat, t.jumlah, t.tanggal_sewa, t.tanggal_kembali, t.total_harga, t.denda, t.status
FROM transaksi t
JOIN pelanggan p ON t.id_pelanggan = p.id_pelanggan
JOIN alat a ON t.id_alat = a.id_alat;
SELECT * FROM detail_penyewaan;

-- view 3 menampilkan stok yang tersisa
CREATE VIEW stok_tersisa AS
SELECT a.nama AS nama_produk, a.stok AS stok_tersisa
FROM alat a;
SELECT * FROM stok_tersisa;

-- view 4 menampilkan alat yang dipinjam perbulan
CREATE VIEW alat_dipinjam_perbulan AS
SELECT 
    MONTH(t.tanggal_sewa) AS bulan, 
    a.nama AS nama_alat, 
    COUNT(*) AS jumlah_peminjaman
FROM transaksi t
JOIN alat a ON t.id_alat = a.id_alat
WHERE t.status = 'Selesai'
GROUP BY MONTH(t.tanggal_sewa), a.nama
ORDER BY MONTH(t.tanggal_sewa), jumlah_peminjaman DESC;
SELECT * FROM alat_dipinjam_perbulan;

-- view 5 menampilkan detail pesanan
CREATE VIEW detail_pesanan AS
SELECT p.nama AS nama_pelanggan, t.total_harga AS total, t.tanggal_sewa AS tanggal_pesanan
FROM transaksi t
JOIN pelanggan p ON t.id_pelanggan = p.id_pelanggan;
SELECT * FROM detail_pesanan;



-- SP 1. Buat stored procedure untuk menambah data ke tabel pelanggan
DELIMITER //
CREATE PROCEDURE add_pelanggan(
    IN p_nama VARCHAR(50),
    IN p_alamat TEXT,
    IN p_telepon VARCHAR(15),
    IN p_email VARCHAR(50)
)
BEGIN
    INSERT INTO pelanggan (nama, alamat, telepon, email)
    VALUES (p_nama, p_alamat, p_telepon, p_email);
END //
DELIMITER ;
-- REPAIR TABLE mysql.proc;


-- SP 2
DELIMITER //
CREATE PROCEDURE manage_alat(
    IN p_id INT,
    IN p_name VARCHAR(50),
    IN p_price DECIMAL(10,2),
    IN p_stock INT,
    IN p_description TEXT,
    IN p_action VARCHAR(10)
)
BEGIN
    IF p_action = 'add' THEN
        INSERT INTO alat (nama, harga_sewa_per_hari, stok, deskripsi) VALUES (p_name, p_price, p_stock, p_description);
    ELSEIF p_action = 'edit' THEN
        UPDATE alat SET nama = p_name, harga_sewa_per_hari = p_price, stok = p_stock, deskripsi = p_description WHERE id_alat = p_id;
    ELSEIF p_action = 'delete' THEN
        DELETE FROM alat WHERE id_alat = p_id;
    END IF;
END //
DELIMITER ;
CALL manage_alat(2, 'Flysheet', 20000.00, 10, 'deskripsi_alat', 'edit');
SELECT * FROM alat;


-- SP 3
DELIMITER //
CREATE PROCEDURE UpdateStokAlat(
    IN id_transaksi INT
)
BEGIN
    DECLARE jumlah_transaksi INT;
    DECLARE id_alat_transaksi INT;

    -- Loop for updating tool stock
    SELECT id_alat, jumlah INTO id_alat_transaksi, jumlah_transaksi
    FROM transaksi
    WHERE id_transaksi = id_transaksi;

    -- Update tool stock based on the rented amount
    UPDATE alat
    SET stok = stok - jumlah_transaksi
    WHERE id_alat = id_alat_transaksi;
END //
DELIMITER ;
CALL UpdateStokAlat(3);
SELECT * FROM alat;


-- SP 4
-- DELIMITER //
-- CREATE PROCEDURE HitungTotalPendapatan(
--     IN tanggal_mulai DATE,
--     IN tanggal_selesai DATE,
--     OUT total_pendapatan DECIMAL(10,2)
-- )
-- BEGIN
--     SELECT SUM(total_harga) INTO total_pendapatan
--     FROM transaksi
--     WHERE tanggal_kembali BETWEEN tanggal_mulai AND tanggal_selesai
--       AND STATUS = 'Selesai';
--       
--     IF total_pendapatan IS NULL THEN
--         SET total_pendapatan = 0;
--     END IF;
-- END //
-- DELIMITER ;
-- CALL HitungTotalPendapatan('2024-02-08', '2025-06-10', @total_pendapatan);
-- SELECT @total_pendapatan;


-- SP 5
-- DELIMITER //
-- CREATE PROCEDURE HitungDendaKeterlambatan(
--     IN id_transaksi INT,
--     OUT denda DECIMAL(10,2)
-- )
-- BEGIN
--     DECLARE tanggal_sewa DATE;
--     DECLARE tanggal_kembali DATE;
--     DECLARE keterlambatan INT;
--     DECLARE harga_sewa DECIMAL(10,2);
--     
--     -- Get rental dates and daily rental price from transaction
--     SELECT tanggal_sewa, tanggal_kembali, harga_sewa_per_hari
--     INTO tanggal_sewa, tanggal_kembali, harga_sewa
--     FROM transaksi
--     JOIN alat ON transaksi.id_alat = alat.id_alat
--     WHERE transaksi.id_transaksi = id_transaksi;
--     
--     SET keterlambatan = DATEDIFF(tanggal_kembali, tanggal_sewa) - 2; -- Subtracting 2 days tolerance
--     
--     IF keterlambatan > 0 THEN
--         SET denda = keterlambatan * harga_sewa;
--     ELSE
--         SET denda = 0;
--     END IF;
-- END //
-- DELIMITER ;
-- CALL HitungDendaKeterlambatan(1, @denda);
-- SELECT @denda AS denda_keterlambatan;



-- trigger 1 ngurangi stok
DELIMITER //
CREATE TRIGGER after_insert_transaksi
AFTER INSERT ON transaksi
FOR EACH ROW
BEGIN
    DECLARE jumlah_sewa INT;
    -- Mengambil jumlah alat yang disewa
    SET jumlah_sewa = NEW.jumlah;
    -- Mengurangi stok alat berdasarkan jumlah yang disewa
    UPDATE alat
    SET stok = stok - jumlah_sewa
    WHERE id_alat = NEW.id_alat;
END //
DELIMITER ;
INSERT INTO transaksi (id_pelanggan, id_alat, id_admin, jumlah, tanggal_sewa, tanggal_kembali, total_harga, denda, STATUS)
VALUES (1, 1, 1, 1, '2024-06-15', '2024-06-17', 20000.00, 0, 'Proses');
SELECT * FROM pelanggan;
SELECT * FROM alat;
SELECT * FROM transaksi;

-- trigger 2 update stok menjadi selesai
DELIMITER //
CREATE TRIGGER update_stok_selesai AFTER UPDATE ON transaksi
FOR EACH ROW
BEGIN
    IF NEW.STATUS = 'selesai' AND OLD.STATUS <> 'selesai' THEN
        UPDATE alat
        SET stok = stok + NEW.jumlah
        WHERE id_alat = NEW.id_alat;
    END IF;
END //
DELIMITER ;

-- trigger 3
DELIMITER //
CREATE TRIGGER before_delete_transaksi
BEFORE DELETE ON transaksi
FOR EACH ROW
BEGIN
    IF OLD.STATUS = 'proses' THEN
        UPDATE alat
        SET stok = stok + OLD.jumlah
        WHERE id_alat = OLD.id_alat;
    END IF;
END //
DELIMITER ;
