<?php
session_start();
include 'xyz.php';
if (empty($_SESSION['user_logado'])) {
	unset($_SESSION['user_logado']);
	header("Location: " . BASE_URL . "login.php");
}

$dados_logado = mysqli_fetch_assoc(mysqli_query($con, "SELECT * FROM users WHERE username = '".$_SESSION['user_logado']."'"));
if ($dados_logado['type'] != "admin") {
	header("Location: " . BASE_URL . "login.php");
	exit();
}

if(isset($_GET['id']) && $dados_logado['type'] == "admin") {
	$adminid = $_GET['id'];
	$check = mysqli_num_rows(mysqli_query($con, "SELECT * FROM users WHERE username = '$adminid'"));
	if ($check >= 1) {
		$msg = mysqli_query($con, "DELETE FROM users WHERE username = '$adminid'");
		if ($msg) {
			$_SESSION['acao'] = '<div class="alert alert-success fade show" role="alert"> User: ' . $adminid . ' deleted.</div>';	
		}
	}
}

if(isset($_GET['resetall'])) {
	$resetAll = $_GET['resetall'];
	if ($dados_logado['type'] == "admin") {
	    $msg = mysqli_query($con, "UPDATE users SET UID=$resetAll");
    	if ($msg) {
    		$_SESSION['acao'] = '<div class="alert alert-success fade show" role="alert"> All users reseted.</div>';
    	}
    }
}

if (isset($_GET['resetuserid'])) {
	$resetUID = $_GET['resetuserid'];
	$msg = mysqli_query($con, "UPDATE users SET UID = NULL WHERE username = '$resetUID'");
	if($msg) {
		$_SESSION['acao']='<div class="alert alert-success fade show" role="alert"> User: '.$resetUID.' reseted.</div>';
	}
}

if(isset($_GET['adddays']) && $dados_logado['type'] == "admin")
{
	$daysCount = $_GET['adddays'];
	$msg = mysqli_query($con,"UPDATE users SET `expired` = DATE_ADD(`expired` , INTERVAL 1 DAY) WHERE `expired` > CURDATE();");
	if ($msg)
	{
		$_SESSION['acao']='<div class="alert alert-success fade show" role="alert"> Added '.$daysCount.' day to all users.</div>';
	}
}

?>
<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="utf-8">
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <title>See User | NewMod Extreme</title>

  <!-- Google Font: Source Sans Pro -->
  <link rel="stylesheet" href="https://fonts.googleapis.com/css?family=Source+Sans+Pro:300,400,400i,700&display=fallback">
  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.2.0/css/all.css"></link>
  <!-- Font Awesome -->
  <link rel="stylesheet" href="plugins/fontawesome-free/css/all.min.css">
  <!-- DataTables -->
  <link rel="stylesheet" href="plugins/datatables-bs4/css/dataTables.bootstrap4.min.css">
  <link rel="stylesheet" href="plugins/datatables-responsive/css/responsive.bootstrap4.min.css">
  <link rel="stylesheet" href="plugins/datatables-buttons/css/buttons.bootstrap4.min.css">
  <!-- Theme style -->
  <link rel="stylesheet" href="dist/css/newmod.min.css">
  <style>
        .disclaimer { display: none; }
</style>

<style>
           img[alt="www.000webhost.com"] {display:none;}
</style>

<script src="https://ajax.googleapis.com/ajax/libs/jquery/2.2.0/jquery.min.js" type="text/javascript"></script>
    <script type="text/javascript" language="javascript"> $(function () { $(this).bind("contextmenu", function (e) { e.preventDefault(); }); }); </script>
   <script>
document.onkeydown = function(e) {
        if (e.ctrlKey &&
            (e.keyCode === 85 )) {
            return false;
        }
};
</script>
</head>
<body class="hold-transition dark-mode sidebar-mini layout-fixed layout-navbar-fixed layout-footer-fixed">
<div class="wrapper">
<!-- Preloader -->
<div class="preloader flex-column justify-content-center align-items-center">
  <img class="animation__wobble" src="dist/img/newmodLogo.png" alt="newmodLogo" height="60" width="60">
</div>

<!-- Navbar -->
<nav class="main-header navbar navbar-expand navbar-dark">
  <!-- Left navbar links -->
  <ul class="navbar-nav">
    <li class="nav-item">
      <a class="nav-link" data-widget="pushmenu" href="#" role="button"><i class="fas fa-bars"></i></a>
    </li>
  </ul>

  <!-- Right navbar links -->
  <ul class="navbar-nav ml-auto">
    <!-- Navbar Search -->
    
    <li class="nav-item">
      <a class="nav-link" data-widget="fullscreen" href="#" role="button">
        <i class="fas fa-expand-arrows-alt"></i>
      </a>
    </li>
    <!-- Notifications Dropdown Menu -->
    <li class="nav-item dropdown user-menu">
      <a href="#" class="nav-link dropdown-toggle" data-toggle="dropdown">
        <img src="dist/img/user2-160x160.jpg" class="user-image img-circle elevation-2" alt="User Image">
      </a>
      <ul class="dropdown-menu dropdown-menu-lg dropdown-menu-right">
        <!-- User image -->
        <li class="user-header bg-primary">
          <img src="dist/img/user2-160x160.jpg" class="img-circle elevation-2" alt="User Image">

          <p>
            NewMod
            <small>Since 26 February 2022</small>
          </p>
        </li>
        <!-- Menu Body -->
        <li class="user-body">
          <div class="row">
            <div class="col-4 text-center">
              <a href="#">Users</a>
            </div>
            <div class="col-4 text-center">
              <a href="#">Sales</a>
            </div>
            <div class="col-4 text-center">
              <a href="#">Credits</a>
            </div>
          </div>
          <!-- /.row -->
        </li>
        <!-- Menu Footer-->
        <li class="user-footer">
          <a href="profile.php" class="btn btn-default btn-flat">Profile</a>
          <a href="logout.php" class="btn btn-default btn-flat float-right">Logout</a>
        </li>
      </ul>
  </ul>
</nav>
<!-- /.navbar -->

<!-- Main Sidebar Container -->
<aside class="main-sidebar sidebar-dark-primary elevation-4">
  <!-- Brand Logo -->
  <a href="index.php" class="brand-link">
    <img src="dist/img/newmodLogo.png" alt="newmod Logo" class="brand-image img-circle elevation-3" style="opacity: .8">
    <span class="brand-text font-weight-light">New Mod Extreme</span>
  </a>

  <!-- Sidebar -->
  <div class="sidebar">

    <!-- SidebarSearch Form -->
    <div class="form-inline">
      <div class="input-group" data-widget="sidebar-search">
        <input class="form-control form-control-sidebar" type="search" placeholder="Search" aria-label="Search">
        <div class="input-group-append">
          <button class="btn btn-sidebar">
            <i class="fas fa-search fa-fw"></i>
          </button>
        </div>
      </div>
    </div>

    <!-- Sidebar Menu -->
    <nav class="mt-2">
      <ul class="nav nav-pills nav-sidebar flex-column" data-widget="treeview" role="menu" data-accordion="false">
        <!-- Add icons to the links using the .nav-icon class
             with font-awesome or any other icon font library -->
        <li class="nav-item menu-open">
          <a href="./index.php" class="nav-link active">
            <i class="nav-icon fas fa-tachometer-alt"></i>
            <p>
              Dashboard
            </p>
          </a>
        </li>
        <li class="nav-header">MANAGEMENT</li>
        <li class="nav-item">
          <a href="#" class="nav-link">
            <i class="nav-icon fas fa-users text-danger"></i>
            <p>
              Users Manage
              <i class="right fas fa-angle-left"></i>
            </p>
          </a>
          <ul class="nav nav-treeview">
            <li class="nav-item">
              <a href="see-users.php" class="nav-link">
                <i class="far fa-eye nav-icon text-warning"></i>
                <p>See Users</p>
              </a>
            </li>
            <li class="nav-item">
              <a href="register-user.php" class="nav-link">
                <i class="fas fa-user-plus nav-icon text-info"></i>
                <p>Register Users</p>
              </a>
            </li>
            <li class="nav-item">
              <a href="add-days.php" class="nav-link">
                <i class="fas fa-calendar-plus nav-icon text-info"></i>
                <p>Add Days Users</p>
              </a>
            </li>
            <li class="nav-item">
              <a href="?resetall=null" class="nav-link">
                <i class="fas fa-arrows-spin fa-spin nav-icon text-info"></i>
                <p>Reset All Users</p>
              </a>
            </li>
          </ul>
        </li>
        <li class="nav-item">
          <a href="#" class="nav-link">
            <i class="nav-icon fas fa-file-code text-success"></i>
            <p>
              Apk Manage
              <i class="right fas fa-angle-left"></i>
            </p>
          </a>
          <ul class="nav nav-treeview">
            <li class="nav-item">
              <a href="upload-apk.php" class="nav-link">
                <i class="fas fa-upload	text-success nav-icon"></i>
                <p>Upload Apk</p>
              </a>
            </li>
            <li class="nav-item">
              <a href="#" class="nav-link">
                <i class="fas fa-download text-info nav-icon"></i>
                <p>Manage Apk</p>
              </a>
            </li>
          </ul>
        </li>
        <li class="nav-item">
          <a href="#" class="nav-link">
            <i class="nav-icon fas fa-sun fa-spin text-info"></i>
            <p>
              Server Settings
              <i class="right fas fa-angle-left"></i>
            </p>
          </a>
          <ul class="nav nav-treeview">
            <li class="nav-item">
              <a href="#" class="nav-link">
                <i class="fas fa-circle-check nav-icon text-success"></i>
                <p>Server On</p>
              </a>
            </li>
            <li class="nav-item">
              <a href="#" class="nav-link">
                <i class="fas fa-sliders nav-icon text-info"></i>
                <p>Settings</p>
              </a>
            </li>
          </ul>
        </li>
        
        <li class="nav-header">LABELS</li>
        <li class="nav-item">
          <a href="logout.php" class="nav-link">
            <i class="nav-icon fa fa-sign-out text-danger"></i>
            <p class="text">Logout</p>
          </a>
        </li>
      </ul>
    </nav>
    <!-- /.sidebar-menu -->
  </div>
  <!-- /.sidebar -->
</aside>

  <!-- Content Wrapper. Contains page content -->
  <div class="content-wrapper">
    <!-- Content Header (Page header) -->
    <section class="content-header">
      <div class="container-fluid">
        <div class="row mb-2">
          <div class="col-sm-6">
          </div>
          <div class="col-sm-6">
          </div>
        </div>
      </div><!-- /.container-fluid -->
    </section>

    <!-- Main content -->
    <section class="content">
      <div class="container-fluid">
        <div class="row">
          <div class="col-12">
            <div class="card">
              <div class="card-header">
                <h3 class="card-title">Users Management</h3>
              </div>
              <!-- /.card-header -->
              <?php if(!empty($_SESSION['acao'])){ echo $_SESSION['acao'].'<hr>'; unset($_SESSION['acao']); }?>
              <div class="card-body">
                <table id="example1" class="table table-bordered table-striped">
                  <thead>
                  <tr>
                    <th>Id</th>
                    <th>Username</th>
                    <th>Password</th>
                    <th>Device</th>
                    <th>Expire Date</th>
                    <th>Provider</th>
                    <?php if ($dados_logado['type'] == "master" || $dados_logado['type'] == "admin") { ?>
                    <th>Type</th>
                    <th>Credit</th>
                    <?php } ?>
                  </tr>
                  </thead>
                  <tbody>
                  <?php 
									
									//$admins=$row['username'];
									$credits = $dados_logado['credits'];
									$query_users = mysqli_query($con,"SELECT * FROM users WHERE `type` != 'Admin' ORDER BY id ASC");
									/*
									$cur_user = $_SESSION['user_logado'];
									$query_users = mysqli_query($con,"SELECT * FROM tokens WHERE 'Vendedor' = $cur_user");
									print_r($query_users);
									*/
									while ($row = mysqli_fetch_assoc($query_users)) {
										if (($dados_logado['type'] == "reseller" && $row['type'] == "member" && strtolower($row['reseller']) == strtolower($_SESSION['user_logado'])) || $dados_logado['type'] == "admin") {
									?>
                  <tr>
                    <td><?php echo $row['id'];?></td>
                    <td><?php echo $row['username'];?></td>
                    <td><?php echo $row['password'];?></td>
                    <td><?php 
										if($row['UID'] == NULL){
											echo "0/1"; 
										}else{
											echo "1/1";
										}
										?>
                    </td>
                    <td><?php echo $row['expired'];?></td>
                    <td><?php echo $row['reseller'];?></td>
                    <?php if ($dados_logado['type'] == "master" || $dados_logado['type'] == "admin") { ?>
                    <td><?php echo $row['type'];?></td>
                    <td><?php echo $row['credits']; ?></td>
                    <?php } ?>
                    <?php
										}
									}
									if ($dados_logado['type'] == "reseller") {
									?>
                  </tr>
                  <?php } ?>
                </table>
              </div>
              <!-- /.card-body -->
            </div>
            <!-- /.card -->
          </div>
          <!-- /.col -->
        </div>
        <!-- /.row -->
      </div>
      <!-- /.container-fluid -->
    </section>
    <!-- /.content -->
  </div>
  <!-- /.content-wrapper -->
  <footer class="main-footer">
    <div class="float-right d-none d-sm-block">
    </div>
    <strong>Copyright &copy; 2022 <a href="https://newmodextreme.ml/">NewMod Extreme</a>.</strong>
  </footer>

  <!-- Control Sidebar -->
  <aside class="control-sidebar control-sidebar-dark">
    <!-- Control sidebar content goes here -->
  </aside>
  <!-- /.control-sidebar -->
</div>
<!-- ./wrapper -->

<!-- jQuery -->
<script src="plugins/jquery/jquery.min.js"></script>
<!-- Bootstrap 4 -->
<script src="plugins/bootstrap/js/bootstrap.bundle.min.js"></script>
<!-- DataTables  & Plugins -->
<script src="plugins/datatables/jquery.dataTables.min.js"></script>
<script src="plugins/datatables-bs4/js/dataTables.bootstrap4.min.js"></script>
<script src="plugins/datatables-responsive/js/dataTables.responsive.min.js"></script>
<script src="plugins/datatables-responsive/js/responsive.bootstrap4.min.js"></script>
<script src="plugins/datatables-buttons/js/dataTables.buttons.min.js"></script>
<script src="plugins/datatables-buttons/js/buttons.bootstrap4.min.js"></script>
<script src="plugins/jszip/jszip.min.js"></script>
<script src="plugins/pdfmake/pdfmake.min.js"></script>
<script src="plugins/pdfmake/vfs_fonts.js"></script>
<script src="plugins/datatables-buttons/js/buttons.html5.min.js"></script>
<script src="plugins/datatables-buttons/js/buttons.print.min.js"></script>
<script src="plugins/datatables-buttons/js/buttons.colVis.min.js"></script>
<!-- newmod App -->
<script src="dist/js/newmod.min.js"></script>
<!-- newmod for demo purposes -->
<script src="dist/js/demo.js"></script>
<!-- Page specific script -->
<script>
  $(function () {
    $("#example1").DataTable({
      "responsive": true, "lengthChange": false, "autoWidth": false,
      "buttons": ["print", "colvis"]
    }).buttons().container().appendTo('#example1_wrapper .col-md-6:eq(0)');
    $('#example2').DataTable({
      "paging": true,
      "lengthChange": false,
      "searching": false,
      "ordering": true,
      "info": true,
      "autoWidth": false,
      "responsive": true,
    });
  });
</script>
</body>
</html>
