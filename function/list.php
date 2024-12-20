<?php
session_start();
// var_dump($_SESSION);
// Verifica se o usuário está logado
if (!isset($_SESSION['username'])) {
    // Redireciona para a página de login se não estiver logado
    header("Location: login.php");
    exit();
}

$userId = $_SESSION['user_id'];

// Armazena o nome de usuário na variável
$username = $_SESSION['username'];
$user_id = $_SESSION['user_id'];

// echo $user_id

?>

<script>
    const currentUserName = <?php echo json_encode($username); ?>;  
    const currentUserId = <?php echo json_encode($user_id); ?>;  
</script>


<!DOCTYPE html>
<html lang="pt-br">
  <head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <title>inTask - Suas Notas</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <link rel="stylesheet" href="../resources/css/style.css" />
    <link
      rel="shortcut icon"
      href="..//resources/images/logo-icon.svg"
      type="image/x-icon"
    />
    <style>
        .popup {
            display: none;
            position: absolute;
            top: 80px;
            background-color: rgba(0, 0, 0, 0.8);
            color: white;
            padding: 20px;
            border-radius: 5px;
            z-index: 1000;
        }
    </style>

    <script>
    //     <?php echo json_encode($userId); ?>;
    // </script>

    <script>
        // Função para mostrar o pop-up
        function showPopup() {
            const popup = document.getElementById('welcomePopup');
            popup.style.display = 'block';
            setTimeout(() => {
                popup.style.display = 'none';
            }, 5000); // Dura 5 segundos
        }

        window.onload = showPopup; // Chama a função ao carregar a página
    </script>
  </head>
  <body
    class="bg-[#040404] relative min-h-screen overflow-x-hidden flex items-center flex-col"
  >
    <img
      src="..//resources/images/bg-grid-intask.svg"
      class="absolute select flex none z-[-1]"
      alt=""
    />

    <main class="min-h-screen flex flex-col items-center overflow-x-hidden w-full">

      <header
        class="justify-center items-center relative bg-[#040404] w-full flex h-[70px] border-solid boder-1 border-b border-zinc-900"
      >
        <nav
          class="center-header px-[70px] relative max-w-[1440px] w-full flex items-center justify-between"
        >
          <a href="../index.html">
            <img src="..//resources/images/logo.svg" alt="" />
          </a>
          <ul class="links font-medium hidden md:flex gap-8">
            <a class="text-white" href="404.php">Inicio</a>
            <a class="text-white" href="404.php">Pagamento</a>
            <a class="text-white" href="404.php">Sobre</a>
            <a class="text-white" href="404.php">Contato</a>
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

      <body class="bg-black text-white flex flex-col items-center justify-center h-screen">

    <div id="welcomePopup" class="popup">
        Bem-vindo, <?php echo htmlspecialchars($username); ?>!
    </div>

      <main class="w-full min-h-[900px] mb-[200px] flex justify-center">
        <section class="w-[1440px] flex flex-col gap-6 pt-6 mt-[20px] h-full px-[70px]">
          <header class="w-full md:justify-start justify-center flex text-white text-3xl">Suas Tarefas</header>
          <header class="w-full md:justify-center items-center text-white text-xl gap-2 md:flex-row flex-col flex">
            <input
              id="searchInput"
              placeholder="Procure por tarefas ja existentes"
              class="max-w-[80%] w-full bg-[#0D0D0D] text-sm border border-solid border-[#3f3f3f] h-[42px] rounded-lg placeholder:text-sm placeholder:text-[#616161] pl-4 pb-1 focus:outline-none"
              type="text"
            />
            <div class="flex gap-2">
              <button
              onclick="addText()"
              class="bg-white p-2 hover:bg-zinc-200 rounded-lg text-black font-medium text-sm h-[42px] px-4 flex gap-2 items-center"
            >
              Adicionar tarefa <span>+</span>
            </button>

            <button onclick="addRemoveIcon()" class="bg-[#520005] rounded-lg text-black font-medium justify-center border border-solid border-[#FF0000] text-sm w-[42px] h-[42px] flex gap-2 items-center"
            >
              <img src="..//resources/images/trash.svg" alt="" />
            </button>
            </div>
          </header>

          <!-- notas -->

          <section
            id="receivebox"
            class="flex gap-6 lg:px-20 flex-col sm:flex-col md:flex-row w-full flex-wrap"
          >
          </section>

          <!-- fim notas -->
        </section>
      </main>

'      <footer class="absolute flex flex-col bottom-0 w-full h-[157px]">
        <section
          class="h-[60%] border-t border-solid justify-center flex items-center border-zinc-900 bg-[#040404]"
        >
          <div class="flex w-full px-[70px] justify-between max-w-[1440px]">
            <nav class="flex items-center justify-between h-full">
              <img class="h-[24px]" src="..//resources/images/logo.svg" alt="" />
            </nav>
            <nav>
              <div class="md:flex gap-8 hidden">
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
          <div class="flex w-full px-[70px] md:justify-between max-w-[1440px]">
            <nav class="flex items-center justify-between h-full">
              <a class="text-white" href="">© 2024 inTask, Inc.</a>
            </nav>
            <nav>
              <div class="lg:flex hidden gap-8">
                <a class="text-white" href="">Termos</a>
                <a class="text-white" href="">Politicas</a>
                <a class="text-white" href="">Cookies</a>
              </div>
            </nav>
          </div>
        </section>
      </footer>'

      <section
        class="bodyText flex flex-col w-screen absolute h-full hidden bg-[#040404]"
      >
        <header
          class="justify-center items-center relative bg-[#040404] w-full flex h-[70px] border-solid boder-1 border-b border-zinc-900"
        >
          <nav
            class="center-header px-[70px] relative max-w-[1440px] w-full flex items-center justify-between"
          >
            <a href="../index.html">
              <img src="..//resources/images/logo.svg" class="lg:h-auto h-[20px]" alt="" />
            </a>
            <ul class="links font-medium items-center flex gap-2 lg:gap-8">
              <a class="text-white lg:flex hidden" href="/index.html">Inicio</a>
              <div class="lg:h-[30px] h-[15px] w-[1px] bg-white"></div>
              <a class="text-white" href="" onclick="backNotes()">Notas</a>
            </ul>

            <div class="flex gap-2">
              <a
                onclick="addTask()"
                class="h-[36px] hover:scale-[.98] hover:bg-gray-200 text-xl transition-[-3s] flex items-center justify-center rounded-[8px] w-[36px] cursor-pointer bg-white"
              >
                +
              </a>

              <a
                href="https://github.com/vnlopes/"
                target="_blank"
                class="h-[36px] hover:scale-[.98] hover:bg-gray-200 lg:flex hidden transition-[-3s] flex items-center justify-center rounded-[8px] w-[36px] cursor-pointer bg-white"
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
            </div>
          </nav>
        </header>

        <input
          placeholder="Title"
          maxlength="23"
          class="title font-semibold bg-transparent mt-3 px-[72px] outline-none text-white text-2xl"
          type="text"
        />

        <textarea
          class="h-full px-[72px] max-w-[1440px] w-full bg-transparent text-white p-4 border-none resize-none outline-none"
          name=""
          id=""
          cols="30"
          rows="10"
        ></textarea>

      </section>

      <div class="modal w-screen h-screen fixed z-30 hidden">

      <div class="max-w-[400px] max-h-[300px] rounded border border-zinc-700 absolute top-0 bottom-32 left-0 right-0 m-auto backdrop-blur-md bg-black/60 flex flex-col justify-center items-center">
      <form id="priorityForm" class="gap-[16px] flex flex-col">
  <span class="text-white text-2xl font-semi-bold">Qual a prioridade da tarefa?</span>
  <hr class="opacity-[30%]">
  <div>
    <div class="radio-button urgent text-zinc-500 text-xl">
      <input type="radio" class="w-4 h-4" name="priority" id="urgent" value="Urgente" />
      <span for="urgent">Urgente</span>
    </div>

    <div class="radio-button green text-zinc-500 text-xl">
      <input type="radio" class="w-4 h-4" name="priority" id="green" value="Deboa" />
      <span for="green">Ainda há tempo</span>
    </div>

    <div class="radio-button yellow text-zinc-500 text-xl">
      <input type="radio" class="w-4 h-4" name="priority" id="yellow" value="Atenção" />
      <span for="yellow">Atenção</span>
    </div>
  </div>

  <button class="py-3 px-6 bg-white hover:bg-zinc-200 transition-[.2s] rounded-[8px] w-fit h-fit font-medium text-lg" type="submit">Salvar Prioridade</button>
</form>
      </div>
      </div>
    </main>
  
  </body>
  <script src="..//function/script.js"></script>
</html>
