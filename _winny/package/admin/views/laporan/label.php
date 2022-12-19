<!DOCTYPE html>
<html>
<head>
	<link rel="stylesheet" href="<?=base_url()?>/assets/css/bootstrap.min.css">
	<link rel="stylesheet" href="<?=base_url()?>/assets/css/minmin.css?v=<?=time()?>">
</head>
<body onload="window.print();setTimeout(function(){window.close();},3000);">
	<div class="labelkirim">
		<?php
			$trxid = (isset($_GET["id"])) ? intval($_GET["id"]) : 0;
			if($trxid != 0){
				$trx = $this->setting->getTransaksi($trxid,"semua");
				$alamat = $this->setting->getAlamat($trx->alamat,"semua");
				$kab = '-';
				$prov = '-';
				$lkp = '-';
				if (isset($alamat->idkab) && $alamat->idkab != null) {
					$kab = $this->rajaongkir->getCity($alamat->idkab)['rajaongkir']['results'];
					$prov = $this->rajaongkir->getProvince($kab['province_id'])['rajaongkir']['results']['province'];
					$lkp = $kab['city_name'] . " " . $prov . " " . $alamat->kodepos;
				}
		?>
			<div class="header">
				<?php if($trx->dropship == ""){ ?>
					<img src="<?=base_url($this->setting->globalset("logo"))?>" class="logo" />
				<?php } ?>
			</div>
			<div class="row">
				<?php if($trx->dropship == ""){ ?>
				<div class="content col-6 p-lr-0">
					<b><u>Pengirim</u></b><br/>
					<b style="font-size:120%;"><?=$this->setting->globalset("nama")?></b> 
					(Telp. <b><?=$this->setting->globalset("notelp")?></b>)<br/>
					<?=$this->rajaongkir->getCity($this->setting->globalset("kota"))['rajaongkir']['results']['city_name'];?>
				</div>
				<?php }else{ ?>
				<div class="content col-6 p-lr-0">
					<b><u>Pengirim</u></b><br/>
					<b style="font-size:120%;"><?=$trx->dropship?></b> 
					(Telp. <b><?=$trx->dropshipnomer?></b>)<br/>
					<?=$trx->dropshipalamat?>
				</div>
				<?php } ?>
				<div class="content col-6 p-lr-0">
					<b><u>Penerima</u></b><br/>
					<b style="font-size:120%;"><?=$alamat->nama?></b> 
					(Telp. <b><?=$alamat->nohp?></b>)<br/>
					<?=$alamat->alamat."<br/>".$lkp?>
				</div>
			</div>
		<?php
			}
		?>
	</div>
</body>
</html>