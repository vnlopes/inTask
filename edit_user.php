<?php
session_start();
require('db.php'); // Inclui a conexão com o banco de dados

if (!isset($_SESSION['username'])) {
    header("Location: login.php");
    exit();
}

$id = $_GET['id'];
$sql = "SELECT * FROM users WHERE id = ?";
$stmt = $conn->prepare($sql);
$stmt->bind_param("i", $id);
$stmt->execute();
$result = $stmt->get_result();
$user = $result->fetch_assoc();

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $username = $_POST['username'];
    $password = $_POST['password'];
    $hashed_password = password_hash($password, PASSWORD_DEFAULT);

    $sql = "UPDATE users SET username = ?, password = ? WHERE id = ?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("ssi", $username, $hashed_password, $id);

    if ($stmt->execute()) {
        header("Location: admin.php");
        exit();
    } else {
        echo "Erro: " . $stmt->error;
    }
}
?>

<!DOCTYPE html>
<html lang="pt-BR">
<head>
    <meta charset="UTF-8">
    <title>Editar Usuário</title>
    <link href="https://cdn.jsdelivr.net/npm/tailwindcss@2.2.19/dist/tailwind.min.css" rel="stylesheet">
</head>
<body class="bg-black text-white flex justify-center items-center min-h-screen">
    <div class="bg-gray-800 p-6 rounded shadow-md w-96">
        <h2 class="text-2xl mb-4">Editar Usuário</h2>
        <form method="POST">
            <input type="text" name="username" value="<?php echo htmlspecialchars($user['username']); ?>" required class="mb-4 p-2 w-full rounded" />
            <input type="password" name="password" placeholder="Nova Senha" required class="mb-4 p-2 w-full rounded" />
            <button type="submit" class="bg-blue-500 p-2 w-full rounded">Salvar</button>
        </form>
    </div>
</body>
</html>

<?php
$stmt->close();
$conn->close(); // Fecha a conexão
?>
