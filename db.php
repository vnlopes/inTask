<?php
$servername = "localhost"; // Define o nome do servidor (geralmente "localhost" em ambientes locais)
$username = "root"; // Nome de usuário para acessar o banco de dados
$password = ""; // Senha para acessar o banco de dados (em muitos ambientes locais, a senha padrão é vazia)
$dbname = "inTask"; // Nome do banco de dados que será utilizado

// Cria a conexão com o banco de dados utilizando a classe MySQLi
$conn = new mysqli($servername, $username, $password, $dbname);

// Verifica se houve erro na conexão com o banco de dados
if ($conn->connect_error) {
    die("Conexão falhou: " . $conn->connect_error); // Encerra o script e exibe a mensagem de erro
}
?>
