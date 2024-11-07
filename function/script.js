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

// Função para adicionar tarefa
const addTask = () => {
  if (textIn.value === "" || titleIn.value === "") {
    alert("Você deve digitar algo");
  } else {
    document.querySelector(".bodyText").classList.add("hidden");

    if (editingTaskId) {
      updateTask(editingTaskId); // Chama para atualizar a tarefa
    } else {
      newTask(); // Chama para adicionar uma nova tarefa
    }

    // Limpa os campos de entrada após a ação
    titleIn.value = "";
    textIn.value = "";
  }
};

// Função para criar uma nova tarefa
const newTask = () => {
  const title = titleIn.value;
  const content = textIn.value;

  fetch(
    `notes.php?action=add&user_id=${currentUserId}&title=${encodeURIComponent(
      title
    )}&content=${encodeURIComponent(content)}`,
    {
      method: "GET",
    }
  )
    .then((response) => response.json())
    .then((data) => {
      if (data.status === "success") {
        const data = new Date();
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
        let month = months[data.getMonth()];

        const newNote = {
          id: data.id, // O ID pode ser retornado do banco de dados, se necessário
          title: title,
          content: content,
          date: `${month} ${data.getDate()}, ${data.getFullYear()}`,
        };

        renderTask(newNote); // Renderiza a nova tarefa
        clearInputs(); // Limpa os campos de entrada
      } else {
        alert(data.message); // Mensagem de erro se a inserção falhar
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
http://localhost/inTask/function/notes.php?action=delete&id=1
function remover(id) {
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

  // Criação do header
  let header = document.createElement("header");
  header.classList.add("headerBox");
  taskDiv.appendChild(header);

  // Adiciona o título da tarefa no header
  let title = document.createElement("span");
  title.classList.add("titleBox");
  title.textContent = task.title;
  header.appendChild(title);

  let footer = document.createElement("footer");
  footer.classList.add("footerBox");
  taskDiv.appendChild(footer);

  let calendar = document.createElement("img");
  calendar.src = "../resources/images/calendar.svg";
  calendar.style.width = "15px";
  footer.appendChild(calendar);

  let date = document.createElement("span");
  date.classList.add("date");
  date.textContent = task.date;
  footer.appendChild(date);

  let receiveText = document.createElement("main");
  receiveText.classList.add("receiveText");
  taskDiv.appendChild(receiveText);

  let text = document.createElement("span");
  text.classList.add("textBox", "border-none");
  text.textContent = task.content.slice(0, 156);
  receiveText.appendChild(text);

  // Evento de clique para carregar o conteúdo da tarefa na área de edição
  receiveText.addEventListener("click", () => {
    document.querySelector(".bodyText").classList.remove("hidden");
    titleIn.value = task.title;
    textIn.value = task.content;
    editingTaskId = task.id; // Define o ID da tarefa que está sendo editada

    // Adiciona o ícone de remoção
    if (!header.querySelector(".x")) {
      addRemoveIcon(header, task.id);
    }
  });
};

const addRemoveIcon = (header, taskId) => {
  // Selecione todos os headers das tasks
  const headers = document.querySelectorAll(".textbox header");

  headers.forEach((header) => {
    // Verifica se o ícone de remoção já existe
    if (!header.querySelector(".x")) {
      let x = document.createElement("img");
      x.classList.add("x");
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
  fetch("notes.php", {
    method: "POST",
    headers: {
      "Content-Type": "application/x-www-form-urlencoded",
    },
    body: new URLSearchParams({
      action: "update",
      user_id: currentUserId,
      id: taskId,
      title: titleIn.value,
      content: textIn.value,
    }),
  }).then(() => {
    refreshTasks();
    clearInputs();
  });

  editingTaskId = null;
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
