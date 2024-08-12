-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Waktu pembuatan: 12 Agu 2024 pada 21.04
-- Versi server: 10.4.32-MariaDB
-- Versi PHP: 8.2.12

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Database: `posyandu`
--

-- --------------------------------------------------------

--
-- Struktur dari tabel `anak`
--

CREATE TABLE `anak` (
  `id` int(11) NOT NULL,
  `nik` varchar(20) NOT NULL,
  `nama_anak` varchar(100) DEFAULT NULL,
  `tempat_lahir` varchar(100) DEFAULT NULL,
  `tanggal_lahir` date DEFAULT NULL,
  `orang_tua_id` int(11) DEFAULT NULL,
  `jenis_kelamin` enum('Laki-Laki','Perempuan') NOT NULL,
  `golongan_darah` varchar(10) DEFAULT NULL,
  `id_ibu` int(11) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data untuk tabel `anak`
--

INSERT INTO `anak` (`id`, `nik`, `nama_anak`, `tempat_lahir`, `tanggal_lahir`, `orang_tua_id`, `jenis_kelamin`, `golongan_darah`, `id_ibu`) VALUES
(19, '1234567890123456', 'Budi', 'Jakarta', '2015-05-15', 1, 'Laki-Laki', 'O', 1),
(20, '1234567890123457', 'Siti', 'Bandung', '2018-08-21', 2, 'Perempuan', 'A', 2),
(21, '1234567890123458', 'Carmen', 'Lampung', '2024-03-10', 3, 'Perempuan', 'O', 3),
(22, '2132121421', 'afwa', 'solok', '1422-02-13', 2, 'Laki-Laki', 'AB', 2),
(24, 'sadasda', 'sadadsada', 'sadasdsa', '1343-02-12', 1, 'Laki-Laki', 'AB', 1);

-- --------------------------------------------------------

--
-- Struktur dari tabel `bidan`
--

CREATE TABLE `bidan` (
  `id_bidan` int(11) NOT NULL,
  `nama_bidan` varchar(50) NOT NULL,
  `tempat_lahir` varchar(30) NOT NULL,
  `tgl_lahir` date NOT NULL,
  `no_hp` varchar(13) NOT NULL,
  `pendidikan_terakhir` varchar(30) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data untuk tabel `bidan`
--

INSERT INTO `bidan` (`id_bidan`, `nama_bidan`, `tempat_lahir`, `tgl_lahir`, `no_hp`, `pendidikan_terakhir`) VALUES
(1, 'siti maimunah', 'Jakarta', '1980-05-10', '081234567890', 'S1 Kebidanan'),
(2, 'Ani Nurhayati', 'Bandung', '1985-08-15', '081298765432', 'D3 Kebidanan'),
(3, 'Linda Sari', 'Surabaya', '1990-01-25', '082112345678', 'S1 Kebidanan'),
(4, 'Maya Puspita', 'Medan', '1983-07-20', '081355667788', 'S2 Kebidanan'),
(5, 'Rina Indriani', 'Yogyakarta', '1992-11-05', '083344556677', 'D3 Kebidanan');

-- --------------------------------------------------------

--
-- Struktur dari tabel `kelola_imunisasi`
--

CREATE TABLE `kelola_imunisasi` (
  `id` int(11) NOT NULL,
  `anak_id` int(11) NOT NULL,
  `orang_tua_no` int(11) NOT NULL,
  `bidan_id` int(11) NOT NULL,
  `petugas` varchar(100) NOT NULL,
  `tanggal_imunisasi` date NOT NULL,
  `usia_anak` int(11) NOT NULL,
  `jenis_imunisasi` enum('HB','Polio','Campak','DPT','BCG') NOT NULL,
  `vitamin` enum('merah','biru') DEFAULT NULL,
  `keterangan` text DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data untuk tabel `kelola_imunisasi`
--

INSERT INTO `kelola_imunisasi` (`id`, `anak_id`, `orang_tua_no`, `bidan_id`, `petugas`, `tanggal_imunisasi`, `usia_anak`, `jenis_imunisasi`, `vitamin`, `keterangan`) VALUES
(17, 21, 3, 3, 'Selvi', '2024-09-12', 6, 'HB', 'biru', 'mantap');

-- --------------------------------------------------------

--
-- Struktur dari tabel `orang_tua`
--

CREATE TABLE `orang_tua` (
  `no` int(11) NOT NULL,
  `nama_ibu` varchar(100) NOT NULL,
  `tempat_lahir_ibu` varchar(100) DEFAULT NULL,
  `tanggal_lahir_ibu` date DEFAULT NULL,
  `golongan_darah_ibu` varchar(10) DEFAULT NULL,
  `pendidikan_ibu` varchar(50) DEFAULT NULL,
  `pekerjaan_ibu` varchar(50) DEFAULT NULL,
  `alamat` text DEFAULT NULL,
  `kota` varchar(100) DEFAULT NULL,
  `kecamatan` varchar(100) DEFAULT NULL,
  `nama_suami` varchar(100) DEFAULT NULL,
  `tempat_lahir_suami` varchar(100) DEFAULT NULL,
  `tanggal_lahir_suami` date DEFAULT NULL,
  `pendidikan_suami` varchar(50) DEFAULT NULL,
  `pekerjaan_suami` varchar(50) DEFAULT NULL,
  `no_telpon` varchar(20) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data untuk tabel `orang_tua`
--

INSERT INTO `orang_tua` (`no`, `nama_ibu`, `tempat_lahir_ibu`, `tanggal_lahir_ibu`, `golongan_darah_ibu`, `pendidikan_ibu`, `pekerjaan_ibu`, `alamat`, `kota`, `kecamatan`, `nama_suami`, `tempat_lahir_suami`, `tanggal_lahir_suami`, `pendidikan_suami`, `pekerjaan_suami`, `no_telpon`) VALUES
(1, 'Siti Aminah', 'Jakarta', '1985-06-15', 'O', 'S1', 'Ibu Rumah Tangga', 'Jl. Mawar No. 10', 'Jakarta', 'Kebon Jeruk', 'Ahmad Supardi', 'Bandung', '1980-09-12', 'S2', 'Pegawai Negeri', '081234567891'),
(2, 'Ade', 'Solok', '2002-05-14', 'B', 'SMA', 'Rumah Tangga', 'Jl. Bunga No. 12', 'Solok', 'Solok', 'Rizal', 'Pasaman', '2002-05-31', 'SMA', 'Pengangguran', '0972948746'),
(3, 'Carmila', 'Pringsewu', '1998-12-12', 'O', 'SMA', 'Ibu Rumah Tangga', 'Lampung', 'Lampung', 'Sidoharjo', 'Tugiono', 'Lampung', '1987-02-12', 'SMA', 'Swasta', '923711236');

-- --------------------------------------------------------

--
-- Struktur dari tabel `penimbangan`
--

CREATE TABLE `penimbangan` (
  `id_penimbangan` int(11) NOT NULL,
  `tgl_timbangan` date NOT NULL,
  `usia` int(11) NOT NULL,
  `bb` double NOT NULL,
  `tb` double NOT NULL,
  `deteksi` enum('Ideal','Tidak Ideal') NOT NULL,
  `ket` text DEFAULT NULL,
  `id_anak` int(11) NOT NULL,
  `id_bidan` int(11) NOT NULL,
  `petugas` varchar(100) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data untuk tabel `penimbangan`
--

INSERT INTO `penimbangan` (`id_penimbangan`, `tgl_timbangan`, `usia`, `bb`, `tb`, `deteksi`, `ket`, `id_anak`, `id_bidan`, `petugas`) VALUES
(20, '2024-09-12', 6, 25, 100, 'Ideal', 'bagus', 21, 3, 'Selvi');

-- --------------------------------------------------------

--
-- Struktur dari tabel `user`
--

CREATE TABLE `user` (
  `id_user` int(11) NOT NULL,
  `nama` varchar(20) NOT NULL,
  `username` varchar(20) NOT NULL,
  `password` varchar(20) NOT NULL,
  `email` varchar(30) NOT NULL,
  `hak_akses` varchar(20) NOT NULL,
  `alamat` varchar(40) DEFAULT NULL,
  `jabatan` varchar(20) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data untuk tabel `user`
--

INSERT INTO `user` (`id_user`, `nama`, `username`, `password`, `email`, `hak_akses`, `alamat`, `jabatan`) VALUES
(1, 'Selvi', 'admin', 'admin', 'admin@gmail.com', 'admin', 'Padang', 'admin'),
(2, 'bidan', 'bidan', 'bidan', 'bidan@gmail.com', 'bidan', 'padang', 'bidan');

--
-- Indexes for dumped tables
--

--
-- Indeks untuk tabel `anak`
--
ALTER TABLE `anak`
  ADD PRIMARY KEY (`id`),
  ADD KEY `fk_orang_tua` (`orang_tua_id`);

--
-- Indeks untuk tabel `bidan`
--
ALTER TABLE `bidan`
  ADD PRIMARY KEY (`id_bidan`);

--
-- Indeks untuk tabel `kelola_imunisasi`
--
ALTER TABLE `kelola_imunisasi`
  ADD PRIMARY KEY (`id`),
  ADD KEY `anak_id` (`anak_id`),
  ADD KEY `orang_tua_no` (`orang_tua_no`),
  ADD KEY `bidan_id` (`bidan_id`);

--
-- Indeks untuk tabel `orang_tua`
--
ALTER TABLE `orang_tua`
  ADD PRIMARY KEY (`no`),
  ADD UNIQUE KEY `no_telpon` (`no_telpon`),
  ADD KEY `idx_nama_ibu` (`nama_ibu`),
  ADD KEY `idx_no_telpon` (`no_telpon`);

--
-- Indeks untuk tabel `penimbangan`
--
ALTER TABLE `penimbangan`
  ADD PRIMARY KEY (`id_penimbangan`),
  ADD KEY `id_anak` (`id_anak`),
  ADD KEY `id_bidan` (`id_bidan`);

--
-- Indeks untuk tabel `user`
--
ALTER TABLE `user`
  ADD PRIMARY KEY (`id_user`);

--
-- AUTO_INCREMENT untuk tabel yang dibuang
--

--
-- AUTO_INCREMENT untuk tabel `anak`
--
ALTER TABLE `anak`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=25;

--
-- AUTO_INCREMENT untuk tabel `bidan`
--
ALTER TABLE `bidan`
  MODIFY `id_bidan` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=7;

--
-- AUTO_INCREMENT untuk tabel `kelola_imunisasi`
--
ALTER TABLE `kelola_imunisasi`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=18;

--
-- AUTO_INCREMENT untuk tabel `orang_tua`
--
ALTER TABLE `orang_tua`
  MODIFY `no` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=35;

--
-- AUTO_INCREMENT untuk tabel `penimbangan`
--
ALTER TABLE `penimbangan`
  MODIFY `id_penimbangan` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=21;

--
-- AUTO_INCREMENT untuk tabel `user`
--
ALTER TABLE `user`
  MODIFY `id_user` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=15;

--
-- Ketidakleluasaan untuk tabel pelimpahan (Dumped Tables)
--

--
-- Ketidakleluasaan untuk tabel `anak`
--
ALTER TABLE `anak`
  ADD CONSTRAINT `anak_ibfk_1` FOREIGN KEY (`orang_tua_id`) REFERENCES `orang_tua` (`no`) ON DELETE SET NULL ON UPDATE CASCADE,
  ADD CONSTRAINT `fk_orang_tua` FOREIGN KEY (`orang_tua_id`) REFERENCES `orang_tua` (`no`) ON DELETE CASCADE ON UPDATE CASCADE;

--
-- Ketidakleluasaan untuk tabel `kelola_imunisasi`
--
ALTER TABLE `kelola_imunisasi`
  ADD CONSTRAINT `kelola_imunisasi_ibfk_1` FOREIGN KEY (`anak_id`) REFERENCES `anak` (`id`),
  ADD CONSTRAINT `kelola_imunisasi_ibfk_2` FOREIGN KEY (`orang_tua_no`) REFERENCES `orang_tua` (`no`),
  ADD CONSTRAINT `kelola_imunisasi_ibfk_3` FOREIGN KEY (`bidan_id`) REFERENCES `bidan` (`id_bidan`);

--
-- Ketidakleluasaan untuk tabel `penimbangan`
--
ALTER TABLE `penimbangan`
  ADD CONSTRAINT `penimbangan_ibfk_1` FOREIGN KEY (`id_anak`) REFERENCES `anak` (`id`),
  ADD CONSTRAINT `penimbangan_ibfk_2` FOREIGN KEY (`id_bidan`) REFERENCES `bidan` (`id_bidan`);
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
