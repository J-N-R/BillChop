$(document).ready(function() {
 
 // When someone clicks 'submit,' send them to the totals page   
    $("#update-form").submit(function(event) {
        event.preventDefault();
        
        window.location.replace("totals.html");
    });
    
 // If bill couldn't be found, tell user
    if(!Cookies.get("bid")) {
      alert("It looks like we couldn't find your bill!\nRedirecting you to main page...");
      window.location.replace(".");
    }
    
 // If name couldn't be found, prompt user for name
    while(Cookies.get("name") == null || Cookies.get("name") == "") {
        var name = prompt("It looks like we missed your name! Could you tell us please?\n(This will make the results page clearer)");
        
        if(name == "" || name == null)
          continue;
          
        Cookies.set("name", name);
          
        return;
    }
      
    $("#code").html(Cookies.get("bid"));
    
 // Call the backend and reserve a color for this user
   fetch("php/setColor.php?owner=" + Cookies.get("name") + "&bid=" + Cookies.get("bid"))
        .then(response => response.json())
        .then(data => console.log(data));
    
 // Call the backend with the saved bid
    fetch("php/joinBill.php?bid=" + Cookies.get("bid"))
        .then(response => response.json())
        .then(data => insertItems(data));
        
 // Update the page every second
    setInterval(updatePage, 1000);
});

function updatePage() {
    fetch("php/updatePage.php?bid=" + Cookies.get("bid"))
           .then(response => response.json())
           .then(data => insertItems(data));
}

async function updateItem(item) {
    var checked = item.childNodes[1].checked;
    
    console.log("Updating item " + item.id);
    console.log("Checked? " + !checked);
    
    var formData = new FormData();
    formData.append("bid", Cookies.get("bid"));
    formData.append("id", item.id);
      
    item.childNodes[1].checked = !item.childNodes[1].checked; 
    
 // if a user clicks an item, set their ownership in the database
    if(checked) {
    
        formData.append("owner", null);
    
        await fetch("php/updateBill.php?" + new URLSearchParams(formData).toString())
                .then(response => response.text())
                .then(data => console.log(data));
    }
    
 // if a user UNCHECKS an item, set their ownership to null in the database
    else {
    
        formData.append("owner", Cookies.get("name"));
    
        await fetch("php/updateBill.php?" + new URLSearchParams(formData).toString())
                .then(response => response.text())
                .then(data => console.log(data));
    }
    
    updatePage();
    
}

function insertItems(items) {

    if(items.hasOwnProperty("Error")) {
    
      if(items.Error.substring(0, 7) == "No bill") {
          alert("It looks like we couldn't find your bill!\nPlease try again with a different code.\nRedirecting you to main page...");
          window.location.replace(".");
      }
      else {
          alert("It looks like there was a problem with your bill.\nPlease contact the developer.\nError: " + items.Error + "\nRedirecting you to the main page...");
          window.location.replace(".");
      }
    }

    //$("#list").hide()
    //$("#list")[0].id = "old-list";
    $("#list").html("");
    var list = $("#list");

 // Loop through each person's totals and insert
    for(var i = 0; i < items.length; i++) {
        var temp = "";
        
        temp += "<li class='list-group-item' id='" + items[i].id + "' ";
        temp += "onclick='updateItem(this)' style='background-color: " + items[i].color + "; cursor: pointer'>";
        temp += "  <input type='checkbox' style='display: none'>";
        temp += "  <div class='row'>";
        temp += "    <div class='col text-left'>" + items[i].name + "</div>";
        temp += "    <div class='col text-right'>$ " + Number(items[i].price).toFixed(2) + "</div>";
        temp += "  </div>";
        temp += "</li>";
        
        list.append(temp);
        
        if(items[i].color == null)
            list[0].childNodes[i].style.color = "black";
        
        if(items[i].owner == Cookies.get("name"))
            list[0].childNodes[i].childNodes[1].checked = true;
          
    }
}