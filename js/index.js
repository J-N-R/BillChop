$(document).ready(function() {

    $("#file").change(function() {
        var src = URL.createObjectURL(this.files[0]);
        $("#bill")[0].src = src;
        $("#bill-intro")[0].scrollIntoView({behavior: 'smooth'});
    });
    
    $("#image-form").submit(function(event) {
        event.preventDefault();
        
        if($("#file")[0].files[0] == null)
          return;
        
        alert("Not implemented yet. Come back later.");
        
        /*var formData = new FormData();
        formData.append("file", $("#file")[0].files[0]);
        
        fetch('../cgi-bin/imageReader.py', {
          method: "POST", 
          body: formData
        })
           .then(response => response.json())
           .then(data => Cookies.set("bid", data.bid);
        
        Cookies.set("name", prompt("What is your name?\n(This will make the results page clearer)"));
        
        window.location.replace("list.html"); */
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

        window.location.replace("list.html");
    });
    
});