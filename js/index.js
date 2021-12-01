$(document).ready(function() {

    $("#file").change(function() {
        var src = URL.createObjectURL(this.files[0]);
        $("#bill")[0].src = src;
        $("#bill-intro")[0].scrollIntoView({behavior: 'smooth'});
    });
    
    $("#image-form").submit(function(event) {
        event.preventDefault();
        
        alert("Not implemented yet. Come back later.");
        
        /*var formData = new FormData();
        formData.append("file", $("#file")[0].files[0]);
        
        fetch('../cgi-bin/imageReader.py', {
          method: "POST", 
          body: formData
        })
           .then(response => response.json())
           .then(data => Cookies.set("bid", data.bid);
        
        window.location.replace("list.html"); */
    });
    
    $("#code-form").submit(function(event) {
        event.preventDefault();
        
        Cookies.set("bid", this[0].value);
        
        window.location.replace("list.html");
    });
    
});