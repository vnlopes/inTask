<?php
require 'db.php'; // Inclui o arquivo de conexão com o banco de dados.

// Verifica se o método de requisição é POST.
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    // Captura os dados enviados pelo formulário.
    $username = $_POST['username']; // Nome de usuário enviado.
    $password = $_POST['password']; // Senha enviada.

    // Validação básica do nome de usuário.
    if (strlen($username) < 3) { // Verifica se o nome de usuário tem menos de 3 caracteres.
        echo "O nome de usuário deve ter pelo menos 3 caracteres."; // Mensagem de erro.
        exit(); // Finaliza o script.
    }

    // Validação básica da senha.
    if (strlen($password) < 6) { // Verifica se a senha tem menos de 6 caracteres.
        echo "A senha deve ter pelo menos 6 caracteres."; // Mensagem de erro.
        exit(); // Finaliza o script.
    }

    // Prepara uma consulta para verificar se o nome de usuário já existe no banco.
    $stmt = $conn->prepare("SELECT id FROM users WHERE username = ?");
    $stmt->bind_param("s", $username); // Substitui o placeholder "?" pelo valor de $username.
    $stmt->execute(); // Executa a consulta.
    $stmt->store_result(); // Armazena os resultados da consulta.

    // Verifica se o nome de usuário já está em uso.
    if ($stmt->num_rows > 0) { // Caso já exista um registro com o mesmo nome de usuário.
        echo "Este nome de usuário já está em uso. Escolha outro."; // Mensagem de erro.
        $stmt->close(); // Fecha a consulta.
        $conn->close(); // Fecha a conexão com o banco.
        exit(); // Finaliza o script.
    }
    $stmt->close(); // Fecha a consulta anterior.

    // Prepara uma consulta para inserir os dados do novo usuário.
    $stmt = $conn->prepare("INSERT INTO users (username, password) VALUES (?, ?)");
    $hashed_password = password_hash($password, PASSWORD_DEFAULT); // Cria um hash seguro para a senha.
    $stmt->bind_param("ss", $username, $hashed_password); // Substitui os placeholders pelos valores.

    // Executa a inserção no banco de dados.
    if ($stmt->execute()) {
        // Redireciona para a página de sucesso ao concluir o registro.
        header('Location: redirect.php?success=1'); // Adiciona um parâmetro de sucesso na URL.
        exit(); // Finaliza o script.
    } else {
        // Exibe uma mensagem de erro caso a inserção falhe.
        echo "Erro ao registrar: " . htmlspecialchars($stmt->error);
    }

    // Fecha a declaração e a conexão com o banco.
    $stmt->close();
    $conn->close();
}
?>


<!DOCTYPE html>
<html lang="pt-BR">
  <head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <title>Registro</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <link
      rel="shortcut icon"
      href="..//resources/images/logo-icon.svg"
      type="image/x-icon"
    />
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

      <div class="bg-[#1F1F1F] p-8 rounded-lg shadow-lg w-96 h-fit fixed top-0 bottom-32 left-0 right-0 m-auto">
        <form method="POST" class="p-6 rounded-lg flex flex-col">
        <h2 class="text-2xl text-white mb-4 text-center">Registro</h2>
        
        <?php if (isset($_GET['success'])): ?>
            <p class="text-green-500 mb-4">Registro realizado com sucesso!.</p>
        <?php endif; ?>

        <input type="text" name="username" required placeholder="Usuário" class="mb-4 p-2 w-full rounded bg-[#414141] text-white placeholder-gray-400" />
        <input type="password" name="password" required placeholder="Senha" class="mb-4 p-2 w-full rounded bg-[#414141] text-white placeholder-gray-400" />
        <button type="submit" class="bg-gradient-to-r from-[#FF2E00] to-[#FF5C00] text-white py-2 px-4 rounded">Registrar</button>
    </form>
    <p class="mt-4 text-white text-center">
          Ja possui uma conta?
          <a href="redirect.php" class="text-[#FF2E00] hover:underline"
            >Login</a
          >
        </p>
      </div>
      <?php 
      require_once 'footer.php'
    ?>
    </main>
  </body>
</html>
