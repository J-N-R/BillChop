$(document).ready(function() {

    $("#file").change(function() {
        var src = URL.createObjectURL(this.files[0]);
        $("#bill")[0].src = src;
        $("#bill-intro")[0].scrollIntoView({behavior: 'smooth'});
    });
    
    $("#image-form").submit(function(event) {
        event.preventDefault();
        
        $(".loading").css("display", "block");
        
        if($("#file")[0].files[0] == null) {
          $(".loading").css("display", "none");
          return;
        }
        
        if($("#file")[0].files[0].size > 1000000) {
          $(".loading").css("display", "none");
          alert("Your photo goes over the max size.\n\nMax size: 1MB, Your size: " + Number($("#file")[0].files[0].size/1000000).toFixed(2) + " MB");
          return;
        }
        
        var formData = new FormData();
        formData.append("file", $("#file")[0].files[0]);
        
        var result = "";
        
        fetch('php/imageReader.php', {
          method: "POST", 
          body: formData
        })
           .then(response => response.json())
           .then(data => newBill(data));
        
    });
    
    $("#code-form").submit(function(event) {
        event.preventDefault();
        
        if(this[0].value == "")
          return;
        
        var name = prompt("What is your name?\n(This will make the results page clearer)");
        
        if(name == "" || name == null)
          return;
        
        Cookies.set("bid", this[0].value);
        
        Cookies.set("name", name);

        window.location.href = "list.html";
    });
    
});

function newBill(result) {
    $(".loading").css("display", "none");

    if(result.hasOwnProperty("Error")) {
        if(result.Error == "API Error.")
            alert("There was a problem reading your bill!\n\nIt looks like one of the external servers we depend on is down.\n\nPlease try again later!");
        else if(result.Error == "Image is too big.")
            alert("There was a problem reading your bill!\n\nYour photo goes over the max file size.\n\nMax: 1MB\n\nPlease try again.");
        else if(result.Error == "Couldn't read the items.")
            alert("There was a problem reading your bill!\n\nWe had trouble reading the items on your bill.\n\nPlease try again with a different photo.");
        else
            alert("There was a problem reading your bill!\nPlease contact the developer.\n\nError Message:\n" + result.Error);
            
        console.log(result);
        return;
    }
        
    console.log(result);
        
    Cookies.set("bid", result.bid);
      
    var name = prompt("What is your name?\n(This will make the results page clearer)");
    
    while(name == "" || name == null)
        name = prompt("What is your name?\n\nREQUIRED\n\n(This will make the results page clearer)");
        
    Cookies.set("name", name);
        
    window.location.href = "list.html";
}