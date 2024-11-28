<?php
session_start(); // Inicia a sessão

// Senha para acesso à administração
$admin_password = "010904"; // Define a senha para acesso ao painel de administração

// Verifica se a senha foi submetida via POST
if (isset($_POST['password'])) {
    $password = $_POST['password']; // Obtém a senha enviada pelo formulário

    // Verifica se a senha está correta
    if ($password === $admin_password) {
        $_SESSION['authenticated'] = true; // Marca a sessão como autenticada se a senha estiver correta
    } else {
        $error_message = "Senha incorreta!"; // Exibe mensagem de erro caso a senha esteja incorreta
    }
}

// Se o usuário estiver autenticado, exibe as opções de administração
if (isset($_SESSION['authenticated']) && $_SESSION['authenticated'] === true) {
    include('db.php'); // Inclui a conexão com o banco de dados

    // Ação de editar usuário
    if (isset($_GET['action']) && $_GET['action'] == 'edit' && isset($_GET['user_id'])) {
        $user_id = $_GET['user_id']; // Obtém o ID do usuário a ser editado
        $stmt = $conn->prepare("SELECT username FROM users WHERE id = ?"); // Prepara a consulta SQL para buscar o nome de usuário
        $stmt->bind_param("i", $user_id); // Faz a ligação do parâmetro de ID com a consulta
        $stmt->execute(); // Executa a consulta
        $result = $stmt->get_result(); // Obtém o resultado da consulta
        $user = $result->fetch_assoc(); // Obtém o usuário

        // Se o formulário de edição for enviado
        if ($_SERVER['REQUEST_METHOD'] == 'POST') {
            $new_username = $_POST['username']; // Obtém o novo nome de usuário
            $new_password = $_POST['password']; // Obtém a nova senha
            $new_hashed_password = password_hash($new_password, PASSWORD_DEFAULT); // Cria um hash da nova senha
            $update_stmt = $conn->prepare("UPDATE users SET username = ?, password = ? WHERE id = ?"); // Prepara a consulta SQL para atualizar o usuário
            $update_stmt->bind_param("ssi", $new_username, $new_hashed_password, $user_id); // Liga os parâmetros
            $update_stmt->execute(); // Executa a consulta de atualização
            header('Location: admin.php'); // Redireciona após a edição
            exit();
        }
    }

    // Ação de excluir usuário
    if (isset($_GET['action']) && $_GET['action'] == 'delete' && isset($_GET['user_id'])) {
        $user_id = $_GET['user_id']; // Obtém o ID do usuário a ser excluído
        $delete_stmt = $conn->prepare("DELETE FROM users WHERE id = ?"); // Prepara a consulta SQL para excluir o usuário
        $delete_stmt->bind_param("i", $user_id); // Liga o parâmetro de ID
        $delete_stmt->execute(); // Executa a consulta de exclusão
        header('Location: admin.php'); // Redireciona após a exclusão
        exit();
    }

    // Exibe os usuários
    $users = []; // Cria um array para armazenar os usuários
    $result = $conn->query("SELECT id, username FROM users"); // Realiza a consulta para buscar todos os usuários
    while ($row = $result->fetch_assoc()) {
        $users[] = $row; // Adiciona os usuários ao array
    }

    // Se o administrador escolher ver as notas de um usuário
    if (isset($_GET['view_notes']) && isset($_GET['user_id'])) {
        $view_user_id = $_GET['user_id']; // Obtém o ID do usuário cujas notas serão visualizadas
        $stmt_notes = $conn->prepare("SELECT title, content, priority FROM notes WHERE user_id = ?"); // Prepara a consulta SQL para buscar as notas do usuário
        $stmt_notes->bind_param("i", $view_user_id); // Liga o parâmetro de ID
        $stmt_notes->execute(); // Executa a consulta
        $notes_result = $stmt_notes->get_result(); // Obtém o resultado das notas
        $notes = $notes_result->fetch_all(MYSQLI_ASSOC); // Obtém todas as notas e armazena em um array
    }
?>

<!DOCTYPE html>
<html lang="pt-br">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Administração de Usuários</title>
    <!-- CDN do Tailwind CSS -->
    <script src="https://cdn.tailwindcss.com"></script>
</head>
<body class="bg-[#040404] relative min-h-screen flex items-center flex-col">
    <img
      src="./resources/images/bg-grid-intask.svg"
      class="absolute select none z-[-1]"
      alt=""
    />

    <main class="min-h-screen flex flex-col items-center w-full">
    <?php 
      require_once 'header.php'
    ?>
    <!-- Container principal -->
    <div class="container mx-auto p-6">

        <!-- Título -->
        <h1 class="text-3xl font-bold text-center mb-6 text-white">Administração de Usuários</h1>

        <!-- Exibe mensagem de erro se a senha estiver incorreta -->
        <?php if (isset($error_message)) { ?>
            <p class="text-red-500 text-center"><?php echo $error_message; ?></p>
        <?php } ?>

        <!-- Tabela de usuários -->
        <h2 class="text-xl font-semibold mb-4">Usuários Registrados</h2>
        <table class="min-w-full bg-white border border-gray-300 rounded-md shadow-md">
            <thead class="bg-gray-200">
                <tr>
                    <th class="px-6 py-3 text-left text-sm font-medium text-gray-700">ID</th>
                    <th class="px-6 py-3 text-left text-sm font-medium text-gray-700">Nome de Usuário</th>
                    <th class="px-6 py-3 text-left text-sm font-medium text-gray-700">Ações</th>
                </tr>
            </thead>
            <tbody>
                <?php foreach ($users as $user) { ?>
                    <tr class="border-b hover:bg-gray-50">
                        <td class="px-6 py-4 text-sm"><?php echo $user['id']; ?></td>
                        <td class="px-6 py-4 text-sm"><?php echo $user['username']; ?></td>
                        <td class="px-6 py-4 text-sm">
                            <a href="admin.php?action=edit&user_id=<?php echo $user['id']; ?>" class="text-blue-500 hover:underline">Editar</a> |
                            <a href="admin.php?action=delete&user_id=<?php echo $user['id']; ?>" onclick="return confirm('Tem certeza de que deseja excluir este usuário?')" class="text-red-500 hover:underline">Excluir</a> |
                            <a href="admin.php?view_notes=1&user_id=<?php echo $user['id']; ?>" class="text-green-500 hover:underline">Ver Notas</a>
                        </td>
                    </tr>
                <?php } ?>
            </tbody>
        </table>

        <!-- Formulário de edição de usuário -->
        <?php if (isset($_GET['action']) && $_GET['action'] == 'edit' && isset($user)) { ?>
            <h2 class="text-xl font-semibold mt-6">Editar Usuário: <?php echo $user['username']; ?></h2>
            <form action="admin.php?action=edit&user_id=<?php echo $user_id; ?>" method="POST" class="mt-4 bg-white p-6 rounded-md shadow-md">
                <label for="username" class="block text-gray-700 text-sm font-medium">Novo Nome de Usuário:</label>
                <input type="text" id="username" name="username" value="<?php echo $user['username']; ?>" required class="w-full mt-2 p-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500">
                
                <label for="password" class="block text-gray-700 text-sm font-medium mt-4">Nova Senha:</label>
                <input type="password" id="password" name="password" required class="w-full mt-2 p-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500">
                
                <input type="submit" value="Atualizar" class="mt-4 w-full p-2 bg-blue-500 text-white rounded-md hover:bg-blue-600">
            </form>
        <?php } ?>

        <!-- Exibição das notas do usuário -->
        <?php if (isset($notes)) { ?>
            <h2 class="text-xl font-semibold mt-6">Notas de <?php echo $user['username']; ?></h2>
            <table class="min-w-full bg-white border border-gray-300 rounded-md shadow-md mt-4">
                <thead class="bg-gray-200">
                    <tr>
                        <th class="px-6 py-3 text-left text-sm font-medium text-gray-700">Título</th>
                        <th class="px-6 py-3 text-left text-sm font-medium text-gray-700">Conteúdo</th>
                        <th class="px-6 py-3 text-left text-sm font-medium text-gray-700">Prioridade</th>
                    </tr>
                </thead>
                <tbody>
                    <?php foreach ($notes as $note) { ?>
                        <tr class="border-b hover:bg-gray-50">
                            <td class="px-6 py-4 text-sm"><?php echo $note['title']; ?></td>
                            <td class="px-6 py-4 text-sm"><?php echo $note['content']; ?></td>
                            <td class="px-6 py-4 text-sm"><?php echo $note['priority']; ?></td>
                        </tr>
                    <?php } ?>
                </tbody>
            </table>
        <?php } ?>

        <!-- Link de logout -->
        <a href="logout.php" class="mt-6 block text-blue-500 hover:underline">Sair</a>
    </div>
    <?php 
      require_once 'footer.php'
    ?>
    </main>
</body>
</html>

<?php
} else {
    // Caso o usuário não esteja autenticado, exibe o formulário de login
?>

<!DOCTYPE html>
<html lang="pt-br">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login - Administração de Usuários</title>
    <!-- CDN do Tailwind CSS -->
    <script src="https://cdn.tailwindcss.com"></script>
</head>
<body class="bg-[#040404] relative min-h-screen flex items-center flex-col">
    <img
      src="./resources/images/bg-grid-intask.svg"
      class="absolute select none z-[-1]"
      alt=""
    />

    <main class="min-h-screen flex flex-col items-center w-full">
    <?php 
      require_once 'header.php'
    ?>

    <div class="container mx-auto p-6">
        <form action="admin.php" method="POST" class="bg-[#1F1F1F] flex flex-col gap-[10px] p-8 rounded-lg shadow-lg w-96 h-fit fixed top-0 bottom-32 left-0 right-0 m-auto">
            <label for="password" class="block text-white text-sm font-medium">Senha:</label>
            <input type="password" id="password" name="password" required class="w-full mt-2 p-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500">
            
            <input type="submit" value="Entrar" class="bg-gradient-to-r from-[#FF2E00] to-[#FF5C00] text-white py-2 px-4 rounded">
        </form>
    </div>
    <?php 
      require_once 'footer.php'
    ?>
</main>
</body>
</html>

<?php
} // Fim do else
?>
