<?php
session_start();
require('db.php'); // Inclui a conexão com o banco de dados

// Verifica se o usuário está logado e se é um administrador
if (!isset($_SESSION['username'])) {
    header("Location: login.php");
    exit();
}

// Captura o nome de usuário
$username = $_SESSION['username'];

// Verifica se o usuário é um administrador
$sql = "SELECT is_admin FROM users WHERE username = ?";
$stmt = $conn->prepare($sql);
$stmt->bind_param("s", $username);
$stmt->execute();
$result = $stmt->get_result();

if ($result->num_rows === 0 || $result->fetch_assoc()['is_admin'] != 1) {
    echo "Acesso negado! Você não tem permissão para acessar esta página.";
    exit();
}

// Captura a lista de usuários do banco de dados
$sql = "SELECT * FROM users";
$result = $conn->query($sql);
?>

<!DOCTYPE html>
<html lang="pt-BR">
<head>
    <meta charset="UTF-8">
    <title>Painel de Administração</title>
    <link href="https://cdn.jsdelivr.net/npm/tailwindcss@2.2.19/dist/tailwind.min.css" rel="stylesheet">
</head>
<body class="bg-black text-white flex flex-col items-center p-6">
    <h1 class="text-3xl mb-6">Gerenciamento de Usuários</h1>
    
    <table class="min-w-full bg-gray-800 rounded-lg shadow-lg">
        <thead>
            <tr>
                <th class="py-2">ID</th>
                <th class="py-2">Nome de Usuário</th>
                <th class="py-2">Ações</th>
            </tr>
        </thead>
        <tbody>
            <?php while ($row = $result->fetch_assoc()): ?>
            <tr class="border-t border-gray-600">
                <td class="py-2 text-center"><?php echo $row['id']; ?></td>
                <td class="py-2 text-center"><?php echo htmlspecialchars($row['username']); ?></td>
                <td class="py-2 text-center">
                    <a href="edit_user.php?id=<?php echo $row['id']; ?>" class="text-blue-400 hover:underline">Editar</a>
                    <a href="delete_user.php?id=<?php echo $row['id']; ?>" class="text-red-400 hover:underline">Excluir</a>
                </td>
            </tr>
            <?php endwhile; ?>
        </tbody>
    </table>
</body>
</html>

<?php
$conn->close(); // Fecha a conexão
?>
