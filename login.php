<?php
session_start(); // Inicia a sessão para armazenar informações do usuário durante a navegação
require('db.php'); // Inclui o arquivo de conexão com o banco de dados

if ($_SERVER['REQUEST_METHOD'] == 'POST') { // Verifica se o método da requisição é POST
    $username = $_POST['username']; // Recebe o nome de usuário enviado pelo formulário
    $password = $_POST['password']; // Recebe a senha enviada pelo formulário

    // Prepara a consulta SQL para evitar injeção de SQL
    $stmt = $conn->prepare("SELECT id, password FROM users WHERE username = ?");
    $stmt->bind_param("s", $username); // Substitui o placeholder na consulta pelo valor do usuário
    $stmt->execute(); // Executa a consulta
    $result = $stmt->get_result(); // Obtém o resultado da consulta

    // Verifica se o nome de usuário existe no banco de dados
    if ($result->num_rows > 0) {
        $row = $result->fetch_assoc(); // Recupera os dados da linha correspondente
        $hashedPassword = $row['password']; // Armazena a senha hash do banco de dados

        // Verifica se a senha fornecida pelo usuário corresponde ao hash armazenado
        if (password_verify($password, $hashedPassword)) {
            // Salva o ID do usuário e o nome de usuário na sessão
            $_SESSION['user_id'] = $row['id'];
            $_SESSION['username'] = $username;
            header("Location: function/list.php"); // Redireciona para a página principal após login bem-sucedido
            exit(); // Encerra o script para evitar execução de código adicional
        } else {
            echo "Nome de usuário ou senha incorretos."; // Exibe mensagem de erro para senha incorreta
        }
    } else {
        echo "Nome de usuário ou senha incorretos."; // Exibe mensagem de erro para usuário não encontrado
    }

    $stmt->close(); // Fecha a consulta preparada
}
?>
