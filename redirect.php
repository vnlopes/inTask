<!DOCTYPE html>
<html lang="pt-BR">
  <head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <title>inTask - Login</title>
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
        <h1 class="text-2xl text-white font-bold mb-4 text-center">Login</h1>
        <form action="login.php" method="POST" class="p-6 rounded-lg">
          <h2 class="text-white text-2xl mb-4">Faça Login</h2>
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
      <?php 
      require_once 'footer.php'
    ?>
    </main>
  </body>
</html>
