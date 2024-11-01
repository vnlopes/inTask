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



// const addTask = (title, content) => {
//   fetch(`notes.php?action=add&user_id=${currentUserId}&title=${encodeURIComponent(title)}&content=${encodeURIComponent(content)}`)
//       .then(response => response.json())
//       .then(data => {
//           console.log(data);
//           // Aqui você pode adicionar lógica para atualizar a interface após a inserção
//       })
//       .catch(error => console.error("Erro ao adicionar nota:", error));
// };

// Função para criar uma nova tarefa
// Função para criar uma nova tarefa
const newTask = () => {
  // Não precisa incrementar o ID aqui se o ID é gerado pelo banco de dados
  const title = titleIn.value;
  const content = textIn.value;

  // Faz uma requisição para adicionar a nova nota no banco de dados
  fetch(`notes.php?action=add&user_id=${currentUserId}&title=${encodeURIComponent(title)}&content=${encodeURIComponent(content)}`, {
    method: 'GET' // Considere usar POST para melhor prática
  })
    .then(response => response.json())
    .then(data => {
      if (data.status === 'success') {
        // Se a adição foi bem-sucedida, renderize a nova nota
        const data = new Date();
        const months = [
          "Jan", "Fev", "Mar", "Abr", "Mai", "Jun",
          "Jul", "Ago", "Set", "Out", "Nov", "Dez"
        ];
        let month = months[data.getMonth()];

        const newNote = {
          id: data.id, // O ID pode ser retornado do banco de dados, se necessário
          title: title,
          content: content,
          date: `${month} ${data.getDate()}, ${data.getFullYear()}`
        };

        renderTask(newNote); // Renderiza a nova tarefa
        clearInputs(); // Limpa os campos de entrada
      } else {
        alert(data.message); // Mensagem de erro se a inserção falhar
      }
    })
    .catch(error => console.error("Erro ao adicionar nota:", error));
};


// Função para salvar a tarefa no Local Storage
const saveTask = (task) => {
  console.log("Enviando nota:", {
    action: "add",
    user_id: currentUserId,
    title: task.title,
    content: task.content,
  });

  fetch("notes.php", {
    method: "POST",
    headers: {
      "Content-Type": "application/x-www-form-urlencoded",
    },
    body: new URLSearchParams({
      action: "add",
      user_id: currentUserId, // Substitua por seu mecanismo de autenticação
      title: task.title,
      content: task.content,
    }),
  });
};

const addRemoveIcon = (header, taskId) => {
  let x = document.createElement("img");
  x.classList.add("x");
  x.src = "/resources/images/x.svg";
  x.addEventListener("click", () => remover(taskId));
  header.appendChild(x);
};

const remover = (taskId) => {
  console.log("Enviando nota:", {
    action: "add",
    user_id: currentUserId,
    title: task.title,
    content: task.content,
  });

  fetch("notes.php", {
    method: "POST",
    headers: {
      "Content-Type": "application/x-www-form-urlencoded",
    },
    body: new URLSearchParams({
      action: "delete",
      user_id: currentUserId,
      id: taskId,
    }),
  }).then(() => {
    refreshTasks();
  });
};

const renderTask = (task) => {
  let taskDiv = document.createElement("div");
  taskDiv.classList.add("textbox", "border-none");
  taskDiv.setAttribute("data-task-id", task.id); // Atributo para identificar facilmente a tarefa
  receiveBox.appendChild(taskDiv);

  let header = document.createElement("header");
  header.classList.add("headerBox");
  taskDiv.appendChild(header);

  let title = document.createElement("span");
  title.classList.add("titleBox");
  title.textContent = task.title;
  header.appendChild(title);

  let footer = document.createElement("footer");
  footer.classList.add("footerBox");
  taskDiv.appendChild(footer);

  let calendar = document.createElement("img");
  calendar.src = "..//resources/images/calendar.svg";
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
    editingTaskId = task.id; // Definir o ID da tarefa que está sendo editada
  });
};

// Função para atualizar a tarefa existente
const updateTask = (taskId) => {
  console.log("Enviando nota:", {
    action: "add",
    user_id: currentUserId,
    title: task.title,
    content: task.content,
  });

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
  fetch("notes.php?action=get&user_id=" + currentUserId) // Alterado de 'username' para 'user_id'
    .then((response) => response.json())
    .then((data) => {
      receiveBox.innerHTML = ""; // Limpa a área de exibição de notas
      data.forEach(renderTask); // Renderiza cada nota do banco de dados
    })
    .catch((error) => console.error("Erro ao carregar notas:", error));
};

// Função para filtrar e exibir tarefas com base na pesquisa
const searchTasks = (query) => {
  const tasks = JSON.parse(localStorage.getItem("tasks")) || [];
  receiveBox.innerHTML = ""; // Limpa a área de exibição de tarefas

  const filteredTasks = tasks.filter(
    (task) =>
      task.title.toLowerCase().includes(query.toLowerCase()) ||
      task.content.toLowerCase().includes(query.toLowerCase())
  );

  filteredTasks.forEach(renderTask); // Renderiza apenas as tarefas filtradas
};

// Evento para capturar a pesquisa em tempo real
const searchInput = document.querySelector("#searchInput"); // Supondo que o campo de pesquisa tenha o ID "searchInput"
searchInput.addEventListener("input", (e) => {
  searchTasks(e.target.value);
});

// Carrega as tarefas armazenadas ao carregar a página
document.addEventListener("DOMContentLoaded", loadTasks);

// localStorage.clear()
