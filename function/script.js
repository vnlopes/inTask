const receiveBox = document.querySelector("#receivebox");
let id = 0; // Inicializa o ID que será usado para cada tarefa
let editingTaskId = null; // Variável para rastrear a tarefa que está sendo editada
// Função para adicionar ou esconder a caixa de texto
const addText = () =>
  document.querySelector(".bodyText").classList.remove("hidden");
const backNotes = () =>
  document.querySelector(".bodyText").classList.add("hidden");

let textIn = document.querySelector("textarea");
let titleIn = document.querySelector(".title");

console.log("currentUserId:", currentUserId); // Verifique se o valor é correto
const modalPriority = document.querySelector(".modal");

// Função para adicionar tarefa
const addTask = () => {
  modalPriority.classList.remove("hidden"); // Exibe o modal de prioridade
};

// Função que será chamada quando o formulário for enviado
const handleSubmit = (event) => {
  event.preventDefault(); // Previne o envio tradicional do formulário (recarregamento da página)
  modalPriority.classList.add("hidden"); // Exibe o modal de prioridade
  // Verifica se os campos de título e texto estão preenchidos
  if (textIn.value === "" || titleIn.value === "") {
    alert("Você deve digitar algo");
    return; // Impede a execução do código abaixo
  }

  // Verifica se uma prioridade foi selecionada
  const priority = document.querySelector('input[name="priority"]:checked');
  if (!priority) {
    alert("Por favor, selecione uma prioridade.");
    return; // Impede a execução do código abaixo
  }

  // Se tudo estiver certo, esconde o conteúdo do corpo (pode ser parte do modal ou da tela)
  document.querySelector(".bodyText").classList.add("hidden");

  // Lógica para adicionar ou atualizar a tarefa com base na prioridade
  if (editingTaskId) {
    updateTask(editingTaskId); // Chama para atualizar a tarefa
  } else {
    newTask(); // Chama para adicionar uma nova tarefa
  }

  // Limpa os campos de entrada após a ação
  titleIn.value = "";
  textIn.value = "";
};

// Obtém o formulário
const form = document.getElementById("priorityForm");

// Adiciona o ouvinte de evento para o envio do formulário
form.addEventListener("submit", handleSubmit);

// Função para criar uma nova tarefa
const newTask = () => {
  const title = titleIn.value;
  const content = textIn.value;
  const priority = document.querySelector(
    'input[name="priority"]:checked'
  ).value;

  fetch(
    `notes.php?action=add&user_id=${currentUserId}&title=${encodeURIComponent(
      title
    )}&content=${encodeURIComponent(content)}&priority=${encodeURIComponent(
      priority
    )}`,
    {
      method: "GET",
    }
  )
    .then((response) => response.json())
    .then((data) => {
      if (data.status === "success") {
        const date = new Date();
        const months = [
          "Jan",
          "Fev",
          "Mar",
          "Abr",
          "Mai",
          "Jun",
          "Jul",
          "Ago",
          "Set",
          "Out",
          "Nov",
          "Dez",
        ];
        const month = months[date.getMonth()];

        const newNote = {
          id: data.id,
          title: title,
          content: content,
          date: `${month} ${date.getDate()}, ${date.getFullYear()}`,
          priority: priority,
        };

        renderTask(newNote);
        clearInputs();
        modalPriority.classList.add("hidden"); // Oculta o modal após salvar
      } else {
        alert(data.message);
      }
    })
    .catch((error) => console.error("Erro ao adicionar nota:", error));
};

// Função para salvar a tarefa no Local Storage
const saveTask = (task) => {
  fetch("notes.php", {
    method: "POST",
    headers: {
      "Content-Type": "application/x-www-form-urlencoded",
    },
    body: new URLSearchParams({
      action: "add",
      user_id: currentUserId,
      title: task.title,
      content: task.content,
    }),
  });
};
//localhost/inTask/function/notes.php?action=delete&id=1
http: function remover(id) {
  fetch(`notes.php?action=delete&id=${id}`, {
    method: "GET",
  })
    .then((response) => response.json()) // Espera um JSON como resposta
    .then((data) => {
      if (data.status === "success") {
        console.log("Tarefa removida com sucesso");
        // Remover a tarefa da lista ou fazer outra ação
        document.querySelector(`[data-task-id="${id}"]`).remove(); // Remove o elemento da interface
      } else {
        console.log("Erro ao remover a tarefa:", data.message);
      }
    })
    .catch((error) => {
      console.error("Erro na requisição:", error);
    });
}

const renderTask = (task) => {
  let taskDiv = document.createElement("div");
  taskDiv.classList.add("textbox", "border-none");
  taskDiv.setAttribute("data-task-id", task.id);
  receiveBox.appendChild(taskDiv);

  // Header com título
  let header = document.createElement("header");
  header.classList.add("headerBox");
  taskDiv.appendChild(header);

  let title = document.createElement("span");
  title.classList.add("titleBox");
  title.textContent = task.title;
  header.appendChild(title);

  // Rodapé com data e prioridade
  let footer = document.createElement("footer");
  footer.classList.add("footerBox", "justify-between");
  taskDiv.appendChild(footer);

  let divCalDate = document.createElement("div");
  divCalDate.classList.add("flex", "gap-[15px]");
  footer.appendChild(divCalDate);

  let calendar = document.createElement("img");
  calendar.src = "../resources/images/calendar.svg";
  calendar.style.width = "15px";
  divCalDate.appendChild(calendar);

  let date = document.createElement("span");
  date.classList.add("date");
  date.textContent = task.date;
  divCalDate.appendChild(date);

  // Indicador de prioridade
  let priorityIndicator = document.createElement("span");
  // priorityIndicator.classList.add(
  //   "w-[15px]",
  //   "h-[15px]",
  //   "bg-[#ff220080]",
  //   "rounded-full",
  //   "border-solid",
  //   "border-[#ff2200]",
  //   "border-2"
  // );
  priorityIndicator.classList.add(
    "priorityIndicator",
    `priority-${task.priority}`
  );
  priorityIndicator.textContent = task.priority;
  footer.appendChild(priorityIndicator);

  // let radio = document.createElement("div");
  // radio.classList.add(
  //   "w-[15px]",
  //   "h-[15px]",
  //   "bg-[#ff220080]",
  //   "rounded-full",
  //   "border-solid",
  //   "border-[#ff2200]",
  //   "border-2"
  // );
  // footer.appendChild(radio);

  // Exibe o conteúdo da tarefa
  let receiveText = document.createElement("main");
  receiveText.classList.add("receiveText", "h-full");
  taskDiv.appendChild(receiveText);

  let text = document.createElement("span");
  text.classList.add("textBox", "border-none");
  text.textContent = task.content.slice(0, 156);
  receiveText.appendChild(text);

  receiveText.addEventListener("click", () => {
    document.querySelector(".bodyText").classList.remove("hidden");
    titleIn.value = task.title;
    textIn.value = task.content;
    editingTaskId = task.id;
  });

  title.addEventListener("click", () => {
    document.querySelector(".bodyText").classList.remove("hidden");
    titleIn.value = task.title;
    textIn.value = task.content;
    editingTaskId = task.id;
  });
};

const addRemoveIcon = (header, taskId) => {
  // Selecione todos os headers das tasks
  const headers = document.querySelectorAll(".textbox header");

  headers.forEach((header) => {
    // Verifica se o ícone de remoção já existe
    const existingIcon = header.querySelector(".x");

    if (existingIcon) {
      // Se o ícone de remoção existe, remova-o
      existingIcon.remove();
    } else {
      // Se o ícone de remoção não existe, cria e adiciona
      let x = document.createElement("img");
      x.classList.add("x", "absolute", "z-10");
      x.src = "..//resources/images/x.svg";

      // Adiciona o evento de remoção
      x.addEventListener("click", (e) => {
        const taskId = header.closest(".textbox").getAttribute("data-task-id");
        remover(taskId); // Passa tanto o ID da tarefa quanto o user_id para a função de remoção
      });

      header.appendChild(x); // Adiciona o ícone ao header
    }
  });
};

// Função para atualizar a tarefa existente
const updateTask = (taskId) => {
  console.log("Updating task with ID:", taskId);

  // Preparar URL para GET
  const url = `notes.php?action=update&user_id=${currentUserId}&id=${taskId}&title=${encodeURIComponent(
    titleIn.value
  )}&content=${encodeURIComponent(textIn.value)}`;

  fetch(url)
    .then((response) => response.json())
    .then((data) => {
      console.log("Update response:", data);
      if (data.status === "success") {
        // Atualiza a tarefa no frontend
        refreshTasks();
        clearInputs();
      } else {
        console.error("Erro ao atualizar a tarefa:", data.message);
      }
    })
    .catch((error) => {
      console.error("Erro na requisição:", error);
    });

  editingTaskId = null; // Resetar o ID de edição
};

// Função para recarregar as tarefas na interface
const refreshTasks = () => {
  receiveBox.innerHTML = "";
  loadTasks();
};

// Função para limpar os campos de entrada
const clearInputs = () => {
  titleIn.value = ""; // Limpa o campo de título
  textIn.value = ""; // Limpa o campo de conteúdo
};

// Função para carregar tarefas do Local Storage quando a página é carregada
const loadTasks = () => {
  fetch("notes.php?action=get&user_id=" + currentUserId)
    .then((response) => response.json())
    .then((data) => {
      receiveBox.innerHTML = ""; // Limpa a área de exibição de notas
      data.forEach(renderTask); // Renderiza cada nota do banco de dados
    })
    .catch((error) => console.error("Erro ao carregar notas:", error));
};

// Função para filtrar e exibir tarefas com base na pesquisa
const searchTasks = (query) => {
  fetch("notes.php?action=get&user_id=" + currentUserId)
    .then((response) => response.json())
    .then((data) => {
      const filteredTasks = data.filter(
        (task) =>
          task.title.toLowerCase().includes(query.toLowerCase()) ||
          task.content.toLowerCase().includes(query.toLowerCase())
      );
      receiveBox.innerHTML = ""; // Limpa a área de exibição de tarefas
      filteredTasks.forEach(renderTask); // Renderiza as tarefas filtradas
    })
    .catch((error) => console.error("Erro ao carregar notas:", error));
};

// Evento para capturar a pesquisa em tempo real
const searchInput = document.querySelector("#searchInput"); // Supondo que o campo de pesquisa tenha o ID "searchInput"
searchInput.addEventListener("input", (e) => {
  searchTasks(e.target.value);
});

// Carrega as tarefas armazenadas ao carregar a página
document.addEventListener("DOMContentLoaded", loadTasks);

// //salvar login:
// const login = (userId) => {
//   localStorage.setItem("currentUserId", userId); // Armazenando o ID do usuário no localStorage
//   loadTasks(); // Carrega as tarefas assim que o usuário loga
// };

// document.addEventListener("DOMContentLoaded", () => {
//   const currentUserId = localStorage.getItem("currentUserId"); // Verifica se há um usuário logado

//   if (currentUserId) {
//     console.log("Usuário logado com ID:", currentUserId);
//     loadTasks(); // Carrega as tarefas do usuário
//   } else {
//     console.log("Usuário não está logado.");
//     // Redireciona ou mostra uma tela de login
//   }
// });

// //logout

// const logout = () => {
//   localStorage.removeItem("currentUserId"); // Remove o ID do usuário do localStorage
//   window.location.reload(); // Recarrega a página ou redireciona para a tela de login
// };

// document.getElementById("logoutButton").addEventListener("click", logout);
