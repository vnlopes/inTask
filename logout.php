<?php
session_start(); // Inicia a sessão
session_unset(); // Remove todas as variáveis da sessão
session_destroy(); // Destroi a sessão

// Redireciona para a página de login
header('Location: admin.php');
exit;
?>
