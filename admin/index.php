<?php
require __DIR__ . '/config.php';

if (!empty($_SESSION['admin_id'])) {
    header('Location: dashboard.php');
    exit;
}

header('Location: login.php');
exit;
