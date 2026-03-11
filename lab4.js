let form = document.getElementById('student-form');
let nameInput = document.getElementById('student-name');
let rollInput = document.getElementById('student-roll');
let list = document.getElementById('student-list');
let totalText = document.getElementById('total');
let attendanceText = document.getElementById('attendance');
let addBtn = document.getElementById('add-btn');
let searchBox = document.getElementById('search');

let students = [];

nameInput.addEventListener("input", function(){
addBtn.disabled = nameInput.value.trim() === "";
});

form.addEventListener("submit", function(e){

e.preventDefault();

let name = nameInput.value.trim();
let roll = rollInput.value.trim();

if(name === "" || roll === ""){
alert("Enter name and roll");
return;
}

let student = {
name: name,
roll: roll,
present:false
};

students.push(student);

nameInput.value="";
rollInput.value="";
addBtn.disabled=true;

renderStudents();

});

function renderStudents(){

list.innerHTML="";

let search = searchBox.value.toLowerCase();

let filteredStudents = students.filter(function(s){

return s.name.toLowerCase().includes(search) ||
s.roll.toLowerCase().includes(search);

});

filteredStudents.forEach(function(student,index){

let li = document.createElement("li");
li.classList.add("student-item");

let span = document.createElement("span");
span.textContent = student.roll + " - " + student.name;

let checkbox = document.createElement("input");
checkbox.type="checkbox";
checkbox.checked = student.present;

if(student.present){
li.classList.add("present");
}

checkbox.addEventListener("change",function(){

student.present = checkbox.checked;

if(student.present){
li.classList.add("present");
}else{
li.classList.remove("present");
}

updateAttendance();

});

let editBtn = document.createElement("button");
editBtn.textContent="Edit";
editBtn.classList.add("btn-edit");

editBtn.onclick = function(){

let newName = prompt("Enter new name",student.name);
let newRoll = prompt("Enter new roll",student.roll);

if(newName && newRoll){

student.name = newName;
student.roll = newRoll;

renderStudents();

}

};

let deleteBtn = document.createElement("button");
deleteBtn.textContent="Delete";
deleteBtn.classList.add("btn-delete");

deleteBtn.onclick = function(){

let confirmDelete = confirm("Do you want to delete this student?");

if(confirmDelete){

students.splice(index,1);

renderStudents();

}

};

li.appendChild(span);
li.appendChild(checkbox);
li.appendChild(editBtn);
li.appendChild(deleteBtn);

list.appendChild(li);

});

updateCount();
updateAttendance();

}

function updateCount(){

totalText.textContent = "Total students: " + students.length;

}

function updateAttendance(){

let present = students.filter(s => s.present).length;

let absent = students.length - present;

attendanceText.textContent = "Present: " + present + " , Absent: " + absent;

}

searchBox.addEventListener("input",renderStudents);

document.getElementById("sort-btn").onclick = function(){

students.sort(function(a,b){

return a.name.localeCompare(b.name);

});

renderStudents();

};

document.getElementById("highlight-btn").onclick = function(){

let items = document.querySelectorAll(".student-item");

items.forEach(function(i){

i.classList.remove("highlight");

});

if(items.length>0){

items[0].classList.add("highlight");

}

};