<?php
session_start();
require('db.php');

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $username = $_POST['username'];
    $password = $_POST['password'];

    $stmt = $conn->prepare("SELECT password FROM users WHERE username = ?");
    $stmt->bind_param("s", $username);
    $stmt->execute();
    $result = $stmt->get_result();

    if ($result->num_rows > 0) {
        $row = $result->fetch_assoc();
        $hashedPassword = $row['password'];

        if (password_verify($password, $hashedPassword)) {
            $_SESSION['username'] = $username;
            header("Location: function/list.php");
            exit();
        } else {
            echo "Login inválido!";
        }
    } else {
        echo "Login inválido!";
    }

    $stmt->close();
    $conn->close();
}
?>

<!-- <!DOCTYPE html>
<html lang="pt-BR">
  <head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <title>Registro</title>
    <script src="https://cdn.tailwindcss.com"></script>
  </head>
  <body class="bg-[#040404] relative min-h-screen flex items-center flex-col">
    <img
      src="./resources/images/bg-grid-intask.svg"
      class="absolute select none z-[-1]"
      alt=""
    />
    <main class="min-h-screen flex flex-col items-center w-full">
      <header
        class="justify-center items-center relative bg-[#040404] mt-[-20px] w-full flex h-[70px] border-solid boder-1 border-b border-zinc-900"
      >
        <nav
          class="center-header px-[70px] relative max-w-[1440px] w-full flex items-center justify-between"
        >
          <a href="..//inTask/index.html">
            <img src="..//inTask/resources/images/logo.svg" alt="" />
          </a>
          <ul class="links font-medium hidden md:flex gap-8">
            <a class="text-white" href="#">Inicio</a>
            <a class="text-white" href="#">Pagamento</a>
            <a class="text-white" href="#">Sobre</a>
            <a class="text-white" href="#">Contato</a>
          </ul>

          <a
            href="https://github.com/vnlopes/"
            target="_blank"
            class="h-[36px] hover:scale-[.98] hover:bg-gray-200 transition-[-3s] flex items-center justify-center rounded-[8px] w-[36px] cursor-pointer bg-white"
          >
            <svg
              width="20"
              height="20"
              viewBox="0 0 20 20"
              fill="none"
              xmlns="http://www.w3.org/2000/svg"
            >
              <path
                d="M10 0C15.525 0 20 4.58819 20 10.2529C19.9995 12.4012 19.3419 14.4952 18.1198 16.2402C16.8977 17.9852 15.1727 19.2933 13.1875 19.9804C12.6875 20.0829 12.5 19.7625 12.5 19.4934C12.5 19.1474 12.5125 18.0452 12.5125 16.6738C12.5125 15.7126 12.2 15.0975 11.8375 14.777C14.0625 14.5207 16.4 13.6492 16.4 9.71466C16.4 8.58684 16.0125 7.67689 15.375 6.95918C15.475 6.70286 15.825 5.65193 15.275 4.24215C15.275 4.24215 14.4375 3.9602 12.525 5.29308C11.725 5.06239 10.875 4.94704 10.025 4.94704C9.175 4.94704 8.325 5.06239 7.525 5.29308C5.6125 3.97301 4.775 4.24215 4.775 4.24215C4.225 5.65193 4.575 6.70286 4.675 6.95918C4.0375 7.67689 3.65 8.59965 3.65 9.71466C3.65 13.6364 5.975 14.5207 8.2 14.777C7.9125 15.0334 7.65 15.4819 7.5625 16.1484C6.9875 16.4175 5.55 16.8533 4.65 15.3025C4.4625 14.9949 3.9 14.2388 3.1125 14.2516C2.275 14.2644 2.775 14.7386 3.125 14.9308C3.55 15.1743 4.0375 16.0843 4.15 16.3791C4.35 16.9558 5 18.058 7.5125 17.5838C7.5125 18.4425 7.525 19.2499 7.525 19.4934C7.525 19.7625 7.3375 20.0701 6.8375 19.9804C4.8458 19.3007 3.11342 17.9952 1.88611 16.2492C0.658808 14.5031 -0.0011006 12.4052 1.37789e-06 10.2529C1.37789e-06 4.58819 4.475 0 10 0Z"
                fill="black"
              />
            </svg>
          </a>
        </nav>
      </header>

      <div class="bg-[#1F1F1F] p-8 rounded-lg shadow-lg w-96 mt-[10%]">
        <h1 class="text-2xl text-white font-bold mb-4 text-center">Login</h1>
        <form action="login.php" method="POST" class="p-6 rounded-lg">
          <h2 class="text-white text-2xl mb-4">Faça Login</h2>
          <?php if (isset($_GET['success'])): ?>
            <p class="text-green-500 mb-4">Registro realizado com sucesso! Faça login.</p>
            <?php endif; ?>
          <input
            type="text"
            name="username"
            placeholder="Nome de Usuário"
            required
            class="mb-4 p-2 w-full rounded bg-[#414141] text-white"
          />
          <input
            type="password"
            name="password"
            placeholder="Senha"
            required
            class="mb-4 p-2 w-full rounded bg-[#414141] text-white"
          />
          <button
            type="submit"
            class="bg-gradient-to-r from-[#FF2E00] to-[#FF5C00] text-white py-2 px-4 rounded"
          >
            Login
          </button>
        </form>
        <p class="mt-4 text-white text-center">
          Não possui uma conta?
          <a href="register.php" class="text-[#FF2E00] hover:underline"
            >Registrar</a
          >
        </p>
      </div>
      <footer class="absolute flex flex-col bottom-0 w-full h-[157px]">
        <section
          class="h-[60%] border-t border-solid justify-center flex items-center border-zinc-900 bg-[#040404]"
        >
          <div class="flex w-full px-[70px] justify-between max-w-[1440px]">
            <nav class="flex items-center justify-between h-full">
              <img class="h-[24px]" src="./resources/images/logo.svg" alt="" />
            </nav>
            <nav>
              <div class="lg:flex hidden gap-8">
                <a class="text-white" href="">Documentação</a>
                <a class="text-white" href="">Segurança</a>
                <a class="text-white" href="">Status</a>
                <a class="text-white" href="">Contato</a>
              </div>
            </nav>
          </div>
        </section>
        <section
          class="h-[40%] border-t border-solid justify-center flex items-center border-zinc-900 bg-[#0D0D0D]"
        >
          <div class="flex w-full px-[70px] justify-between max-w-[1440px]">
            <nav class="flex items-center justify-between h-full">
              <a class="text-white" href="">© 2024 inTask, Inc.</a>
            </nav>
            <nav>
              <div class="lg:flex gap-8 hidden">
                <a class="text-white" href="">Termos</a>
                <a class="text-white" href="">Politicas</a>
                <a class="text-white" href="">Cookies</a>
              </div>
            </nav>
          </div>
        </section>
      </footer>
    </main>
  </body>
</html> -->
