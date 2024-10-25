const receiveBox = document.querySelector("#receivebox");
let id = 0; // Inicializa o ID que será usado para cada tarefa
let editingTaskId = null; // Variável para rastrear a tarefa que está sendo editada
const usuarioId = 1; // ID do usuário autenticado (isso deve vir da sessão ou do login)

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
      updateTask(editingTaskId);
    } else {
      newTask();
    }
  }
};

// Função para criar uma nova tarefa
const newTask = () => {
  id++; // Incrementa o ID

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
    id: id,
    title: titleIn.value,
    content: textIn.value,
    date: `${month} ${data.getDate()}, ${data.getFullYear()}`,
  };
  // Adiciona a nova tarefa ao banco de dados
  adicionarNota(newNote.content); // Chamada para adicionar nota no banco
  renderTask(newNote);
  clearInputs();
};

// Função para adicionar nota no banco de dados
function adicionarNota(conteudo) {
  console.log(`Adicionando nota: ${conteudo} para o usuário ${usuarioId}`);
  fetch("notas.php", {
    method: "POST",
    headers: {
      "Content-Type": "application/x-www-form-urlencoded",
    },
    body: `usuario_id=${usuarioId}&conteudo=${encodeURIComponent(conteudo)}`,
  })
    .then((response) => response.json())
    .then((data) => {
      console.log(data.message); // Exibe a mensagem de sucesso
      carregarNotas(); // Atualiza a lista de notas após adicionar uma nova
    })
    .catch((error) => console.error("Erro:", error));
}

// Função para carregar notas do banco de dados
function carregarNotas() {
  console.log("Carregando notas do banco de dados...");
  fetch(`notas.php?usuario_id=${usuarioId}`)
    .then((response) => response.json())
    .then((notas) => {
      // Limpa a interface antes de exibir novas notas
      receiveBox.innerHTML = ""; // Limpa o conteúdo anterior

      notas.forEach((nota) => {
        const notaTask = {
          id: nota.id, // ID da nota do banco de dados
          title: nota.titulo, // Título da nota
          content: nota.conteudo, // Conteúdo da nota
          date: nota.data, // Data da nota
        };
        renderTask(notaTask); // Renderiza a nota como tarefa
      });
    })
    .catch((error) => console.error("Erro:", error));
}

// Função para renderizar uma tarefa na tela
const renderTask = (task) => {
  let taskDiv = document.createElement("div");
  taskDiv.classList.add("textbox");
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
  text.classList.add("textBox");
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

const saveTaskToDatabase = async (task) => {
  const response = await fetch("caminho/para/seu/script.php", {
    method: "POST",
    headers: {
      "Content-Type": "application/json",
    },
    body: JSON.stringify({
      usuario_id: task.usuario_id, // ID do usuário autenticado
      conteudo: task.content,
    }),
  });

  const result = await response.json();
  console.log(result); // Para depurar o que está sendo retornado
};

// Função para atualizar a tarefa existente
const updateTask = (taskId) => {
  const tasks = JSON.parse(localStorage.getItem("tasks")) || [];
  const taskIndex = tasks.findIndex((task) => task.id === taskId);

  if (taskIndex !== -1) {
    tasks[taskIndex].title = titleIn.value;
    tasks[taskIndex].content = textIn.value;

    localStorage.setItem("tasks", JSON.stringify(tasks));
    refreshTasks();
    clearInputs();
  }

  editingTaskId = null; // Limpar o ID da tarefa em edição
};

// Função para recarregar as tarefas na interface
const refreshTasks = () => {
  receiveBox.innerHTML = "";
  carregarNotas(); // Chama para recarregar as notas do banco de dados
};

// Função para limpar os campos de entrada
const clearInputs = () => {
  textIn.value = "";
  titleIn.value = "";
};

// Função para carregar tarefas do Local Storage quando a página é carregada
const loadTasks = () => {
  carregarNotas(); // Carrega notas do banco de dados ao invés de Local Storage
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

// localStorage.clear();
