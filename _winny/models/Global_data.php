<?php if(!defined('BASEPATH')) exit('Hacking Attempt : Keluar dari sistem !! ');
class Global_data extends CI_Model{
    public function __construct(){
        parent::__construct();
		$this->load->model('user_model');
    }
	
	// GET KATEGORI
	function getKategori($id, $what, $opo = "id")
	{
		$this->db->where($opo, $id);
		$this->db->limit(1);
		$res = $this->db->get("@kategori");

		if ($what == "semua") {
			$result = array();
			foreach ($res->result() as $key => $value) {
				$result[$key] = $value;
				$result = $result[0];
			}
		} else {
			$result = null;
			foreach ($res->result() as $re) {
				$result = $re->$what;
			}
		}
		return $result;
	}

	// RESET USERDATA
	function resetData()
	{
		$this->session->unset_userdata("securesearch");
	}


	/**------------------------------------------------------------------------
	 *                           AUTH
	 *------------------------------------------------------------------------**/
	function cekLogin()
	{
		if (isset($_SESSION['uid']) and isset($_SESSION['gid'])) {
			$data = array("tgl" => date("Y-m-d H:i:s"));
			$this->user_model->update($_SESSION['uid'], $data);

			return $_SESSION["uid"];
		} else {
			return 0;
		}
	}
	function randomPassword()
	{
		$alphabet = 'abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ1234567890';
		$pass = array(); //remember to declare $pass as an array
		$alphaLength = strlen($alphabet) - 1; //put the length -1 in cache
		for ($i = 0; $i <= 16; $i++) {
			$n = rand(0, $alphaLength);
			$pass[] = $alphabet[$n];
		}
		return implode($pass); //turn the array into a string
	}
	function resetPass($email)
	{
		$usrid = $this->user_model->getUser($email, "id", "username");
		if ($usrid > 0) {
			$user = $this->user_model->getUser($usrid, "semua");
			$profil = $this->user_model->getProfil($usrid, "semua", "usrid");
			$generated = $this->randomPassword();
			$data = array("password" => $this->encode($generated));
			$this->user_model->update($usrid, $data);

			$pesan = '
				<html>
				<head>
					<style>
					.border{width:90%;padding:20px;border:1px solid #ccc;border-radius:3px;margin:auto;}
					.pesan{margin-bottom:30px;}
					.link{margin-bottom:20px;}
					.alink{text-decoration:none;background:#c0392b;padding:10px 24px;border-radius:3px;margin-bottom:20px;}
					</style>
				</head>
				<body>
					<div class="border">
					<div class="pesan">
						<h3>Halo, ' . $profil->nama . '</h3><p/>
						Selamat, reset password Anda berhasil dan untuk login ke akun Anda, silahkan menggunakan password dibawah:<br/>
						Pass: ' . $generated . '<p/>&nbsp;<p/>
						Segera masuk dan ganti password Anda untuk meningkatkan keamanan akun Anda kembali.<p/>
					</div>
					<div class="link">
						<a class="alink" style="color:#fff;" href="' . site_url("home/signin") . '">LOGIN DISINI</a>
					</div>
					</div>
				</body>
				</html>
			';
			$pesanWA = '
				Halo, *' . $profil->nama . '* \n' .
				'Selamat, reset password Anda berhasil dan untuk login ke akun Anda, silahkan menggunakan password dibawah: \n' .
				'Pass: ' . $generated . ' \n \n' .
				'Segera masuk dan ganti password Anda untuk meningkatkan keamanan akun Anda kembali.
			';
			if ($this->sendEmail($user->username, $this->getSetting("nama"), $pesan, "Reset password")) {
				$this->sendWA($profil->nohp, $pesanWA);
				return true;
			} else {
				return false;
			}
		} else {
			return false;
		}
	}

	/**------------------------------------------------------------------------
	 **                            ONGKIR
	 *------------------------------------------------------------------------**/
	function getHistoryOngkir($id, $what = "id", $opo = "id")
	{
		if (is_array($id)) {
			foreach ($id as $key => $val) {
				$this->db->where($key, $val);
			}
			$this->db->limit(1);
			$res = $this->db->get("history_ongkir");

			$result = "tidak ditemukan";
			foreach ($res->result() as $re) {
				$result = $re->$what;
			}
		} else {
			$this->db->where($opo, $id);
			$this->db->limit(1);
			$res = $this->db->get("history_ongkir");

			$result = "tidak ditemukan";
			foreach ($res->result() as $re) {
				$result = $re->$what;
			}
		}
		return $result;
	}
	function beratkg($berat = 0, $kurir = "jne")
	{
		$beratkg = ($berat < 1000) ? 1 : round(intval($berat) / 1000, 0, PHP_ROUND_HALF_DOWN);
		if ($kurir == "jne") {
			$selisih = $berat - ($beratkg * 1000);
			if ($selisih > 300) {
				$beratkg = $beratkg + 1;
			}
		} elseif ($kurir == "jnt") {
			$selisih = $berat - ($beratkg * 1000);
			if ($selisih > 200) {
				$beratkg = $beratkg + 1;
			}
		} elseif ($kurir == "pos") {
			$selisih = $berat - ($beratkg * 1000);
			if ($selisih > 200) {
				$beratkg = $beratkg + 1;
			}
		} elseif ($kurir == "tiki") {
			$selisih = $berat - ($beratkg * 1000);
			if ($selisih > 299) {
				$beratkg = $beratkg + 1;
			}
		} else {
			$selisih = $berat - ($beratkg * 1000);
			if ($selisih > 0) {
				$beratkg = $beratkg + 1;
			}
		}
		return $beratkg;
	}

	/**------------------------------------------------------------------------
	 *                           VERIFIKASI
	 *------------------------------------------------------------------------**/
	
	function verifEmail($id = 0)
	{
		if ($id > 0) {
			$profil = $this->user_model->getProfil($id, "semua", "usrid");
			$user = $this->user_model->getUser($id, "semua");
			$verifid = $this->arrEnc(array("id" => $id, "time" => date("YmdHis")));
			$subyek = 'Verifikasi Pendaftaran ' . $this->getSetting("nama");
			$pesan = '
				<html>
				<head>
					<style>
					.border{padding:20px;border-radius:3px;margin:auto;}
					.pesan{margin-bottom:30px;}
					.link{margin-bottom:20px;}
					.alink{text-decoration:none;background:#c0392b;padding:10px 24px;border-radius:3px;margin-bottom:20px;}
					</style>
				</head>
				<body>
					<div class="border">
					<div class="pesan">
					<h3>Halo, ' . $profil->nama . '</h3><p/>
					Terima kasih sudah mendaftar di <b>' . $this->getSetting("nama") . '</b>, untuk mengaktifkan akun Anda, silahkan klik link berikut:<br/>
					</div>
					<div class="link">
						<a class="alink" style="color:#fff;" href="' . site_url("auth/signup?verify=" . $verifid) . '">VERIFIKASI AKUN ' . strtoupper(strtolower($this->globalset("nama"))) . '</a>
						<br/>&nbsp;<br/>atau link dibawah ini<br/>
						<a href="' . site_url("auth/signup?verify=" . $verifid) . '">' . site_url("auth/signup?verify=" . $verifid) . '</a>
					</div>
					</div>
				</body>
				</html>
			';

			if ($this->sendEmail($user->username, $this->getSetting("nama"), $pesan, $subyek)) {
				return true;
			} else {
				return false;
			}
		}
	}
	function verifWA($id = 0)
	{
		if ($id > 0) {
			$profil = $this->user_model->getProfil($id, "semua", "usrid");
			$user = $this->user_model->getUser($id, "semua");
			$verifid = $this->arrEnc(array("id" => $id, "time" => date("YmdHis")));
			$subyek = 'Verifikasi Pendaftaran ' . $this->getSetting("nama");
			$pesan = '
				Halo, *' . $profil->nama . '* \n \n' . 'Terima kasih sudah mendaftar di *' . $this->getSetting("nama") . '*, untuk mengaktifkan akun Anda, silahkan klik link berikut:\n' . site_url("auth/signup?verify=" . $verifid) . ' \n' . '_*Apabila link tidak bisa di klik, simpan nomer whatsapp ini terlebih dahulu_
			';

			if ($this->sendWA($profil->nohp, $pesan)) {
				return true;
			} else {
				return false;
			}
		}
	}
	function demo(){
		return false;
	}
	function mainsite_url($url){
		$mainsite = "https://localhost/botoko/";
		$url = $mainsite.$url;
		return $url;
	}
	// Get Setting
	function getSetting($data)
	{
		if ($data != "semua") {
			$this->db->where("field", $data);
		}
		$res = $this->db->get("@setting");
		$result = null;
		if ($data == "semua") {
			$result = array(null);
			foreach ($res->result() as $re) {
				$result[$re->field] = $re->value;
			}
			$result = (object)$result;
		} else {
			$result = "";
			foreach ($res->result() as $re) {
				$result = $re->value;
			}
		}
		return $result;
	}

	function globalset($data){
		if($data != "semua"){
		$this->db->where("field",$data);
		}
		$res = $this->db->get("@setting");
		$result = null;
		if($data == "semua"){
			$result = array(null);
			foreach($res->result() as $re){
				$result[$re->field] = $re->value;
			}
			$result = (object)$result;
		}else{
			$result = "";
			foreach($res->result() as $re){
				$result = $re->value;
			}
		}
		return $result;
	}

	function maintenis(){
		//return true;
		return false;
	}

	// PEMBAYARAN
	function getBayar($id,$what,$opo="id"){
		$this->db->where($opo,$id);
		$this->db->limit(1);
		$res = $this->db->get("invoice");

		if($what == "semua"){
			$result = array();
			foreach($res->result() as $key => $value){
				$result[$key] = $value;
			}
			$result = $result[0];
		}else{
			$result = null;
			foreach($res->result() as $re){
				$result = $re->$what;
			}
		}
		return $result;
	}
	
	// GET ALAMAT
	function getAlamat($id,$what,$opo="id"){
		$this->db->where($opo,$id);
		$this->db->limit(1);
		$res = $this->db->get("user_alamat");

		if($res->num_rows() > 0){
			if($what == "semua"){
				foreach($res->result() as $key => $value){
					$result[$key] = $value;
				}
				$result = $result[0];
			}else{
				foreach($res->result() as $re){
					$result = $re->$what;
				}
			}
		}else{
			$result = new stdClass();
			$result->nama = "";
			$result->alamat = "";
			$result->judul = "";
			$result->kodepos = "";
			$result->nohp = "";
			$result->idkec = 0;
			$result->usrid = 0;
			$result->status = 0;
		}
		return $result;
	}

	// GET LOKASI
	// function getKec($id,$what,$opo="id"){
	// 	$this->db->where($opo,$id);
	// 	$this->db->limit(1);
	// 	$res = $this->db->get("kec");

	// 	$result = "kecamatan tidak ditemukan";
	// 	if($what == "semua"){
	// 		$result = array();
	// 		foreach($res->result() as $key => $value){
	// 			$result[$key] = $value;
	// 		}
	// 		$result = $result[0];
	// 	}else{
	// 		$result = null;
	// 		foreach($res->result() as $re){
	// 			$result = $re->$what;
	// 		}
	// 	}
	// 	return $result;
	// }
	// function getKab($id,$what,$opo="id"){
	// 	$this->db->where($opo,$id);
	// 	$this->db->limit(1);
	// 	$res = $this->db->get("kab");

	// 	$result = "kabupaten tidak ditemukan";
	// 	if($what == "semua"){
	// 		$result = array();
	// 		foreach($res->result() as $key => $value){
	// 			$result[$key] = $value;
	// 		}
	// 		$result = $result[0];
	// 	}else{
	// 		foreach($res->result() as $re){
	// 			if(is_array($what)){
	// 				$result = array();
	// 				for($i=0; $i<count($what); $i++){
	// 					$result[$what[$i]] = $re->$what[$i];
	// 				}
	// 			}else{
	// 				$result = $re->$what;
	// 			}
	// 		}
	// 	}
	// 	return $result;
	// }
	function getProv($id,$what,$opo="id"){
		$this->db->where($opo,$id);
		$this->db->limit(1);
		$res = $this->db->get("prov");

		$result = "provinsi tidak ditemukan";
		if($what == "semua"){
			$result = array();
			foreach($res->result() as $key => $value){
				$result[$key] = $value;
			}
			$result = $result[0];
		}else{
			foreach($res->result() as $re){
				if(is_array($what)){
					$result = array();
					for($i=0; $i<count($what); $i++){
						$result[$what[$i]] = $re->$what[$i];
					}
				}else{
					$result = $re->$what;
				}
			}
  		}
		return $result;
	}

	// GET WHATSAPP
	function getWasap($id,$what,$opo="id"){
		$this->db->where($opo,$id);
		$this->db->limit(1);
		$res = $this->db->get("@wasap");

		if($what == "semua"){
			$result = array(0);
			foreach($res->result() as $key => $value){
				$result[$key] = $value;
			}
			$result = $result[0];
		}else{
			$result = null;
			foreach($res->result() as $re){
				$result = $re->$what;
			}
		}
		return $result;
	}
	function getRandomWasap(){
		$this->db->order_by("tgl","ASC");
		$this->db->limit(1);
		$res = $this->db->get("@wasap");
		
		$result = 0;
		foreach($res->result() as $r){
			if(substr($r->wasap,0,1) == 0){
				$result = "+62".substr($r->wasap,1);
			}elseif(substr($r->wasap,0,2) == "62"){
				$result = "+".$r->wasap;
			}elseif(substr($r->wasap,0,1) == "+"){
				$result = $r->wasap;
			}
		}
		return $result;
	}

	// GET PRODUK
	function getProduk($id,$what,$opo="id"){
		$this->db->where($opo,$id);
		$this->db->limit(1);
		$res = $this->db->get("produk");

		if($what == "semua"){
			$result = array(0);
			foreach($res->result() as $key => $value){
				$result[$key] = $value;
			}
			$result = $result[0];
		}else{
			$result = null;
			foreach($res->result() as $re){
				$result = $re->$what;
			}
		}
		return $result;
	}
	function getFoto($id,$kat){
		$path = 'uploads/produk/';
		$fpath = FCPATH.$path;
		$server = site_url($path);
		$this->db->where("idproduk",$id);
		if($kat == "utama"){
			$this->db->where("jenis",1);
		}
		$this->db->limit(1);
		$res = $this->db->get("produk_upload");

		$result = "default.png";
		foreach($res->result() as $re){
			$result = $re->nama;
		}

		if ( file_exists($fpath.$result) ) {
			return $server.$result;
		}
		else {
			return $server."default.png";
		}
	}
	function getUpload($id,$what,$opo="id"){
		$this->db->where($opo,$id);
		$res = $this->db->get("produk_upload");
		foreach($res->result() as $re){
			$result = $re->$what;
		}
		
		$path = 'uploads/produk/';
		$fpath = FCPATH.$path;
		$server = site_url($path);
		if ( file_exists($fpath.$result) ) {
			return $server.$result;
		}
		else {
			return $server."default.png";
		}
	}
	function getFotoUpload($id,$what,$opo="id"){
		$this->db->where("idproduk",$id);
		if($opo == "utama"){
			$this->db->where("jenis",1);
		}
		$res = $this->db->get("produk_upload");
		foreach($res->result() as $re){
			$result = $re->$what;
		}
		$path = 'uploads/produk/';
		$fpath = FCPATH.$path;
		$server = site_url($path);
		if ( file_exists($fpath.$result) ) {
			return $server.$result;
		}
		else {
			return $server."default.png";
		}
	}

	// GET VARIASI PRODUK
	function getVariasi($id,$what,$opo="idproduk"){
		$this->db->where($opo,$id);
		$this->db->limit(1);
		$res = $this->db->get("produk_variasi");

		if($what == "semua"){
			$result = array();
			foreach($res->result() as $key => $value){
				$result[$key] = $value;
			}
			$result = $res->num_rows() > 0 ? $result[0] : null;
		}else{
			$result = null;
			foreach($res->result() as $re){
				$result = $re->$what;
			}
		}
		return $result;
	}
	function getVariasiWarna($id,$what,$opo="id"){
		$this->db->where($opo,$id);
		$this->db->limit(1);
		$res = $this->db->get("produk_variasiwarna");

		if($what == "semua"){
			$result = array();
			foreach($res->result() as $key => $value){
				$result[$key] = $value;
			}
			$result = $result[0];
		}else{
			$result = null;
			foreach($res->result() as $re){
				$result = $re->$what;
			}
		}
		return $result;
	}
	function getVariasiSize($id,$what,$opo="id"){
		$this->db->where($opo,$id);
		$this->db->limit(1);
		$res = $this->db->get("produk_variasisize");

		if($what == "semua"){
			$result = array();
			foreach($res->result() as $key => $value){
				$result[$key] = $value;
			}
			$result = $result[0];
		}else{
			$result = null;
			foreach($res->result() as $re){
				$result = $re->$what;
			}
		}
		return $result;
	}
	function getVariasiList($id,$opo="idproduk"){
		$this->db->where($opo,$id);
		$res = $this->db->get("produk_variasi");

		$warnalis = array();
		$warna = array();
		$stok = array();
		foreach($res->result() as $re){
			$stl = ($re->stok > 0) ? " class='text-primary'" : " class='text-danger'";
			$warnalis[] = $re->warna;
			$warna[$re->warna] = (!isset($warna[$re->warna])) ? $this->getVariasiWarna($re->warna,"nama") : $warna[$re->warna];
			$stok[$re->warna][$re->size] = $this->getVariasiSize($re->size,"nama")." (<b$stl>".$re->stok."</b> pcs), ";
		}

		$warnalis = array_unique($warnalis);
		$warnalis = array_values($warnalis);
		$result = "";
		for($i=0; $i<count($warnalis); $i++){
			$result .= "<i><b>".strtoupper(strtolower($warna[$warnalis[$i]]))."</b></i><br/>";
			$result .= implode("",$stok[$warnalis[$i]]);
			$result .= "<div class='m-b-8'></div>";
		}

		return $result;
	}

	// GET TRANSAKSI
	function getTransaksi($id,$what,$opo="id"){
		$this->db->where($opo,$id);
		$this->db->limit(1);
		$res = $this->db->get("sales");

		if($what == "semua"){
			$result = array();
			foreach($res->result() as $key => $value){
				$result[$key] = $value;
			}
			$result = $result[0];
		}else{
			$result = null;
			foreach($res->result() as $re){
				$result = $re->$what;
			}
		}
		return $result;
	}

	// GET USERDATA
	function getProfil($id,$what,$opo="id"){
		$this->db->where($opo,$id);
		$this->db->limit(1);
		$res = $this->db->get("user_profil");

		if($what == "semua"){
			$result = array("User tidak ditemukan");
			foreach($res->result() as $key => $value){
				$result[$key] = $value;
			}
			$result = $result[0];
		}else{
			$result = null;
			foreach($res->result() as $re){
				$result = $re->$what;
			}
		}
		return $result;
	}
	function getUser($id,$what,$opo="id"){
		$this->db->where($opo,$id);
		$this->db->limit(1);
		$res = $this->db->get("user_admin");

		if($what == "semua"){
			$result = array();
			foreach($res->result() as $key => $value){
				$result[$key] = $value;
			}
			$result = $result[0];
		}else{
			$result = null;
			foreach($res->result() as $re){
				$result = $re->$what;
			}
		}
		return $result;
	}
	function getUserdata($id,$what,$opo="id"){
		$this->db->where($opo,$id);
		$this->db->limit(1);
		$res = $this->db->get("user");

		if($what == "semua"){
			$result = array();
			foreach($res->result() as $key => $value){
				$result[$key] = $value;
			}
			$result = $result[0];
		}else{
			$result = null;
			foreach($res->result() as $re){
				$result = $re->$what;
			}
		}
		return $result;
	}
	
	/* PLAYLIST */
	function getPlaylist($id,$what,$opo="id"){
		$this->db->where($opo,$id);
		$this->db->limit(1);
		$res = $this->db->get("topsong");

		if($what == "semua"){
			$result = array();
			foreach($res->result() as $key => $value){
				$result[$key] = $value;
			}
			$result = $result[0];
		}else{
			$result = null;
			foreach($res->result() as $re){
				$result = $re->$what;
			}
		}
		return $result;
	}
	function getJmlPesanan(){
		$this->db->where("status<=",1);
		$db = $this->db->get("sales");
		
		return $db->num_rows();
	}
	function getJmlPesan(){
		$this->db->where("tujuan",0);
		$this->db->where("baca",0);
		// $this->db->group_by("dari");
		$this->db->order_by("id DESC");
		$db = $this->db->get("pesan");
		
		return $db->num_rows();
	}
	
	// GET BLOG
	function getBlog($id,$what,$opo="id"){
		$this->db->where($opo,$id);
		$this->db->limit(1);
		$res = $this->db->get("@blog");

		if($what == "semua"){
			$result = array();
			foreach($res->result() as $key => $value){
				$result[$key] = $value;
			}
			$result = $result[0];
		}else{
			$result = null;
			foreach($res->result() as $re){
				$result = $re->$what;
			}
		}
		return $result;
	}
	
	// GET BANK
	function getBank($id,$what,$opo="id"){
		$this->db->where($opo,$id);
		$this->db->limit(1);
		$res = $this->db->get("@rekening_bank");

		if($what == "semua"){
			$result = array();
			foreach($res->result() as $key => $value){
				$result[$key] = $value;
			}
			$result = $result[0];
		}else{
			$result = null;
			foreach($res->result() as $re){
				$result = $re->$what;
			}
		}
		return $result;
	}
	function getRekening($id,$what,$opo="id"){
		$this->db->where($opo,$id);
		$this->db->limit(1);
		$res = $this->db->get("user_rekening");

		if($what == "semua"){
			$result = array();
      if($res->num_rows() > 0){
  			foreach($res->result() as $key => $value){
  				$result[$key] = $value;
  			}
  			$result = $result[0];
      }
		}else{
			$result = null;
			foreach($res->result() as $re){
				$result = $re->$what;
			}
		}
		return $result;
	}

	// VERIFIKASI
	function sendEmail($tujuan,$judul,$pesan,$subyek,$pengirim=null){
		$data = array(
			"jenis"		=> 1,
			"tujuan"	=> $tujuan,
			"judul"		=> $judul,
			"pesan"		=> $pesan,
			"subyek"	=> $subyek,
			"pengirim"	=> $pengirim,
			"tgl"		=> date("Y-m-d H:i:s"),
			"status"	=> 0
		);
		$this->db->insert("pesan_notifikasi",$data);

		return true;
	}
	function sendEmailOK($tujuan,$judul,$pesan,$subyek,$pengirim=null){
		$this->load->library('email');
		$seting = $this->globalset("semua");
		if($seting->email_jenis == 2){
			$config['protocol'] = "smtp";
			$config['smtp_host'] = $seting->email_server;
			$config['smtp_port'] = $seting->email_port;
			$config['smtp_user'] = $seting->email_notif;
			$config['smtp_pass'] = $seting->email_password;

			if($seting->email_port == 465){
				$config['smtp_crypto'] = "ssl";
			}
		}
		$config['charset'] = "utf-8";
		$config['mailtype'] = "html";
		$config['newline'] = "\r\n";
		$this->email->initialize($config);

		$this->email->from($seting->email_notif, $judul);
		$this->email->to($tujuan);
		if($pengirim != null){
		$this->email->cc($pengirim);
		}

		$pesan = $this->load->view("email_template",array("content"=>$pesan),true);
		$this->email->subject($subyek);
		$this->email->message($pesan);

		if($this->email->send()){
		return true;
		}else{
		//show_error($this->email->print_debugger());
		return false;
		}
	}
	public function sendWA($nomer,$pesan){
		$data = array(
			"jenis"		=> 2,
			"tujuan"	=> $nomer,
			"pesan"		=> $pesan,
			"tgl"		=> date("Y-m-d H:i:s"),
			"status"	=> 0
		);
		$this->db->insert("pesan_notifikasi",$data);
		
		return true;
	}
	public function sendWAOK($nomer,$pesan){
		$key = $this->globalset("woowa");
		$nomer = intval($nomer);
		$nomer = substr($nomer,0,2) != "62" ? "+62".$nomer : "+".$nomer;
		$url='http://116.203.92.59/api/send_message';
		$data = array(
		"phone_no"	=> $nomer,
		"key"		=> $key,
		"message"	=> $pesan."\n".date("Y/m/d H:i:s")
		);
		$data_string = json_encode($data);

		$ch = curl_init($url);
		curl_setopt($ch, CURLOPT_CUSTOMREQUEST, "POST");
		curl_setopt($ch, CURLOPT_POSTFIELDS, $data_string);
		curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
		curl_setopt($ch, CURLOPT_VERBOSE, 0);
		curl_setopt($ch, CURLOPT_CONNECTTIMEOUT, 0);
		curl_setopt($ch, CURLOPT_TIMEOUT, 360);
		curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, 0);
		curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, 0);
		curl_setopt($ch, CURLOPT_HTTPHEADER, array(
		'Content-Type: application/json',
		'Content-Length: ' . strlen($data_string))
		);
		$res = curl_exec($ch);
		curl_close($ch);

		if($res == "success"){
			return true;
		}else{
			return false;
		}
	}

	// SEND NOTIF
	function notiftransfer($order_id=null){
		if($order_id != null){
			$bayar = $this->getBayar($order_id,"semua");
			$trx = $this->getTransaksi($bayar->id,"semua","idbayar");
			$alamat = $this->getAlamat($trx->alamat,"semua");
			$usr = $this->getUserdata($bayar->usrid,"semua");
			$diskon = $bayar->diskon != 0 ? "Diskon: <b>Rp ".$this->formUang($bayar->diskon)."</b><br/>" : "";
			$diskonwa = $bayar->diskon != 0 ? "Diskon: *Rp ".$this->formUang($bayar->diskon)."*\n" : "";
			$toko = $this->globalset("semua");

			$rekening = "";
			$rekeningwa = "";
			$this->db->where("usrid",0);
			$rek = $this->db->get("user_rekening");
			foreach($rek->result() as $res){
				$bank = strtoupper($this->getBank($res->idbank,"nama"));
				$rekening .= "
					<b>".$bank." - ".$res->norek."</b><br/>
					a/n ".$res->atasnama."<br/>
				";
				$rekeningwa .= "
					*".$bank." - ".$res->norek."* \n
					a/n ".$res->atasnama." \n
				";
			}

			$pesan = "
				Halo <b>".$usr->nama."</b><br/>".
				"Terimakasih, sudah membeli produk kami.<br/>".
				"Segera lakukan pembayaran agar pesananmu segera diproses<br/> <br/>".
				"<b>Transfer pembayaran ke rekening berikut</b><br/>".
				$rekening.
				"<br/>".
				"<b>Detail Pesanan</b><br/>".
				"No Invoice: <b>#".$bayar->invoice."</b><br/>".
				"Total Pesanan: <b>Rp ".$this->formUang($bayar->total)."</b><br/>".
				"Ongkos Kirim: <b>Rp ".$this->formUang($trx->ongkir)."</b><br/>".$diskon.
				"Kurir Pengiriman: <b>".strtoupper($trx->kurir." ".$trx->paket)."</b><br/> <br/>".
				"Detail Pengiriman <br/>".
				"Penerima: <b>".$alamat->nama."</b> <br/>".
				"No HP: <b>".$alamat->nohp."</b> <br/>".
				"Alamat: <b>".$alamat->alamat."</b>".
				"<br/> <br/>".
				"Informasi cara pembayaran dan status pesananmu langsung di menu:<br/>".
				"<a href='".$this->mainsite_url("account/order")."'>PESANANKU &raquo;</a>
			";
			$this->sendEmail($usr->username,$toko->nama,$pesan,"Pesanan");
			$pesan = "
				Halo *".$usr->nama."* \n".
				"Terimakasih, sudah membeli produk kami. \n".
				"Segera lakukan pembayaran agar pesananmu segera diproses \n \n".
				"*Transfer pembayaran ke rekening berikut:* \n".
				$rekeningwa."\n".
				" \n".
				"*Detail Pesanan* \n".
				"No Invoice: *#".$bayar->invoice."* \n".
				"Total Pesanan: *Rp ".$this->formUang($bayar->total)."* \n".
				"Ongkos Kirim: *Rp ".$this->formUang($trx->ongkir)."* \n".$diskon.
				"Kurir Pengiriman: *".strtoupper($trx->kurir." ".$trx->paket)."* \n  \n".
				"Detail Pengiriman  \n".
				"Penerima: *".$alamat->nama."*  \n".
				"No HP: *".$alamat->nohp."*  \n".
				"Alamat: *".$alamat->alamat."*".
				" \n  \n".
				"Informasi cara pembayaran dan status pesananmu langsung di menu: \n".
				"*PESANANKU*
			";
			$this->sendWA($this->getProfil($usr->id,"nohp","usrid"),$pesan);
		}
	}
	function notifsukses($order_id=null){
		if($order_id != null){
			$bayar = $this->getBayar($order_id,"semua");
			$trx = $this->getTransaksi($bayar->id,"semua","idbayar");
			$alamat = $this->getAlamat($trx->alamat,"semua");
			$usr = $this->getUserdata($bayar->usrid,"semua");
			$diskon = $bayar->diskon != 0 ? "Diskon: <b>Rp ".$this->formUang($bayar->diskon)."</b><br/>" : "";
			$diskonwa = $bayar->diskon != 0 ? "Diskon: *Rp ".$this->formUang($bayar->diskon)."*\n" : "";
			$toko = $this->globalset("semua");

			$pesan = "
				Halo <b>".$usr->username."</b><br/>".
				"Terimakasih, pembayaran untuk pesananmu sudah kami terima.<br/>".
				"Mohon ditunggu, admin kami akan segera memproses pesananmu<br/>".
				"<b>Detail Pesanan</b><br/>".
				"No Invoice: <b>#".$bayar->invoice."</b><br/>".
				"Total Pesanan: <b>Rp ".$this->func->formUang($bayar->total)."</b><br/>".
				"Ongkos Kirim: <b>Rp ".$this->func->formUang($trx->ongkir)."</b><br/>".$diskon.
				"Kurir Pengiriman: <b>".strtoupper($trx->kurir." ".$trx->paket)."</b><br/> <br/>".
				"Detail Pengiriman <br/>".
				"Penerima: <b>".$alamat->nama."</b> <br/>".
				"No HP: <b>".$alamat->nohp."</b> <br/>".
				"Alamat: <b>".$alamat->alamat."</b>".
				"<br/> <br/>".
				"Cek Status pesananmu langsung di menu:<br/>".
				"<a href='".$this->mainsite_url("account/order")."'>PESANANKU &raquo;</a>
			";
			$this->func->sendEmail($usr->username,$toko->nama." - Pesanan",$pesan,"Pesanan");
			$pesan = "
				Halo *".$usr->username."* \n".
				"Terimakasih, pembayaran untuk pesananmu sudah kami terima. \n".
				"Mohon ditunggu, admin kami akan segera memproses pesananmu \n".
				"*Detail Pesanan* \n".
				"No Invoice: *#".$bayar->invoice."* \n".
				"Total Pesanan: *Rp ".$this->func->formUang($bayar->total)."* \n".
				"Ongkos Kirim: *Rp ".$this->func->formUang($trx->ongkir)."* \n".$diskon.
				"Kurir Pengiriman: *".strtoupper($trx->kurir." ".$trx->paket)."* \n  \n".
				"Detail Pengiriman  \n".
				"Penerima: *".$alamat->nama."* \n".
				"No HP: *".$alamat->nohp."* \n".
				"Alamat: *".$alamat->alamat."*".
				" \n  \n".
				"Cek Status pesananmu langsung di menu: \n".
				"*PESANANKU*
			";
			$this->func->sendWA($this->func->getProfil($usr->id,"nohp","usrid"),$pesan);
		}
	}
	function notifbatal($order_id=null,$jenis=1){
		if($order_id != null){
			$bayar = $this->getBayar($order_id,"semua");
			$trx = $this->getTransaksi($bayar->id,"semua","idbayar");
			$alamat = $this->getAlamat($trx->alamat,"semua");
			$usr = $this->getUserdata($bayar->usrid,"semua");
			$diskon = $bayar->diskon != 0 ? "Diskon: <b>Rp ".$this->formUang($bayar->diskon)."</b><br/>" : "";
			$diskonwa = $bayar->diskon != 0 ? "Diskon: *Rp ".$this->formUang($bayar->diskon)."*\n" : "";
			$toko = $this->globalset("semua");
			
			if($jenis == 1){
				$alasan = "DIBATALKAN OLEH ADMIN";
			}elseif($jenis == 2){
				$alasan = "DIBATALKAN OLEH PEMBELI";
			}elseif($jenis == 3){
				$alasan = "TELAH MELEWATI BATAS WAKTU JATUH TEMPO PEMBAYARAN";
			}else{
				$alasan = "-";
			}

			$pesan = "
				Halo <b>".$usr->username."</b><br/>".
				"Pesanan Anda telah dibatalkan<br/>".
				"Status: <br/>".
				"<b>".$alasan."</b><br/>".
				"<br/>".
				"<b>Detail Pesanan</b><br/>".
				"No Invoice: <b>#".$bayar->invoice."</b><br/>".
				"Total Pesanan: <b>Rp ".$this->formUang($bayar->total)."</b><br/>".
				"Ongkos Kirim: <b>Rp ".$this->formUang($trx->ongkir)."</b><br/>".$diskon.
				"Kurir Pengiriman: <b>".strtoupper($trx->kurir." ".$trx->paket)."</b><br/> <br/>".
				"Detail Pengiriman <br/>".
				"Penerima: <b>".$alamat->nama."</b> <br/>".
				"No HP: <b>".$alamat->nohp."</b> <br/>".
				"Alamat: <b>".$alamat->alamat."</b>".
				"<br/> <br/>".
				"Informasi cara pembayaran dan status pesananmu langsung di menu:<br/>".
				"<a href='".$this->mainsite_url("account/order")."'>PESANANKU &raquo;</a>
			";
			$this->sendEmail($usr->username,$toko->nama,$pesan,"Pesanan Dibatalkan");
			$pesan = "
				Halo *".$usr->username."* \n".
				"Pesanan Anda telah dibatalkan \n".
				"Status: \n".
				"*".$alasan."* \n".
				" \n".
				"*Detail Pesanan* \n".
				"No Invoice: *#".$bayar->invoice."* \n".
				"Total Pesanan: *Rp ".$this->formUang($bayar->total)."* \n".
				"Ongkos Kirim: *Rp ".$this->formUang($trx->ongkir)."* \n".$diskon.
				"Kurir Pengiriman: *".strtoupper($trx->kurir." ".$trx->paket)."* \n  \n".
				"Detail Pengiriman  \n".
				"Penerima: *".$alamat->nama."*  \n".
				"No HP: *".$alamat->nohp."*  \n".
				"Alamat: *".$alamat->alamat."*".
				" \n  \n".
				"Informasi cara pembayaran dan status pesananmu langsung di menu: \n".
				"*PESANANKU*
			";
			$this->sendWA($this->getProfil($usr->id,"nohp","usrid"),$pesan);
		}
	}

	// USABLE FUNCTION
	function cleanURL($url){
		$string = str_replace(' ', '-', $url);
		return preg_replace('/[^A-Za-z0-9\-]/', '', $string);
	}
	function clean($string) {
		//$string = str_replace(' ', '-', $string);
		return preg_replace('/[^A-Za-z0-9\-]/', ' ', $string);
	}
	function encode($string){
		return $this->encryption->encrypt($string);
	}
	function decode($string){
		return $this->encryption->decrypt($string);
	}
	function potong($str,$max,$after=""){
		if(strlen($str) > $max){
			$str = substr($str, 0, $max);
			$str = rtrim($str).$after;
		}
		return $str;
	}
	function formUang($format){
		$result= number_format($format,0,",",".");
		return $result;
	}
	function ubahTgl($format, $tanggal="now", $bahasa="id"){
		$en = array("Sun","Mon","Tue","Wed","Thu","Fri","Sat","Jan","Feb","Mar","Apr","May","Jun","Jul","Aug","Sep","Oct","Nov","Dec");
		$id = array("Minggu","Senin","Selasa","Rabu","Kamis","Jum'at","Sabtu","Januari","Februari","Maret","April","Mei","Juni","Juli","Agustus","September","Oktober","November","Desember");

		return str_replace($en,$$bahasa,date($format,strtotime($tanggal)));
	}
	function arrEnc($arr,$type="encode"){
		if($type == "encode"){
			$result = base64_encode(serialize($arr));
		}else{
			$result = unserialize(base64_decode($arr));
		}
		return $result;
	}
	function starRating($star=1){
		$star = "<i class='fa fa-star'></i>";
		$staro = "<i class='fa fa-star-o'></i>";
		$starho = "<i class='fa fa-star-half-o'></i>";
		if($star == 1){
			$hasil = $star.$staro.$staro.$staro.$staro;
		}
	}
	function createPagination($rows, $page, $perpage = 10, $function = "refreshTabel")
	{
		$tpages = ceil($rows / $perpage);
		$reload = "";
		$adjacents = 2;
		$prevlabel = "&lsaquo;";
		$nextlabel = "&rsaquo;";
		$out = "<div class=\"pagination mt-5 pagination-circle justify-content-center\">";
		// previous
		if ($page == 1) {
			$out .= "";
		} else {
			$out .= "<li class=\"page-item\"><a href=\"javascript:void(0)\" class='page-link' onclick=\"" . $function . "(1)\">&laquo;</a></li>\n";
			$out .= "<li class=\"page-item\"><a href=\"javascript:void(0)\" class='page-link' onclick=\"" . $function . "(" . ($page - 1) . ")\">" . $prevlabel . "</a></li>\n";
		}
		$pmin = ($page > $adjacents) ? ($page - $adjacents) : 1;
		$pmax = ($page < ($tpages - $adjacents)) ? ($page + $adjacents) : $tpages;
		for ($i = $pmin; $i <= $pmax; $i++) {
			if ($i == $page) {
				$out .= "<li class=\"page-item active\"><a href=\"javascript:void(0)\" class='page-link'>" . $i . "</a></li>\n";
			} elseif ($i == 1) {
				$out .= "<li class=\"page-item\"><a href=\"javascript:void(0)\" class='page-link' onclick=\"" . $function . "(" . $i . ")\">" . $i . "</a></li>\n";
			} else {
				$out .= "<li class=\"page-item\"><a href=\"javascript:void(0)\" class='page-link' onclick=\"" . $function . "(" . $i . ")\">" . $i . "</a></li>\n";
			}
		}

		// next
		if ($page < $tpages) {
			$out .= "<li class=\"page-item\"><a href=\"javascript:void(0)\" onclick=\"" . $function . "(" . ($page + 1) . ")\" class='page-link'>" . $nextlabel . "</a></li>\n";
		}
		if ($page < ($tpages - $adjacents)) {
			$out .= "<li class=\"page-item\"><a href=\"javascript:void(0)\" onclick=\"" . $function . "(" . $tpages . ")\" class='page-link'>&raquo;</a></li>\n";
		}
		$out .= "</div>";
		return $out;
	}
}
