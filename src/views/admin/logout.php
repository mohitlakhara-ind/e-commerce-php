<?php

session_start();
require_once __DIR__ . '/../../helpers/asset.php';

unset($_SESSION['admin']);
header('Location: ' . site_url('admin/login'));
exit();