<?php
// Skrip berikut ini adalah skrip yang bertugas untuk meng-export data tadi ke excell
header("Content-type: application/vnd-ms-excel");
header("Content-Disposition: attachment; filename=Lap_TimbanganUser.xls");
include "../config.php";
include '../tgl-indo.php';
$Tahun=date('Y');

?>
<table>
	<tr><td  align="center"><strong></strong></td></tr>
	<tr><td colspan="7"><strong>Laporan Realisasi Penerimaan </strong></td></tr>
	<tr><td colspan="7"><strong>Dinas Perdagangan dan Perindustrian</strong></td></tr>
	<tr><td colspan="7"><strong>Kabupaten Jombang</strong></td></tr>
	<tr><td colspan="7"><strong>Tahun <?php echo $Tahun; ?></strong></td></tr>
	<tr></tr>
</table>

<div class="table-responsive">  
	<table class="table table" border="1" cellpadding="10">
    <thead>
       <thead>
		<tr>
		  <th align="center" rowspan="2">No</th>
		  <th align="center" rowspan="2">Nama UTTP</th>
		  <th align="center" rowspan="2">Alamat UTTP</th>
		  <th align="center" colspan="2">Jenis UTTP</th>
		  <th align="center" rowspan="2">Tahun Masa Tera</th>
		  <th align="center" colspan="2">Nilai Retribusi</th>
		</tr>
		<tr height="25">
			<th align="center" >ID UTTP</th>
			<th align="center" >Nama</th>
			<th align="center" >Lokasi</th>
			<th align="center" >Kantor</th>
		</tr>				  
	  </thead>
	  
	<?php 	  
		$q = "SELECT a.NamaPerson,a.AlamatLengkapPerson,a.NIK,b.KodeLokasi,b.NamaLokasi,b.AlamatLokasi,d.NamaTimbangan,e.NamaKelas,f.NamaUkuran,f.RetribusiDikantor,f.RetribusiDiLokasi,c.IDTimbangan, ADDDATE(h.TglTera, INTERVAL d.MasaTera YEAR) as masa FROM mstperson a join lokasimilikperson b on (a.IDPerson=b.IDPerson) join timbanganperson c on (b.IDPerson,b.KodeLokasi)=(c.IDPerson,c.KodeLokasi)
		join msttimbangan d on c.KodeTimbangan=d.KodeTimbangan 
		join kelas e on (c.KodeTimbangan,c.KodeKelas)=(e.KodeTimbangan,e.KodeKelas) 
		join detilukuran f on (c.KodeTimbangan,c.KodeKelas,c.KodeUkuran)=(f.KodeTimbangan,f.KodeKelas,f.KodeUkuran) 
		left join trtimbanganitem g on c.IDTimbangan=g.IDTimbangan 
		left join tractiontimbangan h on h.NoTransaksi=g.NoTransaksi  
		where a.UserName !='dinas' and c.StatusUTTP='Aktif'";

		if(@$_GET['kec'] !=null){
			$q .= " AND b.KodeKec='".$_GET['kec']."' ";
		}
		if(@$_GET['psr'] !=null){
			$q .= " AND b.KodePasar='".$_GET['psr']."' ";
		}
		$q .=" GROUP by c.IDTimbangan order by a.NamaPerson ASC  ";

		$result = mysqli_query($koneksi,$q);
		$results = array();
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
		@$data[$value['NamaPerson']][$value['NamaLokasi']][$value['IDTimbangan']][$value['NamaTimbangan']][$value['NamaKelas']][$value['NamaUkuran']][$value['masa']][$value['RetribusiDikantor']][$value['RetribusiDiLokasi']] += 1;
	 
		// simpan sementara untuk mencari rowspan KodeLokasi
		@$bind['psr'][$value['NamaPerson']][$value['NamaLokasi']][$value['IDTimbangan']] = 1;
		 
		// simpan sementara untuk mencari rowspan Company
		@$bind['lok'][$value['NamaPerson']][$value['NamaLokasi']][$value['IDTimbangan']] += 1;
		
		// print_r($data);
	}
 
	/**
	 * Hitung rowspan per Nama
	 */
	$rowspanCountry = array();
	if (is_array(@$bind['psr']) || is_object(@$bind['psr'])){
		foreach ($bind['psr'] as $country => $companies) {
			foreach ($companies as $key => $cities) {
				foreach ($cities as $value) {
					$rowspanCountry[$country][] = $value;
				}
			}
		}
	}

	
		
?>
	
	
    <tbody id="table_body">
        <?php $no = 1; ?>
        <?php foreach($data as $country => $companies):?>
 
            <?php $countryRows = array_sum($rowspanCountry[$country]);?>
         
            <tr>
                <td <?=$countryRows>0?'rowspan="'.$countryRows.'"':''?> style="vertical-align: top; text-align: top;"><?=$no;?></td>
                <td <?=$countryRows>0?'rowspan="'.$countryRows.'"':''?> style="vertical-align: top; text-align: top;">
                    <?=$country;?>
                </td>
                 
                <?php foreach($companies as $company => $items):?>
                 
                    <?php $companyRows = sizeof($bind['lok'][$country][$company]);?>
                    <td <?=$companyRows>0?'rowspan="'.$companyRows.'"':''?> style="vertical-align: top; text-align: top;">
                        <?=$company;?>
                    </td>
                     
                    <?php foreach($items as $city => $yo):?>
                     
                        <td style="vertical-align: top; text-align: top;">
                            <?=$city;?>
                            
							
                        </td>
						
						 <?php foreach($yo as $nama => $nmtim):?>
                            <?php foreach($nmtim as $kelas => $ukur):?>
                            <?php foreach($ukur as $ukuran => $masa):?>
                             
                            <td style="vertical-align: top;"><?=$nama." ".$kelas." ".$ukuran?></td>
                            
							 <?php foreach($masa as $tahun => $lokasi):?>
							 
							 
							  <td style="vertical-align: top; text-align: top;">
                            <?php $masa =  isset($tahun) ? $tahun :'' ?>
                            <?=@TanggalIndo($masa)?>
                            
							
                        </td>
							 <?php foreach($lokasi as $tarif1 => $kantor):?>
							  <td style="vertical-align: top; text-align: top;">
                            <?=$tarif1;?>
                            
							
                        </td>
							 <?php foreach($kantor as $tarif2 => $end):?>
							  <td style="vertical-align: top; text-align: top;">
                            <?=$tarif2;?>
                            
							
                        </td>
						
                    </tr> <!-- tr disini sukses kalo cuma 1 third party -->
					 <?php endforeach;?>
					 <?php endforeach;?>
					 <?php endforeach;?>
					 <?php endforeach;?>
					 <?php endforeach;?>
					 <?php endforeach;?>
 
                    <?php endforeach;?>
 
                <?php endforeach;?>
            <?php $no++;?>
        <?php endforeach;?>
    </tbody>
</table>
</div>
<?php
echo '<script language="javascript">
		window.close();
	  </script>';
?>
