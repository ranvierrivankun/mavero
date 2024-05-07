-- phpMyAdmin SQL Dump
-- version 4.8.5
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Waktu pembuatan: 07 Bulan Mei 2024 pada 08.18
-- Versi server: 10.1.38-MariaDB
-- Versi PHP: 5.6.40

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
SET AUTOCOMMIT = 0;
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Database: `mavero_database_default`
--

-- --------------------------------------------------------

--
-- Struktur dari tabel `group`
--

CREATE TABLE `group` (
  `id_group` int(11) NOT NULL,
  `id_user` int(11) NOT NULL,
  `name_group` varchar(255) NOT NULL,
  `status_group` varchar(255) NOT NULL,
  `created` datetime NOT NULL,
  `id_user_updated` int(11) DEFAULT NULL,
  `updated` datetime DEFAULT NULL,
  `id_user_received` int(11) DEFAULT NULL,
  `received` datetime DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

-- --------------------------------------------------------

--
-- Struktur dari tabel `material_delivery`
--

CREATE TABLE `material_delivery` (
  `id_md` int(11) NOT NULL,
  `id_group` int(11) NOT NULL,
  `no_resi` varchar(255) NOT NULL,
  `id_user` int(11) NOT NULL,
  `created` datetime NOT NULL,
  `id_user_updated` int(11) DEFAULT NULL,
  `updated` datetime DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

-- --------------------------------------------------------

--
-- Struktur dari tabel `material_grouping`
--

CREATE TABLE `material_grouping` (
  `id_mg` int(11) NOT NULL,
  `id_group` int(11) NOT NULL,
  `id_material` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

-- --------------------------------------------------------

--
-- Struktur dari tabel `material_out`
--

CREATE TABLE `material_out` (
  `id_mo` int(11) NOT NULL,
  `id_material` int(11) NOT NULL,
  `quantity_mo` int(11) NOT NULL,
  `id_user_mo` int(11) DEFAULT NULL,
  `created_mo` datetime DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

-- --------------------------------------------------------

--
-- Struktur dari tabel `material_pricing`
--

CREATE TABLE `material_pricing` (
  `id_mp` int(11) NOT NULL,
  `id_material` int(11) NOT NULL,
  `price` varchar(255) NOT NULL,
  `id_user` int(11) NOT NULL,
  `created` datetime NOT NULL,
  `id_user_updated` int(11) DEFAULT NULL,
  `updated` datetime DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

-- --------------------------------------------------------

--
-- Struktur dari tabel `material_storage`
--

CREATE TABLE `material_storage` (
  `id_material` int(11) NOT NULL,
  `id_mt` int(11) NOT NULL,
  `id_group` int(11) NOT NULL,
  `name` varchar(255) NOT NULL,
  `size` varchar(255) NOT NULL,
  `quantity` int(11) NOT NULL,
  `unit` varchar(255) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

-- --------------------------------------------------------

--
-- Struktur dari tabel `material_type`
--

CREATE TABLE `material_type` (
  `id_mt` int(11) NOT NULL,
  `name_mt` varchar(255) NOT NULL,
  `description` varchar(255) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Dumping data untuk tabel `material_type`
--

INSERT INTO `material_type` (`id_mt`, `name_mt`, `description`) VALUES
(1, 'Beton', 'Komponen elektronik yang digunakan dalam sistem pintar dan otomasi bangunan. Contoh: Sensor suhu untuk sistem kontrol iklim.'),
(2, 'Baja', 'Logam kuat yang digunakan dalam struktur bangunan dan rangka baja. Contoh: Balok baja struktural.'),
(3, 'Kayu', 'Material alami yang sering digunakan dalam struktur bangunan dan dekorasi. Contoh: Lantai kayu keras.'),
(4, 'Batu', 'Material alami yang digunakan dalam konstruksi bangunan, seperti batu bata atau batu alam. Contoh: Dinding batu bata.'),
(5, 'Bahan isolasi', 'Material untuk isolasi panas atau suara. Contoh: Wol mineral sebagai isolasi atap.'),
(6, 'Keramik', 'Material keras dan tahan panas untuk lantai, dinding, atau lapisan pelindung. Contoh: Lantai keramik.'),
(7, 'Kaca', 'Material transparan yang digunakan untuk jendela dan dinding kaca. Contoh: Jendela kaca ganda.'),
(8, 'Plastik', 'Material ringan yang digunakan dalam berbagai aplikasi bangunan. Contoh: Pipa PVC untuk saluran air.'),
(9, 'Logam', 'Material padat yang kuat dan tahan korosi. Contoh: Baja struktural untuk kerangka bangunan.'),
(10, 'Komposit', 'Material gabungan dua atau lebih material untuk meningkatkan kekuatan dan kegunaan. Contoh: Panel komposit aluminium untuk fasad bangunan.'),
(11, 'Aspal', 'Material yang digunakan untuk pembuatan jalan atau atap. Contoh: Lapisan aspal pada jalan raya.'),
(12, 'Bahan Kimia', 'Zat kimia yang digunakan dalam konstruksi, seperti aditif beton atau pelapis anti-korosi. Contoh: Aditif penambah kekuatan beton.'),
(13, 'Karet', 'Material elastis yang digunakan untuk segel atau bantalan. Contoh: Karet silikon untuk segel jendela.'),
(14, 'Kain', 'Material tekstil yang digunakan sebagai pelapis atau dekorasi dalam bangunan. Contoh: Tirai, karpet, penutup dinding.'),
(15, 'Elektronik', 'Komponen elektronik yang digunakan dalam sistem pintar dan otomasi bangunan. Contoh: Sensor suhu untuk sistem kontrol iklim.'),
(17, 'Tembaga', 'Tembaga adalah logam dengan konduktivitas listrik yang sangat baik. Contoh: Kabel Listrik.');

-- --------------------------------------------------------

--
-- Struktur dari tabel `request_material`
--

CREATE TABLE `request_material` (
  `id_material` int(11) NOT NULL,
  `id_user` int(11) NOT NULL,
  `id_mt` int(11) NOT NULL,
  `id_group` int(11) DEFAULT NULL,
  `name` varchar(255) NOT NULL,
  `size` varchar(255) NOT NULL,
  `quantity` int(11) NOT NULL,
  `unit` varchar(255) NOT NULL,
  `status` varchar(255) NOT NULL,
  `created` datetime NOT NULL,
  `id_user_updated` int(11) DEFAULT NULL,
  `updated` datetime DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Dumping data untuk tabel `request_material`
--

INSERT INTO `request_material` (`id_material`, `id_user`, `id_mt`, `id_group`, `name`, `size`, `quantity`, `unit`, `status`, `created`, `id_user_updated`, `updated`) VALUES
(1, 2, 15, 1, 'Lampu TL 36', '36 Watt', 5, 'Dus', 'pricing', '2023-09-16 17:27:41', NULL, NULL),
(2, 2, 5, 1, 'Isolasi Nitto Listrik', '-', 10, 'Pcs', 'pricing', '2023-09-16 17:30:18', 2, '2023-09-16 17:30:30'),
(3, 2, 15, 1, 'Stater S10', 'S10', 5, 'Pack', 'pricing', '2023-09-16 17:31:25', 2, '2023-09-16 17:31:33'),
(4, 2, 15, 1, 'Ballast 36 Watt', '36 Watt', 10, 'Pcs', 'pricing', '2023-09-16 17:32:09', NULL, NULL),
(5, 2, 17, 1, 'Kabel Ballast', '0,7', 50, 'Meter', 'pricing', '2023-09-16 17:33:52', NULL, NULL);

-- --------------------------------------------------------

--
-- Struktur dari tabel `role_db`
--

CREATE TABLE `role_db` (
  `id_role` int(11) NOT NULL,
  `name_role` varchar(255) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Dumping data untuk tabel `role_db`
--

INSERT INTO `role_db` (`id_role`, `name_role`) VALUES
(1, 'Administration'),
(2, 'Engineering'),
(3, 'Procurement'),
(4, 'Warehouse');

-- --------------------------------------------------------

--
-- Struktur dari tabel `setting`
--

CREATE TABLE `setting` (
  `name` varchar(255) NOT NULL,
  `email` varchar(255) NOT NULL,
  `mobile` varchar(255) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Dumping data untuk tabel `setting`
--

INSERT INTO `setting` (`name`, `email`, `mobile`) VALUES
('PT. Ranvier', 'rvn@mavero.com', '(021) 1234 987');

-- --------------------------------------------------------

--
-- Struktur dari tabel `user_db`
--

CREATE TABLE `user_db` (
  `id_user` int(11) NOT NULL,
  `id_role` int(11) NOT NULL,
  `username` varchar(255) NOT NULL,
  `password` varchar(255) NOT NULL,
  `name` varchar(255) NOT NULL,
  `email` varchar(255) NOT NULL,
  `mobile` varchar(255) NOT NULL,
  `status` varchar(255) NOT NULL,
  `created` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Dumping data untuk tabel `user_db`
--

INSERT INTO `user_db` (`id_user`, `id_role`, `username`, `password`, `name`, `email`, `mobile`, `status`, `created`) VALUES
(1, 1, 'developer', '$2y$10$KGgzgR9JHA5pR19GmOIkou5sKUb06Jq9TAAm6fVP9pqgiABvARQda', 'Developer Mavero', 'developer@email.com', '081283143133', 'aktif', '2023-09-04 16:18:14'),
(2, 2, 'user1', '$2y$10$tydQC0GRHZxQrskN70z0dOdX/4SnSJBok3mby6cXl7wwDD3R9ODxG', 'User Satu', 'user1@email.com', '081232437765', 'aktif', '2023-09-02 04:01:56'),
(3, 3, 'user2', '$2y$10$q/GSnfz.DKdH.u1NipfYh.7CpeRnIKeojfdscd9vve0jhomc8M7sG', 'User Dua', 'user2@gmail.com', '081283245344', 'aktif', '2023-09-02 19:43:03'),
(4, 4, 'user3', '$2y$10$M6TrdZ6XCtpl9Q6hNjwS5Oektld9PxhDlyYZFXXWPAHz8L3D2ly0K', 'User Tiga', 'user3@gmail.com', '081283542343', 'aktif', '2023-09-02 19:43:12'),
(10, 2, 'test', '$2y$10$RyusQURRA0peQuYpBnUUoelm6BoZuCZUGsfetrI561duubxh9PYna', 'Test User', 'test@gmail.com', '081298765673', 'aktif', '2023-09-04 15:57:13');

--
-- Indexes for dumped tables
--

--
-- Indeks untuk tabel `group`
--
ALTER TABLE `group`
  ADD PRIMARY KEY (`id_group`);

--
-- Indeks untuk tabel `material_delivery`
--
ALTER TABLE `material_delivery`
  ADD PRIMARY KEY (`id_md`);

--
-- Indeks untuk tabel `material_grouping`
--
ALTER TABLE `material_grouping`
  ADD PRIMARY KEY (`id_mg`);

--
-- Indeks untuk tabel `material_out`
--
ALTER TABLE `material_out`
  ADD PRIMARY KEY (`id_mo`);

--
-- Indeks untuk tabel `material_pricing`
--
ALTER TABLE `material_pricing`
  ADD PRIMARY KEY (`id_mp`);

--
-- Indeks untuk tabel `material_storage`
--
ALTER TABLE `material_storage`
  ADD PRIMARY KEY (`id_material`);

--
-- Indeks untuk tabel `material_type`
--
ALTER TABLE `material_type`
  ADD PRIMARY KEY (`id_mt`);

--
-- Indeks untuk tabel `request_material`
--
ALTER TABLE `request_material`
  ADD PRIMARY KEY (`id_material`);

--
-- Indeks untuk tabel `role_db`
--
ALTER TABLE `role_db`
  ADD PRIMARY KEY (`id_role`);

--
-- Indeks untuk tabel `user_db`
--
ALTER TABLE `user_db`
  ADD PRIMARY KEY (`id_user`);

--
-- AUTO_INCREMENT untuk tabel yang dibuang
--

--
-- AUTO_INCREMENT untuk tabel `group`
--
ALTER TABLE `group`
  MODIFY `id_group` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT untuk tabel `material_delivery`
--
ALTER TABLE `material_delivery`
  MODIFY `id_md` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT untuk tabel `material_grouping`
--
ALTER TABLE `material_grouping`
  MODIFY `id_mg` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT untuk tabel `material_out`
--
ALTER TABLE `material_out`
  MODIFY `id_mo` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT untuk tabel `material_pricing`
--
ALTER TABLE `material_pricing`
  MODIFY `id_mp` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT untuk tabel `material_type`
--
ALTER TABLE `material_type`
  MODIFY `id_mt` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=18;

--
-- AUTO_INCREMENT untuk tabel `request_material`
--
ALTER TABLE `request_material`
  MODIFY `id_material` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=6;

--
-- AUTO_INCREMENT untuk tabel `role_db`
--
ALTER TABLE `role_db`
  MODIFY `id_role` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=5;

--
-- AUTO_INCREMENT untuk tabel `user_db`
--
ALTER TABLE `user_db`
  MODIFY `id_user` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=11;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
