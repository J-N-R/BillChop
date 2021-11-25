$(document).ready(function() {
    
 // if bill cookie is not set, tell the user
    if(!Cookies.get("bid"))
      alert("Bill not Found");
      
 // Call the backend with the saved bid
    fetch("php/joinBill.php?bid=" + Cookies.get("bid"))
        .then(response => response.json())
        .then(data => console.log(data));
    
 // data is the JSON object. This is where I think Joao takes over
});

function updateItem(item) {
    if(item.checked)
        console.log(item.value);
    else
        console.log(item.value + ' unchecked.');
}