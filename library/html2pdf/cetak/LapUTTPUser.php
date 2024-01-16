<?php 
session_start();  
ob_start();
date_default_timezone_set('Asia/Jakarta');
include_once("../../config.php"); //buat koneksi ke database  
include '../../tgl-indo.php';
include '../../terbilang.php';
$Tahun=date('Y');  


?>

<page style="width:100%;" backtop="5mm" backbottom="10mm"  backright="5mm">
	<table>
		<tr><td colspan="7"><strong>Laporan Realisasi Penerimaan </strong></td></tr>
		<tr><td colspan="7"><strong>Dinas Perdagangan dan Perindustrian</strong></td></tr>
		<tr><td colspan="7"><strong>Kabupaten Jombang</strong></td></tr>
		<tr><td colspan="7"><strong>Tahun <?php echo $Tahun; ?></strong></td></tr>
		
	</table>
	
	
	<table style="width:100%; margin-top: 10px; margin-right: 10px; border-collapse: collapse;">
		<tr height="20" style="border: solid 1px;">
		  <th align="center" rowspan="2" style="padding-top :5px; border: solid 1px; width:5%;">No</th>
		  <th align="center" rowspan="2" style="padding-top :5px; border: solid 1px; width:15%;">Nama Pemilik</th>
		  <th align="center" rowspan="2" style="padding-top :5px; border: solid 1px; width:16%;">Alamat UTTP</th>
		  <th align="center" colspan="2" style="padding-top :5px; border: solid 1px; width:24%;">Jenis UTTP</th>
		  <th align="center" rowspan="2" style="padding-top :5px; border: solid 1px; width:14%;">Tahun Masa Tera</th>
		  <th align="center" colspan="2" style="padding-top :5px; border: solid 1px; width:26%;">Nilai Retribusi</th>
		</tr>
		<tr height="20" style="border: solid 1px;">
			<th align="center" style="padding-top :5px; border: solid 1px; width:14%;">ID UTTP</th>
			<th align="center" style="padding-top :5px; border: solid 1px; width:10%;">Nama UTTP</th>
			<th align="center" style="padding-top :5px; border: solid 1px; width:13%;">Lokasi</th>
			<th align="center" style="padding-top :5px; border: solid 1px; width:13%;">Kantor</th>
		</tr>		
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
		$q .=" GROUP by c.IDTimbangan order by a.NamaPerson ASC";

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
	 <?php $no = 1; ?>
        <?php foreach($data as $country => $companies):?>
		    <?php $countryRows = array_sum($rowspanCountry[$country]);?>
			 <tr  height="20" style="border: solid 1px;">
				<td <?=$countryRows>0?'rowspan="'.$countryRows.'"':''?> style="padding-top :5px; border: solid 1px; width:5%;"><?=$no;?></td>
				<td <?=$countryRows>0?'rowspan="'.$countryRows.'"':''?> style="padding-top :5px; border: solid 1px; width:15%;"><?=$country;?></td>
				<?php foreach($companies as $company => $items):?>
					 <?php $companyRows = sizeof($bind['lok'][$country][$company]);?>
						<td <?=$companyRows>0?'rowspan="'.$companyRows.'"':''?> style="padding-top :5px; border: solid 1px; width:16%">
							<?=$company;?>
						</td>
					 <?php foreach($items as $city => $yo):?>
                     
                        <td style="padding-top :5px; border: solid 1px; width:14%">
                            <?=$city;?>
                            
							
                        </td>
						
						 <?php foreach($yo as $nama => $nmtim):?>
                            <?php foreach($nmtim as $kelas => $ukur):?>
                            <?php foreach($ukur as $ukuran => $masa):?>
                             
                            <td style="padding-top :5px; border: solid 1px; width:10%"><?=$nama." ".$kelas." ".$ukuran?></td>
                            
							 <?php foreach($masa as $tahun => $lokasi):?>
							 
							 
							  <td style="padding-top :5px; border: solid 1px; width:14%">
                            <?php $masa =  isset($tahun) ? $tahun :'' ?>
                            <?=@TanggalIndo($masa)?>
                            
							
                        </td>
							 <?php foreach($lokasi as $tarif1 => $kantor):?>
							  <td style="padding-top :5px; border: solid 1px; width:13%">
                            <?=$tarif1;?>
                            
							
                        </td>
							 <?php foreach($kantor as $tarif2 => $end):?>
							  <td style="padding-top :5px; border: solid 1px; width:14%">
                            <?=$tarif2;?>
                            
							
                        </td>
				 <?php endforeach;?>
				<?php endforeach;?>
				<?php endforeach;?>
				<?php endforeach;?>
				<?php endforeach;?>
				<?php endforeach;?>
				<?php endforeach;?>
				<?php endforeach;?>
			 <?php echo '</tr>'; ?>
			
				
			 <?php $no++;?>
		<?php endforeach;?>
    
       
	</table>
</page>


<?php  
$filename="Lap_UTTPUser.pdf"; //ubah untuk menentukan nama file pdf yang dihasilkan nantinya  
//==========================================================================================================  
//Copy dan paste langsung script dibawah ini,untuk mengetahui lebih jelas tentang fungsinya silahkan baca-baca tutorial tentang HTML2PDF  
//==========================================================================================================  
$content = ob_get_clean();  
// $content = '<page style="font-family: freeserif">'.nl2br($content).'</page>';  
 require_once('../../html2pdf/html2pdf.class.php');
 try  
 {  
  $html2pdf = new HTML2PDF('L','F4','en', false, 'ISO-8859-15',array(18, 5, 15, 3));  
  $html2pdf->setDefaultFont('Arial');  
  $html2pdf->writeHTML($content, isset($_GET['vuehtml']));  
  $html2pdf->Output($filename);  
  
 }  
 catch(HTML2PDF_exception $e) { echo $e; }  
?>  