const receiveBox = document.querySelector("#receivebox");
let id = 0;  // Inicializa o ID que será usado para cada tarefa

// Função para adicionar ou esconder a caixa de texto
const addText = () => document.querySelector(".bodyText").classList.remove("hidden");
const backNotes = () => document.querySelector(".bodyText").classList.add("hidden");

let textIn = document.querySelector("textarea");
let titleIn = document.querySelector(".title");

// Função para adicionar tarefa
const addTask = () => {
  if (textIn.value === "" || titleIn.value === "") {
    alert("Você deve digitar algo");
  } else {
    document.querySelector(".bodyText").classList.add("hidden");
    newTask();
  }
};

// Função para criar uma nova tarefa
const newTask = () => {
  id++;

  const data = new Date();
  const months = ["Jan", "Fev", "Mar", "Abr", "Mai", "Jun", "Jul", "Ago", "Set", "Out", "Nov", "Dez"];
  let month = months[data.getMonth()];

  const newNote = {
    id: id,
    title: titleIn.value,
    content: textIn.value,
    date: `${month} ${data.getDate()}, ${data.getFullYear()}`
  };

  renderTask(newNote);
  saveTask(newNote);
  textIn.value = "";  // Limpa o campo de texto após salvar
  titleIn.value = "";  // Limpa o campo de título após salvar
};

// Função para salvar a tarefa no Local Storage
const saveTask = (task) => {
  const tasks = JSON.parse(localStorage.getItem("tasks")) || [];
  tasks.push(task);
  localStorage.setItem("tasks", JSON.stringify(tasks));
};

// Função para renderizar uma tarefa na tela
const renderTask = (task) => {
  let taskDiv = document.createElement("div");
  taskDiv.classList.add("textbox");
  receiveBox.appendChild(taskDiv);

  let footer = document.createElement("footer");
  footer.classList.add("footerBox");
  taskDiv.appendChild(footer);

  let header = document.createElement("header");
  header.classList.add("headerBox");
  taskDiv.appendChild(header);

  let title = document.createElement("span");
  title.classList.add("titleBox");
  title.textContent = task.title;
  header.appendChild(title);

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
  text.textContent = task.content;
  receiveText.appendChild(text);
};

// Função para carregar tarefas do Local Storage quando a página é carregada
const loadTasks = () => {
  const tasks = JSON.parse(localStorage.getItem("tasks")) || [];
  tasks.forEach(renderTask);
};

// Carrega as tarefas armazenadas ao carregar a página
document.addEventListener("DOMContentLoaded", loadTasks);
