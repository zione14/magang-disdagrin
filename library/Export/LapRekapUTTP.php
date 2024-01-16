<?php
// Skrip berikut ini adalah skrip yang bertugas untuk meng-export data tadi ke excell
header("Content-type: application/vnd-ms-excel");
header("Content-Disposition: attachment; filename=LapRekapUTTP.xls");
include "../config.php";
$QuerySetting = mysqli_query($koneksi,"SELECT KodeTimbangan FROM msttimbangan");
$nums		  = mysqli_num_rows($QuerySetting);
$Tahun=date('Y');

	$q = "SELECT COUNT(c.IDTimbangan) as jumlah, a.IDPerson,b.KodeLokasi,f.RetribusiDikantor,c.IDTimbangan,d.JenisTimbangan,f.KodeUkuran FROM mstperson a join lokasimilikperson b on (a.IDPerson=b.IDPerson) join timbanganperson c on (b.IDPerson,b.KodeLokasi)=(c.IDPerson,c.KodeLokasi)
	join msttimbangan d on c.KodeTimbangan=d.KodeTimbangan 
	join kelas e on (c.KodeTimbangan,c.KodeKelas)=(e.KodeTimbangan,e.KodeKelas) 
	join detilukuran f on (c.KodeTimbangan,c.KodeKelas,c.KodeUkuran)=(f.KodeTimbangan,f.KodeKelas,f.KodeUkuran) 
	where a.UserName !='dinas' and c.StatusUTTP='Aktif'";

	if(@$_GET['kec'] !=null){
			$q .= " AND b.KodeKec='".$_GET['kec']."' ";
	}
	if(@$_GET['psr'] !=null){
		$q .= " AND b.KodePasar='".$_GET['psr']."' ";
	}
	$q .=" GROUP by c.KodeUkuran order by a.NamaPerson,b.NamaLokasi,d.KodeTimbangan  ASC";

	$result = mysqli_query($koneksi,$q);
	$tcount = mysqli_num_rows($result);
	$results = array();
	$num = 0;
	while ($row = mysqli_fetch_array($result)){
		$results[] = $row;
	}

	/**
	 * Tampung rawdata
	 * ~ per nama, lokasi & dan timbangan
	 * Tampung data bantuan
	 * ~ rowspan & timbangan 
	 */
	$data = [];
	$bind = [];
	foreach ($results as $key => $value) {
		$month = (int) ($value['JenisTimbangan']);
		@$data[$value['IDPerson']][$value['KodeLokasi']][$value['KodeUkuran']][$month]['incident'] += 1;
	 
		// simpan sementara untuk mencari rowspan KodeLokasi
		@$bind['psr'][$value['IDPerson']][$value['KodeLokasi']][$value['KodeUkuran']] = 1;
		 
		// simpan sementara untuk mencari rowspan idtimbangan
		@$bind['lok'][$value['IDPerson']][$value['KodeLokasi']][$value['KodeUkuran']] += 1;
		
	}
 
	/**
	 * Hitung rowspan per Nama
	 */
	@$rowspanCountry = array();
	if (is_array(@$bind['psr']) || is_object(@$bind['psr'])){
		foreach (@$bind['psr'] as $country => $companies) {
			foreach (@$companies as $key => $cities) {
				foreach (@$cities as $value) {
					@$rowspanCountry[$country][] = @$value;
				}
			}
		}	
	}
?>
<table>
	<tr><td  align="center"><strong></strong></td></tr>
	<tr><td colspan="7"><strong>Laporan Realisasi Penerimaan </strong></td></tr>
	<tr><td colspan="7"><strong>Dinas Perdagangan dan Perindustrian</strong></td></tr>
	<tr><td colspan="7"><strong>Kabupaten Jombang</strong></td></tr>
	<tr><td colspan="7"><strong>Tahun <?php echo $Tahun; ?></strong></td></tr>
	<tr></tr>
</table>

<table border="1" cellpadding="10">
	<thead><!--style="display:none" -->
		<tr>
			<th rowspan="2">NO</th>
			<th rowspan="2">NAMA PEMILIK UTTP</th>
			<th rowspan="2">NO KTP</th>
			<th rowspan="2">ALAMAT PEMILIK UTTP</th>
			<th rowspan="2">BLOK/LOKASI</th>
			<th rowspan="2">ALAMAT / TEMPAT USAHA</th>

			<td colspan="<?=($nums*3);?>" style="width: 40%; vertical-align: top; text-align: center;">JENIS UTTP YANG DIMILIKI</td>
			<!--<th rowspan="2">JUMLAH</th>
			<th rowspan="2">Masa Berlaku Tanda Tera</th>-->
		</tr>
		<tr>
			<?php 
				$kode = mysqli_query($koneksi,"SELECT JenisTimbangan FROM msttimbangan Order by JenisTimbangan");
				while($r = mysqli_fetch_array($kode)){ ?>
					<th><?php echo $r['JenisTimbangan'] ?></th>
					<th align="center" >Kap</th>
					<th align="center" >Tarif (Rp)</th>
			<?php } ?>
		</tr>		  
	  </thead>
	  
	  
	  <tbody id="table_body">
        <?php $no = 1; ?>
        <?php foreach($data as $country => $companies):?>
            <?php $countryRows = array_sum($rowspanCountry[$country]);?>
            <tr>
                <td <?=$countryRows>0?'rowspan="'.$countryRows.'"':''?> style="vertical-align: top; text-align: top;"><?=$no;?></td>
                <td <?=$countryRows>0?'rowspan="'.$countryRows.'"':''?> style="vertical-align: top; text-align: top;">
					<?php 
						$person = mysqli_query($koneksi,"SELECT NamaPerson,NIK,AlamatLengkapPerson FROM mstperson where IDPerson='$country' order by NamaPerson ASC");
						$nama	= mysqli_fetch_array($person);
						echo $nama[0];
					?>
				</td>
				<td <?=$countryRows>0?'rowspan="'.$countryRows.'"':''?> style="vertical-align: top; text-align: top;">
					<?php echo $nama[1]; ?>
				</td>
				<td <?=$countryRows>0?'rowspan="'.$countryRows.'"':''?> style="vertical-align: top; text-align: top;"><?php echo $nama[2]; ?></td>
			
                 
                <?php foreach($companies as $company => $items):?>
                    <?php $companyRows = sizeof(@$bind['lok'][$country][$company]);?>
                    <td <?=$companyRows>0?'rowspan="'.$companyRows.'"':''?> style="vertical-align: top; text-align: top;">
						<?php 
							$alamat = mysqli_query($koneksi,"SELECT NamaLokasi,AlamatLokasi FROM lokasimilikperson where IDPerson='$country' and KodeLokasi='$company'");
							$blok	= mysqli_fetch_array($alamat);
							echo $blok[0];
						?>
                    </td>
					<td <?=$companyRows>0?'rowspan="'.$companyRows.'"':''?> style="vertical-align: top; text-align: top;">
                        <?php echo $blok[1]; ?>
                    </td>
                     
                    <?php foreach($items as $city => $yo):?>
                     
                       
						<?php 
							$table = mysqli_query($koneksi,"SELECT JenisTimbangan FROM msttimbangan Order by JenisTimbangan");
							while($colom = mysqli_fetch_array($table)){
								$tim = mysqli_query($koneksi,"SELECT b.UkuranRealTimbangan,COUNT(b.IDTimbangan) as jm, c.RetribusiDiKantor,b.Satuan
								FROM msttimbangan a 
								join timbanganperson b on a.KodeTimbangan=b.KodeTimbangan 
								join detilukuran c on (b.KodeTimbangan,b.KodeKelas,b.KodeUkuran)=(c.KodeTimbangan,c.KodeKelas,c.KodeUkuran) 
								WHERE a.JenisTimbangan='".$colom[0]."' and b.IDPerson='$country' and b.KodeLokasi='$company' and b.KodeUkuran='$city' and b.StatusUTTP='Aktif'");
								while($use = mysqli_fetch_array($tim)){
									echo '<td>'.$use[1].'</td>';
									echo '<td>'.$use[0]." ".$use[3].'</td>';
									echo '<td>'.$use[2]*$use[1].'</td>';

								}
				 } ?>
						
                    </tr> <!-- tr disini sukses kalo cuma 1 third party -->
					
 
                    <?php endforeach;?>
 
                <?php endforeach;?>
            <?php $no++;?>
        <?php endforeach;?>
		<tr>
			<td colspan="6" align="center"><strong>JUMLAH</strong></td>
			<?php
				$hitung = mysqli_query($koneksi,"SELECT JenisTimbangan FROM msttimbangan Order by JenisTimbangan");
				while($hasil = mysqli_fetch_array($hitung)){
					$count = mysqli_query($koneksi,"SELECT COUNT(b.IDTimbangan) as jm FROM msttimbangan a join timbanganperson b on a.KodeTimbangan=b.KodeTimbangan where  a.JenisTimbangan='".$hasil[0]."'  and b.StatusUTTP='Aktif' and b.IDPerson !='PRS-2019-0000000'"); 
					while($min = mysqli_fetch_array($count)){
						if(@$tcount !=null OR @$tcount > 0){
							echo '<td>'.$min[0].'</td>';
						}else{
							echo '<td></td>';
						}
							echo '<td></td>';
							echo '<td></td>';
						
					}
				}
			?>
		</tr>
    </tbody>
</table>
<?php
echo '<script language="javascript">
		window.close();
	  </script>';
?>
