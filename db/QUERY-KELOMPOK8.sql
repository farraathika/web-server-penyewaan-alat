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
select * from admin;
select * from transaksi;

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



-- view 1 menampilkan alat yang dipinjam dan transaksinya telah selesai
CREATE VIEW alat_dipinjam AS
SELECT a.id_alat, a.nama, t.id_transaksi, t.tanggal_sewa, t.tanggal_kembali
FROM alat a
JOIN transaksi t ON a.id_alat = t.id_alat
WHERE t.status = 'Selesai';
SELECT * FROM alat_dipinjam;

-- view 2 menampilkan detail lengkap tiap transaksi penyewaan 
CREATE VIEW detail_penyewaan AS
SELECT t.id_transaksi, p.nama AS nama_pelanggan, a.nama AS nama_alat, t.jumlah, t.tanggal_sewa, t.tanggal_kembali, t.total_harga, t.denda, t.status
FROM transaksi t
JOIN pelanggan p ON t.id_pelanggan = p.id_pelanggan
JOIN alat a ON t.id_alat = a.id_alat;
SELECT * FROM detail_penyewaan;

-- view 3 menampilkan stok yang tersisa dari setiap alat
CREATE VIEW stok_tersisa AS
SELECT a.nama AS nama_produk, a.stok AS stok_tersisa
FROM alat a;
SELECT * FROM stok_tersisa;

-- view 4 menampilkan jumlah alat yang dipinjam tiapbulan
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
SELECT * FROM pelanggan;

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
CREATE PROCEDURE manage_pembayaran(
    IN p_id INT,
    IN p_id_transaksi INT,
    IN p_tanggal_pembayaran DATE,
    IN p_jumlah_pembayaran DECIMAL(10,2),
    IN p_metode_pembayaran VARCHAR(50),
    IN p_action VARCHAR(10)
)
BEGIN
    IF p_action = 'add' THEN
        INSERT INTO pembayaran (id_transaksi, tanggal_pembayaran, jumlah_pembayaran, metode_pembayaran) 
        VALUES (p_id_transaksi, p_tanggal_pembayaran, p_jumlah_pembayaran, p_metode_pembayaran);
    ELSEIF p_action = 'edit' THEN
        UPDATE pembayaran 
        SET id_transaksi = p_id_transaksi, tanggal_pembayaran = p_tanggal_pembayaran, 
            jumlah_pembayaran = p_jumlah_pembayaran, metode_pembayaran = p_metode_pembayaran 
        WHERE id_pembayaran = p_id;
    ELSEIF p_action = 'delete' THEN
        DELETE FROM pembayaran WHERE id_pembayaran = p_id;
    END IF;
END //
DELIMITER ;

CALL manage_pembayaran(2, 1, '2024-06-14', 50000.00, 'Transfer', 'edit');
SELECT * FROM alat;

-- SP 4
DELIMITER //
CREATE PROCEDURE addTransaksi (
    IN p_id_pelanggan INT,
    IN p_id_alat INT,
    IN p_id_admin INT,
    IN p_jumlah INT,
    IN p_tanggal_sewa DATE,
    IN p_tanggal_kembali DATE,
    IN p_total_harga DECIMAL(10,2),
    IN p_status VARCHAR(50),
    OUT p_denda DECIMAL(10,2)
)
BEGIN
    DECLARE l_harga_sewa_per_hari DECIMAL(10,2);
    DECLARE l_total_days INT;
    DECLARE l_allowed_days INT DEFAULT 2; 
    DECLARE l_extra_days INT;
    DECLARE l_denda_rate DECIMAL(10,2) DEFAULT 50000.00; 

    SELECT harga_sewa_per_hari INTO l_harga_sewa_per_hari
    FROM alat
    WHERE id_alat = p_id_alat;

    SET l_total_days = DATEDIFF(p_tanggal_kembali, p_tanggal_sewa);
    SET l_extra_days = l_total_days - l_allowed_days;

    IF l_extra_days > 0 THEN
        SET p_denda = l_extra_days * l_denda_rate;
    ELSE
        SET p_denda = 0.00;
    END IF;

    IF p_denda IS NULL THEN
        SET p_denda = 0.00;
    END IF;
    
    INSERT INTO transaksi (
        id_pelanggan,
        id_alat,
        id_admin,
        jumlah,
        tanggal_sewa,
        tanggal_kembali,
        total_harga,
        denda,
        status
    ) VALUES (
        p_id_pelanggan,
        p_id_alat,
        p_id_admin,
        p_jumlah,
        p_tanggal_sewa,
        p_tanggal_kembali,
        p_total_harga,
        p_denda,
        p_status
    );
END //
DELIMITER ;

CALL addTransaksi (1, 1, 1, 3, '2024-06-14', '2024-06-18', 25000, 'Proses', @p_denda);
SELECT * FROM transaksi;


-- sp 5 mengupdate stok kurang dari 5
DELIMITER //
CREATE PROCEDURE UpdateStokAlat (IN stok_tambahan INT)
BEGIN
    DECLARE v_index INT DEFAULT 0;
    DECLARE v_id_alat INT;
    DECLARE v_stok INT;
    DECLARE v_total INT;

    SELECT COUNT(*) INTO v_total FROM alat;
    WHILE v_index < v_total DO
        
        SELECT id_alat, stok INTO v_id_alat, v_stok
        FROM alat
        LIMIT v_index, 1;

        IF v_stok < 5 THEN
            UPDATE alat
            SET stok = stok + stok_tambahan
            WHERE id_alat = v_id_alat;
        END IF;
        
        SET v_index = v_index + 1;
    END WHILE;
END //
DELIMITER ;

CALL UpdateStokAlat(3);
SELECT * FROM alat;


-- trigger 1 ngurangi stok
DELIMITER //
CREATE TRIGGER after_insert_transaksi
AFTER INSERT ON transaksi
FOR EACH ROW
BEGIN
    DECLARE jumlah_sewa INT;
    
    SET jumlah_sewa = NEW.jumlah;
    
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

SHOW triggers;
