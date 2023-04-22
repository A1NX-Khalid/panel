<?php
session_start();
include 'xyz.php';
if (empty($_SESSION['user_logado'])) {
	unset($_SESSION['user_logado']);
	header("Location: " . BASE_URL . "login.php");
}

$dados_logado = mysqli_fetch_assoc(mysqli_query($con, "SELECT * FROM users WHERE username = '".$_SESSION['user_logado']."'"));
if ($dados_logado['type'] != "reseller" && $dados_logado['type'] != "admin" && $dados_logado['type'] != "reseller") {
	header("Location: ".BASE_URL . "login.php");
	exit();
}

if ($dados_logado['type'] != "reseller" && $dados_logado['type'] != "admin" && $dados_logado['credits'] <= 0) {
	$_SESSION['acao'] = '<div class="alert alert-danger fade show" role="alert">Seus créditos acabaram. Adquira com Error404</div>';
	header("Location: " . BASE_URL . "index.php");
	exit();
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

if($dados_logado['credits'] < 1) {
	$_SESSION['acao'] = '<div class="alert alert-danger fade show" role="alert">Seus créditos acabaram. Adquira com Error404</div>';
	header("Location: " . BASE_URL . "index.php");
	exit();
}				
// for updating user info    
if (isset($_POST['Submit'])) {
	$date = date("Y/m/d h:i");
	$user = isset($_POST['usuario']) ? $_POST['usuario'] : '';
	$pass = isset($_POST['senha']) ? $_POST['senha'] : '';
	$devices = isset($_POST['devices']) ? $_POST['devices'] : '';
	$endate = isset($_POST['endate']) ? $_POST['endate'] : '';
	$cargo = isset($_POST['cargo']) ? $_POST['cargo'] : '';
	$vendedor = $_SESSION['user_logado'];
	
		$credit = isset($_POST['credit']) ? $_POST['credit'] : '';
		$creditchecked = mysqli_real_escape_string($con, $credit);
	
	$userchecked = mysqli_real_escape_string($con, $user);
	$passchecked = mysqli_real_escape_string($con, $pass);
	$deviceschecked = mysqli_real_escape_string($con, $devices);
	$endatechecked = mysqli_real_escape_string($con, $endate);
	$cargochecked = mysqli_real_escape_string($con, $cargo);
	$vendedorchecked = mysqli_real_escape_string($con, $vendedor);

	$check = mysqli_num_rows(mysqli_query($con, "SELECT * FROM users WHERE username = '$userchecked'"));
	if ($check > 0) {
		$_SESSION['acao'] = '<div class="alert alert-danger fade show" role="alert">User in use, try another.</div>';
	} else {
		if($deviceschecked > 1) {
			 $_SESSION['acao']= '<div class="alert alert-danger fade show" role="alert">Maximum allowed 1 device!</div>';
		} else if ($deviceschecked < 1) {
			 $_SESSION['acao']= '<div class="alert alert-danger fade show" role="alert">Minimum allowed 1 device!</div>';
		} else {
			if ($deviceschecked == 1) {
				if ($dados_logado['type'] == "reseller") {
					$query = mysqli_query($con, "INSERT INTO `users` (`username`,`password`,`registered`,`expired`,`UID`,`reseller`,`type`,`credits`) VALUES ('$userchecked','$passchecked','$date','$endatechecked', NULL, '$vendedorchecked','cliente','0')");
					$credits = $dados_logado['credits'] - 1;
					if ($query) {
						$msg = mysqli_query($con, "UPDATE users SET credits = $credits WHERE username = '" . $_SESSION['user_logado'] . "'");
						if ($msg) {
							$_SESSION['acao'] = '<div class="alert alert-success fade show" role="alert">' . $userchecked . ' Added. validity: ' . $endatechecked . '</div>';
						}
					}
					//echo "INSERT INTO `users` (`username`,`password`,`registered`,`expired`,`reseller`,`type`,`credits`) VALUES ('$userchecked','$passchecked','$date','$endatechecked','$vendedorchecked','cliente','0')";
					header("Location: ".BASE_URL . "index_reseller.php");
					exit();
				} else if ($dados_logado['type'] == "admin") {
				    $query = mysqli_query($con, "INSERT INTO `users` (`username`,`password`,`registered`,`expired`,`UID`,`reseller`,`type`, `credits`) VALUES ('$userchecked','$passchecked','$date','$endatechecked', NULL, '$vendedorchecked','$cargochecked', '$creditchecked')");
					$credits = $dados_logado['credits'] - 0;
					if ($query) {
						$msg = mysqli_query($con, "UPDATE users SET credits = $credits WHERE username = '" . $_SESSION['user_logado'] . "'");
						if ($msg) {
							$_SESSION['acao'] = '<div class="alert alert-success fade show" role="alert">' . $userchecked . ' Added. validity: ' . $endatechecked . '</div>';
						}
					}
					header("Location: " . BASE_URL . "see-users.php");
					exit();
				}  else {$_SESSION['acao'] = '<div class="alert alert-danger fade show" role="alert">You are not admin or reseller!</div>';
					header("Location: " . BASE_URL . "see-users.php");
					exit();
				}
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
  <title>Register User | NewMod Extreme</title>

  <!-- Google Font: Source Sans Pro -->
  <link rel="stylesheet" href="https://fonts.googleapis.com/css?family=Source+Sans+Pro:300,400,400i,700&display=fallback">
  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.2.0/css/all.css"></link>
  <!-- Font Awesome -->
  <link rel="stylesheet" href="plugins/fontawesome-free/css/all.min.css">
  <!-- Theme style -->
  <link rel="stylesheet" href="dist/css/newmod.min.css">
   <!-- Select2 -->
   <link rel="stylesheet" href="plugins/select2/css/select2.min.css">
   <link rel="stylesheet" href="plugins/select2-bootstrap4-theme/select2-bootstrap4.min.css">
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
          <!-- left column -->
          <div class="col-md-12">
            <!-- jquery validation -->
            <div class="card card-primary">
              <div class="card-header">
                <h3 class="card-title">Register User</h3>
              </div>
              <!-- /.card-header -->
              <!-- form start -->
              <?php if(!empty($_SESSION['acao'])){ echo $_SESSION['acao'].'<hr>'; unset($_SESSION['acao']); }  ?>
              <form action="" method="POST">
                <div class="card-body">
                  <div class="form-group">
                    <label>Username</label>
                    <input type="text" name="usuario" class="form-control" id="exampleInputEmail1" placeholder="Enter Username" required>
                  </div>
                  <div class="form-group">
                    <label for="exampleInputPassword1">Password</label>
                    <input type="password" name="senha" class="form-control" id="exampleInputPassword1" placeholder="Enter Password" required>
                  </div>
                  <div class="form-group">
                    <label>Devices</label>
                    <input type="number" max="1" min="1" name="devices" class="form-control" id="exampleInputPassword1" value="1">
                  </div>
                  <div class="form-group">
                    <label>Start Date</label>
  
                    <div class="input-group">
                      <div class="input-group-prepend">
                        <span class="input-group-text"><i class="far fa-calendar-alt"></i></span>
                      </div>
                      <input type="text" class="form-control" name="startdate" readonly value="<?php echo date("Y-m-d"); ?>" data-inputmask-alias="datetime" data-inputmask-inputformat="dd/mm/yyyy" data-mask>
                    </div>
                    <!-- /.input group -->
                    <div class="position-relative form-group">
        								<label for="exampleEmail" class="">Select Days</label>
        								<select class="form-control" name="endate">
    								    <?php if ($dados_logado['type'] == "reseller") { ?>
    								        <option value="<?php echo Date('Y-m-d h:i', strtotime('+7 days')); 
    								        ?>" selected>7 days</option>
    								        <option value="<?php echo Date('Y-m-d h:i', strtotime('+30 days')); 
    								        ?>" selected>30 days</option>
								        <?php } else { ?>
								            <option value="<?php echo Date('Y-m-d h:i', strtotime('+7 day')); ?>" >7 day</option>
							                <option value="<?php echo Date('Y-m-d h:i', strtotime('+15 day')); ?>" >15 day</option>
            								<option value="<?php echo Date('Y-m-d h:i', strtotime('+30 days')); ?>"selected>30 days</option>
            							<?php } ?>
        								</select>
    								</div>
                <div class="form-group">
                  <label>Version</label>
                  <select class="select2" multiple="multiple" data-placeholder="Select Version" style="width: 100%;">
                    <option>Injector</option>
                    <option>Script</option>
                    <option>Mod Menu</option>
                    <option>Pannel</option>
                  </select>
                </div>
                <div class="position-relative form-group">
        								<label for="exampleEmail" class="">Provider</label>
        								<input type="text" value="<?php echo $_SESSION['user_logado']; ?>" readonly class="form-control" required>
    								</div>
                <div class="position-relative form-group">
        								<label for="exampleEmail" class="">Type</label>
        								<select name="cargo" class="form-control">
            								<?php if ($dados_logado['type'] == "reseller") { ?>
            								<option value="cliente">Member</option>
        								</select>
    								</div>
    								
    								
    								
    								<?php } else if ($dados_logado['type'] == "admin") { ?>
        								<option value="cliente">Member</option>
        								<option value="reseller">Vendedor</option>
        								<option value="admin">Admin</option>
    								</select>
    								</div>
    								
    								<?php } else { ?>
    								<option value="member">Member</option>
    								</select>
    								</div>
    								<?php } ?>
	<?php if ($dados_logado['type'] == "admin") { ?>
    
                <div class="form-group">
                  <label>Credits</label>
                  <input type="number" min="0" max="50" name="credit" required value="0" class="form-control" id="exampleInputPassword1" placeholder="Credits">
                </div>
                <?php } ?>
                  </div>
                  <!-- /.form group -->
                  <div class="form-group mb-0">
                    <div class="custom-control custom-checkbox">
                      <input type="checkbox" name="terms" class="custom-control-input" id="exampleCheck1">
                      <label class="custom-control-label" for="exampleCheck1">I agree to the <a href="#">terms of service</a>.</label>
                    </div>
                  </div>
                </div>
                <!-- /.card-body -->
                <div class="card-footer">
                  <button type="submit" name="Submit" class="btn btn-primary">Register</button>
                </div>
              </form>
            </div>
            <!-- /.card -->
            </div>
          <!--/.col (left) -->
          <!-- right column -->
          <div class="col-md-6">

          </div>
          <!--/.col (right) -->
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
<!-- Bootstrap4 Duallistbox -->
<script src="plugins/bootstrap4-duallistbox/jquery.bootstrap-duallistbox.min.js"></script>
<!-- jquery-validation -->
<script src="plugins/select2/js/select2.full.min.js"></script>
<script src="plugins/jquery-validation/jquery.validate.min.js"></script>
<script src="plugins/jquery-validation/additional-methods.min.js"></script>
<!-- newmod App -->
<script src="plugins/moment/moment.min.js"></script>
<script src="plugins/inputmask/jquery.inputmask.min.js"></script>
<script src="dist/js/newmod.min.js"></script>
<!-- newmod for demo purposes -->
<script src="dist/js/demo.js"></script>
<!-- Page specific script -->
<script>
$(function () {
  //Initialize Select2 Elements
  $('.select2').select2()

 //Initialize Select2 Elements
 $('.select2bs4').select2({
  theme: 'bootstrap4'
 })

 //Datemask dd/mm/yyyy
 $('#datemask').inputmask('dd/mm/yyyy', { 'placeholder': 'dd/mm/yyyy' })
 $('[data-mask]').inputmask()
  $.validator.setDefaults({
    submitHandler: function () {
      alert( "Form successful submitted!" );
    }
  });
  $('#quickForm').validate({
    rules: {
      senha: {
        required: true,
        minlength: 5
      },
      devices: {
        required: true,
        minlength: 1
      },
      usuario: {
        required: true,
        minlength: 5
      },
      credits: {
        required: true,
        minlength: 2
      },
      terms: {
        required: true
      },
    },
    messages: {
      usuario: {
        required: "Please provide a username",
        minlength: "Your username must be at least 5 characters long"
      },
      senha: {
        required: "Please provide a password",
        minlength: "Your password must be at least 5 characters long"
      },
      devices: {
        required: "Please provide a Device",
        minlength: "Your Device must be 1"
      },
      credits: {
        required: "Please provide Credits",
        minlength: "Your Credits must be 10"
      },
      terms: "Please accept our terms"
    },
    errorElement: 'span',
    errorPlacement: function (error, element) {
      error.addClass('invalid-feedback');
      element.closest('.form-group').append(error);
    },
    highlight: function (element, errorClass, validClass) {
      $(element).addClass('is-invalid');
    },
    unhighlight: function (element, errorClass, validClass) {
      $(element).removeClass('is-invalid');
    }
  });
});
</script>
</body>
</html>
