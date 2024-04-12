const receiveBox = document.querySelector("#receivebox");
let count = 0;

const addText = () => {
  document.querySelector(".bodyText").classList.remove("hidden");
};

let arr = {
  text: '',
}

const backNotes = () => {
  document.querySelector(".bodyText").classList.add("hidden");
};


let textIn = document.querySelector("textarea");
let titleIn = document.querySelector(".title");



const addTask = () => {

  document.querySelector(".bodyText").classList.add("hidden");

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
  title.innerHTML = titleIn.value;
  header.appendChild(title);

  let calendar = document.createElement("img");
  calendar.src = "/resources/images/calendar.svg";
  calendar.style = "width: 15px;";
  footer.appendChild(calendar);

  let receiveText = document.createElement("main");
  receiveText.classList.add("receiveText");
  task.appendChild(receiveText);

  let text = document.createElement("span");
  text.classList.add("textBox");
  text.innerHTML = textIn.value;
  receiveText.appendChild(text);
};
