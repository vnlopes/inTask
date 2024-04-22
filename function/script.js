const receiveBox = document.querySelector("#receivebox");
let count = 0;

const addText = () => {
  document.querySelector(".bodyText").classList.remove("hidden");
};

const backNotes = () => {
  document.querySelector(".bodyText").classList.add("hidden");
};

let textIn = document.querySelector("textarea");
let titleIn = document.querySelector(".title");

const addTask = () => {
  if (textIn.value == "" || titleIn.value == "") {
    alert("Você deve digitar algo");
  } else {
    document.querySelector(".bodyText").classList.add("hidden");
    newTask();
  }
};
let id = 0;

const newTask = () => {
  id++;


  let data = new Date();
  let month = data.getMonth();
  
  switch (month) {
    case 1:
      month = "Jan";
      break;
    case 2:
      month = "Fev";
      break;
    case 3:
      month = "Mar";
      break;
    case 4:
      month = "Abr";
      break;
    case 5:
      month = "Mai";
      break;
    case 6:
      month = "Jun";
      break;
    case 7:
      month = "Jul";
      break;
    case 8:
      month = "Ago";
      break;
    case 9:
      month = "Set";
      break;
    case 10:
      month = "Out";
      break;
    case 11:
      month = "Nov";
      break;
    case 12:
      month = "Dez";
      break;
  }
  
  let newNote = {
    id: id,
    title: titleIn.value,
    content: textIn.value,
    data: month + " " + data.getDate(4, 2, 4) + ", " + data.getFullYear(),
  };

  let newNoteJSON = JSON.stringify(newNote)

  console.log(newNoteJSON);

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
  title.innerHTML = newNote.title;
  header.appendChild(title);

  let x = document.createElement("img");
  x.classList.add("x");
  x.src = "/resources/images/x.svg";
  header.appendChild(x);

  let calendar = document.createElement("img");
  calendar.src = "/resources/images/calendar.svg";
  calendar.style = "width: 15px;";
  footer.appendChild(calendar);


  let date = document.createElement("span");
  date.classList.add("date");
  date.innerHTML =
    newNote.data
  footer.appendChild(date);

  let receiveText = document.createElement("main");
  receiveText.classList.add("receiveText");
  task.appendChild(receiveText);

  let myText = newNote.content;
  let text = document.createElement("span");
  text.classList.add("textBox");
  text.innerHTML = myText.slice(0, 156);
  receiveText.appendChild(text);

  const saveData = () => {
    localStorage.setItem("boxText", newNoteJSON);
  };
  
  saveData();
};


  // const showTask = () => {
  //   newNoteJSON = localStorage.getItem("boxText");
  // };
  // showTask();
// localStorage.clear()

const x = document.querySelectorAll(".textbox");

const remove = () => {
  x.forEach((textBox) => {
    textBox.classList.add("x");
  });
};
