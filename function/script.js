const receiveBox = document.querySelector('#receivebox');
let count = 0; 


const addTask = () => {
    count ++
    if(count == 3){
        receiveBox.classList.add('justify-center')
    }

    let task = document.createElement('div');
    task.innerHTML = 'Cala a boca nengue';
    task.style = 'color: white;'
    task.classList.add('textbox');
    receiveBox.appendChild(task);
}