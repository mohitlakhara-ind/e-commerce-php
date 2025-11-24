<?php 

session_start();

require_once __DIR__ . '/../helpers/asset.php';
require __DIR__ . '/../csrf.php';
require __DIR__ . '/db.php';

$rawRedirect = filter_input(INPUT_GET, 'redirect', FILTER_UNSAFE_RAW);
if ($rawRedirect === null || $rawRedirect === '') {
    $rawRedirect = filter_input(INPUT_POST, 'redirect', FILTER_UNSAFE_RAW);
}
$redirectParam = sanitize_redirect_path($rawRedirect);
$redirectTarget = $redirectParam ?: site_url();
$defaultRedirect = site_url();
$registerLink = site_url('register');
if ($redirectParam && $redirectParam !== $defaultRedirect) {
    $registerLink .= '?redirect=' . rawurlencode($redirectParam);
}

if(isset($_SESSION['name'])) {
    header('Location: ' . $redirectTarget);
    exit;
}

$error = false;

if(isset($_POST['login']) && CSRF::validateToken($_POST['token'])) {
    $email = filter_input(INPUT_POST, 'email', FILTER_SANITIZE_EMAIL);
    $password = filter_input(INPUT_POST, 'password');
    $statement = $pdo->prepare("SELECT * FROM users WHERE email=?");
    $statement->execute(array($email));
    if($statement->rowCount() > 0) {
        $result = $statement->fetchAll(PDO::FETCH_ASSOC);
        if(password_verify($password, $result[0]['password'])) {
            $_SESSION['name'] = $result[0]['lastname'] . ' ' . $result[0]['firstname'];
            $_SESSION['email'] = $result[0]['email'];
            $_SESSION['phone'] = $result[0]['phone'];
            $_SESSION['address'] = $result[0]['address'];
            $_SESSION['created-time'] = $result[0]['created'];
            header('Location: ' . $redirectTarget);
            exit;
        }
        $error = true;
    }
    $error = true;
}

?>

<!DOCTYPE html>
<html lang="en">
<head>

  <!-- Basic Page Needs
  ================================================== -->
  <meta charset="utf-8">
  <title>NovaMart | Sign in</title>

  <!-- Mobile Specific Metas
  ================================================== -->
  <meta http-equiv="X-UA-Compatible" content="IE=edge">
  <meta name="description" content="Log in to NovaMart to manage orders, profile and cart.">
  <meta name="viewport" content="width=device-width, initial-scale=1.0, maximum-scale=5.0">
  <meta name="author" content="NovaMart Studio">
  <meta name="generator" content="NovaMart Commerce Stack">
  
  <!-- Favicon -->
  <link rel="shortcut icon" type="image/x-icon" href="<?= asset_url('views/images/favicon.png') ?>" />
  
  <link rel="stylesheet" href="<?= asset_url('views/plugins/themefisher-font/style.css') ?>">
  <!-- bootstrap.min css -->
  <link rel="stylesheet" href="<?= asset_url('views/plugins/bootstrap/css/bootstrap.min.css') ?>">
  
  <!-- Animate css -->
  <link rel="stylesheet" href="<?= asset_url('views/plugins/animate/animate.css') ?>">
  <!-- Slick Carousel -->
  <link rel="stylesheet" href="<?= asset_url('views/plugins/slick/slick.css') ?>">
  <link rel="stylesheet" href="<?= asset_url('views/plugins/slick/slick-theme.css') ?>">
  
  <!-- Main Stylesheet -->
  <link rel="stylesheet" href="<?= asset_url('views/css/style.css') ?>">

</head>

<body id="body">

    <section class="signin-page account">
    <div class="container">
        <div class="row">
            
        <?php if($error): ?>
            <div class="row mt-30">
                <div class="col-xs-12">
                    <div class="alertPart">
                    <div class="alert alert-danger alert-common" role="alert"><i class="tf-ion-close-circled"></i><span>Login Failed!</span> Invalid username/password</div>
                    </div>
                </div>		
            </div>
        <?php endif ?>

        <div class="col-md-6 col-md-offset-3">
            <div class="block text-center">
            <a href="<?= site_url() ?>">
                <svg width="250px" height="29px" viewBox="0 0 200 29" version="1.1" xmlns="http://www.w3.org/2000/svg"
                    xmlns:xlink="http://www.w3.org/1999/xlink">
                    <g id="Page-1" stroke="none" stroke-width="1" fill="none" fill-rule="evenodd" font-size="40"
                        font-family="AustinBold, Austin" font-weight="bold">
                        <g id="Group" transform="translate(-108.000000, -297.000000)" fill="#b388ff">
                            <text id="AVIATO">
                                <tspan x="108.94" y="325">NOVAMART</tspan>
                            </text>
                        </g>
                    </g>
                </svg>
            </a>
            <h2 class="text-center">Welcome Back</h2>
            <form class="text-left clearfix" method="post" action="<?= htmlspecialchars($_SERVER['REQUEST_URI'] ?? site_url('login')) ?>" >
                <?php CSRF::csrfInputField() ?>
                <input type="hidden" name="redirect" value="<?= htmlspecialchars($redirectParam ?? '', ENT_QUOTES, 'UTF-8') ?>">
                <div class="form-group">
                    <input type="email" name="email" class="form-control"  placeholder="Email">
                </div>
                <div class="form-group">
                    <input type="password" name="password" class="form-control" placeholder="Password">
                </div>
                <div class="text-center">
                    <button name="login" type="submit" class="btn btn-main text-center" >Login</button>
                </div>
            </form>
            <p class="mt-20">Don't have an account ?<a href="<?= $registerLink ?>"> Create New Account</a></p>
            <p class="mt-20"><a href="<?= site_url('forgot-password') ?>">Forgot Password?</a></p>
            </div>
        </div>
        </div>
    </div>
    </section>

 <!-- 
    Essential Scripts
    =====================================-->
    
    <!-- Main jQuery -->
    <script src="<?= asset_url('views/plugins/jquery/dist/jquery.min.js') ?>"></script>
    <!-- Bootstrap 3.1 -->
    <script src="<?= asset_url('views/plugins/bootstrap/js/bootstrap.min.js') ?>"></script>
    <!-- Bootstrap Touchpin -->
    <script src="<?= asset_url('views/plugins/bootstrap-touchspin/dist/jquery.bootstrap-touchspin.min.js') ?>"></script>
    <!-- Video Lightbox Plugin -->
    <script src="<?= asset_url('views/plugins/ekko-lightbox/dist/ekko-lightbox.min.js') ?>"></script>
    <!-- Count Down Js -->
    <script src="<?= asset_url('views/plugins/syo-timer/build/jquery.syotimer.min.js') ?>"></script>

    <!-- slick Carousel -->
    <script src="<?= asset_url('views/plugins/slick/slick.min.js') ?>"></script>
    <script src="<?= asset_url('views/plugins/slick/slick-animation.min.js') ?>"></script>

    <!-- Main Js File -->
    <script src="<?= asset_url('views/js/script.js') ?>"></script>
    
    

  </body>
  </html>
