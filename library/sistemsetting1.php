<?php 

$lokasi   = mysqli_query($koneksi,"SELECT * FROM setting where id='10'");
$dinas    = mysqli_query($koneksi,"SELECT * FROM setting where id='1'");
$jalan    = mysqli_query($koneksi,"SELECT * FROM setting where id='4'");
$telpon   = mysqli_query($koneksi,"SELECT * FROM setting where id='2'");
$email    = mysqli_query($koneksi,"SELECT * FROM setting where id='3'");
$bidang1  = mysqli_query($koneksi,"SELECT * FROM setting where id='11'");
$bidang2  = mysqli_query($koneksi,"SELECT * FROM setting where id='12'");
$bidang3  = mysqli_query($koneksi,"SELECT * FROM setting where id='13'");
$bidang4  = mysqli_query($koneksi,"SELECT * FROM setting where id='14'");
$bidang5  = mysqli_query($koneksi,"SELECT * FROM setting where id='24'");
$bidang6  = mysqli_query($koneksi,"SELECT * FROM setting where id='25'");


$carilokasi		= mysqli_fetch_assoc($lokasi);
$caridinas		= mysqli_fetch_assoc($dinas);
$carijalan		= mysqli_fetch_assoc($jalan);
$caritelpon		= mysqli_fetch_assoc($telpon);
$cariemail		= mysqli_fetch_assoc($email);
$res1		= mysqli_fetch_assoc($bidang1);
$res2		= mysqli_fetch_assoc($bidang2);
$res3		= mysqli_fetch_assoc($bidang3);
$res4		= mysqli_fetch_assoc($bidang4);
$res5		= mysqli_fetch_assoc($bidang5);
$res6		= mysqli_fetch_assoc($bidang6);


?>
