<?php
 include '../../library/config.php';
 include '../../library/tgl-indo.php';
 
 $NoTransaksi	=@$_GET['NoTransaksi'];
 $IDPerson		=@$_GET['IDPerson'];
 $login_id		=@$_GET['login_id'];
 $NoUrutTrans	=@$_GET['NoUrutTrans'];
 $Aksi			=@$_GET['Aksi'];
 
	if ($Aksi == 'Edit'){
		$sql = mysqli_query($koneksi, ("Select IDTimbangan,Keterangan from trtimbanganitem where NoTransaksi='$NoTransaksi' and NoUrutTrans='$NoUrutTrans'"));
		$row = mysqli_fetch_array($sql);
	}

?>

<div class="modal-dialog modal-md">
    <div class="modal-content">
    	<div class="modal-header">
		  <h4 id="exampleModalLabel" class="modal-title"><?php echo @$Aksi; ?> Item Timbangan</h4>
		  <button type="button" data-dismiss="modal" aria-label="Close" class="close"><span aria-hidden="true">×</span></button>
		</div>
        <div class="modal-body">
			<form action="TrTerimaTimbangan.php" method="post">
				<div class="row">
					<div class="col-lg-12">
						<input name="NoTransaksi" type="hidden" value="<?php echo $NoTransaksi;?>" />
						<input name="IDPerson" type="hidden" value="<?php echo $IDPerson;?>" />
						<input name="UserName" type="hidden" value="<?php echo $login_id;?>" />
						<input name="Aksi" type="hidden" value="<?php echo $Aksi;?>" />
						<input name="NoUrutTrans" type="hidden" value="<?php echo $NoUrutTrans;?>" />
						<div class="form-group" lign="left">
							<label>Pilih Timbangan</label>
							<?php $jsArray = "var arrData = new Array();\n"; ?>
							<select id="IDLapak" name="IDTimbangan"  onchange="changeValue(this.value)" class="form-control">
							  <?php
								echo "<option value=''>--- Pilih Timbangan ---</option>";
								$menu = mysqli_query($koneksi,"SELECT c.NamaTimbangan,c.IDTimbangan, MAX(a.NoTransaksi), MAX(b.TglTera) as Tanggal,c.KodeLokasi FROM timbanganperson c left join trtimbanganitem a on a.IDTimbangan=c.IDTimbangan left join tractiontimbangan b ON (a.NoTransaksi,a.IDPerson)=(b.NoTransaksi,b.IDPerson) where c.IDPerson='$IDPerson' and c.StatusUTTP='Aktif' GROUP by c.IDTimbangan DESC");
								while($kode = mysqli_fetch_array($menu)){
									if($kode['IDTimbangan'] === $row['IDTimbangan']){
										echo "<option value=\"".$kode['IDTimbangan']."\" selected='selected'>".$kode['NamaTimbangan']."</option>\n";
									}else{
										echo "<option value=\"".$kode['IDTimbangan']."\" >".$kode['NamaTimbangan']."</option>\n";
									}
									$jsArray .= "arrData['".$kode['IDTimbangan']."'] = {
									  IDTimbangan:'".addslashes($kode['IDTimbangan'])."',
									  KodeLokasi:'".addslashes($kode['KodeLokasi'])."',
									  NamaPasar:'".addslashes(TanggalIndo($kode['Tanggal']))."',
									};\n";
								}
							  ?>
							</select>
						</div>
						<p id="demo"></p>
						<input name="KodeLokasi" id="KodeLokasi" type="hidden" value="<?php echo @$row['KodeLokasi'];?>" />
						<div class="form-group" align="left">
							<label>Keterangan</label>
							<textarea type="text" name="Keterangan" class="form-control" rows="4" placeholder="Keterangan"><?php echo @$row['Keterangan']; ?></textarea>
						</div>
						<div class="form-group" align="right">
							<?php if ($Aksi == 'Edit'){ ?>
								<input type="submit"  class="btn btn-primary" name="EditItem" value="Simpan"><br>
							<?php }else{ ?>
								<input type="submit"  class="btn btn-primary" name="SimpanItem" value="Simpan"><br>
							<?php } ?>
						</div>
					</div>	
				</div>
			</form>
        </div>
	</div>
</div>
	
	
<script type="text/javascript">

	<?php echo @$jsArray; ?>
	function changeValue(id){
		var x = arrData[id].NamaPasar;
		var xy = arrData[id].KodeLokasi;
		document.getElementById("demo").innerHTML = "Tanggal Terakhir Tera: " + x;
		document.getElementById("KodeLokasi").value =  xy;
	};
</script>	