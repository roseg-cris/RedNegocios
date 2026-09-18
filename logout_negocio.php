<?php
require_once __DIR__ . '/includes/auth.php';

session_unset();
session_destroy();

header('Location: login_negocio.php');
exit;
