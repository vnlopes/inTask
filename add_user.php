<?php
// Conexão ao banco de dados
$servername = "localhost"; // ou o endereço do seu servidor
$username = "seu_usuario"; // seu usuário do banco de dados
$password = "sua_senha"; // sua senha do banco de dados
$dbname = "intask"; // nome do seu banco de dados

// Criação da conexão
$conn = new mysqli($servername, $username, $password, $dbname);

// Verificação da conexão
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

// Dados do usuário admin
$adminUsername = 'admin';
$adminPassword = password_hash('', PASSWORD_DEFAULT); // Criptografa a senha
$adminRole = 'admin';

// Comando SQL para inserir o usuário
$sql = "INSERT INTO users (username, password, role) VALUES ('$adminUsername', '$adminPassword', '$adminRole')";

if ($conn->query($sql) === TRUE) {
    echo "Usuário admin adicionado com sucesso.";
} else {
    echo "Erro ao adicionar usuário: " . $conn->error;
}

// Fecha a conexão
$conn->close();
?>
