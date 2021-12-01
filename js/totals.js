$(document).ready(function() {

    var items;

 // if bill cookie is not set, tell the user
    if(!Cookies.get("bid"))
      alert("Bill not Found");
      
 // Call the backend with the saved bid
    fetch("php/getTotals.php?bid=" + Cookies.get("bid"))
        .then(response => response.json())
        .then(data => insertTotals(data));
    
});

function insertTotals(items) {
    console.log(items);
    var totals = $("#totals");

 // Loop through each person's totals and insert
    for(var i = 0; i < items.length; i++) {
        
        if(items[i].owner == null)
            totals.append("<h1>Total Unpaid</h1>");
        else
            totals.append("<h1>" + items[i].owner + "'s Total</h1>");
            
        totals.append("<h2>$" + Number(items[i].total).toFixed(2) + "</h2><br>");
    }
}