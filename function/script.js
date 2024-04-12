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
  if (textIn.value == '' || titleIn.value == ''){
    alert('Você deve digitar algo')
  } else{
    document.querySelector(".bodyText").classList.add("hidden");
    newTask()
  }
};


const newTask = () => {
  // count++;
  // if (count == 3) {
  //   receiveBox.classList.add("justify-center");
  // }

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

  let data = new Date(2024, 4, 12);
  let month = data.getMonth();

  if(month == 1){
    month = 'Jan'
  } else if (month == 2){
    month = 'Fev'
  } else if (month == 3){
    month = 'Mar'
  } else if (month == 4){
    month = 'Abr'
  }else if (month == 5){
    month = 'Mai'
  }else if (month == 6){
    month = 'Jun'
  }else if (month == 7){
    month = 'Jul'
  }else if (month == 8){
    month = 'Ago'
  }else if (month == 9){
    month = 'Set'
  }else if (month == 10){
    month = 'Out'
  }else if (month == 11){
    month = 'Nov'
  }else if (month == 12){
    month = 'Dez'
  }

  let date = document.createElement("span");
  date.classList.add("date");
  date.innerHTML = month  + ' ' + data.getDate() + ", " + data.getFullYear();
  footer.appendChild(date);

  let receiveText = document.createElement("main");
  receiveText.classList.add("receiveText");
  task.appendChild(receiveText);


  let myText = textIn.value

  // if(myText.length > 10){
  //   myText.slice(0, 7)

  //   console.log(myText.slice(0, 7))
  // }

  let text = document.createElement("span");
  text.classList.add("textBox");
  text.innerHTML = myText.slice(0, 156);
  receiveText.appendChild(text);


  saveData()
}


const saveData = () => {
  let textBox = document.querySelector('.textbox')
  let htmlContent = textBox.innerHTML
  localStorage.setItem('boxAll', htmlContent)
  localStorage.setItem('boxText', receiveBox)
}

const showTask = () => {
  receiveBox.innerHTML = localStorage.getItem('boxText')
  htmlContent.innerHTML = localStorage.getItem('boxAll')
}

// localStorage.clear()

showTask()


