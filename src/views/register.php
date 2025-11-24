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

if(isset($_SESSION['name'])) {
    header('Location: ' . $redirectTarget);
    exit;
}

$error = false;

if(isset($_POST['register']) && CSRF::validateToken($_POST['token'])) {
  $lastname = filter_input(INPUT_POST, 'lastname');
  $firstname = filter_input(INPUT_POST, 'firstname');
  $email = filter_input(INPUT_POST, 'email', FILTER_SANITIZE_EMAIL);
  $phone = filter_input(INPUT_POST, 'phone');
  $address = filter_input(INPUT_POST, 'address');
  $password = password_hash(filter_input(INPUT_POST, 'password'), PASSWORD_DEFAULT);
  $createdTime = time();

  $statement = $pdo->prepare("SELECT * FROM users WHERE email=?");
  $statement->execute(array($email));
  if($statement->rowCount() > 0) {
      $error = true;
  } else {
    $statement = $pdo->prepare("INSERT INTO users (firstname, lastname, email, phone, address, password, created) VALUES (?, ?, ?, ?, ?, ?, ?)");
    $statement->execute(array($firstname, $lastname, $email, $phone, $address, $password, $createdTime));
    $_SESSION['name'] = $lastname . ' ' . $firstname;
    $_SESSION['email'] = $email;
    $_SESSION['phone'] = $phone;
    $_SESSION['address'] = $address;
    $_SESSION['created-time'] = $createdTime;
    header('Location: ' . $redirectTarget);
    exit;
  }

}
?>

<!DOCTYPE html>
<html lang="en">
<head>

  <!-- Basic Page Needs
  ================================================== -->
  <meta charset="utf-8">
  <title>NovaMart | Create account</title>

  <!-- Mobile Specific Metas
  ================================================== -->
  <meta http-equiv="X-UA-Compatible" content="IE=edge">
  <meta name="description" content="Create a NovaMart profile to track orders and manage deliveries.">
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
                    <div class="alert alert-danger alert-common" role="alert"><i class="tf-ion-close-circled"></i><span>Registration Failed!</span> Email already registered</div>
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
            <form class="text-left clearfix" method="post" action="<?= htmlspecialchars($_SERVER['REQUEST_URI'] ?? site_url('register')) ?>" >
                <?php CSRF::csrfInputField() ?>
                <input type="hidden" name="redirect" value="<?= htmlspecialchars($redirectParam ?? '', ENT_QUOTES, 'UTF-8') ?>">
                <div class="form-group">
                    <input type="text" name="firstname" class="form-control"  placeholder="Firstname">
                </div>
                <div class="form-group">
                    <input type="text" name="lastname" class="form-control"  placeholder="Lastname">
                </div>
                <div class="form-group">
                    <input type="email" name="email" class="form-control"  placeholder="Email">
                </div>
                <div class="form-group">
                    <input type="tel" name="phone" class="form-control"  placeholder="Phone">
                </div>
                <div class="form-group">
                    <input type="text" name="address" class="form-control"  placeholder="Address">
                </div>
                <div class="form-group">
                    <input type="password" name="password" class="form-control" placeholder="Password">
                </div>
                <div class="text-center">
                    <button name="register" type="submit" class="btn btn-main text-center" >Register</button>
                </div>
            </form>
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
