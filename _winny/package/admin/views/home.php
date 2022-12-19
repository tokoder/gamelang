<?php
	$jualtoday = 0;$trxtoday = 0;$omsettoday = 0;
	$jualkemarin = 0;$trxkemarin = 0;$omsetkemarin = 0;
	$jualbulan = 0;$trxbulan = 0;$omsetbulan = 0;
	$juallalu = 0;$trxlalu = 0;$omsetlalu = 0;
	$sebulan = date("Ymd",strtotime("-30 day", strtotime(date("Y-m-d"))));
	$kemarin = date("Y-m-d",strtotime("-1 day", strtotime(date("Y-m-d"))));
	$today = date("Y-m-d");
	$bulan = date("Y-m-");
	$lalu = date("m", strtotime("-1 month"));
	$lalu = date("Y")."-".$lalu."-";
	for($i=0; $i<20; $i++){
		$day = 20 - $i;
		$graphtgl[] = date("d/m/Y",strtotime("-".$day." day", strtotime(date("Y-m-d"))));
	}
	
	$this->db->where("tgl>=",$lalu."1");
	$this->db->where("status",3);
	$sales = $this->db->get("sales");
	if ($sales) :
		foreach($sales->result() as $r){
			if($this->setting->ubahTgl("Y-m-",$r->tgl) == $bulan){
				$trxbulan += 1;
			}elseif($this->setting->ubahTgl("Y-m-",$r->tgl) == $lalu){
				$trxlalu += 1;
			}
			$tglan = $this->setting->ubahTgl("Ymd",$r->tgl);
			$tgln = $this->setting->ubahTgl("d/m/Y",$r->tgl);
			
			$tgl = explode(" ",$r->tgl);
			if($tgl[0] == $today){
				$trxtoday += 1;
			}elseif($tgl[0] == $kemarin){
				$trxkemarin += 1;			
			}
			
			$jml = 0;
			$this->db->where("idtransaksi",$r->id);
			$trx = $this->db->get("sales_produk");
			foreach($trx->result() as $rs){
				if($this->setting->ubahTgl("Y-m-",$r->tgl) == $bulan){
					$jualbulan += $rs->jumlah;
				}elseif($this->setting->ubahTgl("Y-m-",$r->tgl) == $lalu){
					$juallalu += $rs->jumlah;
				}
				
				//$tgl = explode(" ",$rs->tgl);
				if($tgl[0] == $today){
					$jualtoday += $rs->jumlah;
				}elseif($tgl[0] == $kemarin){
					$jualkemarin += $rs->jumlah;			
				}
				if($this->setting->ubahTgl("Ymd",$r->tgl) >= $sebulan){
					$jml += $rs->jumlah;
				}
			}
			
			if($tglan >= $sebulan){
				if(!isset($pcs[$tgln])){ $pcs[$tgln] = 0; }
				$pcs[$tgln] += $jml;
				if(!isset($nota[$tgln])){ $nota[$tgln] = 0; }
				$nota[$tgln] += 1; 
			}
		}
	endif;
	
	for($i=0; $i<count($graphtgl); $i++){
		$pcsn[] = isset($pcs[$graphtgl[$i]]) ? $pcs[$graphtgl[$i]] : 0;
		$notan[] = isset($nota[$graphtgl[$i]]) ? $nota[$graphtgl[$i]] : 0;
		$graphtgls = explode("/",$graphtgl[$i]);
		$graphtgl[$i] = $graphtgls[0]."/".$graphtgls[1];
	}
	
	$this->db->where("tgl>=",$lalu."1");
	$this->db->where("status",1);
	$invoice = $this->db->get("invoice");
	if ($invoice) :
		foreach($invoice->result() as $r){
			if($this->setting->ubahTgl("Y-m-",$r->tgl) == $bulan){
				$omsetbulan += $r->total - $r->kodebayar;
			}elseif($this->setting->ubahTgl("Y-m-",$r->tgl) == $lalu){
				$omsetlalu += $r->total - $r->kodebayar;
			}
			
			$tgl = explode(" ",$r->tgl);
			if($tgl[0] == $today){
				$omsettoday += $r->total - $r->kodebayar;
			}elseif($tgl[0] == $kemarin){
				$omsetkemarin += $r->total - $r->kodebayar;			
			}
		}
	endif;
	
	$this->db->select("SUM(total) AS total,SUM(kodebayar) AS kode,usrid");;
	$this->db->like("tgl",$bulan);
	$this->db->where("status",1);
	$this->db->group_by("usrid");
	$this->db->order_by("total DESC");
	$invoice = $this->db->get("invoice");
	if ($invoice) :
		foreach($invoice->result() as $r){
			$this->db->select("COUNT(*) AS totaldata,usrid");
			$this->db->where("usrid",$r->usrid);
			$trx = $this->db->get("sales");
			foreach($trx->result() as $rf){
				$total = $rf->totaldata;
				$jml = 0;
				$this->db->select("SUM(jumlah) AS jml");
				$this->db->where("usrid",$rf->usrid);
				$trx = $this->db->get("sales_produk");
				foreach($trx->result() as $rs){
					$jml = $rs->jml;
				}
			}
			$usr = $this->user_model->getProfil($r->usrid,"semua","usrid");
			$usrnama = isset($usr->nama) ? $usr->nama : "USER DIHAPUS";
			$usrd = $this->user_model->getUser($r->usrid,"semua");
			if($usrd->level == 3){
				$topagen_total[] = $r->total;
				$topagen_usrid[] = $r->usrid;
				$topagen_nama[] = $usrnama;
				$topagen_transaksi[] = $total;
				$topagen_jmlpcs[] = $jml;
			}elseif($usrd->level == 2){
				$topres_total[] = $r->total;
				$topres_usrid[] = $r->usrid;
				$topres_nama[] = $usrnama;
				$topres_transaksi[] = $total;
				$topres_jmlpcs[] = $jml;
			}else{
				$topus_total[] = $r->total;
				$topus_usrid[] = $r->usrid;
				$topus_nama[] = $usrnama;
				$topus_transaksi[] = $total;
				$topus_jmlpcs[] = $jml;
			}
		}
	endif;
?>
<h4 class="page-title">Dashboard</h4>
<div class="row">
	<div class="col-md-3">
		<div class="card card-stats card-success">
			<div class="card-body">
				<h4 class="card-title p-b-5"><i class="fas fa-dolly-flatbed"></i> Penjualan Hari Ini</h4>
				<div class="numbers border-top p-t-10">
					<h4 class="card-title"><?=$this->setting->formUang($jualtoday)?> PCS</h4>
					<p class="card-category"><?=$this->setting->formUang($trxtoday)?> Transaksi</p>
					<p class="card-category fw-bold">Omset Rp. <?=$this->setting->formUang($omsettoday)?></p>
				</div>
			</div>
		</div>
	</div>
	<div class="col-md-3">
		<div class="card card-stats card-info">
			<div class="card-body">
				<h4 class="card-title p-b-5"><i class="fas fa-history"></i> Penjualan Kemarin</h4>
				<div class="numbers border-top p-t-10">
					<h4 class="card-title"><?=$this->setting->formUang($jualkemarin)?> PCS</h4>
					<p class="card-category"><?=$this->setting->formUang($trxkemarin)?> Transaksi</p>
					<p class="card-category fw-bold">Omset Rp. <?=$this->setting->formUang($omsetkemarin)?></p>
				</div>
			</div>
		</div>
	</div>
	<div class="col-md-3">
		<div class="card card-stats card-primary">
			<div class="card-body">
				<h4 class="card-title p-b-5"><i class="fas fa-calendar-check"></i> Stat. Bulan Ini</h4>
				<div class="numbers border-top p-t-10">
					<h4 class="card-title"><?=$this->setting->formUang($jualbulan)?> PCS</h4>
					<p class="card-category"><?=$this->setting->formUang($trxbulan)?> Transaksi</p>
					<p class="card-category fw-bold">Omset Rp. <?=$this->setting->formUang($omsetbulan)?></p>
				</div>
			</div>
		</div>
	</div>
	<div class="col-md-3">
		<div class="card card-stats card-warning">
			<div class="card-body">
				<h4 class="card-title p-b-5"><i class="fas fa-calendar-alt"></i> Stat. Bulan Lalu</h4>
				<div class="numbers border-top p-t-10">
					<h4 class="card-title"><?=$this->setting->formUang($juallalu)?> PCS</h4>
					<p class="card-category"><?=$this->setting->formUang($trxlalu)?> Transaksi</p>
					<p class="card-category fw-bold">Omset Rp. <?=$this->setting->formUang($omsetlalu)?></p>
				</div>
			</div>
		</div>
	</div>
</div>
<div class="row">
	<div class="col-12">
		<div class="card">
			<div class="card-header">
				<h4 class="card-title">Grafik Penjualan (20 hari terakhir)</h4>
				<p class="card-category">
					<i class="fas fa-square text-success"></i> Penjualan (PCS) &nbsp;
					<i class="fas fa-square text-primary"></i> Transaksi (Nota)
				</p>
			</div>
			<div class="card-body">
				<div id="salesChart" class="chart"></div>
				<!--<small><i class="text-warning">
					<i class="fas fa-circle blink"></i> grafik hanya menampilkan data yang lebih dari 0, 
					data yang kosong tidak akan tampil di grafik
				</i></small>-->
			</div>
		</div>
	</div>
</div>
<div class="row">
	<div class="col-md-4">
		<div class="card">
			<div class="card-header">
				<h4 class="card-title">Top 5 Agen / Distributor</h4>
			</div>
			<div class="card-body">
				<?php
					if(isset($topagen_usrid) AND count($topagen_usrid) > 0){
						$off = (count($topagen_usrid) < 5) ? count($topagen_usrid) : 5;
						for($i=0; $i<$off; $i++){
				?>
					<div class="row m-b-10">
						<div class="col-2 align-items-center fs-40"><i class="fas fa-user-circle"></i></div>
						<div class="col-10">
							<b>
								<?=strtoupper(strtolower($topagen_nama[$i]))?><br/>
								Rp. <?=$this->setting->formUang($topagen_total[$i])?><br/>
							</b>
							<small>Total: <?=$this->setting->formUang($topagen_jmlpcs[$i])?> pcs / <?=$this->setting->formUang($topagen_transaksi[$i])?> nota</small>
						</div>
					</div>
				<?php
						}
					}else{
						echo "<div class='text-center m-b-10'>Belum ada transaksi</div>";
					}
				?>
			</div>
		</div>
	</div>
	<div class="col-md-4">
		<div class="card">
			<div class="card-header">
				<h4 class="card-title">Top 5 Reseller</h4>
			</div>
			<div class="card-body">
				<?php
					if(isset($topres_usrid) AND count($topres_usrid) > 0){
						$off = (count($topres_usrid) < 5) ? count($topres_usrid) : 5;
						for($i=0; $i<$off; $i++){
				?>
					<div class="row m-b-10">
						<div class="col-2 align-items-center fs-40"><i class="fas fa-user-circle"></i></div>
						<div class="col-10">
							<b>
								<?=strtoupper(strtolower($topres_nama[$i]))?><br/>
								Rp. <?=$this->setting->formUang($topres_total[$i])?><br/>
							</b>
							<small>Total: <?=$this->setting->formUang($topres_jmlpcs[$i])?> pcs / <?=$this->setting->formUang($topres_transaksi[$i])?> nota</small>
						</div>
					</div>
				<?php
						}
					}else{
						echo "<div class='text-center m-b-10'>Belum ada transaksi</div>";
					}
				?>
			</div>
		</div>
	</div>
	<div class="col-md-4">
		<div class="card">
			<div class="card-header">
				<h4 class="card-title">Top 5 User Retail</h4>
			</div>
			<div class="card-body">
				<?php
					if(isset($topus_usrid) AND count($topus_usrid) > 0){
						$off = (count($topus_usrid) < 5) ? count($topus_usrid) : 5;
						for($i=0; $i<$off; $i++){
				?>
					<div class="row m-b-10">
						<div class="col-2 align-items-center fs-40"><i class="fas fa-user-circle"></i></div>
						<div class="col-10">
							<b>
								<?=strtoupper(strtolower($topus_nama[$i]))?><br/>
								Rp. <?=$this->setting->formUang($topus_total[$i])?><br/>
							</b>
							<small>Total: <?=$this->setting->formUang($topus_jmlpcs[$i])?> pcs / <?=$this->setting->formUang($topus_transaksi[$i])?> nota</small>
						</div>
					</div>
				<?php
						}
					}else{
						echo "<div class='text-center m-b-10'>Belum ada transaksi</div>";
					}
				?>
			</div>
		</div>
	</div>
</div>

<script type="text/javascript">
	$(function(){
		// salesChart
		var dataSales = {
			labels: [<?="'".implode("','",$graphtgl)."'"?>],
			series: [
			{name: "pcs",data:[<?=implode(",",$pcsn)?>]},
			{name: "nota",data:[<?=implode(",",$notan)?>]}
			]
		}

		var optionChartSales = {
			plugins: [
			Chartist.plugins.tooltip()
			],
			series: {
				'pcs': {
					showArea: true
				},
				'nota': {
					showArea: true
				}
			},
			height: "245px",
		}

		Chartist.Line('#salesChart', dataSales, optionChartSales, []);
	});
</script>