<?php
require 'db.php'; // Inclui a conexão com o banco de dados

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    // Captura os dados do formulário
    $username = $_POST['username'];
    $password = $_POST['password'];

    // Validação básica
    if (strlen($username) < 3) {
        echo "O nome de usuário deve ter pelo menos 3 caracteres.";
        exit();
    }

    if (strlen($password) < 6) {
        echo "A senha deve ter pelo menos 6 caracteres.";
        exit();
    }

    // Verifica se o nome de usuário já existe
    $stmt = $conn->prepare("SELECT id FROM users WHERE username = ?");
    $stmt->bind_param("s", $username);
    $stmt->execute();
    $stmt->store_result();

    if ($stmt->num_rows > 0) {
        echo "Este nome de usuário já está em uso. Escolha outro.";
        $stmt->close();
        $conn->close();
        exit();
    }
    $stmt->close(); // Fecha a consulta anterior

    // Cria uma query para inserir os dados
    $stmt = $conn->prepare("INSERT INTO users (username, password) VALUES (?, ?)");
    $hashed_password = password_hash($password, PASSWORD_DEFAULT); // Hash a senha para segurança
    $stmt->bind_param("ss", $username, $hashed_password);

    if ($stmt->execute()) {
        // Redirecionar após o registro
        header('Location: login.php?success=1'); // Adiciona um parâmetro de sucesso
        exit();
    } else {
        echo "Erro ao registrar: " . htmlspecialchars($stmt->error);
    }

    // Fecha a declaração e a conexão
    $stmt->close();
    $conn->close();
}
?>

<!DOCTYPE html>
<html lang="pt-BR">
<head>
    <meta charset="UTF-8">
    <title>Registro</title>
    <script src="https://cdn.tailwindcss.com"></script>
</head>
<body class="bg-black text-white flex justify-center items-center h-screen">
    <form method="POST" class="bg-gray-800 p-6 rounded-lg shadow-md w-96">
        <h2 class="text-2xl mb-4 text-center">Registro</h2>
        
        <?php if (isset($_GET['success'])): ?>
            <p class="text-green-500 mb-4">Registro realizado com sucesso!.</p>
        <?php endif; ?>

        <input type="text" name="username" required placeholder="Usuário" class="mb-4 p-2 w-full rounded bg-gray-700 text-white placeholder-gray-400" />
        <input type="password" name="password" required placeholder="Senha" class="mb-4 p-2 w-full rounded bg-gray-700 text-white placeholder-gray-400" />
        <button type="submit" class="bg-blue-500 text-white py-2 rounded w-full">Registrar</button>
    </form>
</body>
</html>