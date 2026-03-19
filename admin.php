<?php
/**
 * Atalho de Acesso ao Painel
 */
session_start();
if (isset($_SESSION['is_admin']) && $_SESSION['is_admin']) {
    header("Location: slot-backend/public/admin/index.php");
} else {
    header("Location: slot-backend/public/auth/login_form.html");
}
exit;
