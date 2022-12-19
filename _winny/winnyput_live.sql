-- phpMyAdmin SQL Dump
-- version 4.9.7
-- https://www.phpmyadmin.net/
--
-- Host: localhost:3306
-- Generation Time: Jul 31, 2022 at 01:27 PM
-- Server version: 10.3.35-MariaDB-cll-lve
-- PHP Version: 7.4.30

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
SET AUTOCOMMIT = 0;
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Database: `winnyput_live`
--

-- --------------------------------------------------------

--
-- Table structure for table `blw_@blog`
--

CREATE TABLE `blw_@blog` (
  `id` int(11) NOT NULL,
  `tgl` datetime NOT NULL,
  `judul` text NOT NULL,
  `konten` text NOT NULL,
  `img` text NOT NULL,
  `url` text NOT NULL,
  `views` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Dumping data for table `blw_@blog`
--

INSERT INTO `blw_@blog` (`id`, `tgl`, `judul`, `konten`, `img`, `url`, `views`) VALUES
(1, '2021-11-06 13:10:00', 'Test', '<p>Testing blog</p>', 'blogpost_720211106130958.jpg', 'Test', 0);

-- --------------------------------------------------------

--
-- Table structure for table `blw_@kategori`
--

CREATE TABLE `blw_@kategori` (
  `id` int(11) NOT NULL,
  `parent` int(11) NOT NULL,
  `url` text NOT NULL,
  `nama` text NOT NULL,
  `icon` varchar(500) NOT NULL DEFAULT 'default.png'
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Dumping data for table `blw_@kategori`
--

INSERT INTO `blw_@kategori` (`id`, `parent`, `url`, `nama`, `icon`) VALUES
(25, 0, 'Supplement-Makanan', 'Supplement Makanan', 'default.png'),
(26, 0, 'Perawatan-Wajah-Lainnya', 'Perawatan Wajah Lainnya', 'default.png'),
(27, 0, 'Perawatan-Payudara', 'Perawatan Payudara', 'default.png'),
(29, 0, 'Treatment-Jerawat', 'Treatment Jerawat', 'default.png'),
(30, 0, 'Body-Cream-Body-Lotion-Body-Butter', 'Body Cream, Body Lotion & Body Butter', 'default.png'),
(31, 0, 'Pelembab-Wajah', 'Pelembab Wajah', 'default.png'),
(32, 0, 'Perawatan-Package', 'Perawatan Package', 'default.png'),
(34, 0, 'Serum-Essence-Wajah', 'Serum & Essence Wajah', 'default.png'),
(35, 0, 'Deodorant', 'Deodorant', 'default.png');

-- --------------------------------------------------------

--
-- Table structure for table `blw_@kurir`
--

CREATE TABLE `blw_@kurir` (
  `id` int(11) NOT NULL,
  `nama` varchar(200) NOT NULL,
  `rajaongkir` varchar(300) NOT NULL,
  `namalengkap` varchar(300) NOT NULL,
  `keterangan` text NOT NULL,
  `halaman` text NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Dumping data for table `blw_@kurir`
--

INSERT INTO `blw_@kurir` (`id`, `nama`, `rajaongkir`, `namalengkap`, `keterangan`, `halaman`) VALUES
(2, 'KURIR TOKO', 'toko', 'KURIR TOKO', '', 'toko'),
(3, 'JNE', 'jne', 'PT Tiki Jalur Nugraha Ekakurir', '-', 'jne'),
(4, 'SiCepat Express', 'sicepat', 'PT. SiCepat Ekspres Indonesia', '-', 'sicepat'),
(6, 'GoSend', 'gosend', 'PT Aplikasi Karya Anak Bangsa', '-', 'gosend');

-- --------------------------------------------------------

--
-- Table structure for table `blw_@page`
--

CREATE TABLE `blw_@page` (
  `id` int(11) NOT NULL,
  `tgl` datetime NOT NULL,
  `usrid` int(11) NOT NULL,
  `nama` text COLLATE utf8_bin NOT NULL,
  `slug` text COLLATE utf8_bin NOT NULL,
  `konten` text COLLATE utf8_bin NOT NULL,
  `status` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_bin;

--
-- Dumping data for table `blw_@page`
--

INSERT INTO `blw_@page` (`id`, `tgl`, `usrid`, `nama`, `slug`, `konten`, `status`) VALUES
(1, '2019-06-03 00:00:00', 1, 'Persyaratan Layanan Penjual', 'tos', 'Syarat & ketentuan yang ditetapkan di bawah ini mengatur pemakaian jasa yang ditawarkan oleh ALLBATIK terkait penggunaan situs www.allbatik.id. Pengguna disarankan membaca dengan seksama karena dapat berdampak kepada hak dan kewajiban Pengguna di bawah hukum.\r\nDengan mendaftar dan/atau menggunakan situs www.allbatik.id, maka pengguna dianggap telah membaca, mengerti, memahami dan menyutujui semua isi dalam Syarat & ketentuan. Syarat & ketentuan ini merupakan bentuk kesepakatan yang dituangkan dalam sebuah perjanjian yang sah antara Pengguna dengan ALLBATIK. Jika pengguna tidak menyetujui salah satu, sebagian, atau seluruh isi Syarat & ketentuan, maka pengguna tidak diperkenankan menggunakan layanan di www.allbatik.id.\r\n\r\nA. Definisi\r\nB. Akun, Saldo, Password dan Keamanan\r\nC. Transaksi Penjualan\r\nD. Penataan Etalase\r\nE. Komisi\r\nF. Harga\r\nG. Tarif Pengiriman\r\nH. Konten\r\nI. Jenis Barang\r\nJ. Pengiriman Barang\r\nK. Penarikan Dana\r\nL. Penolakan Jaminan Dan Batasan Tanggung Jawab\r\nM. Pelepasan\r\nN. Ganti Rugi\r\nO. Pilihan Hukum\r\nP. Pembaharuan\r\n\r\n\r\nA. DEFINISI\r\n1. ALLBATIK adalah suatu platform yang menjalankan kegiatan usaha jasa web portal www.allbatik.id, yakni situs pencarian toko dan Barang yang dijual oleh penjual terdaftar. Selanjutnya disebut ALLBATIK.\r\n2. Situs ALLBATIK adalah www.allbatik.id.\r\n3. Syarat & ketentuan adalah perjanjian antara Pengguna dan ALLBATIK yang berisikan seperangkat peraturan yang mengatur hak, kewajiban, tanggung jawab pengguna dan ALLBATIK, serta tata cara penggunaan sistem layanan ALLBATIK.\r\n4. Pengguna adalah pihak yang menggunakan layanan ALLBATIK, termasuk namun tidak terbatas pada pembeli, penjual ataupun pihak lain yang sekedar berkunjung ke Situs ALLBATIK.\r\n5. Pembeli adalah Pengguna terdaftar yang melakukan permintaan atas Barang yang dijual oleh Penjual di Situs ALLBATIK.\r\n6. Penjual adalah Pengguna terdaftar yang melakukan tindakan buka toko dan/atau melakukan penawaran atas suatu Barang kepada para Pengguna Situs ALLBATIK.\r\n7. Barang adalah benda yang berwujud / memiliki fisik Barang yang dapat diantar / memenuhi kriteria pengiriman oleh perusahaan jasa pengiriman Barang.\r\n8. Saldo adalah fasilitas penampungan sementara atas dana milik Pembeli dan Penjual (bukan fasilitas penyimpanan dana), yang disediakan ALLBATIK untuk menampung pengembalian dana transaksi (refund) pembelian Barang dan dana hasil penjualan Barang pada Situs ALLBATIK. Dana ini hanya dapat digunakan kembali untuk melakukan pembelian pada Situs ALLBATIK dan/atau ditarik ke akun bank yang terdaftar.\r\n9. Feed adalah fitur pada Situs/Aplikasi ALLBATIK yang menampilkan konten dari Penjual, KOL, atau pihak lainnya terkait Barang tertentu.\r\n10. Key Opinion Leaders atau KOL adalah pihak yang mempromosikan Barang atau Penjual tertentu melalui Feed.\r\n11. Rekening Resmi ALLBATIK adalah rekening bersama yang disepakati oleh ALLBATIK dan para pengguna untuk proses transaksi jual beli di Situs ALLBATIK. Rekening resmi ALLBATIK dapat ditemukan di halaman https://www.allbatik.id/bantuan/nomor-rekening-ALLBATIK/.\r\n kembali ke atas\r\n\r\nB. AKUN, SALDO, PASSWORD DAN KEAMANAN\r\n1. Pengguna dengan ini menyatakan bahwa pengguna adalah orang yang cakap dan mampu untuk mengikatkan dirinya dalam sebuah perjanjian yang sah menurut hukum.\r\n2. ALLBATIK tidak memungut biaya pendaftaran kepada Pengguna.\r\n3. Pengguna yang telah mendaftar berhak bertindak sebagai Penjual, dengan memanfaatkan layanan buka toko.\r\n4. Pengguna yang akan bertindak sebagai Penjual diwajibkan memilih pilihan menggunakan layanan buka toko. Setelah menggunakan layanan buka toko, Pengguna berhak melakukan pengaturan terhadap item-item yang akan diperdagangkan di etalase pribadi Pengguna.\r\n5. ALLBATIK tanpa pemberitahuan terlebih dahulu kepada Pengguna, memiliki kewenangan untuk melakukan tindakan yang perlu atas setiap dugaan pelanggaran atau pelanggaran Syarat & ketentuan dan/atau hukum yang berlaku, yakni tindakan berupa memindahkan Barang ke gudang, penghapusan Barang, moderasi toko, penutupan toko, pembatalan listing, suspensi akun, dan/atau penghapusan akun pengguna.\r\n6. ALLBATIK memiliki kewenangan untuk menutup toko atau akun Pengguna baik sementara maupun permanen apabila didapati adanya tindakan kecurangan dalam bertransaksi dan/atau pelanggaran terhadap syarat dan ketentuan ALLBATIK. Pengguna menyetujui bahwa ALLBATIK berhak melakukan tindakan lain yang diperlukan terkait hal tersebut, termasuk namun tidak terbatas pada menolak pengajuan pembukaan toko yang baru apabila ditemukan kesamaan data.\r\n7. Pengguna dilarang untuk menciptakan dan/atau menggunakan perangkat, software, fitur dan/atau alat lainnya yang bertujuan untuk melakukan manipulasi pada sistem ALLBATIK, termasuk namun tidak terbatas pada : (i) manipulasi data Toko; (ii) kegiatan perambanan (crawling/scraping); (iii) kegiatan otomatisasi dalam transaksi, jual beli, promosi, dsb; (v) penambahan produk ke etalase; dan/atau (vi) aktivitas lain yang secara wajar dapat dinilai sebagai tindakan manipulasi sistem.\r\n8. ALLBATIK memiliki kewenangan untuk melakukan penyesuaian jumlah transaksi toko, penyesuaian jumlah reputasi, dan/atau melakukan proses moderasi/menutup akun Pengguna, jika diketahui atau diduga adanya kecurangan oleh Pengguna yang bertujuan memanipulasi data transaksi Pengguna demi meningkatkan reputasi toko (review dan atau jumlah transaksi). Contohnya adalah melakukan proses belanja ke toko sendiri dengan menggunakan akun pribadi atau akun pribadi lainnya.\r\n9. Saldo berasal dari pengembalian dana transaksi (refund) pembelian dan penjualan Barang di Situs ALLBATIK dan tidak bisa dilakukan penambahan secara sendiri (top up).\r\n10. Saldo dapat digunakan sebagai salah satu metode pembayaran atas transaksi pembelian Barang di Situs ALLBATIK, dan dapat dilakukan penarikan dana (withdrawal) ke rekening bank yang terdaftar pada akun Pengguna.\r\n11. ALLBATIK memiliki kewenangan untuk melakukan pembekuan Saldo Pengguna apabila ditemukan / diduga adanya tindak kecurangan dalam bertransaksi dan/atau pelanggaran terhadap syarat dan ketentuan ALLBATIK.\r\n12. Penjual dilarang melakukan duplikasi toko, duplikasi produk, atau tindakan-tindakan lain yang dapat diindikasikan sebagai usaha persaingan tidak sehat.\r\n13. Pengguna tidak memiliki hak untuk mengubah nama akun, nama toko dan/atau domain toko Pengguna.\r\n14. Pengguna bertanggung jawab secara pribadi untuk menjaga kerahasiaan akun dan password untuk semua aktivitas yang terjadi dalam akun Pengguna.\r\n15. ALLBATIK tidak akan meminta username, password milik akun Pengguna untuk alasan apapun, oleh karena itu ALLBATIK menghimbau Pengguna agar tidak memberikan data tersebut maupun data penting lainnya kepada pihak yang mengatasnamakan ALLBATIK atau pihak lain yang tidak dapat dijamin keamanannya.\r\n16. Pengguna setuju untuk memastikan bahwa Pengguna keluar dari akun di akhir setiap sesi dan memberitahu ALLBATIK jika ada penggunaan tanpa izin atas sandi atau akun Pengguna.\r\n17. Pengguna dengan ini menyatakan bahwa ALLBATIK tidak bertanggung jawab atas kerugian ataupun kendala yang timbul atas penyalahgunaan akun Pengguna yang diakibatkan oleh kelalaian Pengguna, termasuk namun tidak terbatas pada meminjamkan atau memberikan akses akun kepada pihak lain, mengakses link atau tautan yang diberikan oleh pihak lain, memberikan atau memperlihatkan password atau email kepada pihak lain, maupun kelalaian Pengguna lainnya yang mengakibatkan kerugian ataupun kendala pada akun Pengguna.\r\n18. Penjual dilarang mempromosikan toko dan/atau Barang secara langsung menggunakan fasilitas pesan pribadi, diskusi produk, ulasan produk yang dapat mengganggu kenyamanan Pengguna lain.\r\n kembali ke atas\r\n\r\nC. TRANSAKSI PENJUALAN\r\n1. Penjual dilarang memanipulasi harga Barang dengan tujuan apapun.\r\n2. Penjual dilarang melakukan penawaran / berdagang Barang terlarang sesuai dengan yang telah ditetapkan pada ketentuan \"Jenis Barang\".\r\n3. Penjual wajib memberikan foto dan informasi produk dengan lengkap dan jelas sesuai dengan kondisi dan kualitas produk yang dijualnya. Apabila terdapat ketidaksesuaian antara foto dan informasi produk yang diunggah oleh Penjual dengan produk yang diterima oleh Pembeli, maka ALLBATIK berhak membatalkan/menahan dana transaksi.\r\n4. Dalam menggunakan Fasilitas \"Judul Produk\", \"Foto Produk\", dan \"Deskripsi Produk\", Penjual dilarang membuat peraturan bersifat klausula baku yang tidak memenuhi peraturan perundang-undangan yang berlaku di Indonesia, termasuk namun tidak terbatas pada (i) tidak menerima komplain, (ii) tidak menerima retur (penukaran barang), (iii) tidak menerima refund (pengembalian dana), (iv) barang tidak bergaransi, (v) pengalihan tanggung jawab (termasuk tidak terbatas pada penanggungan ongkos kirim), (vi) penyusutan nilai harga dan (vii) pengiriman barang acak secara sepihak. Jika terdapat pertentangan antara catatan toko dan/atau deskripsi produk dengan Syarat & Ketentuan ALLBATIK, maka peraturan yang berlaku adalah Syarat & Ketentuan ALLBATIK.\r\n5. Penjual wajib memberikan balasan untuk menerima atau menolak pesanan Barang pihak Pembeli dalam batas waktu 2 hari terhitung sejak adanya notifikasi pesanan Barang dari ALLBATIK. Jika dalam batas waktu tersebut tidak ada balasan dari Penjual maka secara otomatis pesanan akan dibatalkan.\r\n6. Demi menjaga kenyamanan Pembeli dalam bertransaksi, Penjual memahami dan menyetujui bahwa ALLBATIK berhak melakukan moderasi toko Penjual apabila Penjual melakukan penolakan, pembatalan dan/atau tidak merespon pesanan Barang milik Pembeli dengan dugaan untuk memanipulasi transaksi, pelanggaran atas Syarat dan Ketentuan, dan/atau kecurangan atau penyalahgunaan lainnya.\r\n7. Penjual wajib memasukan nomor resi pengiriman Barang dalam batas waktu 2 x 24 jam (tidak termasuk hari Sabtu/Minggu/libur Nasional) terhitung sejak adanya notifikasi pesanan Barang dari ALLBATIK. Jika dalam batas waktu tersebut pihak Penjual tidak memasukan nomor resi pengiriman Barang maka secara otomatis pesanan dianggap dibatalkan. Jika Penjual tetap mengirimkan Barang setelah melebihi batas waktu pengiriman sebagaimana dijelaskan diatas, maka Penjual memahami bahwa transaksi akan tetap dibatalkan untuk kemudian Penjual dapat melakukan penarikan Barang pada kurir tempat Barang dikirimkan.\r\n8. Penjual memahami dan menyetujui bahwa pembayaran atas harga Barang dan ongkos kirim (diluar biaya transfer / administrasi) akan dikembalikan sepenuhnya ke Pembeli apabila transaksi dibatalkan dan/atau transaksi tidak berhasil dan/atau ketentuan lain yang diatur dalam Syarat & Ketentuan Poin D. 14.\r\n9. Dalam keadaan Penjual hanya dapat memenuhi sebagian dari jumlah Barang yang dipesan oleh Pembeli, maka Penjual wajib membatalkan transaksi.\r\n10. ALLBATIK memiliki kewenangan untuk menahan pembayaran dana di Rekening Resmi ALLBATIK sampai waktu yang tidak ditentukan apabila terdapat permasalahan dan klaim dari pihak Pembeli terkait proses pengiriman dan kualitas Barang. Pembayaran baru akan dilanjut kirimkan kepada Penjual apabila permasalahan tersebut telah selesai dan/atau Barang telah diterima oleh Pembeli.\r\n11. ALLBATIK berwenang untuk membatalkan transaksi dan/atau menahan dana transaksi dalam hal: (i) nomor resi kurir pengiriman Barang yang diberikan oleh Penjual tidak sesuai dan/atau diduga tidak sesuai dengan transaksi yang terjadi di Situs ALLBATIK; (ii) Penjual mengirimkan Barang melalui jasa kurir/logistik selain dari yang disediakan dan terhubung dengan Situs ALLBATIK; (iii) jika nama produk dan deskripsi produk tidak sesuai/tidak jelas dengan produk yang dikirim; (iv) jika ditemukan adanya manipulasi transaksi; dan/atau (v) mencantumkan nomor resi pengiriman Barang yang telah digunakan oleh Penjual lainnya.\r\n12. Penjual memahami dan menyetujui bahwa Pajak Penghasilan Penjual akan dilaporkan dan diurus sendiri oleh masing-masing Penjual sesuai dengan ketentuan pajak yang berlaku di peraturan perundang-undangan di Indonesia.\r\n13. ALLBATIK berwenang mengambil keputusan atas permasalahan-permasalahan transaksi yang belum terselesaikan akibat tidak adanya kesepakatan penyelesaian, baik antara Penjual dan Pembeli, dengan melihat bukti-bukti yang ada. Keputusan ALLBATIK adalah keputusan akhir yang tidak dapat diganggu gugat dan mengikat pihak Penjual dan Pembeli untuk mematuhinya.\r\n14. Apabila disepakati oleh Penjual dan Pembeli, penggunaan jasa Logistik yang berbeda dari pilihan awal pembeli dapat dilakukan (dengan ketentuan bahwa tarif pengiriman tersebut adalah dibawah tarif pengiriman awal).\r\n15. ALLBATIK berwenang memotong kelebihan tarif pengiriman dari dana pembayaran pembeli dan mengembalikan selisih kelebihan tarif pengiriman kepada pembeli.\r\n16. Penjual memahami sepenuhnya dan menyetujui bahwa invoice yang diterbitkan adalah atas nama Penjual.\r\n kembali ke atas\r\n\r\nD. PENATAAN ETALASE\r\n1.	Penjual dilarang mempergunakan etalase (termasuk dan tidak tebatas pada informasi toko dan informasi barang) sebagai media untuk beriklan atau melakukan promosi ke halaman situs lain diluar situs ALLBATIK.\r\n2.	Penjual dilarang memberikan data kontak pribadi dengan maksud untuk melakukan transaksi secara langsung kepada Pembeli / calon Pembeli.\r\n3.	Penjual dilarang memberikan keterangan (informasi toko dan/atau Barang) selain/diluar daripada keterangan toko dan/atau Barang yang bersangkutan.\r\n4.	Penamaan Barang harus dilakukan sesuai dengan informasi detail, spesifikasi, dan kondisi Barang, dengan demikian Pengguna tidak diperkenankan untuk mencantumkan nama dan/atau kata yang tidak berkaitan dengan Barang tersebut.\r\n5.	Penamaan Barang dan informasi produk harus sesuai dengan kondisi Barang yang ditampilkan dan Pengguna tidak diperkenankan mencantumkan nama dan informasi yang tidak sesuai dengan kondisi Barang.\r\n6.	Penjual wajib memisahkan tiap-tiap Barang yang memiliki ukuran dan harga yang berbeda.\r\n7.	Penjual tidak diperkenankan memperdagangkan jasa, atau Barang non-fisik.\r\n8.	ALLBATIK memiliki kewenangan mengambil-alih sub-domain toko Penjual jika akun Penjual sudah tidak aktif lebih dari 9 bulan, dan/atau pemilik merek dagang resmi (sesuai dengan Daftar Umum Merek di Indonesia) dengan nama yang sama dengan sub-domain Penjual melakukan klaim terhadapnya dikarenakan mereka ingin menggunakan sub-domain tersebut.\r\n9.	ALLBATIK memiliki kewenangan untuk mengubah nama dan/atau memakai nama Toko dan/atau domain Pengguna untuk kepentingan internal ALLBATIK.\r\n kembali ke atas\r\n________________________________________\r\nE.	KOMISI\r\n1.	ALLBATIK memberlakukan sistem komisi untuk setiap transaksi yang dilakukan melalui Rekening Resmi ALLBATIK sebesar 10% dari nilai transaksi. \r\n2.	Dana diteruskan ke Saldo milik Penjual sebesar nilai transaksi yang sudah terpotong komisi 10% dari nilai transaksi tersebut.\r\n kembali ke atas\r\n________________________________________\r\nF.	HARGA\r\n1.	Harga Barang yang terdapat dalam situs ALLBATIK adalah harga yang ditetapkan oleh Penjual. Penjual dilarang memanipulasi harga barang dengan cara apapun.\r\n2.	Penjual dilarang menetapkan harga yang tidak wajar pada Barang yang ditawarkan melalui Situs ALLBATIK. ALLBATIK berhak untuk melakukan tindakan berupa memindahkan Barang ke gudang, pemeriksaan, penundaan, atau penurunan konten serta tindakan lainnya berdasarkan penilaian sendiri dari ALLBATIK atas dasar penetapan harga yang tidak wajar.\r\n3.	Penjual memahami dan menyetujui bahwa kesalahan ketik yang menyebabkan keterangan harga atau informasi lain menjadi tidak benar/sesuai adalah tanggung jawab Penjual. Perlu diingat dalam hal ini, apabila terjadi kesalahan pengetikan keterangan harga Barang yang tidak disengaja, Penjual berhak menolak pesanan Barang yang dilakukan oleh pembeli.\r\n4.	Pengguna memahami dan menyetujui bahwa setiap masalah dan/atau perselisihan yang terjadi akibat ketidaksepahaman antara Penjual dan Pembeli tentang harga bukanlah merupakan tanggung jawab ALLBATIK.\r\n5.	Batasan harga maksimal satuan untuk Barang yang dapat ditawarkan adalah Rp. 100.000.000,-\r\n6.	Situs ALLBATIK untuk saat ini hanya melayani transaksi jual beli Barang dalam mata uang Rupiah.\r\n kembali ke atas\r\n________________________________________\r\nG.	TARIF PENGIRIMAN\r\nPembeli memahami dan mengerti bahwa ALLBATIK telah melakukan usaha sebaik mungkin dalam memberikan informasi tarif pengiriman kepada Pembeli berdasarkan lokasi secara akurat, namun ALLBATIK tidak dapat menjamin keakuratan data tersebut dengan yang ada pada cabang setempat.\r\nKarena itu ALLBATIK menyarankan kepada Penjual untuk mencatat terlebih dahulu tarif yang diberikan ALLBATIK, agar dapat dibandingkan dengan tarif yang dibebankan di cabang setempat. Apabila mendapati perbedaan, mohon sekiranya untuk menginformasikan kepada kami melalui menu contact us dengan memberikan data harga yang didapat beserta kota asal dan tujuan, agar dapat kami telusuri lebih lanjut.\r\nPengguna memahami dan menyetujui bahwa selisih biaya pengiriman Barang adalah di luar tanggung jawab ALLBATIK, dan oleh karena itu, adalah kebijakan Penjual sendiri untuk membatalkan atau tetap melakukan pengiriman Barang.\r\n kembali ke atas\r\n________________________________________\r\nH.	KONTEN\r\n1.	Dalam menggunakan setiap fitur dan/atau layanan ALLBATIK, Pengguna dilarang untuk mengunggah atau mempergunakan kata-kata, komentar, gambar, atau konten apapun yang mengandung unsur SARA, diskriminasi, merendahkan atau menyudutkan orang lain, vulgar, bersifat ancaman, beriklan atau melakukan promosi ke situs selain Situs ALLBATIK, atau hal-hal lain yang dapat dianggap tidak sesuai dengan nilai dan norma sosial maupun berdasarkan kebijakan yang ditentukan sendiri oleh ALLBATIK. ALLBATIK berhak melakukan tindakan yang diperlukan atas pelanggaran ketentuan ini, antara lain penghapusan konten, moderasi toko, pemblokiran akun, dan lain-lain.\r\n2.	Pengguna dilarang mempergunakan foto/gambar Barang yang memiliki watermark yang menandakan hak kepemilikan orang lain.\r\n3.	Pengguna dengan ini memahami dan menyetujui bahwa penyalahgunaan foto/gambar yang di unggah oleh Pengguna adalah tanggung jawab Pengguna secara pribadi.\r\n4.	Penjual tidak diperkenankan untuk mempergunakan foto/gambar Barang atau logo toko sebagai media untuk beriklan atau melakukan promosi ke situs-situs lain diluar Situs ALLBATIK, atau memberikan data kontak pribadi untuk melakukan transaksi secara langsung kepada pembeli / calon pembeli.\r\n5.	Ketika Pengguna mengunggah ke Situs ALLBATIK dengan konten atau posting konten, Pengguna memberikan ALLBATIK hak non-eksklusif, di seluruh dunia, secara terus-menerus, tidak dapat dibatalkan, bebas royalti, disublisensikan ( melalui beberapa tingkatan ) hak untuk melaksanakan setiap dan semua hak cipta, publisitas , merek dagang , hak basis data dan hak kekayaan intelektual yang Pengguna miliki dalam konten, di media manapun yang dikenal sekarang atau di masa depan. Selanjutnya , untuk sepenuhnya diizinkan oleh hukum yang berlaku , Anda mengesampingkan hak moral dan berjanji untuk tidak menuntut hak-hak tersebut terhadap ALLBATIK.\r\n6.	Pengguna menjamin bahwa tidak melanggar hak kekayaan intelektual dalam mengunggah konten Pengguna kedalam situs ALLBATIK. Setiap Pengguna dengan ini bertanggung jawab secara pribadi atas pelanggaran hak kekayaan intelektual dalam mengunggah konten di Situs ALLBATIK.\r\n7.	ALLBATIK menyediakan fitur \"Diskusi\" untuk memudahkan pembeli berinteraksi dengan penjual, perihal Barang yang ditawarkan. Penjual tidak diperkenankan menggunakan fitur tersebut untuk tujuan dengan cara apa pun menaikkan harga Barang dagangannya, termasuk di dalamnya memberi komentar pertama kali atau memberi komentar selanjutnya / terus menerus secara berkala (flooding / spam).\r\n8.	Meskipun kami mencoba untuk menawarkan informasi yang dapat diandalkan, kami tidak bisa menjanjikan bahwa katalog akan selalu akurat dan terbarui, dan Pengguna setuju bahwa Pengguna tidak akan meminta ALLBATIK bertanggung jawab atas ketimpangan dalam katalog. Katalog mungkin termasuk hak cipta, merek dagang atau hak milik lainnya.\r\n9.	Konten atau materi yang akan ditampilkan atau ditayangkan pada Situs/Aplikasi ALLBATIK melalui Feed akan tunduk pada Ketentuan Situs, peraturan hukum, serta etika pariwara yang berlaku.\r\n10.	Pengguna atau pihak lainnya yang menggunakan fitur Feed bertanggungjawab penuh terhadap konten atau materi yang diunggah melalui fitur Feed.\r\n11.	ALLBATIK berhak untuk sewaktu-waktu menurunkan konten atau materi yang terdapat pada Feed yang dianggap melanggar Syarat dan Ketentuan Situs, peraturan hukum yang berlaku, serta etika pariwara yang berlaku.\r\n kembali ke atas\r\n________________________________________\r\nI.	JENIS BARANG\r\nBerikut ini adalah daftar jenis Barang yang dilarang untuk diperdagangkan oleh Penjual pada Situs ALLBATIK:\r\nÃƒÆ’Ã†â€™Ãƒâ€šÃ‚Â¢ÃƒÆ’Ã‚Â¢ÃƒÂ¢Ã¢â€šÂ¬Ã…Â¡Ãƒâ€šÃ‚Â¬ÃƒÆ’Ã¢â‚¬Å¡Ãƒâ€šÃ‚Â¢	- Segala jenis obat-obatan maupun zat-zat lain yang dilarang ataupun dibatasi peredarannya menurut ketentuan hukum yang berlaku, termasuk namun tidak terbatas pada ketentuan Undang-Undang Narkotika, Undang-Undang Psikotropika, dan Undang-Undang Kesehatan. Termasuk pula dalam ketentuan ini ialah obat keras, obat-obatan yang memerlukan resep dokter, obat bius dan sejenisnya, atau obat yang tidak memiliki izin edar dari Badan Pengawas Obat dan Makanan (BPOM).\r\nÃƒÆ’Ã†â€™Ãƒâ€šÃ‚Â¢ÃƒÆ’Ã‚Â¢ÃƒÂ¢Ã¢â€šÂ¬Ã…Â¡Ãƒâ€šÃ‚Â¬ÃƒÆ’Ã¢â‚¬Å¡Ãƒâ€šÃ‚Â¢	- Kosmetik dan makanan minuman yang membahayakan keselamatan penggunanya, ataupun yang tidak mempunyai izin edar dari Badan Pengawas Obat dan Makanan (BPOM).\r\nÃƒÆ’Ã†â€™Ãƒâ€šÃ‚Â¢ÃƒÆ’Ã‚Â¢ÃƒÂ¢Ã¢â€šÂ¬Ã…Â¡Ãƒâ€šÃ‚Â¬ÃƒÆ’Ã¢â‚¬Å¡Ãƒâ€šÃ‚Â¢	-Bahan yang diklasifikasikan sebagai Bahan Berbahaya menurut Peraturan Menteri Perdagangan yang berlaku.\r\nÃƒÆ’Ã†â€™Ãƒâ€šÃ‚Â¢ÃƒÆ’Ã‚Â¢ÃƒÂ¢Ã¢â€šÂ¬Ã…Â¡Ãƒâ€šÃ‚Â¬ÃƒÆ’Ã¢â‚¬Å¡Ãƒâ€šÃ‚Â¢	-Jenis Produk tertentu yang wajib memiliki:\r\nÃƒÆ’Ã†â€™Ãƒâ€šÃ‚Â¢ÃƒÆ’Ã‚Â¢ÃƒÂ¢Ã¢â€šÂ¬Ã…Â¡Ãƒâ€šÃ‚Â¬ÃƒÆ’Ã¢â‚¬Å¡Ãƒâ€šÃ‚Â¢	a. SNI;\r\nÃƒÆ’Ã†â€™Ãƒâ€šÃ‚Â¢ÃƒÆ’Ã‚Â¢ÃƒÂ¢Ã¢â€šÂ¬Ã…Â¡Ãƒâ€šÃ‚Â¬ÃƒÆ’Ã¢â‚¬Å¡Ãƒâ€šÃ‚Â¢	b. Petunjuk penggunaan dalam Bahasa Indonesia; atau\r\nÃƒÆ’Ã†â€™Ãƒâ€šÃ‚Â¢ÃƒÆ’Ã‚Â¢ÃƒÂ¢Ã¢â€šÂ¬Ã…Â¡Ãƒâ€šÃ‚Â¬ÃƒÆ’Ã¢â‚¬Å¡Ãƒâ€šÃ‚Â¢	c. Label dalam Bahasa Indonesia.\r\nÃƒÆ’Ã†â€™Ãƒâ€šÃ‚Â¢ÃƒÆ’Ã‚Â¢ÃƒÂ¢Ã¢â€šÂ¬Ã…Â¡Ãƒâ€šÃ‚Â¬ÃƒÆ’Ã¢â‚¬Å¡Ãƒâ€šÃ‚Â¢	Sementara yang diperjualbelikan tidak mencantumkan hal-hal tersebut.\r\nÃƒÆ’Ã†â€™Ãƒâ€šÃ‚Â¢ÃƒÆ’Ã‚Â¢ÃƒÂ¢Ã¢â€šÂ¬Ã…Â¡Ãƒâ€šÃ‚Â¬ÃƒÆ’Ã¢â‚¬Å¡Ãƒâ€šÃ‚Â¢	- Barang-barang lain yang kepemilikannya ataupun peredarannya melanggar ketentuan hukum yang berlaku di Indonesia.\r\nÃƒÆ’Ã†â€™Ãƒâ€šÃ‚Â¢ÃƒÆ’Ã‚Â¢ÃƒÂ¢Ã¢â€šÂ¬Ã…Â¡Ãƒâ€šÃ‚Â¬ÃƒÆ’Ã¢â‚¬Å¡Ãƒâ€šÃ‚Â¢	- Barang yang merupakan hasil pelanggaran Hak Cipta, termasuk namun tidak terbatas dalam media berbentuk buku, CD/DVD/VCD, informasi dan/atau dokumen elektronik, serta media lain yang bertentangan dengan Undang-Undang Hak Cipta.\r\nÃƒÆ’Ã†â€™Ãƒâ€šÃ‚Â¢ÃƒÆ’Ã‚Â¢ÃƒÂ¢Ã¢â€šÂ¬Ã…Â¡Ãƒâ€šÃ‚Â¬ÃƒÆ’Ã¢â‚¬Å¡Ãƒâ€šÃ‚Â¢	- Barang dewasa penunjang kegiatan seksual termasuk namun tidak terbatas pada obat kuat, obat perangsang, alat bantu seks, pornografi, dan obat-obatan dewasa, kecuali untuk alat kesehatan (kontrasepsi) yang diizinkan untuk diperjual belikan oleh peraturan hukum yang berlaku.\r\nÃƒÆ’Ã†â€™Ãƒâ€šÃ‚Â¢ÃƒÆ’Ã‚Â¢ÃƒÂ¢Ã¢â€šÂ¬Ã…Â¡Ãƒâ€šÃ‚Â¬ÃƒÆ’Ã¢â‚¬Å¡Ãƒâ€šÃ‚Â¢	- Minuman beralkohol.\r\nÃƒÆ’Ã†â€™Ãƒâ€šÃ‚Â¢ÃƒÆ’Ã‚Â¢ÃƒÂ¢Ã¢â€šÂ¬Ã…Â¡Ãƒâ€šÃ‚Â¬ÃƒÆ’Ã¢â‚¬Å¡Ãƒâ€šÃ‚Â¢	- Iklan.\r\nÃƒÆ’Ã†â€™Ãƒâ€šÃ‚Â¢ÃƒÆ’Ã‚Â¢ÃƒÂ¢Ã¢â€šÂ¬Ã…Â¡Ãƒâ€šÃ‚Â¬ÃƒÆ’Ã¢â‚¬Å¡Ãƒâ€šÃ‚Â¢	- Segala bentuk tulisan yang dapat berpengaruh negatif terhadap pemakaian situs ini.\r\nÃƒÆ’Ã†â€™Ãƒâ€šÃ‚Â¢ÃƒÆ’Ã‚Â¢ÃƒÂ¢Ã¢â€šÂ¬Ã…Â¡Ãƒâ€šÃ‚Â¬ÃƒÆ’Ã¢â‚¬Å¡Ãƒâ€šÃ‚Â¢	- Pakaian dalam bekas.\r\nÃƒÆ’Ã†â€™Ãƒâ€šÃ‚Â¢ÃƒÆ’Ã‚Â¢ÃƒÂ¢Ã¢â€šÂ¬Ã…Â¡Ãƒâ€šÃ‚Â¬ÃƒÆ’Ã¢â‚¬Å¡Ãƒâ€šÃ‚Â¢	- Senjata api, senjata tajam, senapan angin, dan segala macam senjata.\r\nÃƒÆ’Ã†â€™Ãƒâ€šÃ‚Â¢ÃƒÆ’Ã‚Â¢ÃƒÂ¢Ã¢â€šÂ¬Ã…Â¡Ãƒâ€šÃ‚Â¬ÃƒÆ’Ã¢â‚¬Å¡Ãƒâ€šÃ‚Â¢	- Dokumen pemerintahan dan perjalanan.\r\nÃƒÆ’Ã†â€™Ãƒâ€šÃ‚Â¢ÃƒÆ’Ã‚Â¢ÃƒÂ¢Ã¢â€šÂ¬Ã…Â¡Ãƒâ€šÃ‚Â¬ÃƒÆ’Ã¢â‚¬Å¡Ãƒâ€šÃ‚Â¢	- Seragam pemerintahan.\r\nÃƒÆ’Ã†â€™Ãƒâ€šÃ‚Â¢ÃƒÆ’Ã‚Â¢ÃƒÂ¢Ã¢â€šÂ¬Ã…Â¡Ãƒâ€šÃ‚Â¬ÃƒÆ’Ã¢â‚¬Å¡Ãƒâ€šÃ‚Â¢	- Bagian/Organ manusia.\r\nÃƒÆ’Ã†â€™Ãƒâ€šÃ‚Â¢ÃƒÆ’Ã‚Â¢ÃƒÂ¢Ã¢â€šÂ¬Ã…Â¡Ãƒâ€šÃ‚Â¬ÃƒÆ’Ã¢â‚¬Å¡Ãƒâ€šÃ‚Â¢	- Mailing list dan informasi pribadi.\r\nÃƒÆ’Ã†â€™Ãƒâ€šÃ‚Â¢ÃƒÆ’Ã‚Â¢ÃƒÂ¢Ã¢â€šÂ¬Ã…Â¡Ãƒâ€šÃ‚Â¬ÃƒÆ’Ã¢â‚¬Å¡Ãƒâ€šÃ‚Â¢	- Barang-Barang yang melecehkan pihak/ras tertentu atau dapat merendahkan martabat orang lain.\r\nÃƒÆ’Ã†â€™Ãƒâ€šÃ‚Â¢ÃƒÆ’Ã‚Â¢ÃƒÂ¢Ã¢â€šÂ¬Ã…Â¡Ãƒâ€šÃ‚Â¬ÃƒÆ’Ã¢â‚¬Å¡Ãƒâ€šÃ‚Â¢	- Pestisida.\r\nÃƒÆ’Ã†â€™Ãƒâ€šÃ‚Â¢ÃƒÆ’Ã‚Â¢ÃƒÂ¢Ã¢â€šÂ¬Ã…Â¡Ãƒâ€šÃ‚Â¬ÃƒÆ’Ã¢â‚¬Å¡Ãƒâ€šÃ‚Â¢	- Atribut kepolisian.\r\nÃƒÆ’Ã†â€™Ãƒâ€šÃ‚Â¢ÃƒÆ’Ã‚Â¢ÃƒÂ¢Ã¢â€šÂ¬Ã…Â¡Ãƒâ€šÃ‚Â¬ÃƒÆ’Ã¢â‚¬Å¡Ãƒâ€šÃ‚Â¢	- Barang hasil tindak pencurian.\r\nÃƒÆ’Ã†â€™Ãƒâ€šÃ‚Â¢ÃƒÆ’Ã‚Â¢ÃƒÂ¢Ã¢â€šÂ¬Ã…Â¡Ãƒâ€šÃ‚Â¬ÃƒÆ’Ã¢â‚¬Å¡Ãƒâ€šÃ‚Â¢	- Pembuka kunci dan segala aksesori penunjang tindakan perampokan/pencurian.\r\nÃƒÆ’Ã†â€™Ãƒâ€šÃ‚Â¢ÃƒÆ’Ã‚Â¢ÃƒÂ¢Ã¢â€šÂ¬Ã…Â¡Ãƒâ€šÃ‚Â¬ÃƒÆ’Ã¢â‚¬Å¡Ãƒâ€šÃ‚Â¢	- Barang yang dapat dan atau mudah meledak, menyala atau terbakar sendiri.\r\nÃƒÆ’Ã†â€™Ãƒâ€šÃ‚Â¢ÃƒÆ’Ã‚Â¢ÃƒÂ¢Ã¢â€šÂ¬Ã…Â¡Ãƒâ€šÃ‚Â¬ÃƒÆ’Ã¢â‚¬Å¡Ãƒâ€šÃ‚Â¢	- Barang cetakan/rekaman yang isinya dapat mengganggu keamanan & ketertiban serta stabilitas nasional.\r\nÃƒÆ’Ã†â€™Ãƒâ€šÃ‚Â¢ÃƒÆ’Ã‚Â¢ÃƒÂ¢Ã¢â€šÂ¬Ã…Â¡Ãƒâ€šÃ‚Â¬ÃƒÆ’Ã¢â‚¬Å¡Ãƒâ€šÃ‚Â¢	- Hewan.\r\nÃƒÆ’Ã†â€™Ãƒâ€šÃ‚Â¢ÃƒÆ’Ã‚Â¢ÃƒÂ¢Ã¢â€šÂ¬Ã…Â¡Ãƒâ€šÃ‚Â¬ÃƒÆ’Ã¢â‚¬Å¡Ãƒâ€šÃ‚Â¢	- Uang tunai termasuk valuta asing kecuali Penjual memiliki dan dapat mencantumkan izin sebagai Penyelenggara Kegiatan Usaha Penukaran Valuta Asing Bukan Bank berdasarkan Peraturan Bank Indonesia No.18/20/PBI/2016 dan/atau peraturan lainnya yang terkait dengan penukaran valuta asing.\r\nÃƒÆ’Ã†â€™Ãƒâ€šÃ‚Â¢ÃƒÆ’Ã‚Â¢ÃƒÂ¢Ã¢â€šÂ¬Ã…Â¡Ãƒâ€šÃ‚Â¬ÃƒÆ’Ã¢â‚¬Å¡Ãƒâ€šÃ‚Â¢	- Materai.\r\nÃƒÆ’Ã†â€™Ãƒâ€šÃ‚Â¢ÃƒÆ’Ã‚Â¢ÃƒÂ¢Ã¢â€šÂ¬Ã…Â¡Ãƒâ€šÃ‚Â¬ÃƒÆ’Ã¢â‚¬Å¡Ãƒâ€šÃ‚Â¢	- Pengacak sinyal, penghilang sinyal, dan/atau alat-alat lain yang dapat mengganggu sinyal atau jaringan telekomunikasi\r\nÃƒÆ’Ã†â€™Ãƒâ€šÃ‚Â¢ÃƒÆ’Ã‚Â¢ÃƒÂ¢Ã¢â€šÂ¬Ã…Â¡Ãƒâ€šÃ‚Â¬ÃƒÆ’Ã¢â‚¬Å¡Ãƒâ€šÃ‚Â¢	- Perlengkapan dan peralatan judi.\r\nÃƒÆ’Ã†â€™Ãƒâ€šÃ‚Â¢ÃƒÆ’Ã‚Â¢ÃƒÂ¢Ã¢â€šÂ¬Ã…Â¡Ãƒâ€šÃ‚Â¬ÃƒÆ’Ã¢â‚¬Å¡Ãƒâ€šÃ‚Â¢	- Jimat-jimat, benda-benda yang diklaim berkekuatan gaib dan memberi ilmu kesaktian.\r\nÃƒÆ’Ã†â€™Ãƒâ€šÃ‚Â¢ÃƒÆ’Ã‚Â¢ÃƒÂ¢Ã¢â€šÂ¬Ã…Â¡Ãƒâ€šÃ‚Â¬ÃƒÆ’Ã¢â‚¬Å¡Ãƒâ€šÃ‚Â¢	- Barang dengan hak Distribusi Eksklusif yang hanya dapat diperdagangkan dengan sistem penjualan lansung oleh penjual resmi dan/atau Barang dengan sistem penjualan Multi Level Marketing.\r\nÃƒÆ’Ã†â€™Ãƒâ€šÃ‚Â¢ÃƒÆ’Ã‚Â¢ÃƒÂ¢Ã¢â€šÂ¬Ã…Â¡Ãƒâ€šÃ‚Â¬ÃƒÆ’Ã¢â‚¬Å¡Ãƒâ€šÃ‚Â¢	- Produk non fisik yang tidak dapat dikirimkan melalui jasa kurir, termasuk namun tidak terbatas pada produk pulsa/voucher (telepon, listrik, game, dan/atau credit digital), tiket pesawat dan/atau tiket kereta.\r\nÃƒÆ’Ã†â€™Ãƒâ€šÃ‚Â¢ÃƒÆ’Ã‚Â¢ÃƒÂ¢Ã¢â€šÂ¬Ã…Â¡Ãƒâ€šÃ‚Â¬ÃƒÆ’Ã¢â‚¬Å¡Ãƒâ€šÃ‚Â¢	- Tiket pertunjukan, termasuk namun tidak terbatas pada tiket konser, baik fisik maupun non fisik.\r\nÃƒÆ’Ã†â€™Ãƒâ€šÃ‚Â¢ÃƒÆ’Ã‚Â¢ÃƒÂ¢Ã¢â€šÂ¬Ã…Â¡Ãƒâ€šÃ‚Â¬ÃƒÆ’Ã¢â‚¬Å¡Ãƒâ€šÃ‚Â¢	- Dokumen-dokumen resmi seperti Sertifikat Toefl, Ijazah, Surat Dokter, Kwitansi, dan lain sebagainya\r\nÃƒÆ’Ã†â€™Ãƒâ€šÃ‚Â¢ÃƒÆ’Ã‚Â¢ÃƒÂ¢Ã¢â€šÂ¬Ã…Â¡Ãƒâ€šÃ‚Â¬ÃƒÆ’Ã¢â‚¬Å¡Ãƒâ€šÃ‚Â¢	- Segala jenis Barang lain yang bertentangan dengan peraturan pengiriman Barang Indonesia.\r\nÃƒÆ’Ã†â€™Ãƒâ€šÃ‚Â¢ÃƒÆ’Ã‚Â¢ÃƒÂ¢Ã¢â€šÂ¬Ã…Â¡Ãƒâ€šÃ‚Â¬ÃƒÆ’Ã¢â‚¬Å¡Ãƒâ€šÃ‚Â¢	- Barang-Barang lain yang melanggar ketentuan hukum yang berlaku di Indonesia.\r\n kembali ke atas\r\n________________________________________\r\nJ.	PENGIRIMAN BARANG\r\nA.	Pengiriman Barang dalam sistem ALLBATIK wajib menggunakan jasa perusahaan ekspedisi yang telah mendapatkan verifikasi rekanan ALLBATIK yang dipilih oleh Pembeli.\r\nB.	Penjual dilarang memberlakukan promosi / sistem bebas ongkos kirim pada setiap Barang yang dijual di dalam Situs ALLBATIK.\r\nC.	Setiap ketentuan berkenaan dengan proses pengiriman Barang adalah wewenang sepenuhnya penyedia jasa layanan pengiriman Barang.\r\nD.	Penjual wajib memenuhi ketentuan yang ditetapkan oleh jasa layanan pengiriman barang tersebut dan bertanggung jawab atas setiap Barang yang dikirimkan.\r\nE.	Pengguna memahami dan menyetujui bahwa setiap permasalahan yang terjadi pada saat proses pengiriman Barang oleh penyedia jasa layanan pengiriman Barang adalah merupakan tanggung jawab penyedia jasa layanan pengiriman.\r\nF.	Dalam hal diperlukan untuk dilakukan proses pengembalian barang, maka Pengguna, baik Penjual maupun Pembeli, diwajibkan untuk melakukan pengiriman barang langsung ke masing-masing Pembeli maupun Penjual. ALLBATIK tidak menerima pengembalian atau pengiriman barang atas transaksi yang dilakukan oleh Pengguna dalam kondisi apapun.\r\n kembali ke atas\r\n________________________________________\r\nK.	PENARIKAN DANA\r\n1.	Penarikan dana sesama bank akan diproses dalam waktu 1x24 jam hari kerja, sedangkan penarikan dana antar bank akan diproses dalam waktu 2x24 jam hari kerja.\r\n2.	Untuk penarikan dana dengan tujuan nomor rekening antar bank apabila ada biaya tambahan yang dibebankan akan menjadi tanggungan dari Pengguna.\r\n3.	Dalam hal ditemukan adanya dugaan pelanggaran terhadap Syarat dan Ketentuan ALLBATIK, kecurangan, manipulasi atau kejahatan, Pengguna memahami dan menyetujui bahwa ALLBATIK berhak melakukan tindakan pemeriksaan, pembekuan, penundaan dan/atau pembatalan terhadap penarikan dana yang dilakukan oleh Pengguna.\r\n4.	Pemeriksaan, pembekuan atau penundaan penarikan dana sebagaimana dimaksud dalam poin 3 dapat dilakukan dalam jangka waktu selama yang diperlukan oleh pihak ALLBATIK.\r\n kembali ke atas\r\n________________________________________\r\nL.	PENOLAKAN JAMINAN DAN BATASAN TANGGUNG JAWAB\r\nALLBATIK adalah portal web dengan model Costumer to Customer Marketplace, yang menyediakan layanan kepada Pengguna untuk dapat menjadi Penjual maupun Pembeli di website ALLBATIK. Dengan demikian transaksi yang terjadi adalah transaksi antar member ALLBATIK, sehingga Pengguna memahami bahwa batasan tanggung jawab ALLBATIK secara proporsional adalah sebagai penyedia jasa portal web\r\nALLBATIK selalu berupaya untuk menjaga Layanan ALLBATIK aman, nyaman, dan berfungsi dengan baik, tapi kami tidak dapat menjamin operasi terus-menerus atau akses ke Layanan kami dapat selalu sempurna. Informasi dan data dalam situs ALLBATIK memiliki kemungkinan tidak terjadi secara real time.\r\nPengguna setuju bahwa Anda memanfaatkan Layanan ALLBATIK atas risiko Pengguna sendiri, dan Layanan ALLBATIK diberikan kepada Anda pada \"SEBAGAIMANA ADANYA\" dan \"SEBAGAIMANA TERSEDIA\".\r\nSejauh diizinkan oleh hukum yang berlaku, ALLBATIK adalah tidak bertanggung jawab, dan Anda setuju untuk tidak menuntut ALLBATIK bertanggung jawab, atas segala kerusakan atau kerugian (termasuk namun tidak terbatas pada hilangnya uang, reputasi, keuntungan, atau kerugian tak berwujud lainnya) yang diakibatkan secara langsung atau tidak langsung dari :\r\nÃƒÆ’Ã†â€™Ãƒâ€šÃ‚Â¢ÃƒÆ’Ã‚Â¢ÃƒÂ¢Ã¢â€šÂ¬Ã…Â¡Ãƒâ€šÃ‚Â¬ÃƒÆ’Ã¢â‚¬Å¡Ãƒâ€šÃ‚Â¢	- Penggunaan atau ketidakmampuan Pengguna dalam menggunakan Layanan ALLBATIK.\r\nÃƒÆ’Ã†â€™Ãƒâ€šÃ‚Â¢ÃƒÆ’Ã‚Â¢ÃƒÂ¢Ã¢â€šÂ¬Ã…Â¡Ãƒâ€šÃ‚Â¬ÃƒÆ’Ã¢â‚¬Å¡Ãƒâ€šÃ‚Â¢	- Harga, Pengiriman atau petunjuk lain yang tersedia dalam layanan ALLBATIK.\r\nÃƒÆ’Ã†â€™Ãƒâ€šÃ‚Â¢ÃƒÆ’Ã‚Â¢ÃƒÂ¢Ã¢â€šÂ¬Ã…Â¡Ãƒâ€šÃ‚Â¬ÃƒÆ’Ã¢â‚¬Å¡Ãƒâ€šÃ‚Â¢	- Keterlambatan atau gangguan dalam Layanan ALLBATIK.\r\nÃƒÆ’Ã†â€™Ãƒâ€šÃ‚Â¢ÃƒÆ’Ã‚Â¢ÃƒÂ¢Ã¢â€šÂ¬Ã…Â¡Ãƒâ€šÃ‚Â¬ÃƒÆ’Ã¢â‚¬Å¡Ãƒâ€šÃ‚Â¢	- Kelalaian dan kerugian yang ditimbulkan oleh masing-masing Pengguna.\r\nÃƒÆ’Ã†â€™Ãƒâ€šÃ‚Â¢ÃƒÆ’Ã‚Â¢ÃƒÂ¢Ã¢â€šÂ¬Ã…Â¡Ãƒâ€šÃ‚Â¬ÃƒÆ’Ã¢â‚¬Å¡Ãƒâ€šÃ‚Â¢	- Kualitas Barang.\r\nÃƒÆ’Ã†â€™Ãƒâ€šÃ‚Â¢ÃƒÆ’Ã‚Â¢ÃƒÂ¢Ã¢â€šÂ¬Ã…Â¡Ãƒâ€šÃ‚Â¬ÃƒÆ’Ã¢â‚¬Å¡Ãƒâ€šÃ‚Â¢	- Pengiriman Barang.\r\nÃƒÆ’Ã†â€™Ãƒâ€šÃ‚Â¢ÃƒÆ’Ã‚Â¢ÃƒÂ¢Ã¢â€šÂ¬Ã…Â¡Ãƒâ€šÃ‚Â¬ÃƒÆ’Ã¢â‚¬Å¡Ãƒâ€šÃ‚Â¢	- Pelanggaran Hak atas Kekayaan Intelektual.\r\nÃƒÆ’Ã†â€™Ãƒâ€šÃ‚Â¢ÃƒÆ’Ã‚Â¢ÃƒÂ¢Ã¢â€šÂ¬Ã…Â¡Ãƒâ€šÃ‚Â¬ÃƒÆ’Ã¢â‚¬Å¡Ãƒâ€šÃ‚Â¢	- Perselisihan antar pengguna.\r\nÃƒÆ’Ã†â€™Ãƒâ€šÃ‚Â¢ÃƒÆ’Ã‚Â¢ÃƒÂ¢Ã¢â€šÂ¬Ã…Â¡Ãƒâ€šÃ‚Â¬ÃƒÆ’Ã¢â‚¬Å¡Ãƒâ€šÃ‚Â¢	- Pencemaran nama baik pihak lain.\r\nÃƒÆ’Ã†â€™Ãƒâ€šÃ‚Â¢ÃƒÆ’Ã‚Â¢ÃƒÂ¢Ã¢â€šÂ¬Ã…Â¡Ãƒâ€šÃ‚Â¬ÃƒÆ’Ã¢â‚¬Å¡Ãƒâ€šÃ‚Â¢	- Setiap penyalahgunaan Barang yang sudah dibeli pihak Pengguna.\r\nÃƒÆ’Ã†â€™Ãƒâ€šÃ‚Â¢ÃƒÆ’Ã‚Â¢ÃƒÂ¢Ã¢â€šÂ¬Ã…Â¡Ãƒâ€šÃ‚Â¬ÃƒÆ’Ã¢â‚¬Å¡Ãƒâ€šÃ‚Â¢	- Kerugian akibat pembayaran tidak resmi kepada pihak lain selain ke Rekening Resmi ALLBATIK, yang dengan cara apa pun mengatas-namakan ALLBATIK ataupun kelalaian penulisan rekening dan/atau informasi lainnya dan/atau kelalaian pihak bank.\r\nÃƒÆ’Ã†â€™Ãƒâ€šÃ‚Â¢ÃƒÆ’Ã‚Â¢ÃƒÂ¢Ã¢â€šÂ¬Ã…Â¡Ãƒâ€šÃ‚Â¬ÃƒÆ’Ã¢â‚¬Å¡Ãƒâ€šÃ‚Â¢	- Pengiriman untuk perbaikan Barang yang bergaransi resmi dari pihak produsen. Pembeli dapat membawa Barang langsung kepada pusat layanan servis terdekat dengan kartu garansi dan faktur pembelian.\r\nÃƒÆ’Ã†â€™Ãƒâ€šÃ‚Â¢ÃƒÆ’Ã‚Â¢ÃƒÂ¢Ã¢â€šÂ¬Ã…Â¡Ãƒâ€šÃ‚Â¬ÃƒÆ’Ã¢â‚¬Å¡Ãƒâ€šÃ‚Â¢	- Virus atau perangkat lunak berbahaya lainnya (bot, script, automation tool selain fitur Power Merchant, hacking tool) yang diperoleh dengan mengakses, atau menghubungkan ke layanan ALLBATIK.\r\nÃƒÆ’Ã†â€™Ãƒâ€šÃ‚Â¢ÃƒÆ’Ã‚Â¢ÃƒÂ¢Ã¢â€šÂ¬Ã…Â¡Ãƒâ€šÃ‚Â¬ÃƒÆ’Ã¢â‚¬Å¡Ãƒâ€šÃ‚Â¢	- Gangguan, bug, kesalahan atau ketidakakuratan apapun dalam Layanan ALLBATIK.\r\nÃƒÆ’Ã†â€™Ãƒâ€šÃ‚Â¢ÃƒÆ’Ã‚Â¢ÃƒÂ¢Ã¢â€šÂ¬Ã…Â¡Ãƒâ€šÃ‚Â¬ÃƒÆ’Ã¢â‚¬Å¡Ãƒâ€šÃ‚Â¢	- Kerusakan pada perangkat keras Anda dari penggunaan setiap Layanan ALLBATIK.\r\nÃƒÆ’Ã†â€™Ãƒâ€šÃ‚Â¢ÃƒÆ’Ã‚Â¢ÃƒÂ¢Ã¢â€šÂ¬Ã…Â¡Ãƒâ€šÃ‚Â¬ÃƒÆ’Ã¢â‚¬Å¡Ãƒâ€šÃ‚Â¢	- Isi, tindakan, atau tidak adanya tindakan dari pihak ketiga, termasuk terkait dengan Produk yang ada dalam situs ALLBATIK yang diduga palsu.\r\nÃƒÆ’Ã†â€™Ãƒâ€šÃ‚Â¢ÃƒÆ’Ã‚Â¢ÃƒÂ¢Ã¢â€šÂ¬Ã…Â¡Ãƒâ€šÃ‚Â¬ÃƒÆ’Ã¢â‚¬Å¡Ãƒâ€šÃ‚Â¢	- Tindak penegakan yang diambil sehubungan dengan akun Pengguna.\r\nÃƒÆ’Ã†â€™Ãƒâ€šÃ‚Â¢ÃƒÆ’Ã‚Â¢ÃƒÂ¢Ã¢â€šÂ¬Ã…Â¡Ãƒâ€šÃ‚Â¬ÃƒÆ’Ã¢â‚¬Å¡Ãƒâ€šÃ‚Â¢	- Adanya tindakan peretasan yang dilakukan oleh pihak ketiga kepada akun pengguna.\r\n kembali ke atas\r\n________________________________________\r\nM.	PELEPASAN\r\nJika Anda memiliki perselisihan dengan satu atau lebih pengguna, Anda melepaskan ALLBATIK  dari klaim dan tuntutan atas kerusakan dan kerugian (aktual dan tersirat) dari setiap jenis dan sifatnya , yang dikenal dan tidak dikenal, yang timbul dari atau dengan cara apapun berhubungan dengan sengketa tersebut, termasuk namun tidak terbatas pada kerugian yang timbul dari pembelian Barang yang telah dilarang pada poin K. Syarat dan Ketentuan ini. Dengan demikian maka Pengguna dengan sengaja melepaskan segala perlindungan hukum (yang terdapat dalam undang-undang atau peraturan hukum yang lain) yang akan membatasi cakupan ketentuan pelepasan ini.\r\n kembali ke atas\r\n________________________________________\r\nN.	GANTI RUGI\r\nPengguna akan melepaskan ALLBATIK dari tuntutan ganti rugi dan menjaga ALLBATIK dari setiap klaim atau tuntutan, termasuk biaya hukum yang wajar, yang dilakukan oleh pihak ketiga yang timbul dalam hal Anda melanggar Perjanjian ini, penggunaan Layanan ALLBATIK yang tidak semestinya dan/ atau pelanggaran Anda terhadap hukum atau hak-hak pihak ketiga.\r\n kembali ke atas\r\n________________________________________\r\nO.	PILIHAN HUKUM\r\nPerjanjian ini akan diatur oleh dan ditafsirkan sesuai dengan hukum Republik Indonesia, tanpa memperhatikan pertentangan aturan hukum. Anda setuju bahwa tindakan hukum apapun atau sengketa yang mungkin timbul dari, berhubungan dengan, atau berada dalam cara apapun berhubungan dengan situs dan/atau Perjanjian ini akan diselesaikan secara eksklusif dalam yurisdiksi pengadilan Republik Indonesia.\r\n kembali ke atas\r\n________________________________________\r\nP.	PEMBAHARUAN\r\nSyarat & ketentuan mungkin di ubah dan/atau diperbaharui dari waktu ke waktu tanpa pemberitahuan sebelumnya. ALLBATIK menyarankan agar anda membaca secara seksama dan memeriksa halaman Syarat & ketentuan ini dari waktu ke waktu untuk mengetahui perubahan apapun. Dengan tetap mengakses dan menggunakan layanan ALLBATIK, maka pengguna dianggap menyetujui perubahan-perubahan dalam Syarat & ketentuan.\r\n kembali ke atas\r\nPembaruan Terakhir : 29/05/2019 15:56\r\n\r\n', 1),
(2, '2020-08-02 00:13:01', 1, 'Pengiriman', 'pengiriman', '<ul>\r\n<li>Pengiriman barang untuk setiap transaksi yang terjadi melalui Platform Bukalapak menggunakan layanan pengiriman barang yang disediakan Bukalapak atas kerjasama Bukalapak dengan pihak jasa ekspedisi pengiriman barang resmi.</li>\r\n<li>Pengguna memahami dan menyetujui bahwa segala bentuk peraturan terkait dengan syarat dan ketentuan pengiriman barang sepenuhnya ditentukan oleh pihak jasa ekspedisi pengiriman barang dan sepenuhnya menjadi tanggung jawab pihak jasa ekspedisi pengiriman barang.</li>\r\n<li>Bukalapak hanya berperan sebagai media perantara antara Pengguna dengan pihak jasa ekspedisi pengiriman barang.</li>\r\n<li>Pengguna wajib memahami, menyetujui, serta mengikuti ketentuan-ketentuan pengiriman barang yang telah dibuat oleh pihak jasa ekspedisi pengiriman barang.</li>\r\n<li>Pengiriman barang atas transaksi melalui sistem Bukalapak menggunakan jasa ekspedisi pengiriman barang dilakukan dengan tujuan agar barang dapat dipantau melalui sistem Bukalapak.</li>\r\n<li>Pelapak (Penjual) wajib bertanggung jawab penuh atas barang yang dikirimnya.</li>\r\n<li>Pengguna memahami dan menyetujui bahwa segala bentuk kerugian yang dapat timbul akibat kerusakan ataupun kehilangan pada barang, baik pada saat pengiriman barang tengah berlangsung maupun pada saat pengiriman barang telah selesai, adalah sepenuhnya tanggung jawab pihak jasa ekspedisi pengiriman barang.</li>\r\n<li>Dalam hal diperlukan untuk dilakukan proses pengembalian barang, maka Pengguna, baik Pelapak (Penjual) maupun Pembeli, diwajibkan untuk melakukan pengiriman barang langsung ke masing-masing Pembeli maupun Pelapak (Penjual). Bukalapak tidak menerima pengembalian atau pengiriman barang atas transaksi yang dilakukan oleh Pengguna dalam kondisi apa pun.</li>\r\n</ul>', 1);

-- --------------------------------------------------------

--
-- Table structure for table `blw_@promo`
--

CREATE TABLE `blw_@promo` (
  `id` int(11) NOT NULL,
  `usrid` int(11) NOT NULL,
  `tgl` datetime NOT NULL,
  `jenis` int(11) NOT NULL COMMENT '1=slider,2=space iklan',
  `status` int(11) NOT NULL COMMENT '1=aktif,0=nonaktif',
  `caption` text NOT NULL,
  `gambar` text NOT NULL,
  `link` text NOT NULL,
  `tgl_selesai` datetime NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Dumping data for table `blw_@promo`
--

INSERT INTO `blw_@promo` (`id`, `usrid`, `tgl`, `jenis`, `status`, `caption`, `gambar`, `link`, `tgl_selesai`) VALUES
(40, 1, '2022-03-18 01:43:48', 1, 1, '', 'promo_upl20220318014401.jpg', '', '2028-12-01 01:43:54');

-- --------------------------------------------------------

--
-- Table structure for table `blw_@rekening_bank`
--

CREATE TABLE `blw_@rekening_bank` (
  `id` int(11) NOT NULL,
  `nama` text NOT NULL,
  `logo` text NOT NULL,
  `kodebank` varchar(100) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Dumping data for table `blw_@rekening_bank`
--

INSERT INTO `blw_@rekening_bank` (`id`, `nama`, `logo`, `kodebank`) VALUES
(1, 'Bank BCA', 'Bank_BCA.png', '14'),
(2, 'Bank Mandiri', 'Bank_Mandiri.png', '8'),
(3, 'Bank BNI', 'Bank_BNI.png', '9'),
(4, 'Bank BNI Syariah', 'Bank_BNI_Syariah.png', '427'),
(5, 'Bank BRI', 'Bank_BRI.png', '2'),
(6, 'Bank Syariah Mandiri', 'Bank_Syariah_Mandiri.png', '451'),
(7, 'Bank CIMB Niaga', 'Bank_CIMB_Niaga.png', '22'),
(8, 'Bank CIMB Niaga Syariah', 'Bank_CIMB_Niaga_Syariah.png', '22'),
(9, 'Bank Muamalat', 'Bank_Muamalat.png', '147'),
(10, 'Bank Tabungan Pensiunan Nasional (BTPN)', 'Bank_Tabungan_Pensiunan_Nasional_(BTPN).png', '213'),
(11, 'JENIUS', 'JENIUS.png', '213'),
(12, 'Bank BRI Syariah', 'Bank_BRI_Syariah.png', '422'),
(13, 'Bank Tabungan Negara (BTN)', 'Bank_Tabungan_Negara_(BTN).png', '200'),
(14, 'Permata Bank', 'Permata_Bank.png', '13'),
(15, 'Bank Danamon', 'Bank_Danamon.png', '11'),
(16, 'Bank BII Maybank', 'Bank_BII_Maybank.png', '16'),
(17, 'Bank Mega', 'Bank_Mega.png', '426'),
(18, 'Bank Sinarmas', 'Bank_Sinarmas.png', '153'),
(19, 'Bank Commonwealth', 'Bank_Commonwealth.png', '950'),
(20, 'Bank OCBC NISP', 'Bank_OCBC_NISP.png', '28'),
(21, 'Bank Bukopin', 'Bank_Bukopin.png', '441'),
(22, 'Bank BCA Syariah', 'Bank_BCA_Syariah.png', '536'),
(23, 'Bank Lippo', 'Bank_Lippo.png', '26'),
(24, 'Citibank', 'Citibank.png', '31'),
(25, 'Indosat Dompetku', 'Indosat_Dompetku.png', '789'),
(26, 'Telkomsel Tcash', 'Telkomsel_Tcash.png', '911'),
(27, 'Bank Jabar dan Banten (BJB)', 'Bank_Jabar_dan_Banten_(BJB).png', '110'),
(28, 'Bank DKI', 'Bank_DKI.png', '111'),
(29, 'BPD DIY', 'BPD_DIY.png', '112'),
(30, 'Bank Jateng', 'Bank_Jateng.png', '113'),
(31, 'Bank Jatim', 'Bank_Jatim.png', '114'),
(32, 'BPD Jambi', 'BPD_Jambi.png', '115'),
(33, 'BPD Aceh, BPD Aceh Syariah', 'BPD_Aceh,_BPD_Aceh_Syariah.png', '116'),
(34, 'Bank Sumut', 'Bank_Sumut.png', '117'),
(35, 'Bank Nagari', 'Bank_Nagari.png', '118'),
(36, 'Bank Riau', 'Bank_Riau.png', '119'),
(37, 'Bank Sumsel Babel', 'Bank_Sumsel_Babel.png', '120'),
(38, 'Bank Lampung', 'Bank_Lampung.png', '121'),
(39, 'Bank Kalsel', 'Bank_Kalsel.png', '122'),
(40, 'Bank Kalimantan Barat', 'Bank_Kalimantan_Barat.png', '123'),
(41, 'Bank Kalimantan Timur dan Utara', 'Bank_Kalimantan_Timur_dan_Utara.png', '124'),
(42, 'Bank Kalteng', 'Bank_Kalteng.png', '125'),
(43, 'Bank Sulsel dan Barat', 'Bank_Sulsel_dan_Barat.png', '126'),
(44, 'Bank Sulut Gorontalo', 'Bank_Sulut_Gorontalo.png', '127'),
(45, 'Bank NTB, NTB Syariah', 'Bank_NTB,_NTB_Syariah.png', '128'),
(46, 'BPD Bali', 'BPD_Bali.png', '129'),
(47, 'Bank NTT', 'Bank_NTT.png', '130'),
(48, 'Bank Maluku Malut', 'Bank_Maluku_Malut.png', '131'),
(49, 'Bank Papua', 'Bank_Papua.png', '132'),
(50, 'Bank Bengkulu', 'Bank_Bengkulu.png', '133'),
(51, 'Bank Sulawesi Tengah', 'Bank_Sulawesi_Tengah.png', '134'),
(52, 'Bank Sultra', 'Bank_Sultra.png', '135'),
(53, 'Bank Ekspor Indonesia', 'Bank_Ekspor_Indonesia.png', '3'),
(54, 'Bank Panin', 'Bank_Panin.png', '19'),
(55, 'Bank Arta Niaga Kencana', 'Bank_Arta_Niaga_Kencana.png', '20'),
(56, 'Bank?UOB Indonesia', 'Bank?UOB_Indonesia.png', '23'),
(57, 'American Express Bank LTD', 'American_Express_Bank_LTD.png', '30'),
(58, 'Citibank N.A', 'Citibank_N.A.png', '31'),
(59, 'JP. Morgan Chase Bank, N.A', 'JP._Morgan_Chase_Bank,_N.A.png', '32'),
(60, 'Bank of America, N.A', 'Bank_of_America,_N.A.png', '33'),
(61, 'ING Indonesia Bank', 'ING_Indonesia_Bank.png', '34'),
(62, 'Link Aja', 'Link_Aja.png', '911'),
(63, 'Bank Artha Graha Internasional', 'Bank_Artha_Graha_Internasional.png', '37'),
(64, 'Bank Credit Agricole Indosuez', 'Bank_Credit_Agricole_Indosuez.png', '39'),
(65, 'The Bangkok Bank Comp. LTD', 'The_Bangkok_Bank_Comp._LTD.png', '40'),
(66, 'The Hongkong & Shanghai B.C. (Bank HSBC)', 'The_Hongkong_&_Shanghai_B.C._(Bank_HSBC).png', '41'),
(67, 'The Bank of Tokyo Mitsubishi UFJ LTD', 'The_Bank_of_Tokyo_Mitsubishi_UFJ_LTD.png', '42'),
(68, 'Bank Sumitomo Mitsui Indonesia', 'Bank_Sumitomo_Mitsui_Indonesia.png', '45'),
(69, 'Bank DBS Indonesia', 'Bank_DBS_Indonesia.png', '46'),
(70, 'Bank Resona Perdania', 'Bank_Resona_Perdania.png', '47'),
(71, 'Bank Mizuho Indonesia', 'Bank_Mizuho_Indonesia.png', '48'),
(72, 'Standard Chartered Bank', 'Standard_Chartered_Bank.png', '50'),
(73, 'Bank ABN Amro', 'Bank_ABN_Amro.png', '52'),
(74, 'Bank Keppel Tatlee Buana', 'Bank_Keppel_Tatlee_Buana.png', '53'),
(75, 'Bank Capital Indonesia', 'Bank_Capital_Indonesia.png', '54'),
(76, 'Bank BNP Paribas Indonesia', 'Bank_BNP_Paribas_Indonesia.png', '57'),
(77, 'Bank UOB Indonesia', 'Bank_UOB_Indonesia.png', '23'),
(78, 'Korea Exchange Bank Danamon', 'Korea_Exchange_Bank_Danamon.png', '59'),
(79, 'Bank BJB Syariah', 'Bank_BJB_Syariah.png', '425'),
(80, 'Bank ANZ Indonesia', 'Bank_ANZ_Indonesia.png', '61'),
(81, 'Deutsche Bank AG.', 'Deutsche_Bank_AG..png', '67'),
(82, 'Bank Woori Indonesia', 'Bank_Woori_Indonesia.png', '68'),
(83, 'Bank OF China?', 'Bank_OF_China?.png', '69'),
(84, 'Bank Bumi Arta', 'Bank_Bumi_Arta.png', '76'),
(85, 'Bank Ekonomi', 'Bank_Ekonomi.png', '87'),
(86, 'Bank Antardaerah', 'Bank_Antardaerah.png', '88'),
(87, 'Bank Haga', 'Bank_Haga.png', '89'),
(88, 'Bank IFI', 'Bank_IFI.png', '93'),
(89, 'Bank?JTRUST', 'Bank?JTRUST.png', '95'),
(90, 'Bank Mayapada', 'Bank_Mayapada.png', '97'),
(91, 'Bank Nusantara Parahyangan', 'Bank_Nusantara_Parahyangan.png', '145'),
(92, 'Bank of India Indonesia', 'Bank_of_India_Indonesia.png', '146'),
(93, 'Bank Mestika Dharma', 'Bank_Mestika_Dharma.png', '151'),
(94, 'Bank Metro Express (Bank Shinhan Indonesia)', 'Bank_Metro_Express_(Bank_Shinhan_Indonesia).png', '152'),
(95, 'Bank Maspion Indonesia', 'Bank_Maspion_Indonesia.png', '157'),
(96, 'Bank Hagakita', 'Bank_Hagakita.png', '159'),
(97, 'Bank Ganesha', 'Bank_Ganesha.png', '161'),
(98, 'Bank Windu Kentjana', 'Bank_Windu_Kentjana.png', '162'),
(99, 'Halim Indonesia Bank (Bank ICBC Indonesia)', 'Halim_Indonesia_Bank_(Bank_ICBC_Indonesia).png', '164'),
(100, 'Bank Harmoni International', 'Bank_Harmoni_International.png', '166'),
(101, 'Bank QNB Kesawan (Bank QNB Indonesia)', 'Bank_QNB_Kesawan_(Bank_QNB_Indonesia).png', '167'),
(102, 'Bank Himpunan Saudara 1906', 'Bank_Himpunan_Saudara_1906.png', '212'),
(103, 'Bank Swaguna', 'Bank_Swaguna.png', '405'),
(104, 'Bank Jasa Jakarta', 'Bank_Jasa_Jakarta.png', '427'),
(105, 'Bank Bisnis Internasional', 'Bank_Bisnis_Internasional.png', '459'),
(106, 'Bank Sri Partha', 'Bank_Sri_Partha.png', '466'),
(107, 'Bank Jasa Jakarta', 'Bank_Jasa_Jakarta.png', '472'),
(108, 'Bank Bintang Manunggal', 'Bank_Bintang_Manunggal.png', '484'),
(109, 'Bank MNC / Bank Bumiputera', 'Bank_MNC_/_Bank_Bumiputera.png', '485'),
(110, 'Bank Yudha Bhakti', 'Bank_Yudha_Bhakti.png', '490'),
(111, 'Bank?BRI Agro', 'Bank?BRI_Agro.png', '494'),
(112, 'Bank Indomonex (Bank SBI Indonesia)', 'Bank_Indomonex_(Bank_SBI_Indonesia).png', '498'),
(113, 'Bank Royal Indonesia', 'Bank_Royal_Indonesia.png', '501'),
(114, 'Bank Alfindo (Bank National Nobu)', 'Bank_Alfindo_(Bank_National_Nobu).png', '503'),
(115, 'Bank Syariah Mega', 'Bank_Syariah_Mega.png', '506'),
(116, 'Bank Ina Perdana', 'Bank_Ina_Perdana.png', '513'),
(117, 'Bank Harfa', 'Bank_Harfa.png', '517'),
(118, 'Prima Master Bank', 'Prima_Master_Bank.png', '520'),
(119, 'Bank Persyarikatan Indonesia', 'Bank_Persyarikatan_Indonesia.png', '521'),
(120, 'Bank Akita', 'Bank_Akita.png', '525'),
(121, 'Liman International Bank', 'Liman_International_Bank.png', '526'),
(122, 'Anglomas Internasional Bank', 'Anglomas_Internasional_Bank.png', '531'),
(123, 'Bank Dipo International (Bank Sahabat Sampoerna)', 'Bank_Dipo_International_(Bank_Sahabat_Sampoerna).png', '523'),
(124, 'Bank Kesejahteraan Ekonomi', 'Bank_Kesejahteraan_Ekonomi.png', '535'),
(125, 'Bank Artos IND', 'Bank_Artos_IND.png', '542'),
(126, 'Bank Purba Danarta', 'Bank_Purba_Danarta.png', '547'),
(127, 'Bank Multi Arta Sentosa', 'Bank_Multi_Arta_Sentosa.png', '548'),
(128, 'Bank Mayora Indonesia', 'Bank_Mayora_Indonesia.png', '553'),
(129, 'Bank Index Selindo', 'Bank_Index_Selindo.png', '555'),
(130, 'Centratama Nasional Bank', 'Centratama_Nasional_Bank.png', '559'),
(131, 'Bank Victoria International', 'Bank_Victoria_International.png', '566'),
(132, 'Bank Fama Internasional', 'Bank_Fama_Internasional.png', '562'),
(133, 'Bank?Mandiri Taspen Pos', 'Bank?Mandiri_Taspen_Pos.png', '564'),
(134, 'Bank Harda', 'Bank_Harda.png', '567'),
(135, 'BPR KS', 'BPR_KS.png', '688'),
(136, 'Bank?Agris', 'Bank?Agris.png', '945'),
(137, 'Bank Merincorp', 'Bank_Merincorp.png', '946'),
(138, 'Bank Maybank Indocorp', 'Bank_Maybank_Indocorp.png', '947'),
(139, 'Bank OCBC ? Indonesia', 'Bank_OCBC_?_Indonesia.png', '948'),
(140, 'Bank CTBC (China Trust) Indonesia', 'Bank_CTBC_(China_Trust)_Indonesia.png', '949');

-- --------------------------------------------------------

--
-- Table structure for table `blw_@setting`
--

CREATE TABLE `blw_@setting` (
  `id` int(11) NOT NULL,
  `tgl` datetime NOT NULL,
  `field` text COLLATE utf8_bin NOT NULL,
  `value` text COLLATE utf8_bin NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_bin;

--
-- Dumping data for table `blw_@setting`
--

INSERT INTO `blw_@setting` (`id`, `tgl`, `field`, `value`) VALUES
(1, '2019-09-29 00:00:00', 'kota', '278'),
(2, '2021-11-06 08:22:44', 'kurir', '5|7|10|12|8|6|4|1|3|9|11'),
(3, '2019-09-29 00:00:00', 'nama', 'Winny Putri Lubis'),
(4, '2019-10-16 00:00:00', 'slogan', ''),
(5, '2019-10-18 00:00:00', 'notelp', '+62 852-7515-2838'),
(6, '2019-10-21 00:00:00', 'wasap', '+62 852-7515-2838'),
(7, '2019-10-21 00:00:00', 'lineid', ''),
(8, '2019-10-21 00:00:00', 'email', 'hello@winnyputrilubis.id'),
(9, '2019-10-21 00:00:00', 'alamat', 'Jl. Beo No. 43a, Kel. Sei Sikambing B, Kec. Medan Sunggal, Kota Medan, Prov. Sumatera Utara'),
(10, '2019-10-21 00:00:00', 'instagram', 'https://instagram.com/winnyputrilubis'),
(11, '2019-10-21 00:00:00', 'facebook', ''),
(12, '2021-11-30 10:36:13', 'logo', 'uploads/logo/logo-20211130103613.png'),
(13, '2021-11-30 10:36:15', 'favicon', 'uploads/logo/favicon-20211130103615.png'),
(14, '2020-07-18 16:02:20', 'email_notif', 'no-reply@winnyputrilubis.id'),
(15, '2020-07-03 00:05:12', 'email_jenis', '2'),
(16, '2020-07-19 00:05:12', 'email_server', 'mail.winnyputrilubis.id'),
(17, '2020-07-19 00:12:35', 'email_port', '465'),
(18, '2020-07-19 00:15:19', 'email_password', 'Vj!gkzK466th'),
(19, '2020-07-19 00:30:35', 'ipaymu', 'EB9B4227-BEBF-432F-BB22-BA7F1A'),
(20, '2020-07-19 00:30:35', 'rajaongkir', 'c603546240c1a6bb03351c475f1467ff'),
(21, '2020-07-19 00:47:47', 'biaya_cod', '12000'),
(22, '2020-07-28 21:37:51', 'fb_pixel', ''),
(23, '2020-08-13 13:39:23', 'woowa', ''),
(24, '2020-08-16 22:20:54', 'color1', '#1da1f2'),
(25, '2020-08-16 22:20:54', 'color2', '#111111'),
(26, '2020-08-16 22:27:09', 'color1rgba', '128,0,128'),
(27, '2020-08-16 22:27:09', 'color2rgba', ''),
(28, '2020-08-17 00:21:58', 'biaya_kurir', '2000'),
(29, '2020-08-17 01:41:48', 'midtrans_server', 'SB-Mid-server-AycVUoO61PNUv8ZKFv'),
(30, '2020-08-17 01:41:48', 'midtrans_client', 'SB-Mid-client-zqpgvGVhpHf-'),
(31, '2020-08-17 02:12:48', 'midtrans_snap', 'https://app.sandbox.midtrans.com/snap/snap.js'),
(32, '2020-08-19 23:16:19', 'notif_booster', '1'),
(33, '2020-08-19 23:16:19', 'payment_transfer', '0'),
(34, '2020-08-19 23:24:11', 'payment_ipaymu', '0'),
(35, '2020-08-19 23:24:11', 'payment_midtrans', '0'),
(36, '2020-08-24 23:24:28', 'jamkerja', 'Senin - Jum\'at<br/>09.00 - 17.00 WIB (Hari Kerja)'),
(37, '2020-08-28 14:21:07', 'ipaymu_url', 'https://sandbox.ipaymu.com/payment'),
(38, '2020-10-04 23:10:54', 'payment_biaya', '3'),
(39, '2020-10-04 23:10:54', 'payment_biayamax', '10000'),
(40, '2020-10-04 23:12:11', 'payment_biayamin', '3000'),
(41, '2020-10-07 21:33:25', 'login_otp', '0'),
(42, '2020-10-13 22:44:21', 'api_wasap', 'wablas'),
(43, '2020-10-13 22:44:21', 'wablas', '1YDNX5o5TnPVc6w7PzVMJpNvWbXjx7pVQfouADenIV1xsixXkqSwZuXTTOqLhrCz'),
(44, '2020-10-13 22:46:06', 'wablas_server', 'https://sawit.wablas.com'),
(45, '2020-12-29 00:00:00', 'colorbody', '#eaeaea'),
(46, '2021-01-01 00:00:00', 'colorbutton', '#111111'),
(47, '2021-10-08 02:16:54', 'payment_xendit', '1'),
(48, '2021-10-08 02:18:05', 'xendit_server', 'xnd_production_7wpexx8AqGz3QhayJ8eNgHzYtNFlCZWyyJUyqXEETSAVJUmA0s7gpPXCyYHlro');

-- --------------------------------------------------------

--
-- Table structure for table `blw_@testimoni`
--

CREATE TABLE `blw_@testimoni` (
  `id` int(11) NOT NULL,
  `tgl` datetime NOT NULL,
  `status` int(11) NOT NULL COMMENT '1=aktif,0=nonaktif',
  `nama` text NOT NULL,
  `foto` text NOT NULL,
  `komentar` text NOT NULL,
  `jabatan` text NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

-- --------------------------------------------------------

--
-- Table structure for table `blw_@wasap`
--

CREATE TABLE `blw_@wasap` (
  `id` int(11) NOT NULL,
  `nama` text NOT NULL,
  `wasap` varchar(30) NOT NULL,
  `tgl` time NOT NULL
) ENGINE=MyISAM DEFAULT CHARSET=latin1;

--
-- Dumping data for table `blw_@wasap`
--

INSERT INTO `blw_@wasap` (`id`, `nama`, `wasap`, `tgl`) VALUES
(12, 'Admin', '+85206598978', '00:00:00');

-- --------------------------------------------------------

--
-- Table structure for table `blw_history_ongkir`
--

CREATE TABLE `blw_history_ongkir` (
  `id` int(11) NOT NULL,
  `dari` int(11) NOT NULL,
  `tujuan` int(11) NOT NULL,
  `service` varchar(100) NOT NULL,
  `harga` int(11) NOT NULL,
  `etd` varchar(20) NOT NULL,
  `kurir` varchar(20) NOT NULL,
  `update` datetime NOT NULL,
  `usrid` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Dumping data for table `blw_history_ongkir`
--

INSERT INTO `blw_history_ongkir` (`id`, `dari`, `tujuan`, `service`, `harga`, `etd`, `kurir`, `update`, `usrid`) VALUES
(13, 278, 278, 'CTC', 7000, '', 'jne', '2022-04-13 20:39:48', 54),
(14, 278, 278, 'BEST', 11000, '', 'sicepat', '2022-07-29 13:20:41', 60),
(15, 278, 54, 'OKE', 27000, '', 'jne', '2022-03-28 07:41:41', 57),
(16, 278, 112, 'YES', 15000, '', 'jne', '2022-04-03 10:18:39', 58),
(17, 278, 112, 'REG', 10000, '', 'jne', '2022-04-03 10:18:48', 58),
(18, 278, 278, 'REG', 7000, '', 'sicepat', '2022-04-11 20:29:16', 54),
(19, 278, 339, 'REG', 29000, '', 'sicepat', '2022-06-13 22:00:44', 59);

-- --------------------------------------------------------

--
-- Table structure for table `blw_history_stok`
--

CREATE TABLE `blw_history_stok` (
  `id` int(11) NOT NULL,
  `idtransaksi` int(11) NOT NULL,
  `variasi` int(11) NOT NULL,
  `stokawal` int(11) NOT NULL,
  `stokakhir` int(11) NOT NULL,
  `jumlah` int(11) NOT NULL,
  `tgl` datetime NOT NULL,
  `usrid` int(11) NOT NULL
) ENGINE=MyISAM DEFAULT CHARSET=latin1;

--
-- Dumping data for table `blw_history_stok`
--

INSERT INTO `blw_history_stok` (`id`, `idtransaksi`, `variasi`, `stokawal`, `stokakhir`, `jumlah`, `tgl`, `usrid`) VALUES
(167, 63, 0, 2051, 2050, 1, '2022-03-18 22:10:41', 54),
(168, 64, 0, 2050, 2049, 1, '2022-03-18 22:11:59', 54),
(169, 65, 0, 2049, 2048, 1, '2022-03-18 22:13:25', 54),
(170, 66, 0, 2048, 2047, 1, '2022-03-18 22:16:14', 55),
(171, 67, 0, 2047, 2046, 1, '2022-03-18 22:20:34', 55),
(172, 68, 0, 2046, 2045, 1, '2022-03-18 22:25:31', 55),
(173, 69, 0, 2045, 2044, 1, '2022-03-18 22:38:58', 54),
(174, 70, 0, 2044, 2043, 1, '2022-03-19 00:55:37', 54),
(175, 71, 0, 2043, 2042, 1, '2022-03-19 13:42:51', 54),
(176, 72, 0, 2042, 2041, 1, '2022-03-19 14:19:13', 56),
(177, 73, 0, 2041, 2040, 1, '2022-03-23 22:09:14', 54),
(178, 74, 0, 2851, 2850, 1, '2022-03-26 13:06:46', 55),
(179, 75, 0, 2040, 2039, 1, '2022-03-29 18:03:31', 54),
(180, 76, 0, 2039, 2038, 1, '2022-03-29 18:05:11', 54),
(181, 77, 0, 4866, 4865, 1, '2022-04-01 12:02:42', 54),
(182, 78, 0, 4869, 4868, 1, '2022-04-03 10:18:53', 58),
(183, 79, 0, 4865, 4864, 1, '2022-04-11 20:29:18', 54),
(184, 80, 0, 2891, 2890, 1, '2022-04-13 20:39:51', 54),
(185, 81, 0, 3654, 3653, 1, '2022-06-13 22:01:00', 59),
(186, 82, 0, 2850, 2848, 2, '2022-07-29 13:20:45', 60);

-- --------------------------------------------------------

--
-- Table structure for table `blw_invoice`
--

CREATE TABLE `blw_invoice` (
  `id` bigint(20) NOT NULL,
  `invoice` varchar(100) NOT NULL,
  `usrid` bigint(20) NOT NULL,
  `tgl` datetime NOT NULL,
  `tglupdate` datetime NOT NULL,
  `total` bigint(20) NOT NULL,
  `saldo` int(11) NOT NULL,
  `transfer` int(11) NOT NULL,
  `kodebayar` int(11) NOT NULL,
  `voucher` int(11) NOT NULL,
  `diskon` int(11) NOT NULL,
  `metode` int(11) NOT NULL,
  `status` int(11) NOT NULL COMMENT '0=belumverif,1=sudahverif,2=gagalverif,3=batal',
  `kadaluarsa` datetime NOT NULL,
  `xendit_id` varchar(100) DEFAULT NULL,
  `xendit_url` varchar(300) DEFAULT NULL,
  `ipaymu_tipe` varchar(300) DEFAULT NULL,
  `midtrans_id` varchar(300) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Dumping data for table `blw_invoice`
--

INSERT INTO `blw_invoice` (`id`, `invoice`, `usrid`, `tgl`, `tglupdate`, `total`, `saldo`, `transfer`, `kodebayar`, `voucher`, `diskon`, `metode`, `status`, `kadaluarsa`, `xendit_id`, `xendit_url`, `ipaymu_tipe`, `midtrans_id`) VALUES
(63, '2022031863962', 54, '2022-03-18 22:10:41', '2022-03-19 01:22:50', 82962, 0, 82000, 962, 0, 0, 1, 3, '2022-03-20 22:10:41', NULL, NULL, NULL, NULL),
(64, '2022031864611', 54, '2022-03-18 22:11:59', '2022-03-19 01:23:05', 86611, 0, 86000, 611, 0, 0, 1, 3, '2022-03-20 22:11:59', NULL, NULL, NULL, NULL),
(65, '2022031865301', 54, '2022-03-18 22:13:25', '2022-03-19 01:22:57', 75301, 0, 75000, 301, 0, 0, 1, 3, '2022-03-20 22:13:25', NULL, NULL, NULL, NULL),
(66, '2022031866392', 55, '2022-03-18 22:16:14', '2022-03-18 22:22:31', 82392, 0, 82000, 392, 0, 0, 1, 3, '2022-03-20 22:16:14', NULL, NULL, NULL, NULL),
(67, '2022031867185', 55, '2022-03-18 22:20:34', '2022-03-18 22:23:01', 86185, 0, 86000, 185, 0, 0, 1, 3, '2022-03-20 22:20:34', NULL, NULL, NULL, NULL),
(68, '2022031868406', 55, '2022-03-18 22:25:31', '2022-03-18 22:27:38', 82406, 0, 82000, 406, 0, 0, 1, 3, '2022-03-20 22:25:31', NULL, NULL, NULL, NULL),
(69, '2022031869550', 54, '2022-03-18 22:38:58', '2022-03-19 01:22:01', 75550, 0, 75000, 550, 0, 0, 1, 3, '2022-03-20 22:38:58', NULL, NULL, NULL, NULL),
(70, '2022031970150', 54, '2022-03-19 00:55:37', '2022-03-19 01:15:52', 75150, 0, 75000, 150, 0, 0, 1, 3, '2022-03-21 00:55:37', NULL, NULL, NULL, NULL),
(71, '2022031971972', 54, '2022-03-19 13:42:51', '2022-03-21 13:45:14', 86972, 0, 86000, 972, 0, 0, 1, 3, '2022-03-21 13:42:51', NULL, NULL, NULL, NULL),
(72, '2022031972483', 56, '2022-03-19 14:19:13', '2022-03-21 14:46:52', 82483, 0, 82000, 483, 0, 0, 1, 3, '2022-03-21 14:19:13', NULL, NULL, NULL, NULL),
(73, '2022032373117', 54, '2022-03-23 22:09:14', '2022-03-24 22:39:49', 82117, 0, 82000, 117, 0, 0, 1, 3, '2022-03-25 22:09:14', '623b381dc7e03f013b420f1c', 'https://checkout.xendit.co/web/623b381dc7e03f013b420f1c', NULL, NULL),
(74, '2022032674359', 55, '2022-03-26 13:06:46', '2022-03-26 13:08:08', 82359, 0, 82000, 359, 0, 0, 1, 3, '2022-03-28 13:06:46', '623ead79e5cb6edfdbd2deaf', 'https://checkout.xendit.co/web/623ead79e5cb6edfdbd2deaf', NULL, NULL),
(75, '2022032975630', 54, '2022-03-29 18:03:31', '2022-03-29 18:07:37', 82630, 0, 82000, 630, 0, 0, 1, 3, '2022-03-31 18:03:31', NULL, NULL, NULL, NULL),
(76, '2022032976447', 54, '2022-03-29 18:05:11', '2022-04-01 01:13:23', 82447, 0, 82000, 447, 0, 0, 1, 3, '2022-03-31 18:05:11', '6242e7ea21e041032462ef4f', 'https://checkout.xendit.co/web/6242e7ea21e041032462ef4f', NULL, NULL),
(77, '2022040177267', 54, '2022-04-01 12:02:42', '2022-04-01 12:03:06', 102267, 0, 102000, 267, 0, 0, 1, 3, '2022-04-03 12:02:42', '62468774274df8105b6a2d01', 'https://checkout.xendit.co/web/62468774274df8105b6a2d01', NULL, NULL),
(78, '2022040378898', 58, '2022-04-03 10:18:53', '0000-00-00 00:00:00', 220898, 0, 220000, 898, 0, 0, 1, 1, '2022-04-05 10:18:53', '6249121f4734073899efd5e4', 'https://checkout.xendit.co/web/6249121f4734073899efd5e4', NULL, NULL),
(79, '2022041179376', 54, '2022-04-11 20:29:18', '2022-04-11 20:29:50', 102376, 0, 102000, 376, 0, 0, 1, 3, '2022-04-13 20:29:18', '62542d324d94db2cd00f11b2', 'https://checkout.xendit.co/web/62542d324d94db2cd00f11b2', NULL, NULL),
(80, '2022041380553', 54, '2022-04-13 20:39:51', '2022-04-13 20:41:20', 86053, 0, 85500, 553, 0, 0, 1, 3, '2022-04-15 20:39:51', '6256d2a9ae5c802440157adf', 'https://checkout.xendit.co/web/6256d2a9ae5c802440157adf', NULL, NULL),
(81, '2022061381277', 59, '2022-06-13 22:01:00', '2022-06-15 22:29:02', 154277, 0, 154000, 277, 0, 0, 1, 3, '2022-06-15 22:01:00', '62a7512f86713b0c7d03a302', 'https://checkout.xendit.co/web/62a7512f86713b0c7d03a302', NULL, NULL),
(82, '2022072982782', 60, '2022-07-29 13:20:45', '2022-07-31 13:23:42', 161782, 0, 161000, 782, 0, 0, 1, 3, '2022-07-31 13:20:45', '62e37c406db8485a7901b8ad', 'https://checkout.xendit.co/web/62e37c406db8485a7901b8ad', NULL, NULL);

-- --------------------------------------------------------

--
-- Table structure for table `blw_invoice_konfirmasi`
--

CREATE TABLE `blw_invoice_konfirmasi` (
  `id` int(11) NOT NULL,
  `tgl` datetime NOT NULL,
  `idbayar` int(11) NOT NULL,
  `bukti` text NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

-- --------------------------------------------------------

--
-- Table structure for table `blw_pesan`
--

CREATE TABLE `blw_pesan` (
  `id` int(11) NOT NULL,
  `tujuan` int(11) NOT NULL,
  `dari` int(11) NOT NULL COMMENT '1=pembeli,2=toko',
  `isipesan` text NOT NULL,
  `tgl` datetime NOT NULL,
  `baca` int(11) NOT NULL DEFAULT 0
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

-- --------------------------------------------------------

--
-- Table structure for table `blw_pesan_notifikasi`
--

CREATE TABLE `blw_pesan_notifikasi` (
  `id` bigint(20) NOT NULL,
  `usrid` bigint(20) NOT NULL,
  `jenis` int(11) NOT NULL COMMENT '1=email,2=wasap',
  `tujuan` text NOT NULL,
  `judul` text NOT NULL,
  `pesan` text NOT NULL,
  `subyek` text NOT NULL,
  `pengirim` text DEFAULT NULL,
  `tgl` datetime NOT NULL,
  `status` int(11) NOT NULL COMMENT '0=belum proses, 1=terkirim, 2=gagal',
  `proses` datetime NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Dumping data for table `blw_pesan_notifikasi`
--

INSERT INTO `blw_pesan_notifikasi` (`id`, `usrid`, `jenis`, `tujuan`, `judul`, `pesan`, `subyek`, `pengirim`, `tgl`, `status`, `proses`) VALUES
(681, 0, 1, 'didihansya@foreskin.market', 'Winny Putri Lubis - Pesanan', '\n					Halo <b>Didihan</b><br/>Terimakasih sudah membeli produk kami.<br/>Saat ini kami sedang menunggu pembayaran darimu sebelum kami memprosesnya. Sebagai informasi, berikut detail pesananmu <br/>No Invoice: <b>#2021120344399</b><br/> <br/>Total Pesanan: <b>Rp 200.399</b><br/>Ongkos Kirim: <b>Rp 0</b><br/>Kurir Pengiriman: <b>LAIN </b><br/> <br/>Detail Pengiriman <br/>Penerima: <b>Didi</b> <br/>No HP: <b>082222222</b> <br/>Alamat: <b>Jl Karya</b><br/> <br/>Untuk pembayaran silahkan langsung klik link berikut:<br/><a href=\'https://dev.winnyputrilubis.id/home/invoice?inv=aTo0NDs=\'>Bayar Pesanan Sekarang &raquo;</a>\n				', 'Pesanan', NULL, '2021-12-03 21:17:07', 0, '0000-00-00 00:00:00'),
(682, 0, 2, '08122222222222', '', '\n					Halo *Didihan*\nTerimakasih sudah membeli produk kami.\nSaat ini kami sedang menunggu pembayaran darimu sebelum kami memprosesnya. Sebagai informasi, berikut detail pesananmu \n \nNo Invoice: *#2021120344399*\nTotal Pesanan: *Rp 200.399*\nOngkos Kirim: *Rp 0*\nKurir Pengiriman: *LAIN *\n \nDetail Pengiriman \nPenerima: *Didi*\nNo HP: *082222222*\nAlamat: *Jl Karya*\n \nUntuk pembayaran silahkan langsung klik link berikut\nhttps://dev.winnyputrilubis.id/home/invoice?inv=aTo0NDs=\n				', '', NULL, '2021-12-03 21:17:07', 0, '0000-00-00 00:00:00'),
(683, 0, 1, 'hello@winnyputrilubis.id', 'Winny Putri Lubis - Pesanan Baru', '\n					<h3>Pesanan Baru</h3><br/>\n					<b>DIDIHAN</b> telah membuat pesanan baru dengan total pembayaran \n					<b>Rp. 200.399</b> Invoice ID: <b>#2021120344399</b>\n					<br/>&nbsp;<br/>&nbsp;<br/>\n					Cek Pesanan Pembeli di <b>Dashboard Admin Winny Putri Lubis</b><br/>\n					<a href=\'https://dev.winnyputrilubis.id/cdn\'>Klik Disini</a>\n				', 'Pesanan Baru di Winny Putri Lubis', NULL, '2021-12-03 21:17:07', 0, '0000-00-00 00:00:00'),
(684, 0, 2, '+62-82111', '', '\n					*Pesanan Baru*\n*DIDIHAN* telah membuat pesanan baru dengan detail:\nTotal Pembayaran: *Rp. 200.399*\nInvoice ID: *#2021120344399*\n \nCek Pesanan Pembeli di *Dashboard Admin Winny Putri Lubis*\n					', '', NULL, '2021-12-03 21:17:07', 0, '0000-00-00 00:00:00'),
(685, 0, 1, 'hendryadrinata@gmail.com', 'Winny Putri Lubis - Pesanan', '\n					Halo <b>ella</b><br/>Terimakasih sudah membeli produk kami.<br/>Saat ini kami sedang menunggu pembayaran darimu sebelum kami memprosesnya. Sebagai informasi, berikut detail pesananmu <br/>No Invoice: <b>#2021120345394</b><br/> <br/>Total Pesanan: <b>Rp 1.000.394</b><br/>Ongkos Kirim: <b>Rp 0</b><br/>Kurir Pengiriman: <b>LAIN </b><br/> <br/>Detail Pengiriman <br/>Penerima: <b>ella</b> <br/>No HP: <b>085275206878</b> <br/>Alamat: <b>komplek titimas gg.tapian nauli medan johor</b><br/> <br/>Untuk pembayaran silahkan langsung klik link berikut:<br/><a href=\'https://dev.winnyputrilubis.id/home/invoice?inv=aTo0NTs=\'>Bayar Pesanan Sekarang &raquo;</a>\n				', 'Pesanan', NULL, '2021-12-03 23:09:27', 0, '0000-00-00 00:00:00'),
(686, 0, 2, '085275206878', '', '\n					Halo *ella*\nTerimakasih sudah membeli produk kami.\nSaat ini kami sedang menunggu pembayaran darimu sebelum kami memprosesnya. Sebagai informasi, berikut detail pesananmu \n \nNo Invoice: *#2021120345394*\nTotal Pesanan: *Rp 1.000.394*\nOngkos Kirim: *Rp 0*\nKurir Pengiriman: *LAIN *\n \nDetail Pengiriman \nPenerima: *ella*\nNo HP: *085275206878*\nAlamat: *komplek titimas gg.tapian nauli medan johor*\n \nUntuk pembayaran silahkan langsung klik link berikut\nhttps://dev.winnyputrilubis.id/home/invoice?inv=aTo0NTs=\n				', '', NULL, '2021-12-03 23:09:27', 0, '0000-00-00 00:00:00'),
(687, 0, 1, 'hello@winnyputrilubis.id', 'Winny Putri Lubis - Pesanan Baru', '\n					<h3>Pesanan Baru</h3><br/>\n					<b>ELLA</b> telah membuat pesanan baru dengan total pembayaran \n					<b>Rp. 1.000.394</b> Invoice ID: <b>#2021120345394</b>\n					<br/>&nbsp;<br/>&nbsp;<br/>\n					Cek Pesanan Pembeli di <b>Dashboard Admin Winny Putri Lubis</b><br/>\n					<a href=\'https://dev.winnyputrilubis.id/cdn\'>Klik Disini</a>\n				', 'Pesanan Baru di Winny Putri Lubis', NULL, '2021-12-03 23:09:28', 0, '0000-00-00 00:00:00'),
(688, 0, 2, '+62-82111', '', '\n					*Pesanan Baru*\n*ELLA* telah membuat pesanan baru dengan detail:\nTotal Pembayaran: *Rp. 1.000.394*\nInvoice ID: *#2021120345394*\n \nCek Pesanan Pembeli di *Dashboard Admin Winny Putri Lubis*\n					', '', NULL, '2021-12-03 23:09:28', 0, '0000-00-00 00:00:00'),
(689, 0, 1, 'rhazaaqbaim@gmail.com', 'Winny Putri Lubis - Pesanan', '\n					Halo <b>siro</b><br/>Terimakasih sudah membeli produk kami.<br/>Saat ini kami sedang menunggu pembayaran darimu sebelum kami memprosesnya. Sebagai informasi, berikut detail pesananmu <br/>No Invoice: <b>#2021120546796</b><br/> <br/>Total Pesanan: <b>Rp 252.796</b><br/>Ongkos Kirim: <b>Rp 7.000</b><br/>Kurir Pengiriman: <b>JNE CTC</b><br/> <br/>Detail Pengiriman <br/>Penerima: <b>siro</b> <br/>No HP: <b>085275206878</b> <br/>Alamat: <b>jl b katamso gg besi no 3</b><br/> <br/>Untuk pembayaran silahkan langsung klik link berikut:<br/><a href=\'https://dev.winnyputrilubis.id/home/invoice?inv=aTo0Njs=\'>Bayar Pesanan Sekarang &raquo;</a>\n				', 'Pesanan', NULL, '2021-12-05 15:36:14', 0, '0000-00-00 00:00:00'),
(690, 0, 2, '087877777772', '', '\n					Halo *siro*\nTerimakasih sudah membeli produk kami.\nSaat ini kami sedang menunggu pembayaran darimu sebelum kami memprosesnya. Sebagai informasi, berikut detail pesananmu \n \nNo Invoice: *#2021120546796*\nTotal Pesanan: *Rp 252.796*\nOngkos Kirim: *Rp 7.000*\nKurir Pengiriman: *JNE CTC*\n \nDetail Pengiriman \nPenerima: *siro*\nNo HP: *085275206878*\nAlamat: *jl b katamso gg besi no 3*\n \nUntuk pembayaran silahkan langsung klik link berikut\nhttps://dev.winnyputrilubis.id/home/invoice?inv=aTo0Njs=\n				', '', NULL, '2021-12-05 15:36:14', 0, '0000-00-00 00:00:00'),
(691, 0, 1, 'hello@winnyputrilubis.id', 'Winny Putri Lubis - Pesanan Baru', '\n					<h3>Pesanan Baru</h3><br/>\n					<b>SIRO</b> telah membuat pesanan baru dengan total pembayaran \n					<b>Rp. 252.796</b> Invoice ID: <b>#2021120546796</b>\n					<br/>&nbsp;<br/>&nbsp;<br/>\n					Cek Pesanan Pembeli di <b>Dashboard Admin Winny Putri Lubis</b><br/>\n					<a href=\'https://dev.winnyputrilubis.id/cdn\'>Klik Disini</a>\n				', 'Pesanan Baru di Winny Putri Lubis', NULL, '2021-12-05 15:36:15', 0, '0000-00-00 00:00:00'),
(692, 0, 2, '+62-82111', '', '\n					*Pesanan Baru*\n*SIRO* telah membuat pesanan baru dengan detail:\nTotal Pembayaran: *Rp. 252.796*\nInvoice ID: *#2021120546796*\n \nCek Pesanan Pembeli di *Dashboard Admin Winny Putri Lubis*\n					', '', NULL, '2021-12-05 15:36:15', 0, '0000-00-00 00:00:00'),
(693, 0, 1, 'didihansya@foreskin.market', 'Winny Putri Lubis - Pesanan', '\n					Halo <b>Didihan</b><br/>Terimakasih sudah membeli produk kami.<br/>Saat ini kami sedang menunggu pembayaran darimu sebelum kami memprosesnya. Sebagai informasi, berikut detail pesananmu <br/>No Invoice: <b>#2021120547668</b><br/> <br/>Total Pesanan: <b>Rp 200.668</b><br/>Ongkos Kirim: <b>Rp 0</b><br/>Kurir Pengiriman: <b>LAIN </b><br/> <br/>Detail Pengiriman <br/>Penerima: <b>Didi</b> <br/>No HP: <b>082222222</b> <br/>Alamat: <b>Jl Karya</b><br/> <br/>Untuk pembayaran silahkan langsung klik link berikut:<br/><a href=\'https://dev.winnyputrilubis.id/home/invoice?inv=aTo0Nzs=\'>Bayar Pesanan Sekarang &raquo;</a>\n				', 'Pesanan', NULL, '2021-12-05 20:31:49', 0, '0000-00-00 00:00:00'),
(694, 0, 2, '08122222222222', '', '\n					Halo *Didihan*\nTerimakasih sudah membeli produk kami.\nSaat ini kami sedang menunggu pembayaran darimu sebelum kami memprosesnya. Sebagai informasi, berikut detail pesananmu \n \nNo Invoice: *#2021120547668*\nTotal Pesanan: *Rp 200.668*\nOngkos Kirim: *Rp 0*\nKurir Pengiriman: *LAIN *\n \nDetail Pengiriman \nPenerima: *Didi*\nNo HP: *082222222*\nAlamat: *Jl Karya*\n \nUntuk pembayaran silahkan langsung klik link berikut\nhttps://dev.winnyputrilubis.id/home/invoice?inv=aTo0Nzs=\n				', '', NULL, '2021-12-05 20:31:49', 0, '0000-00-00 00:00:00'),
(695, 0, 1, 'hello@winnyputrilubis.id', 'Winny Putri Lubis - Pesanan Baru', '\n					<h3>Pesanan Baru</h3><br/>\n					<b>DIDIHAN</b> telah membuat pesanan baru dengan total pembayaran \n					<b>Rp. 200.668</b> Invoice ID: <b>#2021120547668</b>\n					<br/>&nbsp;<br/>&nbsp;<br/>\n					Cek Pesanan Pembeli di <b>Dashboard Admin Winny Putri Lubis</b><br/>\n					<a href=\'https://dev.winnyputrilubis.id/cdn\'>Klik Disini</a>\n				', 'Pesanan Baru di Winny Putri Lubis', NULL, '2021-12-05 20:31:49', 0, '0000-00-00 00:00:00'),
(696, 0, 2, '+62-82111', '', '\n					*Pesanan Baru*\n*DIDIHAN* telah membuat pesanan baru dengan detail:\nTotal Pembayaran: *Rp. 200.668*\nInvoice ID: *#2021120547668*\n \nCek Pesanan Pembeli di *Dashboard Admin Winny Putri Lubis*\n					', '', NULL, '2021-12-05 20:31:49', 0, '0000-00-00 00:00:00'),
(697, 0, 1, 'didi@foreskin.market', 'Winny Putri Lubis - Pesanan', '\n					Halo <b>Didi</b><br/>Terimakasih sudah membeli produk kami.<br/>Saat ini kami sedang menunggu pembayaran darimu sebelum kami memprosesnya. Sebagai informasi, berikut detail pesananmu <br/>No Invoice: <b>#2021120548527</b><br/> <br/>Total Pesanan: <b>Rp 501.527</b><br/>Ongkos Kirim: <b>Rp 11.000</b><br/>Kurir Pengiriman: <b>SICEPAT BEST</b><br/> <br/>Detail Pengiriman <br/>Penerima: <b>Didi</b> <br/>No HP: <b>08121121221</b> <br/>Alamat: <b>JL Eka Surya</b><br/> <br/>Untuk pembayaran silahkan langsung klik link berikut:<br/><a href=\'https://dev.winnyputrilubis.id/home/invoice?inv=aTo0ODs=\'>Bayar Pesanan Sekarang &raquo;</a>\n				', 'Pesanan', NULL, '2021-12-05 20:36:15', 0, '0000-00-00 00:00:00'),
(698, 0, 2, '088128312312312', '', '\n					Halo *Didi*\nTerimakasih sudah membeli produk kami.\nSaat ini kami sedang menunggu pembayaran darimu sebelum kami memprosesnya. Sebagai informasi, berikut detail pesananmu \n \nNo Invoice: *#2021120548527*\nTotal Pesanan: *Rp 501.527*\nOngkos Kirim: *Rp 11.000*\nKurir Pengiriman: *SICEPAT BEST*\n \nDetail Pengiriman \nPenerima: *Didi*\nNo HP: *08121121221*\nAlamat: *JL Eka Surya*\n \nUntuk pembayaran silahkan langsung klik link berikut\nhttps://dev.winnyputrilubis.id/home/invoice?inv=aTo0ODs=\n				', '', NULL, '2021-12-05 20:36:15', 0, '0000-00-00 00:00:00'),
(699, 0, 1, 'hello@winnyputrilubis.id', 'Winny Putri Lubis - Pesanan Baru', '\n					<h3>Pesanan Baru</h3><br/>\n					<b>DIDI</b> telah membuat pesanan baru dengan total pembayaran \n					<b>Rp. 501.527</b> Invoice ID: <b>#2021120548527</b>\n					<br/>&nbsp;<br/>&nbsp;<br/>\n					Cek Pesanan Pembeli di <b>Dashboard Admin Winny Putri Lubis</b><br/>\n					<a href=\'https://dev.winnyputrilubis.id/cdn\'>Klik Disini</a>\n				', 'Pesanan Baru di Winny Putri Lubis', NULL, '2021-12-05 20:36:16', 0, '0000-00-00 00:00:00'),
(700, 0, 2, '+62-82111', '', '\n					*Pesanan Baru*\n*DIDI* telah membuat pesanan baru dengan detail:\nTotal Pembayaran: *Rp. 501.527*\nInvoice ID: *#2021120548527*\n \nCek Pesanan Pembeli di *Dashboard Admin Winny Putri Lubis*\n					', '', NULL, '2021-12-05 20:36:16', 0, '0000-00-00 00:00:00'),
(701, 0, 1, 'didi@foreskin.market', 'Winny Putri Lubis - Pesanan', '\n					Halo <b>Didi</b><br/>Terimakasih sudah membeli produk kami.<br/>Saat ini kami sedang menunggu pembayaran darimu sebelum kami memprosesnya. Sebagai informasi, berikut detail pesananmu <br/>No Invoice: <b>#2021120549215</b><br/> <br/>Total Pesanan: <b>Rp 507.215</b><br/>Ongkos Kirim: <b>Rp 7.000</b><br/>Kurir Pengiriman: <b>JNE CTC</b><br/> <br/>Detail Pengiriman <br/>Penerima: <b>Didi</b> <br/>No HP: <b>08121121221</b> <br/>Alamat: <b>JL Eka Surya</b><br/> <br/>Untuk pembayaran silahkan langsung klik link berikut:<br/><a href=\'https://dev.winnyputrilubis.id/home/invoice?inv=aTo0OTs=\'>Bayar Pesanan Sekarang &raquo;</a>\n				', 'Pesanan', NULL, '2021-12-05 20:38:10', 0, '0000-00-00 00:00:00'),
(702, 0, 2, '088128312312312', '', '\n					Halo *Didi*\nTerimakasih sudah membeli produk kami.\nSaat ini kami sedang menunggu pembayaran darimu sebelum kami memprosesnya. Sebagai informasi, berikut detail pesananmu \n \nNo Invoice: *#2021120549215*\nTotal Pesanan: *Rp 507.215*\nOngkos Kirim: *Rp 7.000*\nKurir Pengiriman: *JNE CTC*\n \nDetail Pengiriman \nPenerima: *Didi*\nNo HP: *08121121221*\nAlamat: *JL Eka Surya*\n \nUntuk pembayaran silahkan langsung klik link berikut\nhttps://dev.winnyputrilubis.id/home/invoice?inv=aTo0OTs=\n				', '', NULL, '2021-12-05 20:38:10', 0, '0000-00-00 00:00:00'),
(703, 0, 1, 'hello@winnyputrilubis.id', 'Winny Putri Lubis - Pesanan Baru', '\n					<h3>Pesanan Baru</h3><br/>\n					<b>DIDI</b> telah membuat pesanan baru dengan total pembayaran \n					<b>Rp. 507.215</b> Invoice ID: <b>#2021120549215</b>\n					<br/>&nbsp;<br/>&nbsp;<br/>\n					Cek Pesanan Pembeli di <b>Dashboard Admin Winny Putri Lubis</b><br/>\n					<a href=\'https://dev.winnyputrilubis.id/cdn\'>Klik Disini</a>\n				', 'Pesanan Baru di Winny Putri Lubis', NULL, '2021-12-05 20:38:10', 0, '0000-00-00 00:00:00'),
(704, 0, 2, '+62-82111', '', '\n					*Pesanan Baru*\n*DIDI* telah membuat pesanan baru dengan detail:\nTotal Pembayaran: *Rp. 507.215*\nInvoice ID: *#2021120549215*\n \nCek Pesanan Pembeli di *Dashboard Admin Winny Putri Lubis*\n					', '', NULL, '2021-12-05 20:38:10', 0, '0000-00-00 00:00:00'),
(705, 0, 1, 'didi@foreskin.market', 'Winny Putri Lubis - Pesanan', '\n					Halo <b>Didi</b><br/>Terimakasih sudah membeli produk kami.<br/>Saat ini kami sedang menunggu pembayaran darimu sebelum kami memprosesnya. Sebagai informasi, berikut detail pesananmu <br/>No Invoice: <b>#2021120550437</b><br/> <br/>Total Pesanan: <b>Rp 155.437</b><br/>Ongkos Kirim: <b>Rp 0</b><br/>Kurir Pengiriman: <b>GOSEND </b><br/> <br/>Detail Pengiriman <br/>Penerima: <b>Didi</b> <br/>No HP: <b>08121121221</b> <br/>Alamat: <b>JL Eka Surya</b><br/> <br/>Untuk pembayaran silahkan langsung klik link berikut:<br/><a href=\'https://dev.winnyputrilubis.id/home/invoice?inv=aTo1MDs=\'>Bayar Pesanan Sekarang &raquo;</a>\n				', 'Pesanan', NULL, '2021-12-05 20:39:50', 0, '0000-00-00 00:00:00'),
(706, 0, 2, '088128312312312', '', '\n					Halo *Didi*\nTerimakasih sudah membeli produk kami.\nSaat ini kami sedang menunggu pembayaran darimu sebelum kami memprosesnya. Sebagai informasi, berikut detail pesananmu \n \nNo Invoice: *#2021120550437*\nTotal Pesanan: *Rp 155.437*\nOngkos Kirim: *Rp 0*\nKurir Pengiriman: *GOSEND *\n \nDetail Pengiriman \nPenerima: *Didi*\nNo HP: *08121121221*\nAlamat: *JL Eka Surya*\n \nUntuk pembayaran silahkan langsung klik link berikut\nhttps://dev.winnyputrilubis.id/home/invoice?inv=aTo1MDs=\n				', '', NULL, '2021-12-05 20:39:50', 0, '0000-00-00 00:00:00'),
(707, 0, 1, 'hello@winnyputrilubis.id', 'Winny Putri Lubis - Pesanan Baru', '\n					<h3>Pesanan Baru</h3><br/>\n					<b>DIDI</b> telah membuat pesanan baru dengan total pembayaran \n					<b>Rp. 155.437</b> Invoice ID: <b>#2021120550437</b>\n					<br/>&nbsp;<br/>&nbsp;<br/>\n					Cek Pesanan Pembeli di <b>Dashboard Admin Winny Putri Lubis</b><br/>\n					<a href=\'https://dev.winnyputrilubis.id/cdn\'>Klik Disini</a>\n				', 'Pesanan Baru di Winny Putri Lubis', NULL, '2021-12-05 20:39:51', 0, '0000-00-00 00:00:00'),
(708, 0, 2, '+62-82111', '', '\n					*Pesanan Baru*\n*DIDI* telah membuat pesanan baru dengan detail:\nTotal Pembayaran: *Rp. 155.437*\nInvoice ID: *#2021120550437*\n \nCek Pesanan Pembeli di *Dashboard Admin Winny Putri Lubis*\n					', '', NULL, '2021-12-05 20:39:51', 0, '0000-00-00 00:00:00'),
(709, 0, 1, 'didihansya@foreskin.market', 'Winny Putri Lubis - Pesanan', '\n					Halo <b>Didihan</b><br/>Terimakasih sudah membeli produk kami.<br/>Saat ini kami sedang menunggu pembayaran darimu sebelum kami memprosesnya. Sebagai informasi, berikut detail pesananmu <br/>No Invoice: <b>#2021120551665</b><br/> <br/>Total Pesanan: <b>Rp 180.665</b><br/>Ongkos Kirim: <b>Rp 100.000</b><br/>Kurir Pengiriman: <b>SENTRAL DARAT ELEKTRONIK</b><br/> <br/>Detail Pengiriman <br/>Penerima: <b>Didi</b> <br/>No HP: <b>082222222</b> <br/>Alamat: <b>Jl Karya</b><br/> <br/>Untuk pembayaran silahkan langsung klik link berikut:<br/><a href=\'https://dev.winnyputrilubis.id/home/invoice?inv=aTo1MTs=\'>Bayar Pesanan Sekarang &raquo;</a>\n				', 'Pesanan', NULL, '2021-12-05 20:42:15', 0, '0000-00-00 00:00:00'),
(710, 0, 2, '08122222222222', '', '\n					Halo *Didihan*\nTerimakasih sudah membeli produk kami.\nSaat ini kami sedang menunggu pembayaran darimu sebelum kami memprosesnya. Sebagai informasi, berikut detail pesananmu \n \nNo Invoice: *#2021120551665*\nTotal Pesanan: *Rp 180.665*\nOngkos Kirim: *Rp 100.000*\nKurir Pengiriman: *SENTRAL DARAT ELEKTRONIK*\n \nDetail Pengiriman \nPenerima: *Didi*\nNo HP: *082222222*\nAlamat: *Jl Karya*\n \nUntuk pembayaran silahkan langsung klik link berikut\nhttps://dev.winnyputrilubis.id/home/invoice?inv=aTo1MTs=\n				', '', NULL, '2021-12-05 20:42:15', 0, '0000-00-00 00:00:00'),
(711, 0, 1, 'hello@winnyputrilubis.id', 'Winny Putri Lubis - Pesanan Baru', '\n					<h3>Pesanan Baru</h3><br/>\n					<b>DIDIHAN</b> telah membuat pesanan baru dengan total pembayaran \n					<b>Rp. 180.665</b> Invoice ID: <b>#2021120551665</b>\n					<br/>&nbsp;<br/>&nbsp;<br/>\n					Cek Pesanan Pembeli di <b>Dashboard Admin Winny Putri Lubis</b><br/>\n					<a href=\'https://dev.winnyputrilubis.id/cdn\'>Klik Disini</a>\n				', 'Pesanan Baru di Winny Putri Lubis', NULL, '2021-12-05 20:42:15', 0, '0000-00-00 00:00:00'),
(712, 0, 2, '+62-82111', '', '\n					*Pesanan Baru*\n*DIDIHAN* telah membuat pesanan baru dengan detail:\nTotal Pembayaran: *Rp. 180.665*\nInvoice ID: *#2021120551665*\n \nCek Pesanan Pembeli di *Dashboard Admin Winny Putri Lubis*\n					', '', NULL, '2021-12-05 20:42:15', 0, '0000-00-00 00:00:00'),
(713, 0, 1, 'didihansya@foreskin.market', 'Winny Putri Lubis - Pesanan', '\n					Halo <b>Didihan</b><br/>Terimakasih sudah membeli produk kami.<br/>Saat ini kami sedang menunggu pembayaran darimu sebelum kami memprosesnya. Sebagai informasi, berikut detail pesananmu <br/>No Invoice: <b>#2021120552715</b><br/> <br/>Total Pesanan: <b>Rp 200.715</b><br/>Ongkos Kirim: <b>Rp 0</b><br/>Kurir Pengiriman: <b>LAIN </b><br/> <br/>Detail Pengiriman <br/>Penerima: <b>Didi</b> <br/>No HP: <b>082222222</b> <br/>Alamat: <b>Jl Karya</b><br/> <br/>Untuk pembayaran silahkan langsung klik link berikut:<br/><a href=\'https://dev.winnyputrilubis.id/home/invoice?inv=aTo1Mjs=\'>Bayar Pesanan Sekarang &raquo;</a>\n				', 'Pesanan', NULL, '2021-12-05 20:44:13', 0, '0000-00-00 00:00:00'),
(714, 0, 2, '08122222222222', '', '\n					Halo *Didihan*\nTerimakasih sudah membeli produk kami.\nSaat ini kami sedang menunggu pembayaran darimu sebelum kami memprosesnya. Sebagai informasi, berikut detail pesananmu \n \nNo Invoice: *#2021120552715*\nTotal Pesanan: *Rp 200.715*\nOngkos Kirim: *Rp 0*\nKurir Pengiriman: *LAIN *\n \nDetail Pengiriman \nPenerima: *Didi*\nNo HP: *082222222*\nAlamat: *Jl Karya*\n \nUntuk pembayaran silahkan langsung klik link berikut\nhttps://dev.winnyputrilubis.id/home/invoice?inv=aTo1Mjs=\n				', '', NULL, '2021-12-05 20:44:13', 0, '0000-00-00 00:00:00'),
(715, 0, 1, 'hello@winnyputrilubis.id', 'Winny Putri Lubis - Pesanan Baru', '\n					<h3>Pesanan Baru</h3><br/>\n					<b>DIDIHAN</b> telah membuat pesanan baru dengan total pembayaran \n					<b>Rp. 200.715</b> Invoice ID: <b>#2021120552715</b>\n					<br/>&nbsp;<br/>&nbsp;<br/>\n					Cek Pesanan Pembeli di <b>Dashboard Admin Winny Putri Lubis</b><br/>\n					<a href=\'https://dev.winnyputrilubis.id/cdn\'>Klik Disini</a>\n				', 'Pesanan Baru di Winny Putri Lubis', NULL, '2021-12-05 20:44:13', 0, '0000-00-00 00:00:00'),
(716, 0, 2, '+62-82111', '', '\n					*Pesanan Baru*\n*DIDIHAN* telah membuat pesanan baru dengan detail:\nTotal Pembayaran: *Rp. 200.715*\nInvoice ID: *#2021120552715*\n \nCek Pesanan Pembeli di *Dashboard Admin Winny Putri Lubis*\n					', '', NULL, '2021-12-05 20:44:13', 0, '0000-00-00 00:00:00'),
(717, 0, 1, 'didihansya@foreskin.market', 'Winny Putri Lubis - Pesanan', '\n					Halo <b>Didihan</b><br/>Terimakasih sudah membeli produk kami.<br/>Saat ini kami sedang menunggu pembayaran darimu sebelum kami memprosesnya. Sebagai informasi, berikut detail pesananmu <br/>No Invoice: <b>#2021120653895</b><br/> <br/>Total Pesanan: <b>Rp 197.895</b><br/>Ongkos Kirim: <b>Rp 0</b><br/>Diskon: <b>Rp 3.000</b><br/>Kurir Pengiriman: <b>LAIN </b><br/> <br/>Detail Pengiriman <br/>Penerima: <b>Didi</b> <br/>No HP: <b>082222222</b> <br/>Alamat: <b>Jl Karya</b><br/> <br/>Untuk pembayaran silahkan langsung klik link berikut:<br/><a href=\'https://dev.winnyputrilubis.id/home/invoice?inv=aTo1Mzs=\'>Bayar Pesanan Sekarang &raquo;</a>\n				', 'Pesanan', NULL, '2021-12-06 19:09:32', 0, '0000-00-00 00:00:00'),
(718, 0, 2, '08122222222222', '', '\n					Halo *Didihan*\nTerimakasih sudah membeli produk kami.\nSaat ini kami sedang menunggu pembayaran darimu sebelum kami memprosesnya. Sebagai informasi, berikut detail pesananmu \n \nNo Invoice: *#2021120653895*\nTotal Pesanan: *Rp 197.895*\nOngkos Kirim: *Rp 0*\nDiskon: *Rp 3.000*\nKurir Pengiriman: *LAIN *\n \nDetail Pengiriman \nPenerima: *Didi*\nNo HP: *082222222*\nAlamat: *Jl Karya*\n \nUntuk pembayaran silahkan langsung klik link berikut\nhttps://dev.winnyputrilubis.id/home/invoice?inv=aTo1Mzs=\n				', '', NULL, '2021-12-06 19:09:32', 0, '0000-00-00 00:00:00'),
(719, 0, 1, 'hello@winnyputrilubis.id', 'Winny Putri Lubis - Pesanan Baru', '\n					<h3>Pesanan Baru</h3><br/>\n					<b>DIDIHAN</b> telah membuat pesanan baru dengan total pembayaran \n					<b>Rp. 197.895</b> Invoice ID: <b>#2021120653895</b>\n					<br/>&nbsp;<br/>&nbsp;<br/>\n					Cek Pesanan Pembeli di <b>Dashboard Admin Winny Putri Lubis</b><br/>\n					<a href=\'https://dev.winnyputrilubis.id/cdn\'>Klik Disini</a>\n				', 'Pesanan Baru di Winny Putri Lubis', NULL, '2021-12-06 19:09:32', 0, '0000-00-00 00:00:00'),
(720, 0, 2, '+62-82111', '', '\n					*Pesanan Baru*\n*DIDIHAN* telah membuat pesanan baru dengan detail:\nTotal Pembayaran: *Rp. 197.895*\nInvoice ID: *#2021120653895*\n \nCek Pesanan Pembeli di *Dashboard Admin Winny Putri Lubis*\n					', '', NULL, '2021-12-06 19:09:32', 0, '0000-00-00 00:00:00'),
(721, 0, 1, 'didi@foreskin.market', 'Winny Putri Lubis - Pesanan', '\n					Halo <b>Didi</b><br/>Terimakasih sudah membeli produk kami.<br/>Saat ini kami sedang menunggu pembayaran darimu sebelum kami memprosesnya. Sebagai informasi, berikut detail pesananmu <br/>No Invoice: <b>#2021120654130</b><br/> <br/>Total Pesanan: <b>Rp 232.130</b><br/>Ongkos Kirim: <b>Rp 7.000</b><br/>Diskon: <b>Rp 20.000</b><br/>Kurir Pengiriman: <b>JNE CTC</b><br/> <br/>Detail Pengiriman <br/>Penerima: <b>Didi</b> <br/>No HP: <b>08121121221</b> <br/>Alamat: <b>JL Eka Surya</b><br/> <br/>Untuk pembayaran silahkan langsung klik link berikut:<br/><a href=\'https://dev.winnyputrilubis.id/home/invoice?inv=aTo1NDs=\'>Bayar Pesanan Sekarang &raquo;</a>\n				', 'Pesanan', NULL, '2021-12-06 19:14:09', 0, '0000-00-00 00:00:00'),
(722, 0, 2, '088128312312312', '', '\n					Halo *Didi*\nTerimakasih sudah membeli produk kami.\nSaat ini kami sedang menunggu pembayaran darimu sebelum kami memprosesnya. Sebagai informasi, berikut detail pesananmu \n \nNo Invoice: *#2021120654130*\nTotal Pesanan: *Rp 232.130*\nOngkos Kirim: *Rp 7.000*\nDiskon: *Rp 20.000*\nKurir Pengiriman: *JNE CTC*\n \nDetail Pengiriman \nPenerima: *Didi*\nNo HP: *08121121221*\nAlamat: *JL Eka Surya*\n \nUntuk pembayaran silahkan langsung klik link berikut\nhttps://dev.winnyputrilubis.id/home/invoice?inv=aTo1NDs=\n				', '', NULL, '2021-12-06 19:14:09', 0, '0000-00-00 00:00:00'),
(723, 0, 1, 'hello@winnyputrilubis.id', 'Winny Putri Lubis - Pesanan Baru', '\n					<h3>Pesanan Baru</h3><br/>\n					<b>DIDI</b> telah membuat pesanan baru dengan total pembayaran \n					<b>Rp. 232.130</b> Invoice ID: <b>#2021120654130</b>\n					<br/>&nbsp;<br/>&nbsp;<br/>\n					Cek Pesanan Pembeli di <b>Dashboard Admin Winny Putri Lubis</b><br/>\n					<a href=\'https://dev.winnyputrilubis.id/cdn\'>Klik Disini</a>\n				', 'Pesanan Baru di Winny Putri Lubis', NULL, '2021-12-06 19:14:10', 0, '0000-00-00 00:00:00'),
(724, 0, 2, '+62-82111', '', '\n					*Pesanan Baru*\n*DIDI* telah membuat pesanan baru dengan detail:\nTotal Pembayaran: *Rp. 232.130*\nInvoice ID: *#2021120654130*\n \nCek Pesanan Pembeli di *Dashboard Admin Winny Putri Lubis*\n					', '', NULL, '2021-12-06 19:14:10', 0, '0000-00-00 00:00:00'),
(725, 0, 1, 'fnz1@gmail.com', 'Winny Putri Lubis', '\n				<html>\n				<head>\n					<style>\n					.border{padding:20px;border-radius:3px;margin:auto;}\n					.pesan{margin-bottom:30px;}\n					.link{margin-bottom:20px;}\n					.alink{text-decoration:none;background:#c0392b;padding:10px 24px;border-radius:3px;margin-bottom:20px;}\n					</style>\n				</head>\n				<body>\n					<div class=\"border\">\n					<div class=\"pesan\">\n					<h3>Halo, fitria novelisa zega  </h3><p/>\n					Terima kasih sudah mendaftar di <b>Winny Putri Lubis</b>, untuk mengaktifkan akun Anda, silahkan klik link berikut:<br/>\n					</div>\n					<div class=\"link\">\n						<a class=\"alink\" style=\"color:#fff;\" href=\"https://dev.winnyputrilubis.id/auth/signup?verify=YToyOntzOjI6ImlkIjtpOjQ4O3M6NDoidGltZSI7czoxNDoiMjAyMTEyMDYyMDE2MzciO30=\">VERIFIKASI AKUN WINNY PUTRI LUBIS</a>\n						<br/>&nbsp;<br/>atau link dibawah ini<br/>\n						<a href=\"https://dev.winnyputrilubis.id/auth/signup?verify=YToyOntzOjI6ImlkIjtpOjQ4O3M6NDoidGltZSI7czoxNDoiMjAyMTEyMDYyMDE2MzciO30=\">https://dev.winnyputrilubis.id/auth/signup?verify=YToyOntzOjI6ImlkIjtpOjQ4O3M6NDoidGltZSI7czoxNDoiMjAyMTEyMDYyMDE2MzciO30=</a>\n					</div>\n					</div>\n				</body>\n				</html>\n			', 'Verifikasi Pendaftaran Winny Putri Lubis', NULL, '2021-12-06 20:16:37', 0, '0000-00-00 00:00:00'),
(726, 0, 2, '085275152838', '', '\n				Halo, *fitria novelisa zega  * \\n \\nTerima kasih sudah mendaftar di *Winny Putri Lubis*, untuk mengaktifkan akun Anda, silahkan klik link berikut:\\nhttps://dev.winnyputrilubis.id/auth/signup?verify=YToyOntzOjI6ImlkIjtpOjQ4O3M6NDoidGltZSI7czoxNDoiMjAyMTEyMDYyMDE2MzciO30= \\n_*Apabila link tidak bisa di klik, simpan nomer whatsapp ini terlebih dahulu_\n			', '', NULL, '2021-12-06 20:16:37', 0, '0000-00-00 00:00:00'),
(727, 0, 1, 'fnz1@gmail.com', 'Winny Putri Lubis - Pesanan', '\n					Halo <b>fitria novelisa zega  </b><br/>Terimakasih sudah membeli produk kami.<br/>Saat ini kami sedang menunggu pembayaran darimu sebelum kami memprosesnya. Sebagai informasi, berikut detail pesananmu <br/>No Invoice: <b>#2021120655523</b><br/> <br/>Total Pesanan: <b>Rp 245.523</b><br/>Ongkos Kirim: <b>Rp 0</b><br/>Kurir Pengiriman: <b>GOSEND </b><br/> <br/>Detail Pengiriman <br/>Penerima: <b>fitria</b> <br/>No HP: <b>085275152838</b> <br/>Alamat: <b>jln. pukat banting 5 no  30  </b><br/> <br/>Untuk pembayaran silahkan langsung klik link berikut:<br/><a href=\'https://dev.winnyputrilubis.id/home/invoice?inv=aTo1NTs=\'>Bayar Pesanan Sekarang &raquo;</a>\n				', 'Pesanan', NULL, '2021-12-06 20:29:55', 0, '0000-00-00 00:00:00'),
(728, 0, 2, '085275152838', '', '\n					Halo *fitria novelisa zega  *\nTerimakasih sudah membeli produk kami.\nSaat ini kami sedang menunggu pembayaran darimu sebelum kami memprosesnya. Sebagai informasi, berikut detail pesananmu \n \nNo Invoice: *#2021120655523*\nTotal Pesanan: *Rp 245.523*\nOngkos Kirim: *Rp 0*\nKurir Pengiriman: *GOSEND *\n \nDetail Pengiriman \nPenerima: *fitria*\nNo HP: *085275152838*\nAlamat: *jln. pukat banting 5 no  30  *\n \nUntuk pembayaran silahkan langsung klik link berikut\nhttps://dev.winnyputrilubis.id/home/invoice?inv=aTo1NTs=\n				', '', NULL, '2021-12-06 20:29:55', 0, '0000-00-00 00:00:00'),
(729, 0, 1, 'hello@winnyputrilubis.id', 'Winny Putri Lubis - Pesanan Baru', '\n					<h3>Pesanan Baru</h3><br/>\n					<b>FITRIA NOVELISA ZEGA  </b> telah membuat pesanan baru dengan total pembayaran \n					<b>Rp. 245.523</b> Invoice ID: <b>#2021120655523</b>\n					<br/>&nbsp;<br/>&nbsp;<br/>\n					Cek Pesanan Pembeli di <b>Dashboard Admin Winny Putri Lubis</b><br/>\n					<a href=\'https://dev.winnyputrilubis.id/cdn\'>Klik Disini</a>\n				', 'Pesanan Baru di Winny Putri Lubis', NULL, '2021-12-06 20:29:55', 0, '0000-00-00 00:00:00'),
(730, 0, 2, '+62-82111', '', '\n					*Pesanan Baru*\n*FITRIA NOVELISA ZEGA  * telah membuat pesanan baru dengan detail:\nTotal Pembayaran: *Rp. 245.523*\nInvoice ID: *#2021120655523*\n \nCek Pesanan Pembeli di *Dashboard Admin Winny Putri Lubis*\n					', '', NULL, '2021-12-06 20:29:55', 0, '0000-00-00 00:00:00'),
(731, 0, 1, 'maypku1@gmail.com', 'Winny Putri Lubis', '\n				<html>\n				<head>\n					<style>\n					.border{padding:20px;border-radius:3px;margin:auto;}\n					.pesan{margin-bottom:30px;}\n					.link{margin-bottom:20px;}\n					.alink{text-decoration:none;background:#c0392b;padding:10px 24px;border-radius:3px;margin-bottom:20px;}\n					</style>\n				</head>\n				<body>\n					<div class=\"border\">\n					<div class=\"pesan\">\n					<h3>Halo, maypku</h3><p/>\n					Terima kasih sudah mendaftar di <b>Winny Putri Lubis</b>, untuk mengaktifkan akun Anda, silahkan klik link berikut:<br/>\n					</div>\n					<div class=\"link\">\n						<a class=\"alink\" style=\"color:#fff;\" href=\"https://dev.winnyputrilubis.id/auth/signup?verify=YToyOntzOjI6ImlkIjtpOjQ5O3M6NDoidGltZSI7czoxNDoiMjAyMTEyMDYyMDM1MzIiO30=\">VERIFIKASI AKUN WINNY PUTRI LUBIS</a>\n						<br/>&nbsp;<br/>atau link dibawah ini<br/>\n						<a href=\"https://dev.winnyputrilubis.id/auth/signup?verify=YToyOntzOjI6ImlkIjtpOjQ5O3M6NDoidGltZSI7czoxNDoiMjAyMTEyMDYyMDM1MzIiO30=\">https://dev.winnyputrilubis.id/auth/signup?verify=YToyOntzOjI6ImlkIjtpOjQ5O3M6NDoidGltZSI7czoxNDoiMjAyMTEyMDYyMDM1MzIiO30=</a>\n					</div>\n					</div>\n				</body>\n				</html>\n			', 'Verifikasi Pendaftaran Winny Putri Lubis', NULL, '2021-12-06 20:35:32', 0, '0000-00-00 00:00:00'),
(732, 0, 2, '085275152838', '', '\n				Halo, *maypku* \\n \\nTerima kasih sudah mendaftar di *Winny Putri Lubis*, untuk mengaktifkan akun Anda, silahkan klik link berikut:\\nhttps://dev.winnyputrilubis.id/auth/signup?verify=YToyOntzOjI6ImlkIjtpOjQ5O3M6NDoidGltZSI7czoxNDoiMjAyMTEyMDYyMDM1MzIiO30= \\n_*Apabila link tidak bisa di klik, simpan nomer whatsapp ini terlebih dahulu_\n			', '', NULL, '2021-12-06 20:35:32', 0, '0000-00-00 00:00:00'),
(733, 0, 1, 'maypku1@gmail.com', 'Winny Putri Lubis - Pesanan', '\n					Halo <b>maypku</b><br/>Terimakasih sudah membeli produk kami.<br/>Saat ini kami sedang menunggu pembayaran darimu sebelum kami memprosesnya. Sebagai informasi, berikut detail pesananmu <br/>No Invoice: <b>#2021120656866</b><br/> <br/>Total Pesanan: <b>Rp 4.040.866</b><br/>Ongkos Kirim: <b>Rp 0</b><br/>Kurir Pengiriman: <b>LAIN </b><br/> <br/>Detail Pengiriman <br/>Penerima: <b>maypku</b> <br/>No HP: <b>085275152838</b> <br/>Alamat: <b>jln. pukat banting 5 no  30  </b><br/> <br/>Untuk pembayaran silahkan langsung klik link berikut:<br/><a href=\'https://dev.winnyputrilubis.id/home/invoice?inv=aTo1Njs=\'>Bayar Pesanan Sekarang &raquo;</a>\n				', 'Pesanan', NULL, '2021-12-06 21:00:21', 0, '0000-00-00 00:00:00'),
(734, 0, 2, '085275152838', '', '\n					Halo *maypku*\nTerimakasih sudah membeli produk kami.\nSaat ini kami sedang menunggu pembayaran darimu sebelum kami memprosesnya. Sebagai informasi, berikut detail pesananmu \n \nNo Invoice: *#2021120656866*\nTotal Pesanan: *Rp 4.040.866*\nOngkos Kirim: *Rp 0*\nKurir Pengiriman: *LAIN *\n \nDetail Pengiriman \nPenerima: *maypku*\nNo HP: *085275152838*\nAlamat: *jln. pukat banting 5 no  30  *\n \nUntuk pembayaran silahkan langsung klik link berikut\nhttps://dev.winnyputrilubis.id/home/invoice?inv=aTo1Njs=\n				', '', NULL, '2021-12-06 21:00:21', 0, '0000-00-00 00:00:00'),
(735, 0, 1, 'hello@winnyputrilubis.id', 'Winny Putri Lubis - Pesanan Baru', '\n					<h3>Pesanan Baru</h3><br/>\n					<b>MAYPKU</b> telah membuat pesanan baru dengan total pembayaran \n					<b>Rp. 4.040.866</b> Invoice ID: <b>#2021120656866</b>\n					<br/>&nbsp;<br/>&nbsp;<br/>\n					Cek Pesanan Pembeli di <b>Dashboard Admin Winny Putri Lubis</b><br/>\n					<a href=\'https://dev.winnyputrilubis.id/cdn\'>Klik Disini</a>\n				', 'Pesanan Baru di Winny Putri Lubis', NULL, '2021-12-06 21:00:22', 0, '0000-00-00 00:00:00'),
(736, 0, 2, '+62-82111', '', '\n					*Pesanan Baru*\n*MAYPKU* telah membuat pesanan baru dengan detail:\nTotal Pembayaran: *Rp. 4.040.866*\nInvoice ID: *#2021120656866*\n \nCek Pesanan Pembeli di *Dashboard Admin Winny Putri Lubis*\n					', '', NULL, '2021-12-06 21:00:22', 0, '0000-00-00 00:00:00'),
(737, 0, 1, 'maypku1@gmail.com', 'Winny Putri Lubis - Resi Pengiriman Pesanan', '\r\n					Berikut resi pengiriman untuk pesanan anda di <b>Winny Putri Lubis</b><br/>\r\n					Resi: <b style=\'font-size:120%\'>1231231231232</b><br/>&nbsp;<br/>&nbsp;<br/>\r\n					Lacak pengirimannya langsung di menu <b>pesananku</b><br/>\r\n					<a href=\'https://localhost/botoko/account/order\'>Klik Disini</a>\r\n				', 'Resi Pengiriman', NULL, '2021-12-06 21:04:20', 0, '0000-00-00 00:00:00'),
(738, 0, 2, '085275152838', '', '\r\n					Berikut resi pengiriman untuk pesanan anda di *Winny Putri Lubis* \nResi: *1231231231232* \n \nLacak pengirimannya langsung di menu *pesananku* \n https://localhost/botoko/account/order\r\n				', '', NULL, '2021-12-06 21:04:20', 0, '0000-00-00 00:00:00'),
(739, 0, 1, 'fnz1@gmail.com', 'Winny Putri Lubis - Resi Pengiriman Pesanan', '\r\n					Berikut resi pengiriman untuk pesanan anda di <b>Winny Putri Lubis</b><br/>\r\n					Resi: <b style=\'font-size:120%\'>BK123XZ-0813212312312</b><br/>&nbsp;<br/>&nbsp;<br/>\r\n					Lacak pengirimannya langsung di menu <b>pesananku</b><br/>\r\n					<a href=\'https://localhost/botoko/account/order\'>Klik Disini</a>\r\n				', 'Resi Pengiriman', NULL, '2021-12-06 21:07:17', 0, '0000-00-00 00:00:00'),
(740, 0, 2, '085275152838', '', '\r\n					Berikut resi pengiriman untuk pesanan anda di *Winny Putri Lubis* \nResi: *BK123XZ-0813212312312* \n \nLacak pengirimannya langsung di menu *pesananku* \n https://localhost/botoko/account/order\r\n				', '', NULL, '2021-12-06 21:07:17', 0, '0000-00-00 00:00:00'),
(741, 0, 1, 'didihansya@foreskin.market', 'Winny Putri Lubis - Pesanan', '\n					Halo <b>Didihan</b><br/>Terimakasih sudah membeli produk kami.<br/>Saat ini kami sedang menunggu pembayaran darimu sebelum kami memprosesnya. Sebagai informasi, berikut detail pesananmu <br/>No Invoice: <b>#2021120657117</b><br/> <br/>Total Pesanan: <b>Rp 0</b><br/>Ongkos Kirim: <b>Rp 0</b><br/>Kurir Pengiriman: <b>LAIN </b><br/> <br/>Detail Pengiriman <br/>Penerima: <b>Didi</b> <br/>No HP: <b>082222222</b> <br/>Alamat: <b>Jl Karya</b><br/> <br/>Untuk pembayaran silahkan langsung klik link berikut:<br/><a href=\'https://dev.winnyputrilubis.id/home/invoice?inv=aTo1Nzs=\'>Bayar Pesanan Sekarang &raquo;</a>\n				', 'Pesanan', NULL, '2021-12-06 21:09:21', 0, '0000-00-00 00:00:00'),
(742, 0, 2, '08122222222222', '', '\n					Halo *Didihan*\nTerimakasih sudah membeli produk kami.\nSaat ini kami sedang menunggu pembayaran darimu sebelum kami memprosesnya. Sebagai informasi, berikut detail pesananmu \n \nNo Invoice: *#2021120657117*\nTotal Pesanan: *Rp 0*\nOngkos Kirim: *Rp 0*\nKurir Pengiriman: *LAIN *\n \nDetail Pengiriman \nPenerima: *Didi*\nNo HP: *082222222*\nAlamat: *Jl Karya*\n \nUntuk pembayaran silahkan langsung klik link berikut\nhttps://dev.winnyputrilubis.id/home/invoice?inv=aTo1Nzs=\n				', '', NULL, '2021-12-06 21:09:21', 0, '0000-00-00 00:00:00'),
(743, 0, 1, 'hello@winnyputrilubis.id', 'Winny Putri Lubis - Pesanan Baru', '\n					<h3>Pesanan Baru</h3><br/>\n					<b>DIDIHAN</b> telah membuat pesanan baru dengan total pembayaran \n					<b>Rp. 0</b> Invoice ID: <b>#2021120657117</b>\n					<br/>&nbsp;<br/>&nbsp;<br/>\n					Cek Pesanan Pembeli di <b>Dashboard Admin Winny Putri Lubis</b><br/>\n					<a href=\'https://dev.winnyputrilubis.id/cdn\'>Klik Disini</a>\n				', 'Pesanan Baru di Winny Putri Lubis', NULL, '2021-12-06 21:09:22', 0, '0000-00-00 00:00:00'),
(744, 0, 2, '+62-82111', '', '\n					*Pesanan Baru*\n*DIDIHAN* telah membuat pesanan baru dengan detail:\nTotal Pembayaran: *Rp. 0*\nInvoice ID: *#2021120657117*\n \nCek Pesanan Pembeli di *Dashboard Admin Winny Putri Lubis*\n					', '', NULL, '2021-12-06 21:09:22', 0, '0000-00-00 00:00:00'),
(745, 0, 1, 'didi@foreskin.market', 'Winny Putri Lubis - Pesanan', '\n					Halo <b>Didi</b><br/>Terimakasih sudah membeli produk kami.<br/>Saat ini kami sedang menunggu pembayaran darimu sebelum kami memprosesnya. Sebagai informasi, berikut detail pesananmu <br/>No Invoice: <b>#2021120658607</b><br/> <br/>Total Pesanan: <b>Rp 57.607</b><br/>Ongkos Kirim: <b>Rp 7.000</b><br/>Kurir Pengiriman: <b>JNE CTC</b><br/> <br/>Detail Pengiriman <br/>Penerima: <b>Didi</b> <br/>No HP: <b>08121121221</b> <br/>Alamat: <b>JL Eka Surya</b><br/> <br/>Untuk pembayaran silahkan langsung klik link berikut:<br/><a href=\'https://dev.winnyputrilubis.id/home/invoice?inv=aTo1ODs=\'>Bayar Pesanan Sekarang &raquo;</a>\n				', 'Pesanan', NULL, '2021-12-06 21:11:04', 0, '0000-00-00 00:00:00'),
(746, 0, 2, '088128312312312', '', '\n					Halo *Didi*\nTerimakasih sudah membeli produk kami.\nSaat ini kami sedang menunggu pembayaran darimu sebelum kami memprosesnya. Sebagai informasi, berikut detail pesananmu \n \nNo Invoice: *#2021120658607*\nTotal Pesanan: *Rp 57.607*\nOngkos Kirim: *Rp 7.000*\nKurir Pengiriman: *JNE CTC*\n \nDetail Pengiriman \nPenerima: *Didi*\nNo HP: *08121121221*\nAlamat: *JL Eka Surya*\n \nUntuk pembayaran silahkan langsung klik link berikut\nhttps://dev.winnyputrilubis.id/home/invoice?inv=aTo1ODs=\n				', '', NULL, '2021-12-06 21:11:04', 0, '0000-00-00 00:00:00'),
(747, 0, 1, 'hello@winnyputrilubis.id', 'Winny Putri Lubis - Pesanan Baru', '\n					<h3>Pesanan Baru</h3><br/>\n					<b>DIDI</b> telah membuat pesanan baru dengan total pembayaran \n					<b>Rp. 57.607</b> Invoice ID: <b>#2021120658607</b>\n					<br/>&nbsp;<br/>&nbsp;<br/>\n					Cek Pesanan Pembeli di <b>Dashboard Admin Winny Putri Lubis</b><br/>\n					<a href=\'https://dev.winnyputrilubis.id/cdn\'>Klik Disini</a>\n				', 'Pesanan Baru di Winny Putri Lubis', NULL, '2021-12-06 21:11:05', 0, '0000-00-00 00:00:00'),
(748, 0, 2, '+62-82111', '', '\n					*Pesanan Baru*\n*DIDI* telah membuat pesanan baru dengan detail:\nTotal Pembayaran: *Rp. 57.607*\nInvoice ID: *#2021120658607*\n \nCek Pesanan Pembeli di *Dashboard Admin Winny Putri Lubis*\n					', '', NULL, '2021-12-06 21:11:05', 0, '0000-00-00 00:00:00'),
(749, 0, 1, 'didi@foreskin.market', 'Winny Putri Lubis - Resi Pengiriman Pesanan', '\r\n					Berikut resi pengiriman untuk pesanan anda di <b>Winny Putri Lubis</b><br/>\r\n					Resi: <b style=\'font-size:120%\'>JNEW2123123</b><br/>&nbsp;<br/>&nbsp;<br/>\r\n					Lacak pengirimannya langsung di menu <b>pesananku</b><br/>\r\n					<a href=\'https://localhost/botoko/account/order\'>Klik Disini</a>\r\n				', 'Resi Pengiriman', NULL, '2021-12-06 21:13:06', 0, '0000-00-00 00:00:00'),
(750, 0, 2, '088128312312312', '', '\r\n					Berikut resi pengiriman untuk pesanan anda di *Winny Putri Lubis* \nResi: *JNEW2123123* \n \nLacak pengirimannya langsung di menu *pesananku* \n https://localhost/botoko/account/order\r\n				', '', NULL, '2021-12-06 21:13:06', 0, '0000-00-00 00:00:00'),
(751, 0, 1, 'rhazaaqbaim@gmail.com', 'Winny Putri Lubis - Pesanan', '\n					Halo <b>siro</b><br/>Terimakasih sudah membeli produk kami.<br/>Saat ini kami sedang menunggu pembayaran darimu sebelum kami memprosesnya. Sebagai informasi, berikut detail pesananmu <br/>No Invoice: <b>#2021121559666</b><br/> <br/>Total Pesanan: <b>Rp 105.666</b><br/>Ongkos Kirim: <b>Rp 10.000</b><br/>Kurir Pengiriman: <b>JNE CTCYES</b><br/> <br/>Detail Pengiriman <br/>Penerima: <b>siro</b> <br/>No HP: <b>085275206878</b> <br/>Alamat: <b>jl b katamso gg besi no 3</b><br/> <br/>Untuk pembayaran silahkan langsung klik link berikut:<br/><a href=\'https://dev.winnyputrilubis.id/home/invoice?inv=aTo1OTs=\'>Bayar Pesanan Sekarang &raquo;</a>\n				', 'Pesanan', NULL, '2021-12-15 20:27:25', 0, '0000-00-00 00:00:00'),
(752, 0, 2, '087877777772', '', '\n					Halo *siro*\nTerimakasih sudah membeli produk kami.\nSaat ini kami sedang menunggu pembayaran darimu sebelum kami memprosesnya. Sebagai informasi, berikut detail pesananmu \n \nNo Invoice: *#2021121559666*\nTotal Pesanan: *Rp 105.666*\nOngkos Kirim: *Rp 10.000*\nKurir Pengiriman: *JNE CTCYES*\n \nDetail Pengiriman \nPenerima: *siro*\nNo HP: *085275206878*\nAlamat: *jl b katamso gg besi no 3*\n \nUntuk pembayaran silahkan langsung klik link berikut\nhttps://dev.winnyputrilubis.id/home/invoice?inv=aTo1OTs=\n				', '', NULL, '2021-12-15 20:27:25', 0, '0000-00-00 00:00:00'),
(753, 0, 1, 'hello@winnyputrilubis.id', 'Winny Putri Lubis - Pesanan Baru', '\n					<h3>Pesanan Baru</h3><br/>\n					<b>SIRO</b> telah membuat pesanan baru dengan total pembayaran \n					<b>Rp. 105.666</b> Invoice ID: <b>#2021121559666</b>\n					<br/>&nbsp;<br/>&nbsp;<br/>\n					Cek Pesanan Pembeli di <b>Dashboard Admin Winny Putri Lubis</b><br/>\n					<a href=\'https://dev.winnyputrilubis.id/cdn\'>Klik Disini</a>\n				', 'Pesanan Baru di Winny Putri Lubis', NULL, '2021-12-15 20:27:25', 0, '0000-00-00 00:00:00'),
(754, 0, 2, '+62-82111', '', '\n					*Pesanan Baru*\n*SIRO* telah membuat pesanan baru dengan detail:\nTotal Pembayaran: *Rp. 105.666*\nInvoice ID: *#2021121559666*\n \nCek Pesanan Pembeli di *Dashboard Admin Winny Putri Lubis*\n					', '', NULL, '2021-12-15 20:27:25', 0, '0000-00-00 00:00:00'),
(755, 0, 1, 'rhazaaqbaim@gmail.com', 'Winny Putri Lubis - Resi Pengiriman Pesanan', '\r\n					Berikut resi pengiriman untuk pesanan anda di <b>Winny Putri Lubis</b><br/>\r\n					Resi: <b style=\'font-size:120%\'>12345</b><br/>&nbsp;<br/>&nbsp;<br/>\r\n					Lacak pengirimannya langsung di menu <b>pesananku</b><br/>\r\n					<a href=\'https://localhost/botoko/account/order\'>Klik Disini</a>\r\n				', 'Resi Pengiriman', NULL, '2021-12-15 20:29:51', 0, '0000-00-00 00:00:00'),
(756, 0, 2, '087877777772', '', '\r\n					Berikut resi pengiriman untuk pesanan anda di *Winny Putri Lubis* \nResi: *12345* \n \nLacak pengirimannya langsung di menu *pesananku* \n https://localhost/botoko/account/order\r\n				', '', NULL, '2021-12-15 20:29:51', 0, '0000-00-00 00:00:00'),
(757, 0, 1, 'didihansya@foreskin.market', 'Winny Putri Lubis - Pesanan', '\n					Halo <b>Didihan</b><br/>Terimakasih sudah membeli produk kami.<br/>Saat ini kami sedang menunggu pembayaran darimu sebelum kami memprosesnya. Sebagai informasi, berikut detail pesananmu <br/>No Invoice: <b>#2021122260130</b><br/> <br/>Total Pesanan: <b>Rp 234.130</b><br/>Ongkos Kirim: <b>Rp 34.000</b><br/>Kurir Pengiriman: <b>SENTRAL UDARA NON ELEKTRONIK</b><br/> <br/>Detail Pengiriman <br/>Penerima: <b>Didi</b> <br/>No HP: <b>082222222</b> <br/>Alamat: <b>Jl Karya</b><br/> <br/>Untuk pembayaran silahkan langsung klik link berikut:<br/><a href=\'https://dev.winnyputrilubis.id/home/invoice?inv=aTo2MDs=\'>Bayar Pesanan Sekarang &raquo;</a>\n				', 'Pesanan', NULL, '2021-12-22 10:22:09', 0, '0000-00-00 00:00:00'),
(758, 0, 2, '08122222222222', '', '\n					Halo *Didihan*\nTerimakasih sudah membeli produk kami.\nSaat ini kami sedang menunggu pembayaran darimu sebelum kami memprosesnya. Sebagai informasi, berikut detail pesananmu \n \nNo Invoice: *#2021122260130*\nTotal Pesanan: *Rp 234.130*\nOngkos Kirim: *Rp 34.000*\nKurir Pengiriman: *SENTRAL UDARA NON ELEKTRONIK*\n \nDetail Pengiriman \nPenerima: *Didi*\nNo HP: *082222222*\nAlamat: *Jl Karya*\n \nUntuk pembayaran silahkan langsung klik link berikut\nhttps://dev.winnyputrilubis.id/home/invoice?inv=aTo2MDs=\n				', '', NULL, '2021-12-22 10:22:09', 0, '0000-00-00 00:00:00'),
(759, 0, 1, 'hello@winnyputrilubis.id', 'Winny Putri Lubis - Pesanan Baru', '\n					<h3>Pesanan Baru</h3><br/>\n					<b>DIDIHAN</b> telah membuat pesanan baru dengan total pembayaran \n					<b>Rp. 234.130</b> Invoice ID: <b>#2021122260130</b>\n					<br/>&nbsp;<br/>&nbsp;<br/>\n					Cek Pesanan Pembeli di <b>Dashboard Admin Winny Putri Lubis</b><br/>\n					<a href=\'https://dev.winnyputrilubis.id/cdn\'>Klik Disini</a>\n				', 'Pesanan Baru di Winny Putri Lubis', NULL, '2021-12-22 10:22:10', 0, '0000-00-00 00:00:00'),
(760, 0, 2, '+62-82111', '', '\n					*Pesanan Baru*\n*DIDIHAN* telah membuat pesanan baru dengan detail:\nTotal Pembayaran: *Rp. 234.130*\nInvoice ID: *#2021122260130*\n \nCek Pesanan Pembeli di *Dashboard Admin Winny Putri Lubis*\n					', '', NULL, '2021-12-22 10:22:10', 0, '0000-00-00 00:00:00'),
(761, 0, 1, 'didihansya@foreskin.market', 'Winny Putri Lubis - Resi Pengiriman Pesanan', '\r\n					Berikut resi pengiriman untuk pesanan anda di <b>Winny Putri Lubis</b><br/>\r\n					Resi: <b style=\'font-size:120%\'>F12412414</b><br/>&nbsp;<br/>&nbsp;<br/>\r\n					Lacak pengirimannya langsung di menu <b>pesananku</b><br/>\r\n					<a href=\'https://localhost/botoko/account/order\'>Klik Disini</a>\r\n				', 'Resi Pengiriman', NULL, '2021-12-22 10:33:34', 0, '0000-00-00 00:00:00'),
(762, 0, 2, '08122222222222', '', '\r\n					Berikut resi pengiriman untuk pesanan anda di *Winny Putri Lubis* \nResi: *F12412414* \n \nLacak pengirimannya langsung di menu *pesananku* \n https://localhost/botoko/account/order\r\n				', '', NULL, '2021-12-22 10:33:34', 0, '0000-00-00 00:00:00'),
(763, 0, 1, 'adrinataland@gmail.com', 'Winny Putri Lubis', '\n				<html>\n				<head>\n					<style>\n					.border{padding:20px;border-radius:3px;margin:auto;}\n					.pesan{margin-bottom:30px;}\n					.link{margin-bottom:20px;}\n					.alink{text-decoration:none;background:#c0392b;padding:10px 24px;border-radius:3px;margin-bottom:20px;}\n					</style>\n				</head>\n				<body>\n					<div class=\"border\">\n					<div class=\"pesan\">\n					<h3>Halo, fitri</h3><p/>\n					Terima kasih sudah mendaftar di <b>Winny Putri Lubis</b>, untuk mengaktifkan akun Anda, silahkan klik link berikut:<br/>\n					</div>\n					<div class=\"link\">\n						<a class=\"alink\" style=\"color:#fff;\" href=\"https://dev.winnyputrilubis.id/auth/signup?verify=YToyOntzOjI6ImlkIjtpOjUwO3M6NDoidGltZSI7czoxNDoiMjAyMjAyMjgxOTQ1NDAiO30=\">VERIFIKASI AKUN WINNY PUTRI LUBIS</a>\n						<br/>&nbsp;<br/>atau link dibawah ini<br/>\n						<a href=\"https://dev.winnyputrilubis.id/auth/signup?verify=YToyOntzOjI6ImlkIjtpOjUwO3M6NDoidGltZSI7czoxNDoiMjAyMjAyMjgxOTQ1NDAiO30=\">https://dev.winnyputrilubis.id/auth/signup?verify=YToyOntzOjI6ImlkIjtpOjUwO3M6NDoidGltZSI7czoxNDoiMjAyMjAyMjgxOTQ1NDAiO30=</a>\n					</div>\n					</div>\n				</body>\n				</html>\n			', 'Verifikasi Pendaftaran Winny Putri Lubis', NULL, '2022-02-28 19:45:40', 0, '0000-00-00 00:00:00'),
(764, 0, 2, '08773636353', '', '\n				Halo, *fitri* \\n \\nTerima kasih sudah mendaftar di *Winny Putri Lubis*, untuk mengaktifkan akun Anda, silahkan klik link berikut:\\nhttps://dev.winnyputrilubis.id/auth/signup?verify=YToyOntzOjI6ImlkIjtpOjUwO3M6NDoidGltZSI7czoxNDoiMjAyMjAyMjgxOTQ1NDAiO30= \\n_*Apabila link tidak bisa di klik, simpan nomer whatsapp ini terlebih dahulu_\n			', '', NULL, '2022-02-28 19:45:40', 0, '0000-00-00 00:00:00'),
(765, 0, 1, 'didi@email.com', 'Winny Putri Lubis', '\n				<html>\n				<head>\n					<style>\n					.border{padding:20px;border-radius:3px;margin:auto;}\n					.pesan{margin-bottom:30px;}\n					.link{margin-bottom:20px;}\n					.alink{text-decoration:none;background:#c0392b;padding:10px 24px;border-radius:3px;margin-bottom:20px;}\n					</style>\n				</head>\n				<body>\n					<div class=\"border\">\n					<div class=\"pesan\">\n					<h3>Halo, Didi</h3><p/>\n					Terima kasih sudah mendaftar di <b>Winny Putri Lubis</b>, untuk mengaktifkan akun Anda, silahkan klik link berikut:<br/>\n					</div>\n					<div class=\"link\">\n						<a class=\"alink\" style=\"color:#fff;\" href=\"https://dev.winnyputrilubis.id/auth/signup?verify=YToyOntzOjI6ImlkIjtpOjUxO3M6NDoidGltZSI7czoxNDoiMjAyMjAyMjgyMDEwMTMiO30=\">VERIFIKASI AKUN WINNY PUTRI LUBIS</a>\n						<br/>&nbsp;<br/>atau link dibawah ini<br/>\n						<a href=\"https://dev.winnyputrilubis.id/auth/signup?verify=YToyOntzOjI6ImlkIjtpOjUxO3M6NDoidGltZSI7czoxNDoiMjAyMjAyMjgyMDEwMTMiO30=\">https://dev.winnyputrilubis.id/auth/signup?verify=YToyOntzOjI6ImlkIjtpOjUxO3M6NDoidGltZSI7czoxNDoiMjAyMjAyMjgyMDEwMTMiO30=</a>\n					</div>\n					</div>\n				</body>\n				</html>\n			', 'Verifikasi Pendaftaran Winny Putri Lubis', NULL, '2022-02-28 20:10:13', 0, '0000-00-00 00:00:00'),
(766, 0, 2, '0812121212', '', '\n				Halo, *Didi* \\n \\nTerima kasih sudah mendaftar di *Winny Putri Lubis*, untuk mengaktifkan akun Anda, silahkan klik link berikut:\\nhttps://dev.winnyputrilubis.id/auth/signup?verify=YToyOntzOjI6ImlkIjtpOjUxO3M6NDoidGltZSI7czoxNDoiMjAyMjAyMjgyMDEwMTMiO30= \\n_*Apabila link tidak bisa di klik, simpan nomer whatsapp ini terlebih dahulu_\n			', '', NULL, '2022-02-28 20:10:13', 0, '0000-00-00 00:00:00'),
(767, 0, 1, 'didi@email.com', 'Winny Putri Lubis - Pesanan', '\n					Halo <b>Didi</b><br/>Terimakasih sudah membeli produk kami.<br/>Saat ini kami sedang menunggu pembayaran darimu sebelum kami memprosesnya. Sebagai informasi, berikut detail pesananmu <br/>No Invoice: <b>#2022022861752</b><br/> <br/>Total Pesanan: <b>Rp 256.752</b><br/>Ongkos Kirim: <b>Rp 11.000</b><br/>Kurir Pengiriman: <b>SICEPAT BEST</b><br/> <br/>Detail Pengiriman <br/>Penerima: <b>Didi</b> <br/>No HP: <b>08123123131</b> <br/>Alamat: <b>Jl Karya Bakti</b><br/> <br/>Untuk pembayaran silahkan langsung klik link berikut:<br/><a href=\'https://dev.winnyputrilubis.id/home/invoice?inv=aTo2MTs=\'>Bayar Pesanan Sekarang &raquo;</a>\n				', 'Pesanan', NULL, '2022-02-28 20:23:58', 0, '0000-00-00 00:00:00');
INSERT INTO `blw_pesan_notifikasi` (`id`, `usrid`, `jenis`, `tujuan`, `judul`, `pesan`, `subyek`, `pengirim`, `tgl`, `status`, `proses`) VALUES
(768, 0, 2, '0812121212', '', '\n					Halo *Didi*\nTerimakasih sudah membeli produk kami.\nSaat ini kami sedang menunggu pembayaran darimu sebelum kami memprosesnya. Sebagai informasi, berikut detail pesananmu \n \nNo Invoice: *#2022022861752*\nTotal Pesanan: *Rp 256.752*\nOngkos Kirim: *Rp 11.000*\nKurir Pengiriman: *SICEPAT BEST*\n \nDetail Pengiriman \nPenerima: *Didi*\nNo HP: *08123123131*\nAlamat: *Jl Karya Bakti*\n \nUntuk pembayaran silahkan langsung klik link berikut\nhttps://dev.winnyputrilubis.id/home/invoice?inv=aTo2MTs=\n				', '', NULL, '2022-02-28 20:23:58', 0, '0000-00-00 00:00:00'),
(769, 0, 1, 'hello@winnyputrilubis.id', 'Winny Putri Lubis - Pesanan Baru', '\n					<h3>Pesanan Baru</h3><br/>\n					<b>DIDI</b> telah membuat pesanan baru dengan total pembayaran \n					<b>Rp. 256.752</b> Invoice ID: <b>#2022022861752</b>\n					<br/>&nbsp;<br/>&nbsp;<br/>\n					Cek Pesanan Pembeli di <b>Dashboard Admin Winny Putri Lubis</b><br/>\n					<a href=\'https://dev.winnyputrilubis.id/cdn\'>Klik Disini</a>\n				', 'Pesanan Baru di Winny Putri Lubis', NULL, '2022-02-28 20:23:58', 0, '0000-00-00 00:00:00'),
(770, 0, 2, '+62-82111', '', '\n					*Pesanan Baru*\n*DIDI* telah membuat pesanan baru dengan detail:\nTotal Pembayaran: *Rp. 256.752*\nInvoice ID: *#2022022861752*\n \nCek Pesanan Pembeli di *Dashboard Admin Winny Putri Lubis*\n					', '', NULL, '2022-02-28 20:23:58', 0, '0000-00-00 00:00:00'),
(771, 0, 1, 'buddy@gmail.com', 'Winny Putri Lubis - Pesanan', '\n					Halo <b>buddy</b><br/>Terimakasih sudah membeli produk kami.<br/>Saat ini kami sedang menunggu pembayaran darimu sebelum kami memprosesnya. Sebagai informasi, berikut detail pesananmu <br/>No Invoice: <b>#2022030262602</b><br/> <br/>Total Pesanan: <b>Rp 102.602</b><br/>Ongkos Kirim: <b>Rp 7.000</b><br/>Kurir Pengiriman: <b>JNE CTC</b><br/> <br/>Detail Pengiriman <br/>Penerima: <b></b> <br/>No HP: <b></b> <br/>Alamat: <b></b><br/> <br/>Untuk pembayaran silahkan langsung klik link berikut:<br/><a href=\'http://localhost/master-ci/_project/test_dev_winny/home/invoice?inv=aTo2Mjs=\'>Bayar Pesanan Sekarang &raquo;</a>\n				', 'Pesanan', NULL, '2022-03-02 20:38:30', 0, '0000-00-00 00:00:00'),
(772, 0, 2, '798798', '', '\n					Halo *buddy*\nTerimakasih sudah membeli produk kami.\nSaat ini kami sedang menunggu pembayaran darimu sebelum kami memprosesnya. Sebagai informasi, berikut detail pesananmu \n \nNo Invoice: *#2022030262602*\nTotal Pesanan: *Rp 102.602*\nOngkos Kirim: *Rp 7.000*\nKurir Pengiriman: *JNE CTC*\n \nDetail Pengiriman \nPenerima: **\nNo HP: **\nAlamat: **\n \nUntuk pembayaran silahkan langsung klik link berikut\nhttp://localhost/master-ci/_project/test_dev_winny/home/invoice?inv=aTo2Mjs=\n				', '', NULL, '2022-03-02 20:38:30', 0, '0000-00-00 00:00:00'),
(773, 0, 1, 'hello@winnyputrilubis.id', 'Winny Putri Lubis - Pesanan Baru', '\n					<h3>Pesanan Baru</h3><br/>\n					<b>BUDDY</b> telah membuat pesanan baru dengan total pembayaran \n					<b>Rp. 102.602</b> Invoice ID: <b>#2022030262602</b>\n					<br/>&nbsp;<br/>&nbsp;<br/>\n					Cek Pesanan Pembeli di <b>Dashboard Admin Winny Putri Lubis</b><br/>\n					<a href=\'http://localhost/master-ci/_project/test_dev_winny/cdn\'>Klik Disini</a>\n				', 'Pesanan Baru di Winny Putri Lubis', NULL, '2022-03-02 20:38:30', 0, '0000-00-00 00:00:00'),
(774, 0, 2, '+62-82111', '', '\n					*Pesanan Baru*\n*BUDDY* telah membuat pesanan baru dengan detail:\nTotal Pembayaran: *Rp. 102.602*\nInvoice ID: *#2022030262602*\n \nCek Pesanan Pembeli di *Dashboard Admin Winny Putri Lubis*\n					', '', NULL, '2022-03-02 20:38:31', 0, '0000-00-00 00:00:00'),
(775, 0, 1, 'shafirafirdausyiofficial@gmail.com', 'Winny Putri Lubis', '\n				<html>\n				<head>\n					<style>\n					.border{padding:20px;border-radius:3px;margin:auto;}\n					.pesan{margin-bottom:30px;}\n					.link{margin-bottom:20px;}\n					.alink{text-decoration:none;background:#c0392b;padding:10px 24px;border-radius:3px;margin-bottom:20px;}\n					</style>\n				</head>\n				<body>\n					<div class=\"border\">\n					<div class=\"pesan\">\n					<h3>Halo, Shafira Firdausy</h3><p/>\n					Terima kasih sudah mendaftar di <b>Winny Putri Lubis</b>, untuk mengaktifkan akun Anda, silahkan klik link berikut:<br/>\n					</div>\n					<div class=\"link\">\n						<a class=\"alink\" style=\"color:#fff;\" href=\"https://winnyputrilubis.id/auth/signup?verify=YToyOntzOjI6ImlkIjtpOjUyO3M6NDoidGltZSI7czoxNDoiMjAyMjAzMTgwNjI0NDMiO30=\">VERIFIKASI AKUN WINNY PUTRI LUBIS</a>\n						<br/>&nbsp;<br/>atau link dibawah ini<br/>\n						<a href=\"https://winnyputrilubis.id/auth/signup?verify=YToyOntzOjI6ImlkIjtpOjUyO3M6NDoidGltZSI7czoxNDoiMjAyMjAzMTgwNjI0NDMiO30=\">https://winnyputrilubis.id/auth/signup?verify=YToyOntzOjI6ImlkIjtpOjUyO3M6NDoidGltZSI7czoxNDoiMjAyMjAzMTgwNjI0NDMiO30=</a>\n					</div>\n					</div>\n				</body>\n				</html>\n			', 'Verifikasi Pendaftaran Winny Putri Lubis', NULL, '2022-03-18 06:24:43', 0, '0000-00-00 00:00:00'),
(776, 0, 2, '082232376727', '', '\n				Halo, *Shafira Firdausy* \\n \\nTerima kasih sudah mendaftar di *Winny Putri Lubis*, untuk mengaktifkan akun Anda, silahkan klik link berikut:\\nhttps://winnyputrilubis.id/auth/signup?verify=YToyOntzOjI6ImlkIjtpOjUyO3M6NDoidGltZSI7czoxNDoiMjAyMjAzMTgwNjI0NDMiO30= \\n_*Apabila link tidak bisa di klik, simpan nomer whatsapp ini terlebih dahulu_\n			', '', NULL, '2022-03-18 06:24:43', 0, '0000-00-00 00:00:00'),
(777, 0, 1, 'FishakitchenPamekasan@gmail.com', 'Winny Putri Lubis', '\n				<html>\n				<head>\n					<style>\n					.border{padding:20px;border-radius:3px;margin:auto;}\n					.pesan{margin-bottom:30px;}\n					.link{margin-bottom:20px;}\n					.alink{text-decoration:none;background:#c0392b;padding:10px 24px;border-radius:3px;margin-bottom:20px;}\n					</style>\n				</head>\n				<body>\n					<div class=\"border\">\n					<div class=\"pesan\">\n					<h3>Halo, Fisha Kitchen Pamekasan</h3><p/>\n					Terima kasih sudah mendaftar di <b>Winny Putri Lubis</b>, untuk mengaktifkan akun Anda, silahkan klik link berikut:<br/>\n					</div>\n					<div class=\"link\">\n						<a class=\"alink\" style=\"color:#fff;\" href=\"https://winnyputrilubis.id/auth/signup?verify=YToyOntzOjI6ImlkIjtpOjUzO3M6NDoidGltZSI7czoxNDoiMjAyMjAzMTgwOTQ0MjgiO30=\">VERIFIKASI AKUN WINNY PUTRI LUBIS</a>\n						<br/>&nbsp;<br/>atau link dibawah ini<br/>\n						<a href=\"https://winnyputrilubis.id/auth/signup?verify=YToyOntzOjI6ImlkIjtpOjUzO3M6NDoidGltZSI7czoxNDoiMjAyMjAzMTgwOTQ0MjgiO30=\">https://winnyputrilubis.id/auth/signup?verify=YToyOntzOjI6ImlkIjtpOjUzO3M6NDoidGltZSI7czoxNDoiMjAyMjAzMTgwOTQ0MjgiO30=</a>\n					</div>\n					</div>\n				</body>\n				</html>\n			', 'Verifikasi Pendaftaran Winny Putri Lubis', NULL, '2022-03-18 09:44:28', 0, '0000-00-00 00:00:00'),
(778, 0, 2, '082232376727', '', '\n				Halo, *Fisha Kitchen Pamekasan* \\n \\nTerima kasih sudah mendaftar di *Winny Putri Lubis*, untuk mengaktifkan akun Anda, silahkan klik link berikut:\\nhttps://winnyputrilubis.id/auth/signup?verify=YToyOntzOjI6ImlkIjtpOjUzO3M6NDoidGltZSI7czoxNDoiMjAyMjAzMTgwOTQ0MjgiO30= \\n_*Apabila link tidak bisa di klik, simpan nomer whatsapp ini terlebih dahulu_\n			', '', NULL, '2022-03-18 09:44:28', 0, '0000-00-00 00:00:00'),
(779, 0, 1, 'fitrianoveliza@gmail.com', 'Winny Putri Lubis', '\n				<html>\n				<head>\n					<style>\n					.border{padding:20px;border-radius:3px;margin:auto;}\n					.pesan{margin-bottom:30px;}\n					.link{margin-bottom:20px;}\n					.alink{text-decoration:none;background:#c0392b;padding:10px 24px;border-radius:3px;margin-bottom:20px;}\n					</style>\n				</head>\n				<body>\n					<div class=\"border\">\n					<div class=\"pesan\">\n					<h3>Halo, fitri</h3><p/>\n					Terima kasih sudah mendaftar di <b>Winny Putri Lubis</b>, untuk mengaktifkan akun Anda, silahkan klik link berikut:<br/>\n					</div>\n					<div class=\"link\">\n						<a class=\"alink\" style=\"color:#fff;\" href=\"https://winnyputrilubis.id/auth/signup?verify=YToyOntzOjI6ImlkIjtpOjU0O3M6NDoidGltZSI7czoxNDoiMjAyMjAzMTgyMjA1MjEiO30=\">VERIFIKASI AKUN WINNY PUTRI LUBIS</a>\n						<br/>&nbsp;<br/>atau link dibawah ini<br/>\n						<a href=\"https://winnyputrilubis.id/auth/signup?verify=YToyOntzOjI6ImlkIjtpOjU0O3M6NDoidGltZSI7czoxNDoiMjAyMjAzMTgyMjA1MjEiO30=\">https://winnyputrilubis.id/auth/signup?verify=YToyOntzOjI6ImlkIjtpOjU0O3M6NDoidGltZSI7czoxNDoiMjAyMjAzMTgyMjA1MjEiO30=</a>\n					</div>\n					</div>\n				</body>\n				</html>\n			', 'Verifikasi Pendaftaran Winny Putri Lubis', NULL, '2022-03-18 22:05:21', 0, '0000-00-00 00:00:00'),
(780, 0, 2, '08575152838', '', '\n				Halo, *fitri* \\n \\nTerima kasih sudah mendaftar di *Winny Putri Lubis*, untuk mengaktifkan akun Anda, silahkan klik link berikut:\\nhttps://winnyputrilubis.id/auth/signup?verify=YToyOntzOjI6ImlkIjtpOjU0O3M6NDoidGltZSI7czoxNDoiMjAyMjAzMTgyMjA1MjEiO30= \\n_*Apabila link tidak bisa di klik, simpan nomer whatsapp ini terlebih dahulu_\n			', '', NULL, '2022-03-18 22:05:21', 0, '0000-00-00 00:00:00'),
(781, 0, 1, 'fitrianoveliza@gmail.com', 'Winny Putri Lubis - Pesanan', '\n					Halo <b>fitri</b><br/>Terimakasih sudah membeli produk kami.<br/>Saat ini kami sedang menunggu pembayaran darimu sebelum kami memprosesnya. Sebagai informasi, berikut detail pesananmu <br/>No Invoice: <b>#2022031863962</b><br/> <br/>Total Pesanan: <b>Rp 82.962</b><br/>Ongkos Kirim: <b>Rp 7.000</b><br/>Kurir Pengiriman: <b>JNE CTC</b><br/> <br/>Detail Pengiriman <br/>Penerima: <b></b> <br/>No HP: <b></b> <br/>Alamat: <b></b><br/> <br/>Untuk pembayaran silahkan langsung klik link berikut:<br/><a href=\'https://winnyputrilubis.id/home/invoice?inv=aTo2Mzs=\'>Bayar Pesanan Sekarang &raquo;</a>\n				', 'Pesanan', NULL, '2022-03-18 22:10:41', 0, '0000-00-00 00:00:00'),
(782, 0, 2, '08575152838', '', '\n					Halo *fitri*\nTerimakasih sudah membeli produk kami.\nSaat ini kami sedang menunggu pembayaran darimu sebelum kami memprosesnya. Sebagai informasi, berikut detail pesananmu \n \nNo Invoice: *#2022031863962*\nTotal Pesanan: *Rp 82.962*\nOngkos Kirim: *Rp 7.000*\nKurir Pengiriman: *JNE CTC*\n \nDetail Pengiriman \nPenerima: **\nNo HP: **\nAlamat: **\n \nUntuk pembayaran silahkan langsung klik link berikut\nhttps://winnyputrilubis.id/home/invoice?inv=aTo2Mzs=\n				', '', NULL, '2022-03-18 22:10:41', 0, '0000-00-00 00:00:00'),
(783, 0, 1, 'hello@winnyputrilubis.id', 'Winny Putri Lubis - Pesanan Baru', '\n					<h3>Pesanan Baru</h3><br/>\n					<b>FITRI</b> telah membuat pesanan baru dengan total pembayaran \n					<b>Rp. 82.962</b> Invoice ID: <b>#2022031863962</b>\n					<br/>&nbsp;<br/>&nbsp;<br/>\n					Cek Pesanan Pembeli di <b>Dashboard Admin Winny Putri Lubis</b><br/>\n					<a href=\'https://winnyputrilubis.id/cdn\'>Klik Disini</a>\n				', 'Pesanan Baru di Winny Putri Lubis', NULL, '2022-03-18 22:10:41', 0, '0000-00-00 00:00:00'),
(784, 0, 2, '+62 821 6888 8256', '', '\n					*Pesanan Baru*\n*FITRI* telah membuat pesanan baru dengan detail:\nTotal Pembayaran: *Rp. 82.962*\nInvoice ID: *#2022031863962*\n \nCek Pesanan Pembeli di *Dashboard Admin Winny Putri Lubis*\n					', '', NULL, '2022-03-18 22:10:41', 0, '0000-00-00 00:00:00'),
(785, 0, 1, 'fitrianoveliza@gmail.com', 'Winny Putri Lubis - Pesanan', '\n					Halo <b>fitri</b><br/>Terimakasih sudah membeli produk kami.<br/>Saat ini kami sedang menunggu pembayaran darimu sebelum kami memprosesnya. Sebagai informasi, berikut detail pesananmu <br/>No Invoice: <b>#2022031864611</b><br/> <br/>Total Pesanan: <b>Rp 86.611</b><br/>Ongkos Kirim: <b>Rp 11.000</b><br/>Kurir Pengiriman: <b>SICEPAT BEST</b><br/> <br/>Detail Pengiriman <br/>Penerima: <b></b> <br/>No HP: <b></b> <br/>Alamat: <b></b><br/> <br/>Untuk pembayaran silahkan langsung klik link berikut:<br/><a href=\'https://winnyputrilubis.id/home/invoice?inv=aTo2NDs=\'>Bayar Pesanan Sekarang &raquo;</a>\n				', 'Pesanan', NULL, '2022-03-18 22:11:59', 0, '0000-00-00 00:00:00'),
(786, 0, 2, '08575152838', '', '\n					Halo *fitri*\nTerimakasih sudah membeli produk kami.\nSaat ini kami sedang menunggu pembayaran darimu sebelum kami memprosesnya. Sebagai informasi, berikut detail pesananmu \n \nNo Invoice: *#2022031864611*\nTotal Pesanan: *Rp 86.611*\nOngkos Kirim: *Rp 11.000*\nKurir Pengiriman: *SICEPAT BEST*\n \nDetail Pengiriman \nPenerima: **\nNo HP: **\nAlamat: **\n \nUntuk pembayaran silahkan langsung klik link berikut\nhttps://winnyputrilubis.id/home/invoice?inv=aTo2NDs=\n				', '', NULL, '2022-03-18 22:11:59', 0, '0000-00-00 00:00:00'),
(787, 0, 1, 'hello@winnyputrilubis.id', 'Winny Putri Lubis - Pesanan Baru', '\n					<h3>Pesanan Baru</h3><br/>\n					<b>FITRI</b> telah membuat pesanan baru dengan total pembayaran \n					<b>Rp. 86.611</b> Invoice ID: <b>#2022031864611</b>\n					<br/>&nbsp;<br/>&nbsp;<br/>\n					Cek Pesanan Pembeli di <b>Dashboard Admin Winny Putri Lubis</b><br/>\n					<a href=\'https://winnyputrilubis.id/cdn\'>Klik Disini</a>\n				', 'Pesanan Baru di Winny Putri Lubis', NULL, '2022-03-18 22:11:59', 0, '0000-00-00 00:00:00'),
(788, 0, 2, '+62 821 6888 8256', '', '\n					*Pesanan Baru*\n*FITRI* telah membuat pesanan baru dengan detail:\nTotal Pembayaran: *Rp. 86.611*\nInvoice ID: *#2022031864611*\n \nCek Pesanan Pembeli di *Dashboard Admin Winny Putri Lubis*\n					', '', NULL, '2022-03-18 22:11:59', 0, '0000-00-00 00:00:00'),
(789, 0, 1, 'fitrianoveliza@gmail.com', 'Winny Putri Lubis - Pesanan', '\n					Halo <b>fitri</b><br/>Terimakasih sudah membeli produk kami.<br/>Saat ini kami sedang menunggu pembayaran darimu sebelum kami memprosesnya. Sebagai informasi, berikut detail pesananmu <br/>No Invoice: <b>#2022031865301</b><br/> <br/>Total Pesanan: <b>Rp 75.301</b><br/>Ongkos Kirim: <b>Rp 0</b><br/>Kurir Pengiriman: <b>GOSEND </b><br/> <br/>Detail Pengiriman <br/>Penerima: <b>fitria</b> <br/>No HP: <b>085275152838</b> <br/>Alamat: <b>jl beo no 43 ABC</b><br/> <br/>Untuk pembayaran silahkan langsung klik link berikut:<br/><a href=\'https://winnyputrilubis.id/home/invoice?inv=aTo2NTs=\'>Bayar Pesanan Sekarang &raquo;</a>\n				', 'Pesanan', NULL, '2022-03-18 22:13:25', 0, '0000-00-00 00:00:00'),
(790, 0, 2, '08575152838', '', '\n					Halo *fitri*\nTerimakasih sudah membeli produk kami.\nSaat ini kami sedang menunggu pembayaran darimu sebelum kami memprosesnya. Sebagai informasi, berikut detail pesananmu \n \nNo Invoice: *#2022031865301*\nTotal Pesanan: *Rp 75.301*\nOngkos Kirim: *Rp 0*\nKurir Pengiriman: *GOSEND *\n \nDetail Pengiriman \nPenerima: *fitria*\nNo HP: *085275152838*\nAlamat: *jl beo no 43 ABC*\n \nUntuk pembayaran silahkan langsung klik link berikut\nhttps://winnyputrilubis.id/home/invoice?inv=aTo2NTs=\n				', '', NULL, '2022-03-18 22:13:25', 0, '0000-00-00 00:00:00'),
(791, 0, 1, 'hello@winnyputrilubis.id', 'Winny Putri Lubis - Pesanan Baru', '\n					<h3>Pesanan Baru</h3><br/>\n					<b>FITRI</b> telah membuat pesanan baru dengan total pembayaran \n					<b>Rp. 75.301</b> Invoice ID: <b>#2022031865301</b>\n					<br/>&nbsp;<br/>&nbsp;<br/>\n					Cek Pesanan Pembeli di <b>Dashboard Admin Winny Putri Lubis</b><br/>\n					<a href=\'https://winnyputrilubis.id/cdn\'>Klik Disini</a>\n				', 'Pesanan Baru di Winny Putri Lubis', NULL, '2022-03-18 22:13:25', 0, '0000-00-00 00:00:00'),
(792, 0, 2, '+62 821 6888 8256', '', '\n					*Pesanan Baru*\n*FITRI* telah membuat pesanan baru dengan detail:\nTotal Pembayaran: *Rp. 75.301*\nInvoice ID: *#2022031865301*\n \nCek Pesanan Pembeli di *Dashboard Admin Winny Putri Lubis*\n					', '', NULL, '2022-03-18 22:13:25', 0, '0000-00-00 00:00:00'),
(793, 0, 1, 'didihansya@gmail.com', 'Winny Putri Lubis', '\n				<html>\n				<head>\n					<style>\n					.border{padding:20px;border-radius:3px;margin:auto;}\n					.pesan{margin-bottom:30px;}\n					.link{margin-bottom:20px;}\n					.alink{text-decoration:none;background:#c0392b;padding:10px 24px;border-radius:3px;margin-bottom:20px;}\n					</style>\n				</head>\n				<body>\n					<div class=\"border\">\n					<div class=\"pesan\">\n					<h3>Halo, Didi Khodriansyah</h3><p/>\n					Terima kasih sudah mendaftar di <b>Winny Putri Lubis</b>, untuk mengaktifkan akun Anda, silahkan klik link berikut:<br/>\n					</div>\n					<div class=\"link\">\n						<a class=\"alink\" style=\"color:#fff;\" href=\"https://winnyputrilubis.id/auth/signup?verify=YToyOntzOjI6ImlkIjtpOjU1O3M6NDoidGltZSI7czoxNDoiMjAyMjAzMTgyMjE0NTMiO30=\">VERIFIKASI AKUN WINNY PUTRI LUBIS</a>\n						<br/>&nbsp;<br/>atau link dibawah ini<br/>\n						<a href=\"https://winnyputrilubis.id/auth/signup?verify=YToyOntzOjI6ImlkIjtpOjU1O3M6NDoidGltZSI7czoxNDoiMjAyMjAzMTgyMjE0NTMiO30=\">https://winnyputrilubis.id/auth/signup?verify=YToyOntzOjI6ImlkIjtpOjU1O3M6NDoidGltZSI7czoxNDoiMjAyMjAzMTgyMjE0NTMiO30=</a>\n					</div>\n					</div>\n				</body>\n				</html>\n			', 'Verifikasi Pendaftaran Winny Putri Lubis', NULL, '2022-03-18 22:14:53', 0, '0000-00-00 00:00:00'),
(794, 0, 2, '085161900922', '', '\n				Halo, *Didi Khodriansyah* \\n \\nTerima kasih sudah mendaftar di *Winny Putri Lubis*, untuk mengaktifkan akun Anda, silahkan klik link berikut:\\nhttps://winnyputrilubis.id/auth/signup?verify=YToyOntzOjI6ImlkIjtpOjU1O3M6NDoidGltZSI7czoxNDoiMjAyMjAzMTgyMjE0NTMiO30= \\n_*Apabila link tidak bisa di klik, simpan nomer whatsapp ini terlebih dahulu_\n			', '', NULL, '2022-03-18 22:14:53', 0, '0000-00-00 00:00:00'),
(795, 0, 1, 'didihansya@gmail.com', 'Winny Putri Lubis - Pesanan', '\n					Halo <b>Didi Khodriansyah</b><br/>Terimakasih sudah membeli produk kami.<br/>Saat ini kami sedang menunggu pembayaran darimu sebelum kami memprosesnya. Sebagai informasi, berikut detail pesananmu <br/>No Invoice: <b>#2022031866392</b><br/> <br/>Total Pesanan: <b>Rp 82.392</b><br/>Ongkos Kirim: <b>Rp 7.000</b><br/>Kurir Pengiriman: <b>JNE CTC</b><br/> <br/>Detail Pengiriman <br/>Penerima: <b>Didi Khodriansyah</b> <br/>No HP: <b>085161900922</b> <br/>Alamat: <b>Jl Karya Bakti Gg Bakti 4 No 30b</b><br/> <br/>Untuk pembayaran silahkan langsung klik link berikut:<br/><a href=\'https://winnyputrilubis.id/home/invoice?inv=aTo2Njs=\'>Bayar Pesanan Sekarang &raquo;</a>\n				', 'Pesanan', NULL, '2022-03-18 22:16:14', 0, '0000-00-00 00:00:00'),
(796, 0, 2, '085161900922', '', '\n					Halo *Didi Khodriansyah*\nTerimakasih sudah membeli produk kami.\nSaat ini kami sedang menunggu pembayaran darimu sebelum kami memprosesnya. Sebagai informasi, berikut detail pesananmu \n \nNo Invoice: *#2022031866392*\nTotal Pesanan: *Rp 82.392*\nOngkos Kirim: *Rp 7.000*\nKurir Pengiriman: *JNE CTC*\n \nDetail Pengiriman \nPenerima: *Didi Khodriansyah*\nNo HP: *085161900922*\nAlamat: *Jl Karya Bakti Gg Bakti 4 No 30b*\n \nUntuk pembayaran silahkan langsung klik link berikut\nhttps://winnyputrilubis.id/home/invoice?inv=aTo2Njs=\n				', '', NULL, '2022-03-18 22:16:14', 0, '0000-00-00 00:00:00'),
(797, 0, 1, 'hello@winnyputrilubis.id', 'Winny Putri Lubis - Pesanan Baru', '\n					<h3>Pesanan Baru</h3><br/>\n					<b>DIDI KHODRIANSYAH</b> telah membuat pesanan baru dengan total pembayaran \n					<b>Rp. 82.392</b> Invoice ID: <b>#2022031866392</b>\n					<br/>&nbsp;<br/>&nbsp;<br/>\n					Cek Pesanan Pembeli di <b>Dashboard Admin Winny Putri Lubis</b><br/>\n					<a href=\'https://winnyputrilubis.id/cdn\'>Klik Disini</a>\n				', 'Pesanan Baru di Winny Putri Lubis', NULL, '2022-03-18 22:16:15', 0, '0000-00-00 00:00:00'),
(798, 0, 2, '+62 821 6888 8256', '', '\n					*Pesanan Baru*\n*DIDI KHODRIANSYAH* telah membuat pesanan baru dengan detail:\nTotal Pembayaran: *Rp. 82.392*\nInvoice ID: *#2022031866392*\n \nCek Pesanan Pembeli di *Dashboard Admin Winny Putri Lubis*\n					', '', NULL, '2022-03-18 22:16:15', 0, '0000-00-00 00:00:00'),
(799, 0, 1, 'didihansya@gmail.com', 'Winny Putri Lubis - Pesanan', '\n					Halo <b>Didi Khodriansyah</b><br/>Terimakasih sudah membeli produk kami.<br/>Saat ini kami sedang menunggu pembayaran darimu sebelum kami memprosesnya. Sebagai informasi, berikut detail pesananmu <br/>No Invoice: <b>#2022031867185</b><br/> <br/>Total Pesanan: <b>Rp 86.185</b><br/>Ongkos Kirim: <b>Rp 11.000</b><br/>Kurir Pengiriman: <b>SICEPAT BEST</b><br/> <br/>Detail Pengiriman <br/>Penerima: <b>Didi Khodriansyah</b> <br/>No HP: <b>085161900922</b> <br/>Alamat: <b>Jl Karya Bakti Gg Bakti 4 No 30b</b><br/> <br/>Untuk pembayaran silahkan langsung klik link berikut:<br/><a href=\'https://winnyputrilubis.id/home/invoice?inv=aTo2Nzs=\'>Bayar Pesanan Sekarang &raquo;</a>\n				', 'Pesanan', NULL, '2022-03-18 22:20:34', 0, '0000-00-00 00:00:00'),
(800, 0, 2, '085161900922', '', '\n					Halo *Didi Khodriansyah*\nTerimakasih sudah membeli produk kami.\nSaat ini kami sedang menunggu pembayaran darimu sebelum kami memprosesnya. Sebagai informasi, berikut detail pesananmu \n \nNo Invoice: *#2022031867185*\nTotal Pesanan: *Rp 86.185*\nOngkos Kirim: *Rp 11.000*\nKurir Pengiriman: *SICEPAT BEST*\n \nDetail Pengiriman \nPenerima: *Didi Khodriansyah*\nNo HP: *085161900922*\nAlamat: *Jl Karya Bakti Gg Bakti 4 No 30b*\n \nUntuk pembayaran silahkan langsung klik link berikut\nhttps://winnyputrilubis.id/home/invoice?inv=aTo2Nzs=\n				', '', NULL, '2022-03-18 22:20:34', 0, '0000-00-00 00:00:00'),
(801, 0, 1, 'hello@winnyputrilubis.id', 'Winny Putri Lubis - Pesanan Baru', '\n					<h3>Pesanan Baru</h3><br/>\n					<b>DIDI KHODRIANSYAH</b> telah membuat pesanan baru dengan total pembayaran \n					<b>Rp. 86.185</b> Invoice ID: <b>#2022031867185</b>\n					<br/>&nbsp;<br/>&nbsp;<br/>\n					Cek Pesanan Pembeli di <b>Dashboard Admin Winny Putri Lubis</b><br/>\n					<a href=\'https://winnyputrilubis.id/cdn\'>Klik Disini</a>\n				', 'Pesanan Baru di Winny Putri Lubis', NULL, '2022-03-18 22:20:34', 0, '0000-00-00 00:00:00'),
(802, 0, 2, '+62 821 6888 8256', '', '\n					*Pesanan Baru*\n*DIDI KHODRIANSYAH* telah membuat pesanan baru dengan detail:\nTotal Pembayaran: *Rp. 86.185*\nInvoice ID: *#2022031867185*\n \nCek Pesanan Pembeli di *Dashboard Admin Winny Putri Lubis*\n					', '', NULL, '2022-03-18 22:20:34', 0, '0000-00-00 00:00:00'),
(803, 0, 1, 'didihansya@gmail.com', 'Winny Putri Lubis - Pesanan', '\n					Halo <b>Didi Khodriansyah</b><br/>Terimakasih sudah membeli produk kami.<br/>Saat ini kami sedang menunggu pembayaran darimu sebelum kami memprosesnya. Sebagai informasi, berikut detail pesananmu <br/>No Invoice: <b>#2022031868406</b><br/> <br/>Total Pesanan: <b>Rp 82.406</b><br/>Ongkos Kirim: <b>Rp 7.000</b><br/>Kurir Pengiriman: <b>JNE CTC</b><br/> <br/>Detail Pengiriman <br/>Penerima: <b>Didi Khodriansyah</b> <br/>No HP: <b>085161900922</b> <br/>Alamat: <b>Jl Karya Bakti Gg Bakti 4 No 30b</b><br/> <br/>Untuk pembayaran silahkan langsung klik link berikut:<br/><a href=\'https://winnyputrilubis.id/home/invoice?inv=aTo2ODs=\'>Bayar Pesanan Sekarang &raquo;</a>\n				', 'Pesanan', NULL, '2022-03-18 22:25:31', 0, '0000-00-00 00:00:00'),
(804, 0, 2, '085161900922', '', '\n					Halo *Didi Khodriansyah*\nTerimakasih sudah membeli produk kami.\nSaat ini kami sedang menunggu pembayaran darimu sebelum kami memprosesnya. Sebagai informasi, berikut detail pesananmu \n \nNo Invoice: *#2022031868406*\nTotal Pesanan: *Rp 82.406*\nOngkos Kirim: *Rp 7.000*\nKurir Pengiriman: *JNE CTC*\n \nDetail Pengiriman \nPenerima: *Didi Khodriansyah*\nNo HP: *085161900922*\nAlamat: *Jl Karya Bakti Gg Bakti 4 No 30b*\n \nUntuk pembayaran silahkan langsung klik link berikut\nhttps://winnyputrilubis.id/home/invoice?inv=aTo2ODs=\n				', '', NULL, '2022-03-18 22:25:31', 0, '0000-00-00 00:00:00'),
(805, 0, 1, 'hello@winnyputrilubis.id', 'Winny Putri Lubis - Pesanan Baru', '\n					<h3>Pesanan Baru</h3><br/>\n					<b>DIDI KHODRIANSYAH</b> telah membuat pesanan baru dengan total pembayaran \n					<b>Rp. 82.406</b> Invoice ID: <b>#2022031868406</b>\n					<br/>&nbsp;<br/>&nbsp;<br/>\n					Cek Pesanan Pembeli di <b>Dashboard Admin Winny Putri Lubis</b><br/>\n					<a href=\'https://winnyputrilubis.id/cdn\'>Klik Disini</a>\n				', 'Pesanan Baru di Winny Putri Lubis', NULL, '2022-03-18 22:25:32', 0, '0000-00-00 00:00:00'),
(806, 0, 2, '+62 821 6888 8256', '', '\n					*Pesanan Baru*\n*DIDI KHODRIANSYAH* telah membuat pesanan baru dengan detail:\nTotal Pembayaran: *Rp. 82.406*\nInvoice ID: *#2022031868406*\n \nCek Pesanan Pembeli di *Dashboard Admin Winny Putri Lubis*\n					', '', NULL, '2022-03-18 22:25:32', 0, '0000-00-00 00:00:00'),
(807, 0, 1, 'fitrianoveliza@gmail.com', 'Winny Putri Lubis - Pesanan', '\n					Halo <b>fitri</b><br/>Terimakasih sudah membeli produk kami.<br/>Saat ini kami sedang menunggu pembayaran darimu sebelum kami memprosesnya. Sebagai informasi, berikut detail pesananmu <br/>No Invoice: <b>#2022031869550</b><br/> <br/>Total Pesanan: <b>Rp 75.550</b><br/>Ongkos Kirim: <b>Rp 0</b><br/>Kurir Pengiriman: <b>GOSEND </b><br/> <br/>Detail Pengiriman <br/>Penerima: <b>fitria</b> <br/>No HP: <b>085275152838</b> <br/>Alamat: <b>jl beo no 43 ABC</b><br/> <br/>Untuk pembayaran silahkan langsung klik link berikut:<br/><a href=\'https://winnyputrilubis.id/home/invoice?inv=aTo2OTs=\'>Bayar Pesanan Sekarang &raquo;</a>\n				', 'Pesanan', NULL, '2022-03-18 22:38:58', 0, '0000-00-00 00:00:00'),
(808, 0, 2, '08575152838', '', '\n					Halo *fitri*\nTerimakasih sudah membeli produk kami.\nSaat ini kami sedang menunggu pembayaran darimu sebelum kami memprosesnya. Sebagai informasi, berikut detail pesananmu \n \nNo Invoice: *#2022031869550*\nTotal Pesanan: *Rp 75.550*\nOngkos Kirim: *Rp 0*\nKurir Pengiriman: *GOSEND *\n \nDetail Pengiriman \nPenerima: *fitria*\nNo HP: *085275152838*\nAlamat: *jl beo no 43 ABC*\n \nUntuk pembayaran silahkan langsung klik link berikut\nhttps://winnyputrilubis.id/home/invoice?inv=aTo2OTs=\n				', '', NULL, '2022-03-18 22:38:58', 0, '0000-00-00 00:00:00'),
(809, 0, 1, 'hello@winnyputrilubis.id', 'Winny Putri Lubis - Pesanan Baru', '\n					<h3>Pesanan Baru</h3><br/>\n					<b>FITRI</b> telah membuat pesanan baru dengan total pembayaran \n					<b>Rp. 75.550</b> Invoice ID: <b>#2022031869550</b>\n					<br/>&nbsp;<br/>&nbsp;<br/>\n					Cek Pesanan Pembeli di <b>Dashboard Admin Winny Putri Lubis</b><br/>\n					<a href=\'https://winnyputrilubis.id/cdn\'>Klik Disini</a>\n				', 'Pesanan Baru di Winny Putri Lubis', NULL, '2022-03-18 22:38:58', 0, '0000-00-00 00:00:00'),
(810, 0, 2, '+62 821 6888 8256', '', '\n					*Pesanan Baru*\n*FITRI* telah membuat pesanan baru dengan detail:\nTotal Pembayaran: *Rp. 75.550*\nInvoice ID: *#2022031869550*\n \nCek Pesanan Pembeli di *Dashboard Admin Winny Putri Lubis*\n					', '', NULL, '2022-03-18 22:38:58', 0, '0000-00-00 00:00:00'),
(811, 0, 1, 'fitrianoveliza@gmail.com', 'Winny Putri Lubis - Pesanan', '\n					Halo <b>fitri</b><br/>Terimakasih sudah membeli produk kami.<br/>Saat ini kami sedang menunggu pembayaran darimu sebelum kami memprosesnya. Sebagai informasi, berikut detail pesananmu <br/>No Invoice: <b>#2022031970150</b><br/> <br/>Total Pesanan: <b>Rp 75.150</b><br/>Ongkos Kirim: <b>Rp 0</b><br/>Kurir Pengiriman: <b>GOSEND </b><br/> <br/>Detail Pengiriman <br/>Penerima: <b>fitria</b> <br/>No HP: <b>085275152838</b> <br/>Alamat: <b>jl beo no 43 ABC</b><br/> <br/>Untuk pembayaran silahkan langsung klik link berikut:<br/><a href=\'https://winnyputrilubis.id/home/invoice?inv=aTo3MDs=\'>Bayar Pesanan Sekarang &raquo;</a>\n				', 'Pesanan', NULL, '2022-03-19 00:55:37', 0, '0000-00-00 00:00:00'),
(812, 0, 2, '08575152838', '', '\n					Halo *fitri*\nTerimakasih sudah membeli produk kami.\nSaat ini kami sedang menunggu pembayaran darimu sebelum kami memprosesnya. Sebagai informasi, berikut detail pesananmu \n \nNo Invoice: *#2022031970150*\nTotal Pesanan: *Rp 75.150*\nOngkos Kirim: *Rp 0*\nKurir Pengiriman: *GOSEND *\n \nDetail Pengiriman \nPenerima: *fitria*\nNo HP: *085275152838*\nAlamat: *jl beo no 43 ABC*\n \nUntuk pembayaran silahkan langsung klik link berikut\nhttps://winnyputrilubis.id/home/invoice?inv=aTo3MDs=\n				', '', NULL, '2022-03-19 00:55:37', 0, '0000-00-00 00:00:00'),
(813, 0, 1, 'hello@winnyputrilubis.id', 'Winny Putri Lubis - Pesanan Baru', '\n					<h3>Pesanan Baru</h3><br/>\n					<b>FITRI</b> telah membuat pesanan baru dengan total pembayaran \n					<b>Rp. 75.150</b> Invoice ID: <b>#2022031970150</b>\n					<br/>&nbsp;<br/>&nbsp;<br/>\n					Cek Pesanan Pembeli di <b>Dashboard Admin Winny Putri Lubis</b><br/>\n					<a href=\'https://winnyputrilubis.id/cdn\'>Klik Disini</a>\n				', 'Pesanan Baru di Winny Putri Lubis', NULL, '2022-03-19 00:55:37', 0, '0000-00-00 00:00:00'),
(814, 0, 2, '+62 821 6888 8256', '', '\n					*Pesanan Baru*\n*FITRI* telah membuat pesanan baru dengan detail:\nTotal Pembayaran: *Rp. 75.150*\nInvoice ID: *#2022031970150*\n \nCek Pesanan Pembeli di *Dashboard Admin Winny Putri Lubis*\n					', '', NULL, '2022-03-19 00:55:37', 0, '0000-00-00 00:00:00'),
(815, 0, 1, 'fitrianoveliza@gmail.com', 'Winny Putri Lubis - Pesanan', '\n					Halo <b>fitri</b><br/>Terimakasih sudah membeli produk kami.<br/>Saat ini kami sedang menunggu pembayaran darimu sebelum kami memprosesnya. Sebagai informasi, berikut detail pesananmu <br/>No Invoice: <b>#2022031971972</b><br/> <br/>Total Pesanan: <b>Rp 86.972</b><br/>Ongkos Kirim: <b>Rp 11.000</b><br/>Kurir Pengiriman: <b>SICEPAT BEST</b><br/> <br/>Detail Pengiriman <br/>Penerima: <b>fitria</b> <br/>No HP: <b>085275152838</b> <br/>Alamat: <b>jl beo no 43 ABC</b><br/> <br/>Untuk pembayaran silahkan langsung klik link berikut:<br/><a href=\'https://winnyputrilubis.id/home/invoice?inv=aTo3MTs=\'>Bayar Pesanan Sekarang &raquo;</a>\n				', 'Pesanan', NULL, '2022-03-19 13:42:51', 0, '0000-00-00 00:00:00'),
(816, 0, 2, '08575152838', '', '\n					Halo *fitri*\nTerimakasih sudah membeli produk kami.\nSaat ini kami sedang menunggu pembayaran darimu sebelum kami memprosesnya. Sebagai informasi, berikut detail pesananmu \n \nNo Invoice: *#2022031971972*\nTotal Pesanan: *Rp 86.972*\nOngkos Kirim: *Rp 11.000*\nKurir Pengiriman: *SICEPAT BEST*\n \nDetail Pengiriman \nPenerima: *fitria*\nNo HP: *085275152838*\nAlamat: *jl beo no 43 ABC*\n \nUntuk pembayaran silahkan langsung klik link berikut\nhttps://winnyputrilubis.id/home/invoice?inv=aTo3MTs=\n				', '', NULL, '2022-03-19 13:42:51', 0, '0000-00-00 00:00:00'),
(817, 0, 1, 'hello@winnyputrilubis.id', 'Winny Putri Lubis - Pesanan Baru', '\n					<h3>Pesanan Baru</h3><br/>\n					<b>FITRI</b> telah membuat pesanan baru dengan total pembayaran \n					<b>Rp. 86.972</b> Invoice ID: <b>#2022031971972</b>\n					<br/>&nbsp;<br/>&nbsp;<br/>\n					Cek Pesanan Pembeli di <b>Dashboard Admin Winny Putri Lubis</b><br/>\n					<a href=\'https://winnyputrilubis.id/cdn\'>Klik Disini</a>\n				', 'Pesanan Baru di Winny Putri Lubis', NULL, '2022-03-19 13:42:51', 0, '0000-00-00 00:00:00'),
(818, 0, 2, '+62 821 6888 8256', '', '\n					*Pesanan Baru*\n*FITRI* telah membuat pesanan baru dengan detail:\nTotal Pembayaran: *Rp. 86.972*\nInvoice ID: *#2022031971972*\n \nCek Pesanan Pembeli di *Dashboard Admin Winny Putri Lubis*\n					', '', NULL, '2022-03-19 13:42:51', 0, '0000-00-00 00:00:00'),
(819, 0, 1, 'hamdaniversi08@gmail.com', 'Winny Putri Lubis', '\n				<html>\n				<head>\n					<style>\n					.border{padding:20px;border-radius:3px;margin:auto;}\n					.pesan{margin-bottom:30px;}\n					.link{margin-bottom:20px;}\n					.alink{text-decoration:none;background:#c0392b;padding:10px 24px;border-radius:3px;margin-bottom:20px;}\n					</style>\n				</head>\n				<body>\n					<div class=\"border\">\n					<div class=\"pesan\">\n					<h3>Halo, Riski Hamdani</h3><p/>\n					Terima kasih sudah mendaftar di <b>Winny Putri Lubis</b>, untuk mengaktifkan akun Anda, silahkan klik link berikut:<br/>\n					</div>\n					<div class=\"link\">\n						<a class=\"alink\" style=\"color:#fff;\" href=\"http://winnyputrilubis.id/auth/signup?verify=YToyOntzOjI6ImlkIjtpOjU2O3M6NDoidGltZSI7czoxNDoiMjAyMjAzMTkxNDE2MzQiO30=\">VERIFIKASI AKUN WINNY PUTRI LUBIS</a>\n						<br/>&nbsp;<br/>atau link dibawah ini<br/>\n						<a href=\"http://winnyputrilubis.id/auth/signup?verify=YToyOntzOjI6ImlkIjtpOjU2O3M6NDoidGltZSI7czoxNDoiMjAyMjAzMTkxNDE2MzQiO30=\">http://winnyputrilubis.id/auth/signup?verify=YToyOntzOjI6ImlkIjtpOjU2O3M6NDoidGltZSI7czoxNDoiMjAyMjAzMTkxNDE2MzQiO30=</a>\n					</div>\n					</div>\n				</body>\n				</html>\n			', 'Verifikasi Pendaftaran Winny Putri Lubis', NULL, '2022-03-19 14:16:34', 0, '0000-00-00 00:00:00'),
(820, 0, 2, '081375078785', '', '\n				Halo, *Riski Hamdani* \\n \\nTerima kasih sudah mendaftar di *Winny Putri Lubis*, untuk mengaktifkan akun Anda, silahkan klik link berikut:\\nhttp://winnyputrilubis.id/auth/signup?verify=YToyOntzOjI6ImlkIjtpOjU2O3M6NDoidGltZSI7czoxNDoiMjAyMjAzMTkxNDE2MzQiO30= \\n_*Apabila link tidak bisa di klik, simpan nomer whatsapp ini terlebih dahulu_\n			', '', NULL, '2022-03-19 14:16:34', 0, '0000-00-00 00:00:00'),
(821, 0, 1, 'hamdaniversi08@gmail.com', 'Winny Putri Lubis - Pesanan', '\n					Halo <b>Riski Hamdani</b><br/>Terimakasih sudah membeli produk kami.<br/>Saat ini kami sedang menunggu pembayaran darimu sebelum kami memprosesnya. Sebagai informasi, berikut detail pesananmu <br/>No Invoice: <b>#2022031972483</b><br/> <br/>Total Pesanan: <b>Rp 82.483</b><br/>Ongkos Kirim: <b>Rp 7.000</b><br/>Kurir Pengiriman: <b>JNE CTC</b><br/> <br/>Detail Pengiriman <br/>Penerima: <b></b> <br/>No HP: <b></b> <br/>Alamat: <b></b><br/> <br/>Untuk pembayaran silahkan langsung klik link berikut:<br/><a href=\'http://winnyputrilubis.id/home/invoice?inv=aTo3Mjs=\'>Bayar Pesanan Sekarang &raquo;</a>\n				', 'Pesanan', NULL, '2022-03-19 14:19:13', 0, '0000-00-00 00:00:00'),
(822, 0, 2, '081375078785', '', '\n					Halo *Riski Hamdani*\nTerimakasih sudah membeli produk kami.\nSaat ini kami sedang menunggu pembayaran darimu sebelum kami memprosesnya. Sebagai informasi, berikut detail pesananmu \n \nNo Invoice: *#2022031972483*\nTotal Pesanan: *Rp 82.483*\nOngkos Kirim: *Rp 7.000*\nKurir Pengiriman: *JNE CTC*\n \nDetail Pengiriman \nPenerima: **\nNo HP: **\nAlamat: **\n \nUntuk pembayaran silahkan langsung klik link berikut\nhttp://winnyputrilubis.id/home/invoice?inv=aTo3Mjs=\n				', '', NULL, '2022-03-19 14:19:13', 0, '0000-00-00 00:00:00'),
(823, 0, 1, 'hello@winnyputrilubis.id', 'Winny Putri Lubis - Pesanan Baru', '\n					<h3>Pesanan Baru</h3><br/>\n					<b>RISKI HAMDANI</b> telah membuat pesanan baru dengan total pembayaran \n					<b>Rp. 82.483</b> Invoice ID: <b>#2022031972483</b>\n					<br/>&nbsp;<br/>&nbsp;<br/>\n					Cek Pesanan Pembeli di <b>Dashboard Admin Winny Putri Lubis</b><br/>\n					<a href=\'http://winnyputrilubis.id/cdn\'>Klik Disini</a>\n				', 'Pesanan Baru di Winny Putri Lubis', NULL, '2022-03-19 14:19:13', 0, '0000-00-00 00:00:00'),
(824, 0, 2, '+62 821 6888 8256', '', '\n					*Pesanan Baru*\n*RISKI HAMDANI* telah membuat pesanan baru dengan detail:\nTotal Pembayaran: *Rp. 82.483*\nInvoice ID: *#2022031972483*\n \nCek Pesanan Pembeli di *Dashboard Admin Winny Putri Lubis*\n					', '', NULL, '2022-03-19 14:19:13', 0, '0000-00-00 00:00:00'),
(825, 0, 1, 'fitrianoveliza@gmail.com', 'Winny Putri Lubis - Pesanan', '\n					Halo <b>fitri</b><br/>Terimakasih sudah membeli produk kami.<br/>Saat ini kami sedang menunggu pembayaran darimu sebelum kami memprosesnya. Sebagai informasi, berikut detail pesananmu <br/>No Invoice: <b>#2022032373117</b><br/> <br/>Total Pesanan: <b>Rp 82.117</b><br/>Ongkos Kirim: <b>Rp 7.000</b><br/>Kurir Pengiriman: <b>JNE CTC</b><br/> <br/>Detail Pengiriman <br/>Penerima: <b>fitria</b> <br/>No HP: <b>085275152838</b> <br/>Alamat: <b>jl beo no 43 ABC</b><br/> <br/>Untuk pembayaran silahkan langsung klik link berikut:<br/><a href=\'https://winnyputrilubis.id/home/invoice?inv=aTo3Mzs=\'>Bayar Pesanan Sekarang &raquo;</a>\n				', 'Pesanan', NULL, '2022-03-23 22:09:14', 0, '0000-00-00 00:00:00'),
(826, 0, 2, '08575152838', '', '\n					Halo *fitri*\nTerimakasih sudah membeli produk kami.\nSaat ini kami sedang menunggu pembayaran darimu sebelum kami memprosesnya. Sebagai informasi, berikut detail pesananmu \n \nNo Invoice: *#2022032373117*\nTotal Pesanan: *Rp 82.117*\nOngkos Kirim: *Rp 7.000*\nKurir Pengiriman: *JNE CTC*\n \nDetail Pengiriman \nPenerima: *fitria*\nNo HP: *085275152838*\nAlamat: *jl beo no 43 ABC*\n \nUntuk pembayaran silahkan langsung klik link berikut\nhttps://winnyputrilubis.id/home/invoice?inv=aTo3Mzs=\n				', '', NULL, '2022-03-23 22:09:14', 0, '0000-00-00 00:00:00'),
(827, 0, 1, 'hello@winnyputrilubis.id', 'Winny Putri Lubis - Pesanan Baru', '\n					<h3>Pesanan Baru</h3><br/>\n					<b>FITRI</b> telah membuat pesanan baru dengan total pembayaran \n					<b>Rp. 82.117</b> Invoice ID: <b>#2022032373117</b>\n					<br/>&nbsp;<br/>&nbsp;<br/>\n					Cek Pesanan Pembeli di <b>Dashboard Admin Winny Putri Lubis</b><br/>\n					<a href=\'https://winnyputrilubis.id/cdn\'>Klik Disini</a>\n				', 'Pesanan Baru di Winny Putri Lubis', NULL, '2022-03-23 22:09:14', 0, '0000-00-00 00:00:00'),
(828, 0, 2, '+62 821 6888 8256', '', '\n					*Pesanan Baru*\n*FITRI* telah membuat pesanan baru dengan detail:\nTotal Pembayaran: *Rp. 82.117*\nInvoice ID: *#2022032373117*\n \nCek Pesanan Pembeli di *Dashboard Admin Winny Putri Lubis*\n					', '', NULL, '2022-03-23 22:09:14', 0, '0000-00-00 00:00:00'),
(829, 0, 1, 'didihansya@gmail.com', 'Winny Putri Lubis - Pesanan', '\n					Halo <b>Didi Khodriansyah</b><br/>Terimakasih sudah membeli produk kami.<br/>Saat ini kami sedang menunggu pembayaran darimu sebelum kami memprosesnya. Sebagai informasi, berikut detail pesananmu <br/>No Invoice: <b>#2022032674359</b><br/> <br/>Total Pesanan: <b>Rp 82.359</b><br/>Ongkos Kirim: <b>Rp 7.000</b><br/>Kurir Pengiriman: <b>JNE CTC</b><br/> <br/>Detail Pengiriman <br/>Penerima: <b>Didi Khodriansyah</b> <br/>No HP: <b>085161900922</b> <br/>Alamat: <b>Jl Karya Bakti Gg Bakti 4 No 30b</b><br/> <br/>Untuk pembayaran silahkan langsung klik link berikut:<br/><a href=\'https://winnyputrilubis.id/home/invoice?inv=aTo3NDs=\'>Bayar Pesanan Sekarang &raquo;</a>\n				', 'Pesanan', NULL, '2022-03-26 13:06:46', 0, '0000-00-00 00:00:00'),
(830, 0, 2, '085161900922', '', '\n					Halo *Didi Khodriansyah*\nTerimakasih sudah membeli produk kami.\nSaat ini kami sedang menunggu pembayaran darimu sebelum kami memprosesnya. Sebagai informasi, berikut detail pesananmu \n \nNo Invoice: *#2022032674359*\nTotal Pesanan: *Rp 82.359*\nOngkos Kirim: *Rp 7.000*\nKurir Pengiriman: *JNE CTC*\n \nDetail Pengiriman \nPenerima: *Didi Khodriansyah*\nNo HP: *085161900922*\nAlamat: *Jl Karya Bakti Gg Bakti 4 No 30b*\n \nUntuk pembayaran silahkan langsung klik link berikut\nhttps://winnyputrilubis.id/home/invoice?inv=aTo3NDs=\n				', '', NULL, '2022-03-26 13:06:46', 0, '0000-00-00 00:00:00'),
(831, 0, 1, 'hello@winnyputrilubis.id', 'Winny Putri Lubis - Pesanan Baru', '\n					<h3>Pesanan Baru</h3><br/>\n					<b>DIDI KHODRIANSYAH</b> telah membuat pesanan baru dengan total pembayaran \n					<b>Rp. 82.359</b> Invoice ID: <b>#2022032674359</b>\n					<br/>&nbsp;<br/>&nbsp;<br/>\n					Cek Pesanan Pembeli di <b>Dashboard Admin Winny Putri Lubis</b><br/>\n					<a href=\'https://winnyputrilubis.id/cdn\'>Klik Disini</a>\n				', 'Pesanan Baru di Winny Putri Lubis', NULL, '2022-03-26 13:06:46', 0, '0000-00-00 00:00:00'),
(832, 0, 2, '+62 821 6888 8256', '', '\n					*Pesanan Baru*\n*DIDI KHODRIANSYAH* telah membuat pesanan baru dengan detail:\nTotal Pembayaran: *Rp. 82.359*\nInvoice ID: *#2022032674359*\n \nCek Pesanan Pembeli di *Dashboard Admin Winny Putri Lubis*\n					', '', NULL, '2022-03-26 13:06:46', 0, '0000-00-00 00:00:00'),
(833, 0, 1, 'widiaarumsari98@gmail.com', 'Winny Putri Lubis', '\n				<html>\n				<head>\n					<style>\n					.border{padding:20px;border-radius:3px;margin:auto;}\n					.pesan{margin-bottom:30px;}\n					.link{margin-bottom:20px;}\n					.alink{text-decoration:none;background:#c0392b;padding:10px 24px;border-radius:3px;margin-bottom:20px;}\n					</style>\n				</head>\n				<body>\n					<div class=\"border\">\n					<div class=\"pesan\">\n					<h3>Halo, Widia</h3><p/>\n					Terima kasih sudah mendaftar di <b>Winny Putri Lubis</b>, untuk mengaktifkan akun Anda, silahkan klik link berikut:<br/>\n					</div>\n					<div class=\"link\">\n						<a class=\"alink\" style=\"color:#fff;\" href=\"https://winnyputrilubis.id/auth/signup?verify=YToyOntzOjI6ImlkIjtpOjU3O3M6NDoidGltZSI7czoxNDoiMjAyMjAzMjgwNzM1MjIiO30=\">VERIFIKASI AKUN WINNY PUTRI LUBIS</a>\n						<br/>&nbsp;<br/>atau link dibawah ini<br/>\n						<a href=\"https://winnyputrilubis.id/auth/signup?verify=YToyOntzOjI6ImlkIjtpOjU3O3M6NDoidGltZSI7czoxNDoiMjAyMjAzMjgwNzM1MjIiO30=\">https://winnyputrilubis.id/auth/signup?verify=YToyOntzOjI6ImlkIjtpOjU3O3M6NDoidGltZSI7czoxNDoiMjAyMjAzMjgwNzM1MjIiO30=</a>\n					</div>\n					</div>\n				</body>\n				</html>\n			', 'Verifikasi Pendaftaran Winny Putri Lubis', NULL, '2022-03-28 07:35:22', 0, '0000-00-00 00:00:00'),
(834, 0, 2, '08999077810', '', '\n				Halo, *Widia* \\n \\nTerima kasih sudah mendaftar di *Winny Putri Lubis*, untuk mengaktifkan akun Anda, silahkan klik link berikut:\\nhttps://winnyputrilubis.id/auth/signup?verify=YToyOntzOjI6ImlkIjtpOjU3O3M6NDoidGltZSI7czoxNDoiMjAyMjAzMjgwNzM1MjIiO30= \\n_*Apabila link tidak bisa di klik, simpan nomer whatsapp ini terlebih dahulu_\n			', '', NULL, '2022-03-28 07:35:22', 0, '0000-00-00 00:00:00'),
(835, 0, 1, 'fitrianoveliza@gmail.com', 'Winny Putri Lubis - Pesanan', '\n					Halo <b>fitri</b><br/>Terimakasih sudah membeli produk kami.<br/>Saat ini kami sedang menunggu pembayaran darimu sebelum kami memprosesnya. Sebagai informasi, berikut detail pesananmu <br/>No Invoice: <b>#2022032975630</b><br/> <br/>Total Pesanan: <b>Rp 82.630</b><br/>Ongkos Kirim: <b>Rp 7.000</b><br/>Kurir Pengiriman: <b>JNE CTC</b><br/> <br/>Detail Pengiriman <br/>Penerima: <b>fitria</b> <br/>No HP: <b>085275152838</b> <br/>Alamat: <b>jl beo no 43 ABC</b><br/> <br/>Untuk pembayaran silahkan langsung klik link berikut:<br/><a href=\'https://winnyputrilubis.id/home/invoice?inv=aTo3NTs=\'>Bayar Pesanan Sekarang &raquo;</a>\n				', 'Pesanan', NULL, '2022-03-29 18:03:31', 0, '0000-00-00 00:00:00'),
(836, 0, 2, '08575152838', '', '\n					Halo *fitri*\nTerimakasih sudah membeli produk kami.\nSaat ini kami sedang menunggu pembayaran darimu sebelum kami memprosesnya. Sebagai informasi, berikut detail pesananmu \n \nNo Invoice: *#2022032975630*\nTotal Pesanan: *Rp 82.630*\nOngkos Kirim: *Rp 7.000*\nKurir Pengiriman: *JNE CTC*\n \nDetail Pengiriman \nPenerima: *fitria*\nNo HP: *085275152838*\nAlamat: *jl beo no 43 ABC*\n \nUntuk pembayaran silahkan langsung klik link berikut\nhttps://winnyputrilubis.id/home/invoice?inv=aTo3NTs=\n				', '', NULL, '2022-03-29 18:03:31', 0, '0000-00-00 00:00:00'),
(837, 0, 1, 'hello@winnyputrilubis.id', 'Winny Putri Lubis - Pesanan Baru', '\n					<h3>Pesanan Baru</h3><br/>\n					<b>FITRI</b> telah membuat pesanan baru dengan total pembayaran \n					<b>Rp. 82.630</b> Invoice ID: <b>#2022032975630</b>\n					<br/>&nbsp;<br/>&nbsp;<br/>\n					Cek Pesanan Pembeli di <b>Dashboard Admin Winny Putri Lubis</b><br/>\n					<a href=\'https://winnyputrilubis.id/cdn\'>Klik Disini</a>\n				', 'Pesanan Baru di Winny Putri Lubis', NULL, '2022-03-29 18:03:32', 0, '0000-00-00 00:00:00'),
(838, 0, 2, '+62 821 6888 8256', '', '\n					*Pesanan Baru*\n*FITRI* telah membuat pesanan baru dengan detail:\nTotal Pembayaran: *Rp. 82.630*\nInvoice ID: *#2022032975630*\n \nCek Pesanan Pembeli di *Dashboard Admin Winny Putri Lubis*\n					', '', NULL, '2022-03-29 18:03:32', 0, '0000-00-00 00:00:00'),
(839, 0, 1, 'fitrianoveliza@gmail.com', 'Winny Putri Lubis - Pesanan', '\n					Halo <b>fitri</b><br/>Terimakasih sudah membeli produk kami.<br/>Saat ini kami sedang menunggu pembayaran darimu sebelum kami memprosesnya. Sebagai informasi, berikut detail pesananmu <br/>No Invoice: <b>#2022032976447</b><br/> <br/>Total Pesanan: <b>Rp 82.447</b><br/>Ongkos Kirim: <b>Rp 7.000</b><br/>Kurir Pengiriman: <b>JNE CTC</b><br/> <br/>Detail Pengiriman <br/>Penerima: <b>fitria</b> <br/>No HP: <b>085275152838</b> <br/>Alamat: <b>jl beo no 43 ABC</b><br/> <br/>Untuk pembayaran silahkan langsung klik link berikut:<br/><a href=\'https://winnyputrilubis.id/home/invoice?inv=aTo3Njs=\'>Bayar Pesanan Sekarang &raquo;</a>\n				', 'Pesanan', NULL, '2022-03-29 18:05:11', 0, '0000-00-00 00:00:00'),
(840, 0, 2, '08575152838', '', '\n					Halo *fitri*\nTerimakasih sudah membeli produk kami.\nSaat ini kami sedang menunggu pembayaran darimu sebelum kami memprosesnya. Sebagai informasi, berikut detail pesananmu \n \nNo Invoice: *#2022032976447*\nTotal Pesanan: *Rp 82.447*\nOngkos Kirim: *Rp 7.000*\nKurir Pengiriman: *JNE CTC*\n \nDetail Pengiriman \nPenerima: *fitria*\nNo HP: *085275152838*\nAlamat: *jl beo no 43 ABC*\n \nUntuk pembayaran silahkan langsung klik link berikut\nhttps://winnyputrilubis.id/home/invoice?inv=aTo3Njs=\n				', '', NULL, '2022-03-29 18:05:11', 0, '0000-00-00 00:00:00'),
(841, 0, 1, 'hello@winnyputrilubis.id', 'Winny Putri Lubis - Pesanan Baru', '\n					<h3>Pesanan Baru</h3><br/>\n					<b>FITRI</b> telah membuat pesanan baru dengan total pembayaran \n					<b>Rp. 82.447</b> Invoice ID: <b>#2022032976447</b>\n					<br/>&nbsp;<br/>&nbsp;<br/>\n					Cek Pesanan Pembeli di <b>Dashboard Admin Winny Putri Lubis</b><br/>\n					<a href=\'https://winnyputrilubis.id/cdn\'>Klik Disini</a>\n				', 'Pesanan Baru di Winny Putri Lubis', NULL, '2022-03-29 18:05:11', 0, '0000-00-00 00:00:00'),
(842, 0, 2, '+62 821 6888 8256', '', '\n					*Pesanan Baru*\n*FITRI* telah membuat pesanan baru dengan detail:\nTotal Pembayaran: *Rp. 82.447*\nInvoice ID: *#2022032976447*\n \nCek Pesanan Pembeli di *Dashboard Admin Winny Putri Lubis*\n					', '', NULL, '2022-03-29 18:05:11', 0, '0000-00-00 00:00:00'),
(843, 0, 1, 'fitrianoveliza@gmail.com', 'Winny Putri Lubis - Pesanan', '\n					Halo <b>fitri</b><br/>Terimakasih sudah membeli produk kami.<br/>Saat ini kami sedang menunggu pembayaran darimu sebelum kami memprosesnya. Sebagai informasi, berikut detail pesananmu <br/>No Invoice: <b>#2022040177267</b><br/> <br/>Total Pesanan: <b>Rp 102.267</b><br/>Ongkos Kirim: <b>Rp 7.000</b><br/>Kurir Pengiriman: <b>JNE CTC</b><br/> <br/>Detail Pengiriman <br/>Penerima: <b>fitria</b> <br/>No HP: <b>085275152838</b> <br/>Alamat: <b>jl beo no 43 ABC</b><br/> <br/>Untuk pembayaran silahkan langsung klik link berikut:<br/><a href=\'https://winnyputrilubis.id/home/invoice?inv=aTo3Nzs=\'>Bayar Pesanan Sekarang &raquo;</a>\n				', 'Pesanan', NULL, '2022-04-01 12:02:42', 0, '0000-00-00 00:00:00'),
(844, 0, 2, '08575152838', '', '\n					Halo *fitri*\nTerimakasih sudah membeli produk kami.\nSaat ini kami sedang menunggu pembayaran darimu sebelum kami memprosesnya. Sebagai informasi, berikut detail pesananmu \n \nNo Invoice: *#2022040177267*\nTotal Pesanan: *Rp 102.267*\nOngkos Kirim: *Rp 7.000*\nKurir Pengiriman: *JNE CTC*\n \nDetail Pengiriman \nPenerima: *fitria*\nNo HP: *085275152838*\nAlamat: *jl beo no 43 ABC*\n \nUntuk pembayaran silahkan langsung klik link berikut\nhttps://winnyputrilubis.id/home/invoice?inv=aTo3Nzs=\n				', '', NULL, '2022-04-01 12:02:42', 0, '0000-00-00 00:00:00'),
(845, 0, 1, 'hello@winnyputrilubis.id', 'Winny Putri Lubis - Pesanan Baru', '\n					<h3>Pesanan Baru</h3><br/>\n					<b>FITRI</b> telah membuat pesanan baru dengan total pembayaran \n					<b>Rp. 102.267</b> Invoice ID: <b>#2022040177267</b>\n					<br/>&nbsp;<br/>&nbsp;<br/>\n					Cek Pesanan Pembeli di <b>Dashboard Admin Winny Putri Lubis</b><br/>\n					<a href=\'https://winnyputrilubis.id/cdn\'>Klik Disini</a>\n				', 'Pesanan Baru di Winny Putri Lubis', NULL, '2022-04-01 12:02:42', 0, '0000-00-00 00:00:00'),
(846, 0, 2, '+62 821 6888 8256', '', '\n					*Pesanan Baru*\n*FITRI* telah membuat pesanan baru dengan detail:\nTotal Pembayaran: *Rp. 102.267*\nInvoice ID: *#2022040177267*\n \nCek Pesanan Pembeli di *Dashboard Admin Winny Putri Lubis*\n					', '', NULL, '2022-04-01 12:02:42', 0, '0000-00-00 00:00:00'),
(847, 0, 1, 'Astria_novita30@yahoo.com', 'Winny Putri Lubis', '\n				<html>\n				<head>\n					<style>\n					.border{padding:20px;border-radius:3px;margin:auto;}\n					.pesan{margin-bottom:30px;}\n					.link{margin-bottom:20px;}\n					.alink{text-decoration:none;background:#c0392b;padding:10px 24px;border-radius:3px;margin-bottom:20px;}\n					</style>\n				</head>\n				<body>\n					<div class=\"border\">\n					<div class=\"pesan\">\n					<h3>Halo, Astria novita</h3><p/>\n					Terima kasih sudah mendaftar di <b>Winny Putri Lubis</b>, untuk mengaktifkan akun Anda, silahkan klik link berikut:<br/>\n					</div>\n					<div class=\"link\">\n						<a class=\"alink\" style=\"color:#fff;\" href=\"https://winnyputrilubis.id/auth/signup?verify=YToyOntzOjI6ImlkIjtpOjU4O3M6NDoidGltZSI7czoxNDoiMjAyMjA0MDMxMDE2MTUiO30=\">VERIFIKASI AKUN WINNY PUTRI LUBIS</a>\n						<br/>&nbsp;<br/>atau link dibawah ini<br/>\n						<a href=\"https://winnyputrilubis.id/auth/signup?verify=YToyOntzOjI6ImlkIjtpOjU4O3M6NDoidGltZSI7czoxNDoiMjAyMjA0MDMxMDE2MTUiO30=\">https://winnyputrilubis.id/auth/signup?verify=YToyOntzOjI6ImlkIjtpOjU4O3M6NDoidGltZSI7czoxNDoiMjAyMjA0MDMxMDE2MTUiO30=</a>\n					</div>\n					</div>\n				</body>\n				</html>\n			', 'Verifikasi Pendaftaran Winny Putri Lubis', NULL, '2022-04-03 10:16:15', 0, '0000-00-00 00:00:00'),
(848, 0, 2, '081265203534', '', '\n				Halo, *Astria novita* \\n \\nTerima kasih sudah mendaftar di *Winny Putri Lubis*, untuk mengaktifkan akun Anda, silahkan klik link berikut:\\nhttps://winnyputrilubis.id/auth/signup?verify=YToyOntzOjI6ImlkIjtpOjU4O3M6NDoidGltZSI7czoxNDoiMjAyMjA0MDMxMDE2MTUiO30= \\n_*Apabila link tidak bisa di klik, simpan nomer whatsapp ini terlebih dahulu_\n			', '', NULL, '2022-04-03 10:16:15', 0, '0000-00-00 00:00:00');
INSERT INTO `blw_pesan_notifikasi` (`id`, `usrid`, `jenis`, `tujuan`, `judul`, `pesan`, `subyek`, `pengirim`, `tgl`, `status`, `proses`) VALUES
(849, 0, 1, 'Astria_novita30@yahoo.com', 'Winny Putri Lubis - Pesanan', '\n					Halo <b>Astria novita</b><br/>Terimakasih sudah membeli produk kami.<br/>Saat ini kami sedang menunggu pembayaran darimu sebelum kami memprosesnya. Sebagai informasi, berikut detail pesananmu <br/>No Invoice: <b>#2022040378898</b><br/> <br/>Total Pesanan: <b>Rp 220.898</b><br/>Ongkos Kirim: <b>Rp 10.000</b><br/>Kurir Pengiriman: <b>JNE REG</b><br/> <br/>Detail Pengiriman <br/>Penerima: <b>Astria novita</b> <br/>No HP: <b>081265203534</b> <br/>Alamat: <b>Jl. Sederhana Dusun VII , tandem pasar 6 tionghua \r\nNo.37 Kab. Deli serdang - Hamparan perak , sumatera utara \r\nKode pos 20374</b><br/> <br/>Untuk pembayaran silahkan langsung klik link berikut:<br/><a href=\'https://winnyputrilubis.id/home/invoice?inv=aTo3ODs=\'>Bayar Pesanan Sekarang &raquo;</a>\n				', 'Pesanan', NULL, '2022-04-03 10:18:53', 0, '0000-00-00 00:00:00'),
(850, 0, 2, '081265203534', '', '\n					Halo *Astria novita*\nTerimakasih sudah membeli produk kami.\nSaat ini kami sedang menunggu pembayaran darimu sebelum kami memprosesnya. Sebagai informasi, berikut detail pesananmu \n \nNo Invoice: *#2022040378898*\nTotal Pesanan: *Rp 220.898*\nOngkos Kirim: *Rp 10.000*\nKurir Pengiriman: *JNE REG*\n \nDetail Pengiriman \nPenerima: *Astria novita*\nNo HP: *081265203534*\nAlamat: *Jl. Sederhana Dusun VII , tandem pasar 6 tionghua \r\nNo.37 Kab. Deli serdang - Hamparan perak , sumatera utara \r\nKode pos 20374*\n \nUntuk pembayaran silahkan langsung klik link berikut\nhttps://winnyputrilubis.id/home/invoice?inv=aTo3ODs=\n				', '', NULL, '2022-04-03 10:18:53', 0, '0000-00-00 00:00:00'),
(851, 0, 1, 'hello@winnyputrilubis.id', 'Winny Putri Lubis - Pesanan Baru', '\n					<h3>Pesanan Baru</h3><br/>\n					<b>ASTRIA NOVITA</b> telah membuat pesanan baru dengan total pembayaran \n					<b>Rp. 220.898</b> Invoice ID: <b>#2022040378898</b>\n					<br/>&nbsp;<br/>&nbsp;<br/>\n					Cek Pesanan Pembeli di <b>Dashboard Admin Winny Putri Lubis</b><br/>\n					<a href=\'https://winnyputrilubis.id/cdn\'>Klik Disini</a>\n				', 'Pesanan Baru di Winny Putri Lubis', NULL, '2022-04-03 10:18:53', 0, '0000-00-00 00:00:00'),
(852, 0, 2, '+62 821 6888 8256', '', '\n					*Pesanan Baru*\n*ASTRIA NOVITA* telah membuat pesanan baru dengan detail:\nTotal Pembayaran: *Rp. 220.898*\nInvoice ID: *#2022040378898*\n \nCek Pesanan Pembeli di *Dashboard Admin Winny Putri Lubis*\n					', '', NULL, '2022-04-03 10:18:53', 0, '0000-00-00 00:00:00'),
(853, 0, 1, 'Astria_novita30@yahoo.com', 'Winny Putri Lubis - Resi Pengiriman Pesanan', '\r\n					Berikut resi pengiriman untuk pesanan anda di <b>Winny Putri Lubis</b><br/>\r\n					Resi: <b style=\'font-size:120%\'>040620005384722</b><br/>&nbsp;<br/>&nbsp;<br/>\r\n					Lacak pengirimannya langsung di menu <b>pesananku</b><br/>\r\n					<a href=\'https://localhost/botoko/account/order\'>Klik Disini</a>\r\n				', 'Resi Pengiriman', NULL, '2022-04-06 11:46:22', 0, '0000-00-00 00:00:00'),
(854, 0, 2, '081265203534', '', '\r\n					Berikut resi pengiriman untuk pesanan anda di *Winny Putri Lubis* \nResi: *040620005384722* \n \nLacak pengirimannya langsung di menu *pesananku* \n https://localhost/botoko/account/order\r\n				', '', NULL, '2022-04-06 11:46:22', 0, '0000-00-00 00:00:00'),
(855, 0, 1, 'fitrianoveliza@gmail.com', 'Winny Putri Lubis - Pesanan', '\n					Halo <b>fitri</b><br/>Terimakasih sudah membeli produk kami.<br/>Saat ini kami sedang menunggu pembayaran darimu sebelum kami memprosesnya. Sebagai informasi, berikut detail pesananmu <br/>No Invoice: <b>#2022041179376</b><br/> <br/>Total Pesanan: <b>Rp 102.376</b><br/>Ongkos Kirim: <b>Rp 7.000</b><br/>Kurir Pengiriman: <b>SICEPAT REG</b><br/> <br/>Detail Pengiriman <br/>Penerima: <b>fitria</b> <br/>No HP: <b>085275152838</b> <br/>Alamat: <b>jl beo no 43 ABC</b><br/> <br/>Untuk pembayaran silahkan langsung klik link berikut:<br/><a href=\'https://winnyputrilubis.id/home/invoice?inv=aTo3OTs=\'>Bayar Pesanan Sekarang &raquo;</a>\n				', 'Pesanan', NULL, '2022-04-11 20:29:18', 0, '0000-00-00 00:00:00'),
(856, 0, 2, '08575152838', '', '\n					Halo *fitri*\nTerimakasih sudah membeli produk kami.\nSaat ini kami sedang menunggu pembayaran darimu sebelum kami memprosesnya. Sebagai informasi, berikut detail pesananmu \n \nNo Invoice: *#2022041179376*\nTotal Pesanan: *Rp 102.376*\nOngkos Kirim: *Rp 7.000*\nKurir Pengiriman: *SICEPAT REG*\n \nDetail Pengiriman \nPenerima: *fitria*\nNo HP: *085275152838*\nAlamat: *jl beo no 43 ABC*\n \nUntuk pembayaran silahkan langsung klik link berikut\nhttps://winnyputrilubis.id/home/invoice?inv=aTo3OTs=\n				', '', NULL, '2022-04-11 20:29:18', 0, '0000-00-00 00:00:00'),
(857, 0, 1, 'hello@winnyputrilubis.id', 'Winny Putri Lubis - Pesanan Baru', '\n					<h3>Pesanan Baru</h3><br/>\n					<b>FITRI</b> telah membuat pesanan baru dengan total pembayaran \n					<b>Rp. 102.376</b> Invoice ID: <b>#2022041179376</b>\n					<br/>&nbsp;<br/>&nbsp;<br/>\n					Cek Pesanan Pembeli di <b>Dashboard Admin Winny Putri Lubis</b><br/>\n					<a href=\'https://winnyputrilubis.id/cdn\'>Klik Disini</a>\n				', 'Pesanan Baru di Winny Putri Lubis', NULL, '2022-04-11 20:29:19', 0, '0000-00-00 00:00:00'),
(858, 0, 2, '+62 821 6888 8256', '', '\n					*Pesanan Baru*\n*FITRI* telah membuat pesanan baru dengan detail:\nTotal Pembayaran: *Rp. 102.376*\nInvoice ID: *#2022041179376*\n \nCek Pesanan Pembeli di *Dashboard Admin Winny Putri Lubis*\n					', '', NULL, '2022-04-11 20:29:19', 0, '0000-00-00 00:00:00'),
(859, 0, 1, 'fitrianoveliza@gmail.com', 'Winny Putri Lubis - Pesanan', '\n					Halo <b>fitri</b><br/>Terimakasih sudah membeli produk kami.<br/>Saat ini kami sedang menunggu pembayaran darimu sebelum kami memprosesnya. Sebagai informasi, berikut detail pesananmu <br/>No Invoice: <b>#2022041380553</b><br/> <br/>Total Pesanan: <b>Rp 86.053</b><br/>Ongkos Kirim: <b>Rp 7.000</b><br/>Kurir Pengiriman: <b>JNE CTC</b><br/> <br/>Detail Pengiriman <br/>Penerima: <b>fitria</b> <br/>No HP: <b>085275152838</b> <br/>Alamat: <b>jl beo no 43 ABC</b><br/> <br/>Untuk pembayaran silahkan langsung klik link berikut:<br/><a href=\'https://winnyputrilubis.id/home/invoice?inv=aTo4MDs=\'>Bayar Pesanan Sekarang &raquo;</a>\n				', 'Pesanan', NULL, '2022-04-13 20:39:51', 0, '0000-00-00 00:00:00'),
(860, 0, 2, '08575152838', '', '\n					Halo *fitri*\nTerimakasih sudah membeli produk kami.\nSaat ini kami sedang menunggu pembayaran darimu sebelum kami memprosesnya. Sebagai informasi, berikut detail pesananmu \n \nNo Invoice: *#2022041380553*\nTotal Pesanan: *Rp 86.053*\nOngkos Kirim: *Rp 7.000*\nKurir Pengiriman: *JNE CTC*\n \nDetail Pengiriman \nPenerima: *fitria*\nNo HP: *085275152838*\nAlamat: *jl beo no 43 ABC*\n \nUntuk pembayaran silahkan langsung klik link berikut\nhttps://winnyputrilubis.id/home/invoice?inv=aTo4MDs=\n				', '', NULL, '2022-04-13 20:39:51', 0, '0000-00-00 00:00:00'),
(861, 0, 1, 'hello@winnyputrilubis.id', 'Winny Putri Lubis - Pesanan Baru', '\n					<h3>Pesanan Baru</h3><br/>\n					<b>FITRI</b> telah membuat pesanan baru dengan total pembayaran \n					<b>Rp. 86.053</b> Invoice ID: <b>#2022041380553</b>\n					<br/>&nbsp;<br/>&nbsp;<br/>\n					Cek Pesanan Pembeli di <b>Dashboard Admin Winny Putri Lubis</b><br/>\n					<a href=\'https://winnyputrilubis.id/cdn\'>Klik Disini</a>\n				', 'Pesanan Baru di Winny Putri Lubis', NULL, '2022-04-13 20:39:51', 0, '0000-00-00 00:00:00'),
(862, 0, 2, '+62 821 6888 8256', '', '\n					*Pesanan Baru*\n*FITRI* telah membuat pesanan baru dengan detail:\nTotal Pembayaran: *Rp. 86.053*\nInvoice ID: *#2022041380553*\n \nCek Pesanan Pembeli di *Dashboard Admin Winny Putri Lubis*\n					', '', NULL, '2022-04-13 20:39:51', 0, '0000-00-00 00:00:00'),
(863, 0, 1, 'leginag422@gmail.com', 'Winny Putri Lubis', '\n				<html>\n				<head>\n					<style>\n					.border{padding:20px;border-radius:3px;margin:auto;}\n					.pesan{margin-bottom:30px;}\n					.link{margin-bottom:20px;}\n					.alink{text-decoration:none;background:#c0392b;padding:10px 24px;border-radius:3px;margin-bottom:20px;}\n					</style>\n				</head>\n				<body>\n					<div class=\"border\">\n					<div class=\"pesan\">\n					<h3>Halo, Egina</h3><p/>\n					Terima kasih sudah mendaftar di <b>Winny Putri Lubis</b>, untuk mengaktifkan akun Anda, silahkan klik link berikut:<br/>\n					</div>\n					<div class=\"link\">\n						<a class=\"alink\" style=\"color:#fff;\" href=\"https://winnyputrilubis.id/auth/signup?verify=YToyOntzOjI6ImlkIjtpOjU5O3M6NDoidGltZSI7czoxNDoiMjAyMjA2MTMyMTU0NDAiO30=\">VERIFIKASI AKUN WINNY PUTRI LUBIS</a>\n						<br/>&nbsp;<br/>atau link dibawah ini<br/>\n						<a href=\"https://winnyputrilubis.id/auth/signup?verify=YToyOntzOjI6ImlkIjtpOjU5O3M6NDoidGltZSI7czoxNDoiMjAyMjA2MTMyMTU0NDAiO30=\">https://winnyputrilubis.id/auth/signup?verify=YToyOntzOjI6ImlkIjtpOjU5O3M6NDoidGltZSI7czoxNDoiMjAyMjA2MTMyMTU0NDAiO30=</a>\n					</div>\n					</div>\n				</body>\n				</html>\n			', 'Verifikasi Pendaftaran Winny Putri Lubis', NULL, '2022-06-13 21:54:40', 0, '0000-00-00 00:00:00'),
(864, 0, 2, '081310667020', '', '\n				Halo, *Egina* \\n \\nTerima kasih sudah mendaftar di *Winny Putri Lubis*, untuk mengaktifkan akun Anda, silahkan klik link berikut:\\nhttps://winnyputrilubis.id/auth/signup?verify=YToyOntzOjI6ImlkIjtpOjU5O3M6NDoidGltZSI7czoxNDoiMjAyMjA2MTMyMTU0NDAiO30= \\n_*Apabila link tidak bisa di klik, simpan nomer whatsapp ini terlebih dahulu_\n			', '', NULL, '2022-06-13 21:54:40', 0, '0000-00-00 00:00:00'),
(865, 0, 1, 'leginag422@gmail.com', 'Winny Putri Lubis - Pesanan', '\n					Halo <b>Egina</b><br/>Terimakasih sudah membeli produk kami.<br/>Saat ini kami sedang menunggu pembayaran darimu sebelum kami memprosesnya. Sebagai informasi, berikut detail pesananmu <br/>No Invoice: <b>#2022061381277</b><br/> <br/>Total Pesanan: <b>Rp 154.277</b><br/>Ongkos Kirim: <b>Rp 29.000</b><br/>Kurir Pengiriman: <b>SICEPAT REG</b><br/> <br/>Detail Pengiriman <br/>Penerima: <b>Egina wafi</b> <br/>No HP: <b>081310667020</b> <br/>Alamat: <b>Jorong VII polongan dua, depan tower telkomsel, rao kabupaten Pasaman sumatra barat id. 26353</b><br/> <br/>Untuk pembayaran silahkan langsung klik link berikut:<br/><a href=\'https://winnyputrilubis.id/home/invoice?inv=aTo4MTs=\'>Bayar Pesanan Sekarang &raquo;</a>\n				', 'Pesanan', NULL, '2022-06-13 22:01:00', 0, '0000-00-00 00:00:00'),
(866, 0, 2, '081310667020', '', '\n					Halo *Egina*\nTerimakasih sudah membeli produk kami.\nSaat ini kami sedang menunggu pembayaran darimu sebelum kami memprosesnya. Sebagai informasi, berikut detail pesananmu \n \nNo Invoice: *#2022061381277*\nTotal Pesanan: *Rp 154.277*\nOngkos Kirim: *Rp 29.000*\nKurir Pengiriman: *SICEPAT REG*\n \nDetail Pengiriman \nPenerima: *Egina wafi*\nNo HP: *081310667020*\nAlamat: *Jorong VII polongan dua, depan tower telkomsel, rao kabupaten Pasaman sumatra barat id. 26353*\n \nUntuk pembayaran silahkan langsung klik link berikut\nhttps://winnyputrilubis.id/home/invoice?inv=aTo4MTs=\n				', '', NULL, '2022-06-13 22:01:00', 0, '0000-00-00 00:00:00'),
(867, 0, 1, 'hello@winnyputrilubis.id', 'Winny Putri Lubis - Pesanan Baru', '\n					<h3>Pesanan Baru</h3><br/>\n					<b>EGINA</b> telah membuat pesanan baru dengan total pembayaran \n					<b>Rp. 154.277</b> Invoice ID: <b>#2022061381277</b>\n					<br/>&nbsp;<br/>&nbsp;<br/>\n					Cek Pesanan Pembeli di <b>Dashboard Admin Winny Putri Lubis</b><br/>\n					<a href=\'https://winnyputrilubis.id/cdn\'>Klik Disini</a>\n				', 'Pesanan Baru di Winny Putri Lubis', NULL, '2022-06-13 22:01:00', 0, '0000-00-00 00:00:00'),
(868, 0, 2, '+62 852-7515-2838', '', '\n					*Pesanan Baru*\n*EGINA* telah membuat pesanan baru dengan detail:\nTotal Pembayaran: *Rp. 154.277*\nInvoice ID: *#2022061381277*\n \nCek Pesanan Pembeli di *Dashboard Admin Winny Putri Lubis*\n					', '', NULL, '2022-06-13 22:01:00', 0, '0000-00-00 00:00:00'),
(869, 0, 1, 'jenny.pratama@outlook.com', 'Winny Putri Lubis', '\n				<html>\n				<head>\n					<style>\n					.border{padding:20px;border-radius:3px;margin:auto;}\n					.pesan{margin-bottom:30px;}\n					.link{margin-bottom:20px;}\n					.alink{text-decoration:none;background:#c0392b;padding:10px 24px;border-radius:3px;margin-bottom:20px;}\n					</style>\n				</head>\n				<body>\n					<div class=\"border\">\n					<div class=\"pesan\">\n					<h3>Halo, Jenny Pratama</h3><p/>\n					Terima kasih sudah mendaftar di <b>Winny Putri Lubis</b>, untuk mengaktifkan akun Anda, silahkan klik link berikut:<br/>\n					</div>\n					<div class=\"link\">\n						<a class=\"alink\" style=\"color:#fff;\" href=\"https://winnyputrilubis.id/auth/signup?verify=YToyOntzOjI6ImlkIjtpOjYwO3M6NDoidGltZSI7czoxNDoiMjAyMjA3MjkxMzE1NDciO30=\">VERIFIKASI AKUN WINNY PUTRI LUBIS</a>\n						<br/>&nbsp;<br/>atau link dibawah ini<br/>\n						<a href=\"https://winnyputrilubis.id/auth/signup?verify=YToyOntzOjI6ImlkIjtpOjYwO3M6NDoidGltZSI7czoxNDoiMjAyMjA3MjkxMzE1NDciO30=\">https://winnyputrilubis.id/auth/signup?verify=YToyOntzOjI6ImlkIjtpOjYwO3M6NDoidGltZSI7czoxNDoiMjAyMjA3MjkxMzE1NDciO30=</a>\n					</div>\n					</div>\n				</body>\n				</html>\n			', 'Verifikasi Pendaftaran Winny Putri Lubis', NULL, '2022-07-29 13:15:47', 0, '0000-00-00 00:00:00'),
(870, 0, 2, '6282360399922', '', '\n				Halo, *Jenny Pratama* \\n \\nTerima kasih sudah mendaftar di *Winny Putri Lubis*, untuk mengaktifkan akun Anda, silahkan klik link berikut:\\nhttps://winnyputrilubis.id/auth/signup?verify=YToyOntzOjI6ImlkIjtpOjYwO3M6NDoidGltZSI7czoxNDoiMjAyMjA3MjkxMzE1NDciO30= \\n_*Apabila link tidak bisa di klik, simpan nomer whatsapp ini terlebih dahulu_\n			', '', NULL, '2022-07-29 13:15:47', 0, '0000-00-00 00:00:00'),
(871, 0, 1, 'jenny.pratama@outlook.com', 'Winny Putri Lubis - Pesanan', '\n					Halo <b>Jenny Pratama</b><br/>Terimakasih sudah membeli produk kami.<br/>Saat ini kami sedang menunggu pembayaran darimu sebelum kami memprosesnya. Sebagai informasi, berikut detail pesananmu <br/>No Invoice: <b>#2022072982782</b><br/> <br/>Total Pesanan: <b>Rp 161.782</b><br/>Ongkos Kirim: <b>Rp 11.000</b><br/>Kurir Pengiriman: <b>SICEPAT BEST</b><br/> <br/>Detail Pengiriman <br/>Penerima: <b>Jenny Pratama</b> <br/>No HP: <b>6282360399922</b> <br/>Alamat: <b>Komp. Mutiara Residence. JL. Rumah Sakit Haji, Pancing Tembung Medan. Blok V. No. 8A</b><br/> <br/>Untuk pembayaran silahkan langsung klik link berikut:<br/><a href=\'https://winnyputrilubis.id/home/invoice?inv=aTo4Mjs=\'>Bayar Pesanan Sekarang &raquo;</a>\n				', 'Pesanan', NULL, '2022-07-29 13:20:45', 0, '0000-00-00 00:00:00'),
(872, 0, 2, '6282360399922', '', '\n					Halo *Jenny Pratama*\nTerimakasih sudah membeli produk kami.\nSaat ini kami sedang menunggu pembayaran darimu sebelum kami memprosesnya. Sebagai informasi, berikut detail pesananmu \n \nNo Invoice: *#2022072982782*\nTotal Pesanan: *Rp 161.782*\nOngkos Kirim: *Rp 11.000*\nKurir Pengiriman: *SICEPAT BEST*\n \nDetail Pengiriman \nPenerima: *Jenny Pratama*\nNo HP: *6282360399922*\nAlamat: *Komp. Mutiara Residence. JL. Rumah Sakit Haji, Pancing Tembung Medan. Blok V. No. 8A*\n \nUntuk pembayaran silahkan langsung klik link berikut\nhttps://winnyputrilubis.id/home/invoice?inv=aTo4Mjs=\n				', '', NULL, '2022-07-29 13:20:45', 0, '0000-00-00 00:00:00'),
(873, 0, 1, 'hello@winnyputrilubis.id', 'Winny Putri Lubis - Pesanan Baru', '\n					<h3>Pesanan Baru</h3><br/>\n					<b>JENNY PRATAMA</b> telah membuat pesanan baru dengan total pembayaran \n					<b>Rp. 161.782</b> Invoice ID: <b>#2022072982782</b>\n					<br/>&nbsp;<br/>&nbsp;<br/>\n					Cek Pesanan Pembeli di <b>Dashboard Admin Winny Putri Lubis</b><br/>\n					<a href=\'https://winnyputrilubis.id/cdn\'>Klik Disini</a>\n				', 'Pesanan Baru di Winny Putri Lubis', NULL, '2022-07-29 13:20:45', 0, '0000-00-00 00:00:00'),
(874, 0, 2, '+62 852-7515-2838', '', '\n					*Pesanan Baru*\n*JENNY PRATAMA* telah membuat pesanan baru dengan detail:\nTotal Pembayaran: *Rp. 161.782*\nInvoice ID: *#2022072982782*\n \nCek Pesanan Pembeli di *Dashboard Admin Winny Putri Lubis*\n					', '', NULL, '2022-07-29 13:20:45', 0, '0000-00-00 00:00:00');

-- --------------------------------------------------------

--
-- Table structure for table `blw_preorder`
--

CREATE TABLE `blw_preorder` (
  `id` int(11) NOT NULL,
  `orderid` varchar(200) NOT NULL,
  `usrid` int(11) NOT NULL,
  `idproduk` int(11) NOT NULL,
  `tgl` datetime NOT NULL,
  `jumlah` int(11) NOT NULL,
  `variasi` int(11) NOT NULL,
  `harga` int(11) NOT NULL,
  `bukti` text NOT NULL,
  `total` bigint(20) NOT NULL,
  `saldo` int(11) NOT NULL,
  `transfer` int(11) NOT NULL,
  `kodebayar` int(11) NOT NULL,
  `status` int(11) NOT NULL,
  `xendit_id` varchar(100) DEFAULT NULL,
  `xendit_url` varchar(300) DEFAULT NULL
) ENGINE=MyISAM DEFAULT CHARSET=latin1;

-- --------------------------------------------------------

--
-- Table structure for table `blw_produk`
--

CREATE TABLE `blw_produk` (
  `id` int(11) NOT NULL,
  `tglbuat` datetime NOT NULL,
  `tglupdate` datetime NOT NULL,
  `nama` varchar(160) NOT NULL,
  `kode` text NOT NULL,
  `url` text NOT NULL,
  `deskripsi` text NOT NULL,
  `idcat` int(11) NOT NULL,
  `berat` int(11) NOT NULL,
  `harga` int(11) NOT NULL,
  `hargacoret` int(11) NOT NULL,
  `hargareseller` int(11) NOT NULL,
  `hargaagen` int(11) NOT NULL,
  `hargaagensp` int(11) NOT NULL,
  `hargadistri` int(11) NOT NULL,
  `minorder` int(11) NOT NULL,
  `stok` int(11) NOT NULL COMMENT '1=ready,2=preorder',
  `variasi` text NOT NULL,
  `subvariasi` text NOT NULL,
  `status` int(11) NOT NULL COMMENT '0=verif,1=online,2=blokir',
  `preorder_id` int(11) NOT NULL,
  `tglpo` date NOT NULL,
  `idtoko` int(11) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Dumping data for table `blw_produk`
--

INSERT INTO `blw_produk` (`id`, `tglbuat`, `tglupdate`, `nama`, `kode`, `url`, `deskripsi`, `idcat`, `berat`, `harga`, `hargacoret`, `hargareseller`, `hargaagen`, `hargaagensp`, `hargadistri`, `minorder`, `stok`, `variasi`, `subvariasi`, `status`, `preorder_id`, `tglpo`, `idtoko`) VALUES
(24, '2022-03-18 00:54:20', '2022-07-14 15:57:58', 'Premium Breast Ampoule Pengencang Payudara Ultraglow', 'UG-PBA', 'Premium-Breast-Ampoule-Pengencang-Payudara-Ultraglow-005420', '<p><span style=\"caret-color: rgba(0, 0, 0, 0.8); color: rgba(0, 0, 0, 0.8); font-family: Roboto, \'Helvetica Neue\', Helvetica, Arial, ?????, \'WenQuanYi Zen Hei\', \'Hiragino Sans GB\', \'?? Pro\', \'LiHei Pro\', \'Heiti TC\', ?????, \'Microsoft JhengHei UI\', \'Microsoft JhengHei\', sans-serif; font-size: 14px; white-space: pre-wrap; background-color: #ffffff;\">Breast Ampoule, serum payudara dengan concentrate tinggi, fokus untuk mengencangkan &amp; memadatkan payudara yang kendur / lembek, akibat menyusui ataupun faktor lain. Dapat menghilangkan stretchmark di payudara Mengandung DNA Salmon &amp; Green Caviar Produk sudah BPOM Hasil dapat terlihat dalam 1 minggu pemakaian.</span></p>', 27, 250, 375000, 0, 0, 0, 0, 0, 1, 2435, '-', '-', 1, 0, '0000-00-00', NULL),
(26, '2022-03-18 01:00:46', '2022-03-18 01:07:25', '15gr Premium Breast Cream, Pengencang & Pembesar Payudara Ultraglow', 'UG-PBC', '15gr-Premium-Breast-Cream-Pengencang-Pembesar-Payudara-Ultraglow-010046', '<p><span style=\"caret-color: rgba(0, 0, 0, 0.8); color: rgba(0, 0, 0, 0.8); font-family: Roboto, \'Helvetica Neue\', Helvetica, Arial, ?????, \'WenQuanYi Zen Hei\', \'Hiragino Sans GB\', \'?? Pro\', \'LiHei Pro\', \'Heiti TC\', ?????, \'Microsoft JhengHei UI\', \'Microsoft JhengHei\', sans-serif; font-size: 14px; white-space: pre-wrap; background-color: #ffffff;\">Krim utk mengencangkan payudara, sudah bersertifikat HALAL &amp; BPOM.. . AMAN utk BUMIL &amp; BUSUI . Manfaat : - menghilangkan stretchmark di payudara - mengencangkan payudara - menaikkan ukuran cup payudara 1-2size (pemakaian rutin minimal 2bulan) - mengeraskan payudara yang kendur . Cara pakai : sehari dipakai 2x saat pagi setelah mandi &amp; malam sebelum tidur, usapkan ke area payudara / bokong dengan gerakan melingkar sambil dipijat pelan selama 3menit.. . Perubahan akan terlihat dalam pemakaian 2 minggu</span></p>', 27, 250, 260000, 0, 0, 0, 0, 0, 1, 4988, '-', '-', 1, 0, '0000-00-00', NULL),
(27, '2022-03-18 01:02:48', '2022-03-18 01:07:03', '30gr Premium Breast Cream, Pengencang & Pembesar Payudara Ultraglow', 'UG-PBC', '30gr-Premium-Breast-Cream-Pengencang-Pembesar-Payudara-Ultraglow-010248', '<p><span style=\"caret-color: rgba(0, 0, 0, 0.8); color: rgba(0, 0, 0, 0.8); font-family: Roboto, \'Helvetica Neue\', Helvetica, Arial, ?????, \'WenQuanYi Zen Hei\', \'Hiragino Sans GB\', \'?? Pro\', \'LiHei Pro\', \'Heiti TC\', ?????, \'Microsoft JhengHei UI\', \'Microsoft JhengHei\', sans-serif; font-size: 14px; white-space: pre-wrap; background-color: #ffffff;\">Krim utk mengencangkan payudara, sudah bersertifikat HALAL &amp; BPOM.. . AMAN utk BUMIL &amp; BUSUI . Manfaat : - menghilangkan stretchmark di payudara - mengencangkan payudara - menaikkan ukuran cup payudara 1-2size (pemakaian rutin minimal 2bulan) - mengeraskan payudara yang kendur . Cara pakai : sehari dipakai 2x saat pagi setelah mandi &amp; malam sebelum tidur, usapkan ke area payudara / bokong dengan gerakan melingkar sambil dipijat pelan selama 3menit.. . Perubahan akan terlihat dalam pemakaian 2 minggu</span></p>', 27, 250, 480000, 0, 0, 0, 0, 0, 1, 4988, '-', '-', 1, 0, '0000-00-00', NULL),
(28, '2022-03-18 01:04:05', '2022-03-18 01:07:15', '50gr Premium Breast Cream, Pengencang & Pembesar Payudara Ultraglow', 'UG-PBA', '50gr-Premium-Breast-Cream-Pengencang-Pembesar-Payudara-Ultraglow-010405', '<p><span style=\"caret-color: rgba(0, 0, 0, 0.8); color: rgba(0, 0, 0, 0.8); font-family: Roboto, \'Helvetica Neue\', Helvetica, Arial, ?????, \'WenQuanYi Zen Hei\', \'Hiragino Sans GB\', \'?? Pro\', \'LiHei Pro\', \'Heiti TC\', ?????, \'Microsoft JhengHei UI\', \'Microsoft JhengHei\', sans-serif; font-size: 14px; white-space: pre-wrap; background-color: #ffffff;\">Krim utk mengencangkan payudara, sudah bersertifikat HALAL &amp; BPOM.. . AMAN utk BUMIL &amp; BUSUI . Manfaat : - menghilangkan stretchmark di payudara - mengencangkan payudara - menaikkan ukuran cup payudara 1-2size (pemakaian rutin minimal 2bulan) - mengeraskan payudara yang kendur . Cara pakai : sehari dipakai 2x saat pagi setelah mandi &amp; malam sebelum tidur, usapkan ke area payudara / bokong dengan gerakan melingkar sambil dipijat pelan selama 3menit.. . Perubahan akan terlihat dalam pemakaian 2 minggu</span></p>', 27, 250, 650000, 0, 0, 0, 0, 0, 1, 4988, '-', '-', 1, 0, '0000-00-00', NULL),
(29, '2022-03-18 01:06:47', '2022-07-14 15:26:36', 'Daily Breast Gel Pengencang dan Pembesar Payudara (low budget versi murah) Ultraglow', 'UG-DBG', 'Daily-Breast-Gel-Pengencang-dan-Pembesar-Payudara-low-budget-versi-murah-Ultraglow-010647', '<p><span style=\"caret-color: rgba(0, 0, 0, 0.8); color: rgba(0, 0, 0, 0.8); font-family: Roboto, \'Helvetica Neue\', Helvetica, Arial, ?????, \'WenQuanYi Zen Hei\', \'Hiragino Sans GB\', \'?? Pro\', \'LiHei Pro\', \'Heiti TC\', ?????, \'Microsoft JhengHei UI\', \'Microsoft JhengHei\', sans-serif; font-size: 14px; white-space: pre-wrap; background-color: #ffffff;\">Krim utk mengencangkan payudara, sudah bersertifikat HALAL &amp; BPOM.. . AMAN utk BUMIL &amp; BUSUI . Manfaat : - menghilangkan stretchmark di payudara - mengencangkan payudara - menaikkan ukuran cup payudara 1-2size (pemakaian rutin minimal 2bulan) - mengeraskan payudara yang kendur . Cara pakai : sehari dipakai 2-3x saat pagi setelah mandi &amp; malam sebelum tidur, usapkan ke area payudara / bokong dengan gerakan melingkar sambil dipijat pelan selama 3menit.. . Perubahan akan terlihat dalam pemakaian 2 minggu</span></p>', 27, 250, 125000, 0, 0, 0, 0, 0, 1, 3653, '-', '-', 1, 0, '0000-00-00', NULL),
(30, '2022-03-18 01:09:33', '2022-03-18 01:09:33', 'Breast & Collagen Drink minuman pengencang payudara', 'WPL-BCD', 'Breast-Collagen-Drink-minuman-pengencang-payudara-010933', '<p><span style=\"caret-color: rgba(0, 0, 0, 0.8); color: rgba(0, 0, 0, 0.8); font-family: Roboto, \'Helvetica Neue\', Helvetica, Arial, ?????, \'WenQuanYi Zen Hei\', \'Hiragino Sans GB\', \'?? Pro\', \'LiHei Pro\', \'Heiti TC\', ?????, \'Microsoft JhengHei UI\', \'Microsoft JhengHei\', sans-serif; font-size: 14px; white-space: pre-wrap; background-color: #ffffff;\">Satu satunya di Indonesia, minuman BPOM yang bekerja dari dalam untuk mengencangkan payudara.. Bisa memperbesar payudara 1-2 cup.. Sudah BPOM &amp; HALAL.. Mengandung collagen juga jadi bisa untuk mencerahkan kulit ????????????</span></p>', 27, 500, 225000, 0, 0, 0, 0, 0, 1, 2356, '-', '-', 1, 0, '0000-00-00', NULL),
(31, '2022-03-18 01:12:47', '2022-03-18 01:12:47', 'Brightening Series WPL Skincare', 'WPL-BS', 'Brightening-Series-WPL-Skincare-011247', '<p><span style=\"caret-color: rgba(0, 0, 0, 0.8); color: rgba(0, 0, 0, 0.8); font-family: Roboto, \'Helvetica Neue\', Helvetica, Arial, ?????, \'WenQuanYi Zen Hei\', \'Hiragino Sans GB\', \'?? Pro\', \'LiHei Pro\', \'Heiti TC\', ?????, \'Microsoft JhengHei UI\', \'Microsoft JhengHei\', sans-serif; font-size: 14px; white-space: pre-wrap; background-color: #ffffff;\">Skincare BPOM &amp; aman untuk BUMIL &amp; BUSUI</span></p>', 26, 350, 199000, 0, 0, 0, 0, 0, 1, 2654, '-', '-', 1, 0, '0000-00-00', NULL),
(32, '2022-03-18 01:14:06', '2022-03-18 01:14:06', 'Brightening Serum WPL Skincare', 'WPL-BS', 'Brightening-Serum-WPL-Skincare-011406', '<p><span style=\"caret-color: rgba(0, 0, 0, 0.8); color: rgba(0, 0, 0, 0.8); font-family: Roboto, \'Helvetica Neue\', Helvetica, Arial, ?????, \'WenQuanYi Zen Hei\', \'Hiragino Sans GB\', \'?? Pro\', \'LiHei Pro\', \'Heiti TC\', ?????, \'Microsoft JhengHei UI\', \'Microsoft JhengHei\', sans-serif; font-size: 14px; white-space: pre-wrap; background-color: #ffffff;\">Serum untuk mencerahkan wajah, sudah BPOM</span></p>', 26, 250, 85000, 0, 0, 0, 0, 0, 1, 1123, '-', '-', 1, 0, '0000-00-00', NULL),
(33, '2022-03-18 01:15:10', '2022-04-13 20:39:51', 'Brightening Day Cream WPL Skincare', 'WPL-BDC', 'Brightening-Day-Cream-WPL-Skincare-011510', '<p><span style=\"caret-color: rgba(0, 0, 0, 0.8); color: rgba(0, 0, 0, 0.8); font-family: Roboto, \'Helvetica Neue\', Helvetica, Arial, ?????, \'WenQuanYi Zen Hei\', \'Hiragino Sans GB\', \'?? Pro\', \'LiHei Pro\', \'Heiti TC\', ?????, \'Microsoft JhengHei UI\', \'Microsoft JhengHei\', sans-serif; font-size: 14px; white-space: pre-wrap; background-color: #ffffff;\">Krim siang dengan sunscreen SPF 30, untuk kulit normal.. </span></p>', 26, 250, 78500, 0, 0, 0, 0, 0, 1, 2890, '-', '-', 1, 0, '0000-00-00', NULL),
(34, '2022-03-18 01:17:11', '2022-04-11 20:29:18', '30 Capsule - MakeSlim Pelangsing BPOM Herbal JAMU', 'MS', '30-Capsule-MakeSlim-Pelangsing-BPOM-Herbal-JAMU-011711', '<p><span style=\"caret-color: rgba(0, 0, 0, 0.8); color: rgba(0, 0, 0, 0.8); font-family: Roboto, \'Helvetica Neue\', Helvetica, Arial, ?????, \'WenQuanYi Zen Hei\', \'Hiragino Sans GB\', \'?? Pro\', \'LiHei Pro\', \'Heiti TC\', ?????, \'Microsoft JhengHei UI\', \'Microsoft JhengHei\', sans-serif; font-size: 14px; white-space: pre-wrap; background-color: #ffffff;\">Pil pelangsing aman sudah BPOM Terbuat dari bahan yang alami</span></p>', 25, 250, 95000, 0, 0, 0, 0, 0, 1, 4864, '-', '-', 1, 0, '0000-00-00', NULL),
(35, '2022-03-18 01:18:05', '2022-03-18 01:18:05', '60 Capsule - MakeSlim Pelangsing BPOM Herbal JAMU', 'MS', '60-Capsule-MakeSlim-Pelangsing-BPOM-Herbal-JAMU-011805', '<p><span style=\"caret-color: rgba(0, 0, 0, 0.8); color: rgba(0, 0, 0, 0.8); font-family: Roboto, \'Helvetica Neue\', Helvetica, Arial, ?????, \'WenQuanYi Zen Hei\', \'Hiragino Sans GB\', \'?? Pro\', \'LiHei Pro\', \'Heiti TC\', ?????, \'Microsoft JhengHei UI\', \'Microsoft JhengHei\', sans-serif; font-size: 14px; white-space: pre-wrap; background-color: #ffffff;\">Pil pelangsing aman sudah BPOM Terbuat dari bahan yang alami</span></p>', 25, 250, 175000, 0, 0, 0, 0, 0, 1, 4883, '-', '-', 1, 0, '0000-00-00', NULL),
(36, '2022-03-18 01:20:16', '2022-07-14 15:55:45', 'Madu D-Healthy by dr Dony Siregar & Winny Putri Lubis', 'DH', 'Madu-D-Healthy-by-dr-Dony-Siregar-Winny-Putri-Lubis-012016', '<p><span style=\"caret-color: rgba(0, 0, 0, 0.8); color: rgba(0, 0, 0, 0.8); font-family: Roboto, \'Helvetica Neue\', Helvetica, Arial, ?????, \'WenQuanYi Zen Hei\', \'Hiragino Sans GB\', \'?? Pro\', \'LiHei Pro\', \'Heiti TC\', ?????, \'Microsoft JhengHei UI\', \'Microsoft JhengHei\', sans-serif; font-size: 14px; white-space: pre-wrap; background-color: #ffffff;\">Madu alami untuk memelihara kesehatan tubuh, AMAN &amp; BPOM.. Dengan kandungan : - Madu - Kurma - Daun Kelor - Jahe Merah - Temulawak - Ekstrak Manggis Harga sangat terjangkau dengan kualitas yang sangat bagus..</span></p>', 25, 250, 155000, 0, 0, 0, 0, 0, 1, 2935, '-', '-', 1, 0, '0000-00-00', NULL),
(37, '2022-03-18 01:22:06', '2022-07-14 16:00:43', 'WPL Collagen Whitening Drink', 'WPL-CWD', 'WPL-Collagen-Whitening-Drink-012206', '<p><span style=\"caret-color: rgba(0, 0, 0, 0.8); color: rgba(0, 0, 0, 0.8); font-family: Roboto, \'Helvetica Neue\', Helvetica, Arial, ?????, \'WenQuanYi Zen Hei\', \'Hiragino Sans GB\', \'?? Pro\', \'LiHei Pro\', \'Heiti TC\', ?????, \'Microsoft JhengHei UI\', \'Microsoft JhengHei\', sans-serif; font-size: 14px; white-space: pre-wrap; background-color: #ffffff;\">Minuman pemutih tubuh yang mengandung : Collagen, Vitamin C, Glutathione, dll Sudah BPOM &amp; HALAL</span></p>', 25, 250, 85000, 0, 0, 0, 0, 0, 1, 1332, '-', '-', 1, 0, '0000-00-00', NULL),
(38, '2022-03-18 01:23:45', '2022-03-18 01:23:45', 'Acne Series WPL Skincare', 'WPL-AS', 'Acne-Series-WPL-Skincare-012345', '<p><span style=\"caret-color: rgba(0, 0, 0, 0.8); color: rgba(0, 0, 0, 0.8); font-family: Roboto, \'Helvetica Neue\', Helvetica, Arial, ?????, \'WenQuanYi Zen Hei\', \'Hiragino Sans GB\', \'?? Pro\', \'LiHei Pro\', \'Heiti TC\', ?????, \'Microsoft JhengHei UI\', \'Microsoft JhengHei\', sans-serif; font-size: 14px; white-space: pre-wrap; background-color: #ffffff;\">Skincare BPOM &amp; aman untuk BUMIL &amp; BUSUI</span></p>', 29, 250, 195000, 0, 0, 0, 0, 0, 1, 1203, '-', '-', 1, 0, '0000-00-00', NULL),
(39, '2022-03-18 01:24:46', '2022-03-18 01:24:46', 'Acne Serum WPL Skincare', 'WPL-AS', 'Acne-Serum-WPL-Skincare-012446', '<p><span style=\"caret-color: rgba(0, 0, 0, 0.8); color: rgba(0, 0, 0, 0.8); font-family: Roboto, \'Helvetica Neue\', Helvetica, Arial, ?????, \'WenQuanYi Zen Hei\', \'Hiragino Sans GB\', \'?? Pro\', \'LiHei Pro\', \'Heiti TC\', ?????, \'Microsoft JhengHei UI\', \'Microsoft JhengHei\', sans-serif; font-size: 14px; white-space: pre-wrap; background-color: #ffffff;\">Serum untuk kulit berjerawat dan berminyak</span></p>', 29, 250, 79000, 0, 0, 0, 0, 0, 1, 1247, '-', '-', 1, 0, '0000-00-00', NULL),
(40, '2022-03-18 01:25:48', '2022-03-18 01:25:48', 'Acne Day Cream WPL Skincare', 'WPL-ADC', 'Acne-Day-Cream-WPL-Skincare-012548', '<p><span style=\"caret-color: rgba(0, 0, 0, 0.8); color: rgba(0, 0, 0, 0.8); font-family: Roboto, \'Helvetica Neue\', Helvetica, Arial, ?????, \'WenQuanYi Zen Hei\', \'Hiragino Sans GB\', \'?? Pro\', \'LiHei Pro\', \'Heiti TC\', ?????, \'Microsoft JhengHei UI\', \'Microsoft JhengHei\', sans-serif; font-size: 14px; white-space: pre-wrap; background-color: #ffffff;\">Krim siang dengan sunscreen, SPF 15.. Sudah BPOM.. Khusus untuk kulit yang berjerawat, berminyak, dan pori2 besar</span></p>', 29, 250, 73000, 0, 0, 0, 0, 0, 1, 1027, '-', '-', 1, 0, '0000-00-00', NULL),
(41, '2022-03-18 01:27:27', '2022-07-14 16:05:26', 'Body Serum Ultraglow Rich Concentrated', 'UG-BS', 'Body-Serum-Ultraglow-Rich-Concentrated-012727', '<p><span style=\"caret-color: rgba(0, 0, 0, 0.8); color: rgba(0, 0, 0, 0.8); font-family: Roboto, \'Helvetica Neue\', Helvetica, Arial, ?????, \'WenQuanYi Zen Hei\', \'Hiragino Sans GB\', \'?? Pro\', \'LiHei Pro\', \'Heiti TC\', ?????, \'Microsoft JhengHei UI\', \'Microsoft JhengHei\', sans-serif; font-size: 14px; white-space: pre-wrap; background-color: #ffffff;\">Body Serum Premium dengan harga paling terjangkau ? Mengandung : Green Caviar, Collagen, Salmon DNA, HA (Hyaluronic Acid), Stemcell, Niacinamide,dll.. . Dapat memutihkan kulit, dan sangat baik untuk anti aging, mencegah kerutan.. Dalam 1 minggu hasil sudah kelihatan, sudah BPOM..</span></p>', 30, 300, 245000, 0, 0, 0, 0, 0, 1, 2818, '-', '-', 1, 0, '0000-00-00', NULL),
(42, '2022-03-18 01:28:56', '2022-07-14 16:03:18', 'Body Lotion Ultraglow', 'UG-BL', 'Body-Lotion-Ultraglow-012856', '<p><span style=\"caret-color: rgba(0, 0, 0, 0.8); color: rgba(0, 0, 0, 0.8); font-family: Roboto, \'Helvetica Neue\', Helvetica, Arial, ?????, \'WenQuanYi Zen Hei\', \'Hiragino Sans GB\', \'?? Pro\', \'LiHei Pro\', \'Heiti TC\', ?????, \'Microsoft JhengHei UI\', \'Microsoft JhengHei\', sans-serif; font-size: 14px; white-space: pre-wrap; background-color: #ffffff;\">Lotion Premium dengan harga paling terjangkau.. Premium Lotion with : Stemcell, Gold Nest (ekstrak wallet), HA (Hyaluronic Acid), CICA (Centella Asiatica), High Collagen, Niacinamide, dll ?</span></p>', 30, 300, 210000, 0, 0, 0, 0, 0, 1, 4868, '-', '-', 1, 0, '0000-00-00', NULL),
(43, '2022-03-18 01:30:18', '2022-03-18 22:26:59', 'Set Body Serum & Lotion Premium Ultraglow by Winny Putri Lubis', 'UG-BSLP', 'Set-Body-Serum-Lotion-Premium-Ultraglow-by-Winny-Putri-Lubis-013018', '<p><span style=\"caret-color: rgba(0, 0, 0, 0.8); color: rgba(0, 0, 0, 0.8); font-family: Roboto, \'Helvetica Neue\', Helvetica, Arial, ?????, \'WenQuanYi Zen Hei\', \'Hiragino Sans GB\', \'?? Pro\', \'LiHei Pro\', \'Heiti TC\', ?????, \'Microsoft JhengHei UI\', \'Microsoft JhengHei\', sans-serif; font-size: 14px; white-space: pre-wrap; background-color: #ffffff;\">1 Set : 1 Body Serum &amp; 1 Body Lotion Premium Ultraglow, harga menjadi lebih HEMAT ????????</span></p>', 30, 500, 450000, 0, 0, 0, 0, 0, 1, 951, '-', '-', 1, 0, '0000-00-00', NULL),
(44, '2022-03-18 01:31:36', '2022-07-29 13:20:45', 'Brightening Night Cream WPL Skincare', 'WPL-BNC', 'Brightening-Night-Cream-WPL-Skincare-013136', '<p><span style=\"caret-color: rgba(0, 0, 0, 0.8); color: rgba(0, 0, 0, 0.8); font-family: Roboto, \'Helvetica Neue\', Helvetica, Arial, ?????, \'WenQuanYi Zen Hei\', \'Hiragino Sans GB\', \'?? Pro\', \'LiHei Pro\', \'Heiti TC\', ?????, \'Microsoft JhengHei UI\', \'Microsoft JhengHei\', sans-serif; font-size: 14px; white-space: pre-wrap; background-color: #ffffff;\">Krim malam untuk kulit normal.. </span></p>', 31, 250, 75000, 0, 0, 0, 0, 0, 1, 2848, '-', '-', 1, 0, '0000-00-00', NULL),
(45, '2022-03-18 01:32:34', '2022-03-18 01:32:34', 'Acne Night Cream WPL Skincare', 'WPL-ANC', 'Acne-Night-Cream-WPL-Skincare-013234', '<p><span style=\"caret-color: rgba(0, 0, 0, 0.8); color: rgba(0, 0, 0, 0.8); font-family: Roboto, \'Helvetica Neue\', Helvetica, Arial, ?????, \'WenQuanYi Zen Hei\', \'Hiragino Sans GB\', \'?? Pro\', \'LiHei Pro\', \'Heiti TC\', ?????, \'Microsoft JhengHei UI\', \'Microsoft JhengHei\', sans-serif; font-size: 14px; white-space: pre-wrap; background-color: #ffffff;\">Krim malam khusus untuk kulit yang berjerawat, berminyak, dan pori2 besar</span></p>', 31, 250, 75000, 0, 0, 0, 0, 0, 1, 1060, '-', '-', 1, 0, '0000-00-00', NULL),
(46, '2022-03-18 01:35:44', '2022-03-18 01:35:44', 'Acne Series - Platinum WPL Skincare', 'WPL-PAS', 'Acne-Series-Platinum-WPL-Skincare-013544', '<p><span style=\"caret-color: rgba(0, 0, 0, 0.8); color: rgba(0, 0, 0, 0.8); font-family: Roboto, \'Helvetica Neue\', Helvetica, Arial, ?????, \'WenQuanYi Zen Hei\', \'Hiragino Sans GB\', \'?? Pro\', \'LiHei Pro\', \'Heiti TC\', ?????, \'Microsoft JhengHei UI\', \'Microsoft JhengHei\', sans-serif; font-size: 14px; white-space: pre-wrap; background-color: #ffffff;\">Paket lengkap skincare : - Facial Wash - Face Essence - Serum - Day Cream - Night Cream</span></p>', 32, 500, 275000, 0, 0, 0, 0, 0, 1, 1586, '-', '-', 1, 0, '0000-00-00', NULL),
(47, '2022-03-18 01:36:45', '2022-03-18 01:36:45', 'Brightening Series - Platinum WPL Skincare', 'WPL-PBS', 'Brightening-Series-Platinum-WPL-Skincare-013645', '<p><span style=\"caret-color: rgba(0, 0, 0, 0.8); color: rgba(0, 0, 0, 0.8); font-family: Roboto, \'Helvetica Neue\', Helvetica, Arial, ?????, \'WenQuanYi Zen Hei\', \'Hiragino Sans GB\', \'?? Pro\', \'LiHei Pro\', \'Heiti TC\', ?????, \'Microsoft JhengHei UI\', \'Microsoft JhengHei\', sans-serif; font-size: 14px; white-space: pre-wrap; background-color: #ffffff;\">Paket lengkap skincare : - Facial Wash - Face Essence - Serum - Day Cream - Night Cream</span></p>', 32, 500, 280000, 0, 0, 0, 0, 0, 1, 1559, '-', '-', 1, 0, '0000-00-00', NULL),
(48, '2022-03-18 01:38:21', '2022-07-14 15:52:29', 'WPL Whitening Deodorant', 'WPL-WD', 'WPL-Whitening-Deodorant-013821', '<p><span style=\"caret-color: rgba(0, 0, 0, 0.8); color: rgba(0, 0, 0, 0.8); font-family: Roboto, \'Helvetica Neue\', Helvetica, Arial, ?????, \'WenQuanYi Zen Hei\', \'Hiragino Sans GB\', \'?? Pro\', \'LiHei Pro\', \'Heiti TC\', ?????, \'Microsoft JhengHei UI\', \'Microsoft JhengHei\', sans-serif; font-size: 14px; white-space: pre-wrap; background-color: #ffffff;\">Deodorant khusus whitening, sdh BPOM 100% alami dan natural aman utk bumil &amp; busui</span></p>', 35, 250, 80000, 0, 0, 0, 0, 0, 1, 2038, '-', '-', 1, 0, '0000-00-00', NULL),
(49, '2022-03-18 01:39:55', '2022-03-18 01:39:55', 'Jelly Booster WPL', 'WPL-JB', 'Jelly-Booster-WPL-013955', '<p><span style=\"caret-color: rgba(0, 0, 0, 0.8); color: rgba(0, 0, 0, 0.8); font-family: Roboto, \'Helvetica Neue\', Helvetica, Arial, ?????, \'WenQuanYi Zen Hei\', \'Hiragino Sans GB\', \'?? Pro\', \'LiHei Pro\', \'Heiti TC\', ?????, \'Microsoft JhengHei UI\', \'Microsoft JhengHei\', sans-serif; font-size: 14px; white-space: pre-wrap; background-color: #ffffff;\">Dipakai pada malam hari setelah pemakaian essence.. Dilarang memakai serum pada saat menggunakan Jelly Booster.. Step pemakaian WPL Skincare : Pagi : Facial Wash - Face Essence - Serum - Day Cream Malam : Facial Wash - Face Essence - Serum / Jelly Booster (pilih salah 1, tdk boleh ditimpa) - Night Cream</span></p>', 34, 250, 135000, 0, 0, 0, 0, 0, 1, 4353, '-', '-', 1, 0, '0000-00-00', NULL);

-- --------------------------------------------------------

--
-- Table structure for table `blw_produk_upload`
--

CREATE TABLE `blw_produk_upload` (
  `id` int(11) NOT NULL,
  `idproduk` int(11) NOT NULL,
  `jenis` int(11) NOT NULL,
  `nama` text NOT NULL,
  `tgl` datetime NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Dumping data for table `blw_produk_upload`
--

INSERT INTO `blw_produk_upload` (`id`, `idproduk`, `jenis`, `nama`, `tgl`) VALUES
(73, 24, 0, '120220318005409.jpg', '2022-03-18 00:54:09'),
(75, 26, 0, '120220318010001.jpg', '2022-03-18 01:00:01'),
(76, 26, 1, '120220318010034.jpg', '2022-03-18 01:00:34'),
(77, 27, 1, '120220318010239.jpg', '2022-03-18 01:02:39'),
(78, 27, 0, '120220318010245.jpg', '2022-03-18 01:02:46'),
(79, 28, 1, '120220318010343.jpg', '2022-03-18 01:03:43'),
(80, 28, 0, '120220318010357.jpg', '2022-03-18 01:03:57'),
(83, 30, 1, '120220318010929.jpg', '2022-03-18 01:09:30'),
(84, 31, 1, '120220318011243.jpg', '2022-03-18 01:12:43'),
(85, 32, 1, '120220318011356.jpg', '2022-03-18 01:13:56'),
(86, 33, 1, '120220318011508.jpg', '2022-03-18 01:15:08'),
(87, 34, 1, '120220318011703.jpg', '2022-03-18 01:17:03'),
(88, 35, 1, '120220318011803.jpg', '2022-03-18 01:18:03'),
(93, 38, 1, '120220318012333.jpg', '2022-03-18 01:23:33'),
(94, 39, 1, '120220318012425.jpg', '2022-03-18 01:24:25'),
(95, 40, 1, '120220318012526.jpg', '2022-03-18 01:25:26'),
(96, 41, 0, '120220318012716.jpg', '2022-03-18 01:27:16'),
(97, 41, 1, '120220318012725.jpg', '2022-03-18 01:27:25'),
(99, 42, 0, '120220318012854.jpg', '2022-03-18 01:28:54'),
(100, 43, 1, '120220318012928.jpg', '2022-03-18 01:29:28'),
(101, 44, 1, '120220318013129.jpg', '2022-03-18 01:31:29'),
(102, 45, 1, '120220318013203.jpg', '2022-03-18 01:32:03'),
(103, 46, 1, '120220318013507.jpg', '2022-03-18 01:35:07'),
(104, 46, 0, '120220318013513.jpg', '2022-03-18 01:35:13'),
(105, 47, 1, '120220318013625.jpg', '2022-03-18 01:36:25'),
(106, 47, 0, '120220318013631.jpg', '2022-03-18 01:36:31'),
(108, 49, 1, '120220318013933.jpg', '2022-03-18 01:39:33'),
(109, 29, 1, '120220714152512.jpg', '2022-07-14 15:25:12'),
(110, 29, 0, '120220714152540.jpg', '2022-07-14 15:25:40'),
(111, 29, 0, '120220714152625.JPG', '2022-07-14 15:26:25'),
(112, 48, 1, '120220714152734.jpg', '2022-07-14 15:27:34'),
(113, 48, 0, '120220714152746.jpg', '2022-07-14 15:27:46'),
(114, 48, 0, '120220714152833.JPG', '2022-07-14 15:28:33'),
(115, 36, 1, '120220714155524.jpg', '2022-07-14 15:55:24'),
(116, 36, 0, '120220714155536.jpg', '2022-07-14 15:55:36'),
(117, 24, 1, '120220714155729.jpg', '2022-07-14 15:57:29'),
(118, 24, 0, '120220714155742.jpg', '2022-07-14 15:57:42'),
(119, 24, 0, '120220714155754.jpg', '2022-07-14 15:57:54'),
(120, 37, 1, '120220714160002.jpg', '2022-07-14 16:00:02'),
(121, 37, 0, '120220714160021.jpg', '2022-07-14 16:00:21'),
(122, 42, 1, '120220714160253.jpg', '2022-07-14 16:02:53'),
(123, 42, 0, '120220714160315.jpg', '2022-07-14 16:03:15'),
(124, 41, 0, '120220714160521.JPG', '2022-07-14 16:05:21');

-- --------------------------------------------------------

--
-- Table structure for table `blw_produk_variasi`
--

CREATE TABLE `blw_produk_variasi` (
  `id` int(11) NOT NULL,
  `kode` varchar(300) COLLATE utf8_bin NOT NULL,
  `idproduk` int(11) NOT NULL,
  `warna` int(11) NOT NULL,
  `size` int(11) NOT NULL,
  `stok` int(11) NOT NULL,
  `kuota` int(11) NOT NULL,
  `harga` int(11) NOT NULL,
  `hargareseller` int(11) NOT NULL,
  `hargaagen` int(11) NOT NULL,
  `hargaagensp` int(11) NOT NULL,
  `hargadistri` int(11) NOT NULL,
  `tgl` datetime NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_bin;

-- --------------------------------------------------------

--
-- Table structure for table `blw_produk_variasisize`
--

CREATE TABLE `blw_produk_variasisize` (
  `id` int(11) NOT NULL,
  `tgl` datetime NOT NULL,
  `usrid` int(11) NOT NULL,
  `nama` text COLLATE utf8_bin NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_bin;

-- --------------------------------------------------------

--
-- Table structure for table `blw_produk_variasiwarna`
--

CREATE TABLE `blw_produk_variasiwarna` (
  `id` int(11) NOT NULL,
  `tgl` datetime NOT NULL,
  `usrid` int(11) NOT NULL,
  `nama` text COLLATE utf8_bin NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_bin;

-- --------------------------------------------------------

--
-- Table structure for table `blw_sales`
--

CREATE TABLE `blw_sales` (
  `id` int(11) NOT NULL,
  `orderid` varchar(300) NOT NULL,
  `tgl` datetime NOT NULL,
  `kadaluarsa` datetime NOT NULL,
  `usrid` int(11) NOT NULL,
  `dropship` varchar(200) NOT NULL,
  `dropshipnomer` varchar(100) NOT NULL,
  `dropshipalamat` text NOT NULL,
  `alamat` int(11) NOT NULL,
  `dp` int(11) NOT NULL,
  `berat` int(11) NOT NULL,
  `ongkir` int(11) NOT NULL,
  `kurir` varchar(100) NOT NULL,
  `paket` varchar(100) NOT NULL,
  `dari` int(11) NOT NULL,
  `tujuan` int(11) NOT NULL,
  `resi` varchar(200) NOT NULL,
  `kirim` datetime NOT NULL,
  `status` int(11) NOT NULL COMMENT '0=blmbayar,1=dikemas,2=dikirim,3=diterima,4=tolak',
  `idbayar` int(11) NOT NULL,
  `selesai` datetime NOT NULL,
  `ajukanbatal` int(11) NOT NULL,
  `keterangan` text NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Dumping data for table `blw_sales`
--

INSERT INTO `blw_sales` (`id`, `orderid`, `tgl`, `kadaluarsa`, `usrid`, `dropship`, `dropshipnomer`, `dropshipalamat`, `alamat`, `dp`, `berat`, `ongkir`, `kurir`, `paket`, `dari`, `tujuan`, `resi`, `kirim`, `status`, `idbayar`, `selesai`, `ajukanbatal`, `keterangan`) VALUES
(63, 'TRX20220318221041', '2022-03-18 22:10:41', '2022-03-20 22:10:41', 54, '', '', '', 0, 0, 250, 7000, 'jne', 'CTC', 278, 278, '', '0000-00-00 00:00:00', 4, 63, '2022-03-19 01:22:50', 0, 'dibatalkan oleh pembeli.'),
(64, 'TRX20220318221159', '2022-03-18 22:11:59', '2022-03-20 22:11:59', 54, '', '', '', 0, 0, 250, 11000, 'sicepat', 'BEST', 278, 278, '', '0000-00-00 00:00:00', 4, 64, '2022-03-19 01:23:05', 0, 'dibatalkan oleh pembeli.'),
(65, 'TRX20220318221325', '2022-03-18 22:13:25', '2022-03-20 22:13:25', 54, '', '', '', 33, 0, 250, 0, 'gosend', '', 278, 278, '', '0000-00-00 00:00:00', 4, 65, '2022-03-19 01:22:57', 0, 'dibatalkan oleh pembeli.'),
(66, 'TRX20220318221614', '2022-03-18 22:16:14', '2022-03-20 22:16:14', 55, '', '', '', 34, 0, 250, 7000, 'jne', 'CTC', 278, 278, '', '0000-00-00 00:00:00', 4, 66, '2022-03-18 22:22:31', 0, 'dibatalkan oleh pembeli.'),
(67, 'TRX20220318222034', '2022-03-18 22:20:34', '2022-03-20 22:20:34', 55, '', '', '', 34, 0, 250, 11000, 'sicepat', 'BEST', 278, 278, '', '0000-00-00 00:00:00', 4, 67, '2022-03-18 22:23:01', 0, 'dibatalkan oleh pembeli.'),
(68, 'TRX20220318222531', '2022-03-18 22:25:31', '2022-03-20 22:25:31', 55, '', '', '', 34, 0, 250, 7000, 'jne', 'CTC', 278, 278, '', '0000-00-00 00:00:00', 4, 68, '2022-03-18 22:27:38', 0, 'dibatalkan oleh pembeli.'),
(69, 'TRX20220318223858', '2022-03-18 22:38:58', '2022-03-20 22:38:58', 54, '', '', '', 33, 0, 250, 0, 'gosend', '', 278, 278, '', '0000-00-00 00:00:00', 4, 69, '2022-03-19 01:22:01', 0, 'dibatalkan oleh pembeli.'),
(70, 'TRX20220319005537', '2022-03-19 00:55:37', '2022-03-21 00:55:37', 54, '', '', '', 33, 0, 250, 0, 'gosend', '', 278, 278, '', '0000-00-00 00:00:00', 4, 70, '2022-03-19 01:15:52', 0, 'dibatalkan oleh pembeli.'),
(71, 'TRX20220319134251', '2022-03-19 13:42:51', '2022-03-21 13:42:51', 54, '', '', '', 33, 0, 250, 11000, 'sicepat', 'BEST', 278, 278, '', '0000-00-00 00:00:00', 4, 71, '2022-03-21 13:45:14', 0, 'dibatalkan oleh sistem, karena melewati batas waktu pembayaran'),
(72, 'TRX20220319141913', '2022-03-19 14:19:13', '2022-03-21 14:19:13', 56, '', '', '', 0, 0, 250, 7000, 'jne', 'CTC', 278, 278, '', '0000-00-00 00:00:00', 4, 72, '2022-03-21 14:46:52', 0, 'dibatalkan oleh sistem, karena melewati batas waktu pembayaran'),
(73, 'TRX20220323220914', '2022-03-23 22:09:14', '2022-03-25 22:09:14', 54, '', '', '', 33, 0, 250, 7000, 'jne', 'CTC', 278, 278, '', '0000-00-00 00:00:00', 4, 73, '2022-03-24 22:39:49', 0, 'dibatalkan oleh pembeli.'),
(74, 'TRX20220326130646', '2022-03-26 13:06:46', '2022-03-28 13:06:46', 55, '', '', '', 34, 0, 250, 7000, 'jne', 'CTC', 278, 278, '', '0000-00-00 00:00:00', 4, 74, '2022-03-26 13:08:08', 0, 'dibatalkan oleh pembeli.'),
(75, 'TRX20220329180331', '2022-03-29 18:03:31', '2022-03-31 18:03:31', 54, '', '', '', 33, 0, 250, 7000, 'jne', 'CTC', 278, 278, '', '0000-00-00 00:00:00', 4, 75, '2022-03-29 18:07:37', 0, 'dibatalkan oleh pembeli.'),
(76, 'TRX20220329180511', '2022-03-29 18:05:11', '2022-03-31 18:05:11', 54, '', '', '', 33, 0, 250, 7000, 'jne', 'CTC', 278, 278, '', '0000-00-00 00:00:00', 4, 76, '2022-04-01 01:13:23', 0, 'dibatalkan oleh sistem, karena melewati batas waktu pembayaran'),
(77, 'TRX20220401120242', '2022-04-01 12:02:42', '2022-04-03 12:02:42', 54, '', '', '', 33, 0, 250, 7000, 'jne', 'CTC', 278, 278, '', '0000-00-00 00:00:00', 4, 77, '2022-04-01 12:03:06', 0, 'dibatalkan oleh pembeli.'),
(78, 'TRX20220403101853', '2022-04-03 10:18:53', '2022-04-05 10:18:53', 58, '', '', '', 35, 0, 300, 10000, 'jne', 'REG', 278, 112, '040620005384722', '2022-04-06 11:46:22', 3, 78, '2022-04-07 14:56:38', 0, ''),
(79, 'TRX20220411202918', '2022-04-11 20:29:18', '2022-04-13 20:29:18', 54, '', '', '', 33, 0, 250, 7000, 'sicepat', 'REG', 278, 278, '', '0000-00-00 00:00:00', 4, 79, '2022-04-11 20:29:50', 0, 'dibatalkan oleh pembeli.'),
(80, 'TRX20220413203951', '2022-04-13 20:39:51', '2022-04-15 20:39:51', 54, '', '', '', 33, 0, 250, 7000, 'jne', 'CTC', 278, 278, '', '0000-00-00 00:00:00', 4, 80, '2022-04-13 20:41:20', 0, 'dibatalkan oleh pembeli.'),
(81, 'TRX20220613220100', '2022-06-13 22:01:00', '2022-06-15 22:01:00', 59, '', '', '', 36, 0, 250, 29000, 'sicepat', 'REG', 278, 339, '', '0000-00-00 00:00:00', 4, 81, '2022-06-15 22:29:02', 0, 'dibatalkan oleh sistem, karena melewati batas waktu pembayaran'),
(82, 'TRX20220729132045', '2022-07-29 13:20:45', '2022-07-31 13:20:45', 60, '', '', '', 37, 0, 500, 11000, 'sicepat', 'BEST', 278, 278, '', '0000-00-00 00:00:00', 4, 82, '2022-07-31 13:23:42', 0, 'dibatalkan oleh sistem, karena melewati batas waktu pembayaran');

-- --------------------------------------------------------

--
-- Table structure for table `blw_sales_booster`
--

CREATE TABLE `blw_sales_booster` (
  `id` int(11) NOT NULL,
  `tgl` datetime NOT NULL,
  `idproduk` int(11) NOT NULL,
  `usrid` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

-- --------------------------------------------------------

--
-- Table structure for table `blw_sales_produk`
--

CREATE TABLE `blw_sales_produk` (
  `id` bigint(11) NOT NULL,
  `usrid` bigint(11) NOT NULL,
  `variasi` int(11) NOT NULL,
  `idproduk` bigint(20) NOT NULL,
  `tgl` datetime NOT NULL,
  `jumlah` int(11) NOT NULL,
  `harga` bigint(20) NOT NULL,
  `diskon` int(11) NOT NULL,
  `keterangan` text NOT NULL,
  `idtransaksi` int(11) NOT NULL,
  `idpo` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Dumping data for table `blw_sales_produk`
--

INSERT INTO `blw_sales_produk` (`id`, `usrid`, `variasi`, `idproduk`, `tgl`, `jumlah`, `harga`, `diskon`, `keterangan`, `idtransaksi`, `idpo`) VALUES
(97, 54, 0, 48, '2022-03-18 22:09:21', 1, 75000, 0, '', 64, 0),
(98, 54, 0, 48, '2022-03-18 22:12:59', 1, 75000, 0, '', 65, 0),
(99, 55, 0, 48, '2022-03-18 22:15:54', 1, 75000, 0, '', 66, 0),
(100, 55, 0, 48, '2022-03-18 22:19:54', 1, 75000, 0, '', 67, 0),
(101, 55, 0, 48, '2022-03-18 22:24:58', 1, 75000, 0, '', 68, 0),
(102, 54, 0, 48, '2022-03-18 22:38:40', 1, 75000, 0, '', 69, 0),
(103, 54, 0, 48, '2022-03-19 00:55:18', 1, 75000, 0, '', 70, 0),
(104, 54, 0, 48, '2022-03-19 13:37:34', 1, 75000, 0, '', 71, 0),
(105, 56, 0, 48, '2022-03-19 14:17:45', 1, 75000, 0, '', 72, 0),
(106, 54, 0, 48, '2022-03-23 22:08:48', 1, 75000, 0, '', 73, 0),
(107, 55, 0, 44, '2022-03-26 13:06:22', 1, 75000, 0, '', 74, 0),
(108, 57, 0, 29, '2022-03-28 07:36:16', 1, 125000, 0, '', 0, 0),
(109, 54, 0, 48, '2022-03-29 18:03:03', 1, 75000, 0, '', 75, 0),
(110, 54, 0, 48, '2022-03-29 18:04:46', 1, 75000, 0, '', 76, 0),
(111, 54, 0, 34, '2022-04-01 12:02:22', 1, 95000, 0, '', 77, 0),
(112, 58, 0, 42, '2022-04-03 10:16:46', 1, 210000, 0, '', 78, 0),
(113, 54, 0, 34, '2022-04-11 20:28:56', 1, 95000, 0, '', 79, 0),
(114, 54, 0, 33, '2022-04-13 20:39:20', 1, 78500, 0, '', 80, 0),
(115, 59, 0, 29, '2022-06-13 21:57:08', 1, 125000, 0, 'COD', 81, 0),
(117, 60, 0, 44, '2022-07-29 13:16:40', 2, 75000, 0, '', 82, 0);

-- --------------------------------------------------------

--
-- Table structure for table `blw_sales_review`
--

CREATE TABLE `blw_sales_review` (
  `id` int(11) NOT NULL,
  `usrid` int(11) NOT NULL,
  `idtransaksi` int(11) NOT NULL,
  `idproduk` int(11) NOT NULL,
  `nilai` int(11) NOT NULL,
  `tgl` datetime NOT NULL,
  `keterangan` text NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

-- --------------------------------------------------------

--
-- Table structure for table `blw_sales_voucher`
--

CREATE TABLE `blw_sales_voucher` (
  `id` int(11) NOT NULL,
  `usrid` int(11) NOT NULL,
  `nama` text NOT NULL,
  `deskripsi` text NOT NULL,
  `jenis` int(11) NOT NULL COMMENT '1=potongan harga,2=potongan ongkir',
  `kode` varchar(50) NOT NULL,
  `mulai` date NOT NULL,
  `selesai` date NOT NULL,
  `potongan` int(11) NOT NULL,
  `potonganmin` int(11) NOT NULL,
  `potonganmaks` int(11) NOT NULL,
  `tipe` int(11) NOT NULL COMMENT '2=nominal,1=persen',
  `peruser` int(11) NOT NULL,
  `idproduk` int(11) NOT NULL
) ENGINE=MyISAM DEFAULT CHARSET=latin1;

-- --------------------------------------------------------

--
-- Table structure for table `blw_sales_wishlist`
--

CREATE TABLE `blw_sales_wishlist` (
  `id` int(11) NOT NULL,
  `usrid` int(11) NOT NULL,
  `tgl` datetime NOT NULL,
  `idproduk` int(11) NOT NULL,
  `status` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

-- --------------------------------------------------------

--
-- Table structure for table `blw_user`
--

CREATE TABLE `blw_user` (
  `id` int(11) NOT NULL,
  `status` int(11) NOT NULL,
  `username` varchar(100) NOT NULL,
  `password` text NOT NULL,
  `tgl` datetime NOT NULL,
  `level` int(11) NOT NULL COMMENT '1=normal,2=reseller,3=agen',
  `nohp` varchar(50) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Dumping data for table `blw_user`
--

INSERT INTO `blw_user` (`id`, `status`, `username`, `password`, `tgl`, `level`, `nohp`) VALUES
(52, 1, 'shafirafirdausyiofficial@gmail.com', 'eb5cb83357c5595e33eb4eb1fbdd7f27ed44f269c380b0add4a6f37565277c1fd48685fdf1bac2024deb75639021632f482d942e6b59052cadbc62581a57c24dM9IPRYnkS+M/LC1OEahoPQSIXpMc8Fcj45Jh/YRfNUA=', '0000-00-00 00:00:00', 1, '082232376727'),
(53, 1, 'FishakitchenPamekasan@gmail.com', 'f8475303ab38bfcc6aad43fbdb1039d2a2667f846366342365b998805676b502f6fbcbef38b734a88a03bc9535f67147e192880215f9ef462856ef803175ad50495c//qqemn4+Y3ZlPPXKEmNQoeLHbqS0pdkIXuuJCY=', '0000-00-00 00:00:00', 1, '082232376727'),
(54, 1, 'fitrianoveliza@gmail.com', '4fcdf2ccef452d441b45a08741bf45b01b71fbf5a6f41ee53575cfcf9d75270d0362c38add49275f275b39571ae956251633a8f47b3723f732b184861c5c78fb+WDB1bYX87GaXm6TNInhv0F+D1OwFgXb6Osqidxeda8=', '2022-04-13 21:42:28', 1, '08575152838'),
(55, 1, 'didihansya@gmail.com', 'c9f8779cf37e423ec555610c18997fca5065f14d691cf9a3890339a68d9502bada22b6511e3c1fe8856ea190101ec956dd9a867eb45d476d5ed4925391f72ccfMecqoeQpGN5XisZmaycXxfCVoP93WARuLOfDPH7ElaE=', '2022-04-26 04:06:28', 1, '085161900922'),
(56, 1, 'hamdaniversi08@gmail.com', '56489395d873fbf1195030aa86e91ecb44bf81504d9b9d3fb2c703e9a91be56e5380e2f6b275304e906f672824720b1c813a50aa0c8eaa676cc553b9963621f3LrmLHE419Q0GFpAFW7ctNjIow52bey7UlL24/UQPkMU=', '2022-03-19 14:37:58', 1, '081375078785'),
(57, 1, 'widiaarumsari98@gmail.com', '1748d4462294bf4d8df67923453ace4eafedb9ccaecc5fd3a368643b0348cac464b0f54bb9d29420886db9e2739cb64837da311413a32043476258c798dcb4d6ndAxQCedQbfNRkvibv8zD+26zkmnMi7q3vQX6YyXlr4=', '2022-03-28 07:42:49', 1, '08999077810'),
(58, 1, 'Astria_novita30@yahoo.com', 'a284cca2c1fe14e9362f7012139359686a1f118f19201ead067386dacd39c166b157cebd6c9371bea3036b9a0c74cac85b9234eeda5304f7ed09f1f117dfb0458hK9FvNVxTpC1bna5wBqu8iHd31JHrzm48uQ02CUG/0=', '2022-04-07 14:56:30', 1, '081265203534'),
(59, 1, 'leginag422@gmail.com', '0007c7a797c118a132b4112bda642f234fac564868916e71f1f355f733699192c1401c4de38ebe2178e026e00585a7ca36e53cdfd8fe4751035aa9ae742bcb1dY3PpdVazLf6LAHPQNy0t71jk3TtWOrg23SnDt1VgulI=', '2022-06-13 22:01:04', 1, '081310667020'),
(60, 1, 'jenny.pratama@outlook.com', '2693a069f09d86652d552b5ec1345107880ae8cc7a503a486cfb68643071bbfb374c23c32f6789bbd17ea01705d180d8580deb41c44e94f80e84429385e8a8c5KXiSyvEpB97o4rlZ3bFssVpSNPZweVDTEpvn5ah0BLc=', '2022-07-29 13:32:26', 1, '6282360399922'),
(61, 1, 'buddy@gmail.com', 'ac420d06a2c37ba6d0dc3bb8c2bb0409cfecf28c4dfd806cbf267a1161e3448392fc63e68daa091c36f2f7ccb0b54070e8affc7d13abae9dc594f1f54347ceb4/x+CVtr6clQBYHM2uLREHnDhoekEeIUaIc2LX26W7oI=', '0000-00-00 00:00:00', 1, '34324283904');

-- --------------------------------------------------------

--
-- Table structure for table `blw_user_admin`
--

CREATE TABLE `blw_user_admin` (
  `id` int(11) NOT NULL,
  `nama` text NOT NULL,
  `level` int(11) NOT NULL DEFAULT 1 COMMENT '1=editor,2=admin',
  `username` varchar(100) NOT NULL,
  `password` text NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Dumping data for table `blw_user_admin`
--

INSERT INTO `blw_user_admin` (`id`, `nama`, `level`, `username`, `password`) VALUES
(1, 'Mimin Ganteng', 2, 'mimin', 'b84b23ec53369ef4d9bf9b14033cf1dacaff167b34746ff5e80d453dca2619fcecb3e75e2b837b538663edb97a9be094a8140580ded9f58295ed23de8a55c03bLVb+GI7CWKnmPFtqVELIhspJDjXABgeRxc5bu18sH38='),
(7, 'Demo Admin', 1, 'demoadmin', 'ff405076c9b2c47ebbb68eba34fec6abd433952bcc106d8232372b0388b98b5d0bdd42a50fb7616616644c7e3fbd4ce28cc9e36e08fbeb3098a78110403ba307KkN2Lqrwi5s1JNAOZy4x0zZbqbgaZq24oNY/qc6+KHI=');

-- --------------------------------------------------------

--
-- Table structure for table `blw_user_alamat`
--

CREATE TABLE `blw_user_alamat` (
  `id` int(11) NOT NULL,
  `usrid` int(11) NOT NULL,
  `status` int(11) NOT NULL COMMENT '1=utama,0=lainnya',
  `idkec` int(11) NOT NULL,
  `judul` text NOT NULL,
  `alamat` text NOT NULL,
  `kodepos` varchar(10) NOT NULL,
  `nama` varchar(100) NOT NULL,
  `nohp` varchar(100) NOT NULL,
  `idkab` int(11) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Dumping data for table `blw_user_alamat`
--

INSERT INTO `blw_user_alamat` (`id`, `usrid`, `status`, `idkec`, `judul`, `alamat`, `kodepos`, `nama`, `nohp`, `idkab`) VALUES
(21, 38, 1, 0, 'Rumah', 'Jl Eka Surya', '20143', 'Didi', '0812723123', 278),
(33, 54, 1, 0, 'rumah ', 'jl beo no 43 ABC', '20228', 'fitria', '085275152838', 278),
(34, 55, 1, 0, 'Rumah', 'Jl Karya Bakti Gg Bakti 4 No 30b', '20143', 'Didi Khodriansyah', '085161900922', 278),
(35, 58, 1, 0, '', 'Jl. Sederhana Dusun VII , tandem pasar 6 tionghua \r\nNo.37 Kab. Deli serdang - Hamparan perak , sumatera utara \r\nKode pos 20374', '20511', 'Astria novita', '081265203534', 112),
(36, 59, 1, 0, 'Rumah', 'Jorong VII polongan dua, depan tower telkomsel, rao kabupaten Pasaman sumatra barat id. 26353', '26318', 'Egina wafi', '081310667020', 339),
(37, 60, 1, 0, 'Alamat rumah', 'Komp. Mutiara Residence. JL. Rumah Sakit Haji, Pancing Tembung Medan. Blok V. No. 8A', '20228', 'Jenny Pratama', '6282360399922', 278);

-- --------------------------------------------------------

--
-- Table structure for table `blw_user_otpdaftar`
--

CREATE TABLE `blw_user_otpdaftar` (
  `id` bigint(20) NOT NULL,
  `tgl` datetime NOT NULL,
  `emailhp` varchar(500) NOT NULL,
  `kode` varchar(6) NOT NULL,
  `kadaluarsa` datetime NOT NULL,
  `status` int(11) NOT NULL COMMENT '0=belum,1=berhasil,2=gagal',
  `masuk` datetime NOT NULL,
  `emailnya` varchar(30) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

-- --------------------------------------------------------

--
-- Table structure for table `blw_user_otplogin`
--

CREATE TABLE `blw_user_otplogin` (
  `id` bigint(20) NOT NULL,
  `tgl` datetime NOT NULL,
  `usrid` bigint(20) NOT NULL,
  `kode` varchar(6) NOT NULL,
  `kadaluarsa` datetime NOT NULL,
  `status` int(11) NOT NULL COMMENT '0=belum,1=berhasil,2=gagal',
  `masuk` datetime NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

-- --------------------------------------------------------

--
-- Table structure for table `blw_user_profil`
--

CREATE TABLE `blw_user_profil` (
  `id` int(11) NOT NULL,
  `usrid` int(11) NOT NULL,
  `nohp` varchar(20) NOT NULL,
  `lahir` date NOT NULL,
  `nama` text NOT NULL,
  `kelamin` int(11) NOT NULL,
  `foto` text NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Dumping data for table `blw_user_profil`
--

INSERT INTO `blw_user_profil` (`id`, `usrid`, `nohp`, `lahir`, `nama`, `kelamin`, `foto`) VALUES
(52, 52, '082232376727', '2003-01-01', 'Shafira Firdausy', 1, 'user.png'),
(53, 53, '082232376727', '2003-01-01', 'Fisha Kitchen Pamekasan', 1, 'user.png'),
(54, 54, '08575152838', '2003-01-01', 'fitri', 1, 'user.png'),
(55, 55, '085161900922', '2003-01-01', 'Didi Khodriansyah', 1, 'user.png'),
(56, 56, '081375078785', '2003-01-01', 'Riski Hamdani', 1, 'user.png'),
(57, 57, '08999077810', '2003-01-01', 'Widia', 1, 'user.png'),
(58, 58, '081265203534', '2003-01-01', 'Astria novita', 1, 'user.png'),
(59, 59, '081310667020', '2003-01-01', 'Egina', 1, 'user.png'),
(60, 60, '6282360399922', '2003-01-01', 'Jenny Pratama', 1, 'user.png'),
(61, 61, '34324283904', '2003-01-01', 'buddy', 1, 'user.png');

-- --------------------------------------------------------

--
-- Table structure for table `blw_user_rekening`
--

CREATE TABLE `blw_user_rekening` (
  `id` bigint(20) NOT NULL,
  `usrid` bigint(20) NOT NULL,
  `idbank` bigint(20) NOT NULL,
  `atasnama` varchar(200) NOT NULL,
  `norek` varchar(100) NOT NULL,
  `kcp` varchar(200) NOT NULL,
  `tgl` datetime NOT NULL,
  `userid` varchar(300) NOT NULL,
  `pass` varchar(300) NOT NULL,
  `token` text NOT NULL,
  `mutasi` int(11) NOT NULL DEFAULT 0
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

-- --------------------------------------------------------

--
-- Table structure for table `blw_user_saldo`
--

CREATE TABLE `blw_user_saldo` (
  `id` int(11) NOT NULL,
  `usrid` int(11) NOT NULL,
  `apdet` datetime NOT NULL,
  `saldo` bigint(20) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Dumping data for table `blw_user_saldo`
--

INSERT INTO `blw_user_saldo` (`id`, `usrid`, `apdet`, `saldo`) VALUES
(1, 40, '2021-11-06 12:04:00', 0),
(2, 41, '2021-11-06 14:02:45', 0),
(3, 42, '2021-11-18 19:06:30', 0),
(4, 43, '2021-11-18 20:09:01', 0),
(5, 44, '2021-11-24 20:53:25', 0),
(6, 45, '2021-11-24 20:54:28', 0),
(7, 46, '2021-12-03 20:23:45', 0),
(8, 47, '2021-12-03 21:13:18', 0),
(9, 48, '2021-12-06 20:16:37', 0),
(10, 49, '2021-12-06 20:35:32', 0),
(11, 50, '2022-02-28 19:45:40', 0),
(12, 51, '2022-02-28 20:10:13', 0),
(13, 52, '2022-03-18 06:24:43', 0),
(14, 53, '2022-03-18 09:44:28', 0),
(15, 54, '2022-03-18 22:05:21', 0),
(16, 55, '2022-03-18 22:14:53', 0),
(17, 56, '2022-03-19 14:16:34', 0),
(18, 57, '2022-03-28 07:35:22', 0),
(19, 58, '2022-04-03 10:16:15', 0),
(20, 59, '2022-06-13 21:54:40', 0),
(21, 60, '2022-07-29 13:15:47', 0),
(22, 61, '2022-07-31 13:27:47', 0);

-- --------------------------------------------------------

--
-- Table structure for table `blw_user_saldo_history`
--

CREATE TABLE `blw_user_saldo_history` (
  `id` int(11) NOT NULL,
  `tgl` datetime NOT NULL,
  `usrid` int(11) NOT NULL,
  `jenis` int(11) NOT NULL COMMENT '1=kredit,2=debit',
  `jumlah` bigint(20) NOT NULL,
  `darike` int(11) NOT NULL,
  `sambung` int(11) NOT NULL,
  `saldoawal` bigint(20) NOT NULL,
  `saldoakhir` bigint(20) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

-- --------------------------------------------------------

--
-- Table structure for table `blw_user_saldo_tarik`
--

CREATE TABLE `blw_user_saldo_tarik` (
  `id` int(11) NOT NULL,
  `trxid` varchar(200) NOT NULL,
  `jenis` int(11) NOT NULL COMMENT '1=tarik,2=topup',
  `status` int(11) NOT NULL COMMENT '0=blmproses,1=selesai',
  `selesai` datetime NOT NULL,
  `usrid` int(11) NOT NULL,
  `idrek` int(11) NOT NULL,
  `total` int(11) NOT NULL,
  `tgl` datetime NOT NULL,
  `ipaymu` varchar(300) NOT NULL,
  `ipaymu_trx` text NOT NULL,
  `ipaymu_link` text NOT NULL,
  `ipaymu_tipe` varchar(50) NOT NULL,
  `ipaymu_channel` text NOT NULL,
  `ipaymu_nama` text NOT NULL,
  `ipaymu_kode` text NOT NULL,
  `midtransid` text NOT NULL,
  `keterangan` text NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

-- --------------------------------------------------------

--
-- Table structure for table `blw_user_token`
--

CREATE TABLE `blw_user_token` (
  `id` int(11) NOT NULL,
  `tgl` datetime NOT NULL,
  `usrid` int(11) NOT NULL,
  `token` text NOT NULL,
  `last_access` datetime NOT NULL,
  `status` int(11) NOT NULL
) ENGINE=MyISAM DEFAULT CHARSET=latin1;

--
-- Indexes for dumped tables
--

--
-- Indexes for table `blw_@blog`
--
ALTER TABLE `blw_@blog`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `blw_@kategori`
--
ALTER TABLE `blw_@kategori`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `blw_@kurir`
--
ALTER TABLE `blw_@kurir`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `blw_@page`
--
ALTER TABLE `blw_@page`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `blw_@promo`
--
ALTER TABLE `blw_@promo`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `blw_@rekening_bank`
--
ALTER TABLE `blw_@rekening_bank`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `blw_@setting`
--
ALTER TABLE `blw_@setting`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `blw_@testimoni`
--
ALTER TABLE `blw_@testimoni`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `blw_@wasap`
--
ALTER TABLE `blw_@wasap`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `blw_history_ongkir`
--
ALTER TABLE `blw_history_ongkir`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `blw_history_stok`
--
ALTER TABLE `blw_history_stok`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `blw_invoice`
--
ALTER TABLE `blw_invoice`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `blw_invoice_konfirmasi`
--
ALTER TABLE `blw_invoice_konfirmasi`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `blw_pesan`
--
ALTER TABLE `blw_pesan`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `blw_pesan_notifikasi`
--
ALTER TABLE `blw_pesan_notifikasi`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `blw_preorder`
--
ALTER TABLE `blw_preorder`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `blw_produk`
--
ALTER TABLE `blw_produk`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `blw_produk_upload`
--
ALTER TABLE `blw_produk_upload`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `blw_produk_variasi`
--
ALTER TABLE `blw_produk_variasi`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `blw_produk_variasisize`
--
ALTER TABLE `blw_produk_variasisize`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `blw_produk_variasiwarna`
--
ALTER TABLE `blw_produk_variasiwarna`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `blw_sales`
--
ALTER TABLE `blw_sales`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `orderid` (`orderid`);

--
-- Indexes for table `blw_sales_booster`
--
ALTER TABLE `blw_sales_booster`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `blw_sales_produk`
--
ALTER TABLE `blw_sales_produk`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `blw_sales_review`
--
ALTER TABLE `blw_sales_review`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `blw_sales_voucher`
--
ALTER TABLE `blw_sales_voucher`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `blw_sales_wishlist`
--
ALTER TABLE `blw_sales_wishlist`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `blw_user`
--
ALTER TABLE `blw_user`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `blw_user_admin`
--
ALTER TABLE `blw_user_admin`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `blw_user_alamat`
--
ALTER TABLE `blw_user_alamat`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `blw_user_otpdaftar`
--
ALTER TABLE `blw_user_otpdaftar`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `blw_user_otplogin`
--
ALTER TABLE `blw_user_otplogin`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `blw_user_profil`
--
ALTER TABLE `blw_user_profil`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `blw_user_rekening`
--
ALTER TABLE `blw_user_rekening`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `blw_user_saldo`
--
ALTER TABLE `blw_user_saldo`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `blw_user_saldo_history`
--
ALTER TABLE `blw_user_saldo_history`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `blw_user_saldo_tarik`
--
ALTER TABLE `blw_user_saldo_tarik`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `blw_user_token`
--
ALTER TABLE `blw_user_token`
  ADD PRIMARY KEY (`id`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `blw_@blog`
--
ALTER TABLE `blw_@blog`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;

--
-- AUTO_INCREMENT for table `blw_@kategori`
--
ALTER TABLE `blw_@kategori`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=36;

--
-- AUTO_INCREMENT for table `blw_@kurir`
--
ALTER TABLE `blw_@kurir`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=7;

--
-- AUTO_INCREMENT for table `blw_@page`
--
ALTER TABLE `blw_@page`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=3;

--
-- AUTO_INCREMENT for table `blw_@promo`
--
ALTER TABLE `blw_@promo`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=41;

--
-- AUTO_INCREMENT for table `blw_@rekening_bank`
--
ALTER TABLE `blw_@rekening_bank`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=141;

--
-- AUTO_INCREMENT for table `blw_@setting`
--
ALTER TABLE `blw_@setting`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=49;

--
-- AUTO_INCREMENT for table `blw_@testimoni`
--
ALTER TABLE `blw_@testimoni`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `blw_@wasap`
--
ALTER TABLE `blw_@wasap`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=13;

--
-- AUTO_INCREMENT for table `blw_history_ongkir`
--
ALTER TABLE `blw_history_ongkir`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=20;

--
-- AUTO_INCREMENT for table `blw_history_stok`
--
ALTER TABLE `blw_history_stok`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=187;

--
-- AUTO_INCREMENT for table `blw_invoice`
--
ALTER TABLE `blw_invoice`
  MODIFY `id` bigint(20) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=83;

--
-- AUTO_INCREMENT for table `blw_invoice_konfirmasi`
--
ALTER TABLE `blw_invoice_konfirmasi`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `blw_pesan`
--
ALTER TABLE `blw_pesan`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `blw_pesan_notifikasi`
--
ALTER TABLE `blw_pesan_notifikasi`
  MODIFY `id` bigint(20) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=875;

--
-- AUTO_INCREMENT for table `blw_preorder`
--
ALTER TABLE `blw_preorder`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=10;

--
-- AUTO_INCREMENT for table `blw_produk`
--
ALTER TABLE `blw_produk`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=50;

--
-- AUTO_INCREMENT for table `blw_produk_upload`
--
ALTER TABLE `blw_produk_upload`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=125;

--
-- AUTO_INCREMENT for table `blw_produk_variasi`
--
ALTER TABLE `blw_produk_variasi`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `blw_produk_variasisize`
--
ALTER TABLE `blw_produk_variasisize`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=5;

--
-- AUTO_INCREMENT for table `blw_produk_variasiwarna`
--
ALTER TABLE `blw_produk_variasiwarna`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=9;

--
-- AUTO_INCREMENT for table `blw_sales`
--
ALTER TABLE `blw_sales`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=83;

--
-- AUTO_INCREMENT for table `blw_sales_booster`
--
ALTER TABLE `blw_sales_booster`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;

--
-- AUTO_INCREMENT for table `blw_sales_produk`
--
ALTER TABLE `blw_sales_produk`
  MODIFY `id` bigint(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=118;

--
-- AUTO_INCREMENT for table `blw_sales_review`
--
ALTER TABLE `blw_sales_review`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `blw_sales_voucher`
--
ALTER TABLE `blw_sales_voucher`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=6;

--
-- AUTO_INCREMENT for table `blw_sales_wishlist`
--
ALTER TABLE `blw_sales_wishlist`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;

--
-- AUTO_INCREMENT for table `blw_user`
--
ALTER TABLE `blw_user`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=62;

--
-- AUTO_INCREMENT for table `blw_user_admin`
--
ALTER TABLE `blw_user_admin`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=8;

--
-- AUTO_INCREMENT for table `blw_user_alamat`
--
ALTER TABLE `blw_user_alamat`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=38;

--
-- AUTO_INCREMENT for table `blw_user_otpdaftar`
--
ALTER TABLE `blw_user_otpdaftar`
  MODIFY `id` bigint(20) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `blw_user_otplogin`
--
ALTER TABLE `blw_user_otplogin`
  MODIFY `id` bigint(20) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `blw_user_profil`
--
ALTER TABLE `blw_user_profil`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=62;

--
-- AUTO_INCREMENT for table `blw_user_rekening`
--
ALTER TABLE `blw_user_rekening`
  MODIFY `id` bigint(20) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `blw_user_saldo`
--
ALTER TABLE `blw_user_saldo`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=23;

--
-- AUTO_INCREMENT for table `blw_user_saldo_history`
--
ALTER TABLE `blw_user_saldo_history`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `blw_user_saldo_tarik`
--
ALTER TABLE `blw_user_saldo_tarik`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `blw_user_token`
--
ALTER TABLE `blw_user_token`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
