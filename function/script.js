const receiveBox = document.querySelector("#receivebox");
let count = 0;

const addTask = () => {
  count++;
  if (count == 3) {
    receiveBox.classList.add("justify-center");
  }

  let task = document.createElement("div");
  task.classList.add("textbox");
  receiveBox.appendChild(task);

  let footer = document.createElement("footer");
  footer.classList.add("footerBox");
  task.appendChild(footer);

  let header = document.createElement("header");
  header.classList.add("headerBox");
  task.appendChild(header);

  let title = document.createElement("span");
  title.classList.add("titleBox");
  title.innerHTML = "Title";
  header.appendChild(title);

  let calendar = document.createElement("img");
  calendar.src = "/resources/images/calendar.svg";
  calendar.style = "width: 15px;";
  footer.appendChild(calendar);

  let receiveText = document.createElement('main');
  receiveText.classList.add('receiveText')
  task.appendChild(receiveText)

  let text = document.createElement("span");
  text.classList.add("textBox");
  text.innerHTML =
    "Bem-vindo ao inTask! Estamos extremamente felizes em tê-lo(a) a bordo, pronto(a) para começar...";
  receiveText.appendChild(text);
};
