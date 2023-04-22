<?php
session_start();
unset($_SESSION['user_logado']);
if (isset($_POST['user'])) {
	include 'xyz.php';
	
	$usuario = trim(isset($_POST['user']) ? $_POST['user'] : '');
	$senha = trim(isset($_POST['pass']) ? $_POST['pass'] : '');
	
	$userchecked = mysqli_real_escape_string($con, $usuario);
	$passchecked = mysqli_real_escape_string($con, $senha);
	
	$query = mysqli_query($con, "SELECT * FROM users WHERE username = '".$userchecked."' AND password = '".$passchecked."'");
	$check = mysqli_num_rows($query);
	if ($check == 1) {
		$_SESSION['user_logado'] = $userchecked;
		$det = mysqli_fetch_assoc($query);
		if ($det['type'] == "reseller") {
			header("Location: ".BASE_URL . "seller/index.php");
		} else if ($det['type'] == "admin") {
			header("Location: ".BASE_URL . "index.php");
        } else if ($det['type'] == "cliente") {
            header("Location: ".BASE_URL . "user/index.php");
		} else {
			$_SESSION['acao'] = "Você não tem permissão para acessar esta pagina.";
			session_destroy();
		}
	} else {
		$_SESSION['acao'] = "Usuário ou senha incorretos.";
	}
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta http-equiv="Content-Language" content="pt-BR">
    <meta http-equiv="Content-Type" content="text/html; charset=utf-8"/>
    <title>Login - NewMod Extreme</title>
    <meta name="viewport" content="width=device-width, initial-scale=1, maximum-scale=1, user-scalable=no, shrink-to-fit=no" />
    <link href="assets/css/login.css" rel="stylesheet"></head>
    <body>
        <div class="app-theme-gray body-tabs-shadow">
        <div class="app-container">
            <div class="h-100 bg-padrao bg-animation">
                <div class="d-flex h-100 justify-content-center align-items-center">
                    <div class="mx-auto app-login-box col-md-8">
                        <div class="modal-dialog w-100 mx-auto">
                            <div class="modal-content">
                                <div class="modal-body">
                                    <div class="h5 modal-title text-center">
                                        <h4 class="mt-2">
                                            <div class="text-info"> <h2>NewMod Extreme</h2></div>
                                            <span>Enter your login to connect</span>
                                        </h4>
                                    </div>
									
									<?php if (!empty($_SESSION['acao'])) { ?>
									    <div class="alert alert-danger" role="alert">
									        <?php echo $_SESSION['acao'];
									        unset($_SESSION['acao']);?>
								        </div>
								    <?php } ?>
									
                                    <form action="" method="POST" class="">
                                        <div class="form-row">
                                            <div class="col-md-12">
                                                <div class="position-relative form-group"><input name="user" placeholder="Username" type="text" class="form-control" required></div>
                                            </div>
                                            <div class="col-md-12">
                                                <div class="position-relative form-group"><input name="pass" placeholder="Password" type="password" class="form-control" required></div>
                                            </div>
                                        </div>
                                </div>
                                <div class="modal-footer clearfix">
                                    <div class="float-right">
                                        <button type="submit" class="btn btn-info btn-lg">Login</button>
                                    </div>
                                </div>
								 </form>
                            </div>
                        </div>
                        <div class="text-center opacity-8 mt-3">
                            Copyright © <b>NewMod Extreme </b> <?php echo date('Y'); ?>
                        </div>
                    </div>
                </div>
            </div>
        </div>
</div>


<script type="text/javascript" src="/assets/scripts/main.js"></script>
</body>
</html>
