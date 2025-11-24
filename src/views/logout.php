<?php

session_start();
require_once __DIR__ . '/../helpers/asset.php';

unset($_SESSION['name']);
unset($_SESSION['email']);
unset($_SESSION['phone']);
unset($_SESSION['address']);
unset($_SESSION['created-time']);
header('Location: ' . site_url());
exit;