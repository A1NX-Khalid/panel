<?php
session_start();
include 'xyz.php';
if (empty($_SESSION['user_logado'])) {
	unset($_SESSION['user_logado']);
	header("Location: " . BASE_URL . "login.php");
}

$dados_logado = mysqli_fetch_assoc(mysqli_query($con, "SELECT * FROM users WHERE username = '".$_SESSION['user_logado']."'"));
if ($dados_logado['type'] != "cliente" && $dados_logado['type'] != "admin" && $dados_logado['type'] != "reseller") {
	header("Location: " . BASE_URL . "login.php");
	exit();
}

// for updating user info    
if (isset($_POST['Submit'])) {
	
	$oldpass = isset($_POST['oldpass']) ? $_POST['oldpass'] : '';
	$newpass = isset($_POST['newpass']) ? $_POST['newpass'] : '';
	$confirmpass = isset($_POST['confirmpass']) ? $_POST['confirmpass'] : '';
	
	$usuario = $_SESSION['user_logado'];
	
	$oldchecked = mysqli_real_escape_string($con, $oldpass);
	$newchecked = mysqli_real_escape_string($con, $newpass);
	$confirmchecked = mysqli_real_escape_string($con, $confirmpass);
	
	$check = mysqli_num_rows(mysqli_query($con, "SELECT * FROM users WHERE username = '$usuario' AND password = '$oldchecked'"));
	if ($check < 1) {
		$_SESSION['acao'] = '<div class="alert alert-danger fade show" role="alert">Old Password Incorrect !</div>';
	} else {
		if ($oldchecked == $newchecked) {
			$_SESSION['acao'] = '<div class="alert alert-warning fade show" role="alert">The new password cannot be the same as the old one.</div>';
		} else if ($newchecked != $confirmchecked) {
			$_SESSION['acao'] = '<div class="alert alert-danger fade show" role="alert">Confirm your password correctly.</div>';
		} else {
			$query = mysqli_query($con, "UPDATE users SET password = '$newchecked' WHERE username='$usuario'");
			if ($query) {
			    $_SESSION['acao'] = '<div class="alert alert-success fade show" role="alert">Password changed successfully!</div>';
			} else {
			    $_SESSION['acao'] = '<div class="alert alert-warning fade show" role="alert">Password changed with failed!</div>';
			}
		}
	}
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="utf-8">
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <title>Profile | NewMod Extreme</title>

  <!-- Google Font: Source Sans Pro -->
  <link rel="stylesheet" href="https://fonts.googleapis.com/css?family=Source+Sans+Pro:300,400,400i,700&display=fallback">
  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.2.0/css/all.css"></link>
  <!-- Font Awesome -->
  <link rel="stylesheet" href="plugins/fontawesome-free/css/all.min.css">
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
              <a href="register-user.php" class="nav-link">
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
          <a href="#" class="nav-link">
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
  <div class="content-header">
    <div class="container-fluid">
      <div class="row mb-2">
        <div class="col-sm-6">
        </div><!-- /.col -->
        <div class="col-sm-6">
          <ol class="breadcrumb float-sm-right">
          </ol>
        </div><!-- /.col -->
      </div><!-- /.row -->
    </div><!-- /.container-fluid -->
  </div>
  <!-- /.content-header -->

    <!-- Main content -->
    <section class="content">
      <div class="container-fluid">
        <div class="row">
          <div class="col-md-3">

            <!-- Profile Image -->
            <div class="card card-primary card-outline">
              <div class="card-body box-profile">
                <div class="text-center">
                  <img class="profile-user-img img-fluid img-circle"
                       src="dist/img/user4-128x128.jpg"
                       alt="User profile picture">
                </div>

                <h3 class="profile-username text-center"><?php echo $_SESSION['user_logado']; ?></h3>

                <p class="text-muted text-center">Powered By NewMod</p>

                <ul class="list-group list-group-unbordered mb-3">
                  <li class="list-group-item">
                    <b>Total Users</b> <a class="float-right">Coming Soon</a>
                  </li>
                  <li class="list-group-item">
                    <b>Total Sellers</b> <a class="float-right">Coming Soon</a>
                  </li>
                  <li class="list-group-item">
                    <b>Total Downloads</b> <a class="float-right">Coming Soon</a>
                  </li>
              </div>
              <!-- /.card-body -->
            </div>
            <!-- /.card -->
          </div>
          <!-- /.col -->
          <div class="col-md-9">
            <div class="card">
              <div class="card-header p-2">
                <span> Your Profile</span>
              </div><!-- /.card-header -->
              <div class="card-body">
              <?php if(!empty($_SESSION['acao'])) { echo $_SESSION['acao'].'<hr>'; unset($_SESSION['acao']); } ?>
                    <form class="form-horizontal" action="" method="POST">
                      <div class="form-group row">
                        <label for="inputEmail" class="col-sm-2 col-form-label">Username</label>
                        <div class="col-sm-10">
                          <input type="email" class="form-control" id="inputEmail" readonly value="<?php echo $_SESSION['user_logado']; ?>" required>
                        </div>
                      </div>
                      <div class="form-group row">
                        <label for="inputName2" class="col-sm-2 col-form-label">Old Password</label>
                        <div class="col-sm-10">
                          <input type="text" name="oldpass" required class="form-control" id="inputName2" placeholder="Old Password">
                        </div>
                      </div>
                      <div class="form-group row">
                        <label for="inputName2" class="col-sm-2 col-form-label">New Password</label>
                        <div class="col-sm-10">
                          <input type="text" name="newpass" class="form-control" id="inputName2" placeholder="New Password" required>
                        </div>
                      </div>
                      <div class="form-group row">
                        <label for="inputSkills" class="col-sm-2 col-form-label">Conform Password</label>
                        <div class="col-sm-10">
                          <input type="text" name="confirmpass" required class="form-control" id="inputSkills" placeholder="Conform Password">
                        </div>
                      </div>
                      <div class="form-group row">
                        <div class="offset-sm-2 col-sm-10">
                          <div class="checkbox">
                            <label>
                              <input type="checkbox"> I agree to the <a href="#">terms and conditions</a>
                            </label>
                          </div>
                        </div>
                      </div>
                      <div class="form-group row">
                        <div class="offset-sm-2 col-sm-10">
                          <button type="submit" name="Submit" class="btn btn-danger">Submit</button>
                        </div>
                      </div>
                    </form>
                  </div>
                  <!-- /.tab-pane -->
                </div>
                <!-- /.tab-content -->
              </div><!-- /.card-body -->
            </div>
            <!-- /.card -->
          </div>
          <!-- /.col -->
        </div>
        <!-- /.row -->
      </div><!-- /.container-fluid -->
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
<!-- newmod App -->
<script src="dist/js/newmod.min.js"></script>
<!-- newmod for demo purposes -->
<script src="dist/js/demo.js"></script>
</body>
</html>
