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
        updateTask(editingTaskId);
      } else {
        newTask();
      }
    }
  };

  // Função para criar uma nova tarefa
  const newTask = () => {
    id++;

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

    renderTask(newNote);
    saveTask(newNote);
    clearInputs();
  };

  // Função para salvar a tarefa no Local Storage
  const saveTask = (task) => {
    const tasks = JSON.parse(localStorage.getItem("tasks")) || [];
    tasks.push(task);
    localStorage.setItem("tasks", JSON.stringify(tasks));
  };

  document.getElementById("addRemovalIcons").addEventListener("click", () => {
    document.querySelectorAll(".headerBox").forEach((header) => {
      let taskId = header.parentNode.getAttribute("data-task-id"); // Assume que o div da tarefa é o elemento pai do header
      if (!header.querySelector(".x")) {
        // Evita adicionar múltiplos ícones
        addRemoveIcon(header, taskId);
      }
    });
  });

  const addRemoveIcon = (header, taskId) => {
    let x = document.createElement("img");
    x.classList.add("x");
    x.src = "/resources/images/x.svg";
    x.addEventListener("click", () => remover(taskId));
    header.appendChild(x);
  };

  const remover = (taskId) => {
    // Remove a tarefa da interface
    let taskElement = document.querySelector(`div[data-task-id='${taskId}']`);
    if (taskElement) taskElement.remove();

    // Remove a tarefa do Local Storage
    let tasks = JSON.parse(localStorage.getItem("tasks")) || [];
    tasks = tasks.filter((task) => task.id !== parseInt(taskId));
    localStorage.setItem("tasks", JSON.stringify(tasks));
  };

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
    calendar.src = "/resources/images/calendar.svg";
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
    loadTasks();
  };

  // Função para limpar os campos de entrada
  const clearInputs = () => {
    textIn.value = "";
    titleIn.value = "";
  };

  // Função para carregar tarefas do Local Storage quando a página é carregada
  const loadTasks = () => {
    const tasks = JSON.parse(localStorage.getItem("tasks")) || [];
    tasks.forEach(renderTask);
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
