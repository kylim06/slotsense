<?php
session_start();

if (!isset($_SESSION['teste'])) {
    $_SESSION['teste'] = "funcionou!";
}

echo json_encode($_SESSION);
