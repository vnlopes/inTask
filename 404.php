<!DOCTYPE html>
<html lang="en">
  <head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <title>404 - Page Not Found</title>
    <script src="https://cdn.tailwindcss.com"></script>
  </head>
  <body class="bg-[#040404] relative min-h-screen flex items-center flex-col">
    <img
      src="./resources/images/bg-grid-intask.svg"
      class="absolute select none z-[-1]"
      alt=""
    />  
    <!-- Header -->
    <?php
       require_once 'header.php' 
    ?>

    <!-- Main Content -->
    <main class="min-h-screen text-center relative flex flex-col items-center w-full">
      <div class="absolute flex flex-col w-[430px] items-center top-[250px]">
      <div
        class="text-6xl font-bold text-white flex justify-center space-x-2 items-center"
      >
        <span>4</span>
        <img src="./resources/images/transferir.png" class="h-16 w-16" />
        <span>4</span>
      </div>
      <h1 class="mt-4 text-2xl text-white font-semibold">Essa página não existe.</h1>
      <p class="mt-2 text-gray-500">
        Você pode voltar e continuar usando as outras funcionalidades.
      </p>
      <a
        href="./index.html"
        class="py-3 px-6 bg-white hover:bg-zinc-200 transition-[.2s] mt-10 rounded-[8px] w-fit h-fit font-medium text-xl"
      >
        Volte para a Home
      </a>
      </div>
    </main>

    <!-- Footer -->
    <?php
       require_once 'footer.php' 
    ?>
  </body>
</html>
