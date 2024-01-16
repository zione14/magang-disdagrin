<?php
include '../pasar/akses.php';
$Page = 'Histori';

?>
<!DOCTYPE html>
<html>
  <head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <?php include 'title.php';?>
    <!-- Datepcker -->
    <link rel="stylesheet" href="../library/Datepicker/dist/css/default/zebra_datepicker.min.css" type="text/css">
  </head>
  <body>
    <div class="page">
      <!-- Main Navbar-->
      <?php 
	  include 'header.php'; ?>
      <div class="page-content d-flex align-items-stretch"> 
        <!-- Side Navbar -->
        <?php include 'menu.php';?>
        <div class="content-inner">
          <!-- Page Header-->
          <header class="page-header">
            <div class="container-fluid">
              <h2 class="no-margin-bottom">Histori Pengeluaran Karcis Retribusi</h2>
            </div>
          </header>
          <!-- Dashboard Counts Section-->
         <section class="tables"> 
            <div class="container-fluid">
                <div class="col-lg-12">
                  <!-- <ul class="nav nav-pills">
                    <li <?php if(@$id==null){echo 'class="active"';} ?>>
                      <a href="#home-pills" data-toggle="tab"><span class="btn btn-primary">Data Histori Karcis</span></a>&nbsp;
                    </li>
                    <li><a href="Request_tambah.php"><span class="btn btn-primary">Entry Histori</span></a>&nbsp;</li>
                  </ul><br/> -->
                  <div class="card">
                    <div class="tab-content">
                      <div class="tab-pane fade <?php if(@$_GET['id']==null){ echo 'in active show'; }?>" id="home-pills">
                        <div class="card-header d-flex align-items-center">
                          <h3 class="h4">Histori Pengeluaran Karcis</h3>
                        </div>
                        <div class="card-body">               
                          <div class="col-lg-5 offset-lg-7">
                            <form method="post" action="">
                              <div class="form-group input-group">            
                                <input type="text" name="keyword" id="time1" class="form-control" placeholder="Tanggal Pengeluaran..." value="<?php echo @$_REQUEST['keyword']; ?>"> 
                                <span class="input-group-btn">
                                  <button class="btn btn-success" type="submit">Cari</button>
                                </span>
                              </div>
                            </form>
                          </div>
                          <div class="table-responsive">  
                          <table class="table table-striped">
                            <thead>
                            <tr>
                              <th>No</th>
                              <th>Tanggal</th>
                              <th>Keterangan</th>
                              <th>Jenis Karcis</th>
                              <th>Keluar</th>
                              <th>Nominal</th>
                            </tr>
                            </thead>
                            <?php
                              include '../library/pagination1.php';
                              // mengatur variabel reload dan sql
                              $reload = "Histori.php?pagination=true";
                              $sql =  "SELECT m.NamaKB, p.NamaPasar, t.TanggalTransaksi, t.Keterangan, i.TotalNominal, i.JumlahKreditKB 
                              FROM traruskbitem  i
                              JOIN traruskb t ON i.NoTransArusKB = t.NoTransArusKB
                              JOIN mstkertasberharga m ON i.KodeKB = m.KodeKB
                              JOIN mstpasar p ON t.KodePasar=p.KodePasar
                              WHERE t.TipeTransaksi='PENGELUARAN' AND t.KodePasar = '$KodePasar'";
                              
                              if(@$_REQUEST['keyword']!=null){
                                $sql .= " AND t.TanggalTransaksi LIKE '%".$_REQUEST['keyword']."%'  ";
                              }
                              
                              $sql .=" ORDER BY t.TanggalTransaksi ASC";
                              $result = mysqli_query($koneksi,$sql);
                              
                              //pagination config start
                              $rpp = 20; // jumlah record per halaman
                              $page = intval(@$_GET["page"]);
                              if($page<=0) $page = 1;  
                              @$tcount = mysqli_num_rows($result);
                              $tpages = ($tcount) ? ceil($tcount/$rpp) : 1; // total pages, last page number
                              $count = 0;
                              $i = ($page-1)*$rpp;
                              $no_urut = ($page-1)*$rpp;
                              //pagination config end       
                            ?>
                            <tbody>
                              <?php
                              if($tcount == null OR $tcount === 0){
                                echo '<tr class="odd gradeX"><td colspan="9" align="center"><strong>Tidak ada data</strong></td></tr>';
                              } else {
                                while(($count<$rpp) && ($i<$tcount)) {
                                  mysqli_data_seek($result,$i);
                                  @$data = mysqli_fetch_array($result);
                                
                              ?>
                              <tr class="odd gradeX">
                                <td width="50px">
                                  <?php echo ++$no_urut;?> 
                                </td>
                                <td>
                                  <?php echo @TanggalIndo($data['TanggalTransaksi']); ?>
                                </td>
                                <td align="left">
                                  <?php echo $data ['Keterangan']; ?>
                                </td>
                                <td align="left">
                                  <?php echo $data['NamaKB']?>
                                </td>
                                <td align="left">
                                  <?php echo $data['JumlahKreditKB']?>
                                </td>
                                <td align="left">
                                  <?php echo number_format($data['TotalNominal'])?>
                                </td>
                               
                              </tr>
                              <?php
                                $i++; 
                                $count++;
                                } 
                              }
                              
                              ?>
                            </tbody>
                          </table>
                          <div><?php echo paginate_one($reload, $page, $tpages); ?></div>
                          </div>
                        </div>
                      </div>
                    </div>
                  </div>
                </div>
            </div>
          </section>
        </div>
      </div>
    </div>

    <div id="dialog-hapus" class="modal fade" role="dialog">
      <div class="modal-dialog">
          <!-- konten modal-->
          <div class="modal-content">
            <form action="Request_aksi.php" method="post">
                <!-- heading modal -->
                <div class="modal-header">
                    <h4 class="modal-title">Konfirmasi Hapus</h4>
                </div>
                <div class="modal-body" style="padding:27px;">
                    <input type="hidden" name="view" value="1">
                    <input type="hidden" name="id" id="id" value="1">
                    <input type="hidden" name="nm" id="nm" value="1">
                    <input type="hidden" name="aksi" id="aksi" value="1">
                  <label for="">Apakah anda yakin akan menghapus data ini ?</label>
                </div>
                <div class="modal-footer">
                  <button type="submit" id="btn-hapus-modal" class="btn btn-sm btn-danger">Hapus</button> 
                  <a href="#" data-dismiss="modal" class="btn btn-sm btn-secondary">Batal</a>   
                </div>
            </form>
          </div>
      </div>
    </div>
	
    <!-- JavaScript files-->
    <script src="../komponen/vendor/jquery/jquery.min.js"></script>
    <script src="../komponen/vendor/popper.js/umd/popper.min.js"> </script>
    <script src="../komponen/vendor/bootstrap/js/bootstrap.min.js"></script>
    <script src="../komponen/vendor/jquery.cookie/jquery.cookie.js"> </script>
    <script src="../komponen/vendor/chart.js/Chart.min.js"></script>
    <script src="../komponen/vendor/jquery-validation/jquery.validate.min.js"></script>
    <script src="../komponen/js/charts-home.js"></script>
    <!-- DatePicker -->
    <script type="text/javascript" src="../library/Datepicker/dist/zebra_datepicker.min.js"></script>
    <!-- Main File-->
    <script src="../komponen/js/front.js"></script>
    <script type="text/javascript">
      $(document).ready(function() {
        $('#time1').Zebra_DatePicker({format: 'Y-m-d'});
      });

      function konfirmasihapus(id, nm, aksi){
        document.getElementById('id').value = id;
        document.getElementById('nm').value = nm;
        document.getElementById('aksi').value = aksi;
        $("#dialog-hapus").modal("show");
      }
    </script>
	
  
  </body>
</html>