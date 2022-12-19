<?php
	$this->db->where("idproduk",$idproduk);
	$this->db->order_by("jenis","DESC");
	$db = $this->db->get("produk_upload");
	foreach($db->result() as $res){
		$path = 'uploads/produk/';
		$fpath = FCPATH.$path;
		$server = site_url($path);
		if ( file_exists($fpath.$res->nama) ) {
			$return = $server.$res->nama;
		}
		else {
			$return = $server."default.png";
		}
		if($res->jenis == 1){
			$btn = "<button type='button' class='utama' disabled>foto utama</button>";
		}else{
			$btn = "<button type='button' class='jadiutama' onclick='jadikanUtama(".$res->id.")'>Utama</button>
				<button type='button' class='hapus' onclick='hapusFoto(".$res->id.")'>Hapus</button>";
		}
		echo "
			<div class='uploadfoto-item'>
				<img src='".$return."' />
				".$btn."
			</div>
		";
	}
?>