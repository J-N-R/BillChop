var Object = [{ "owner" : "Aspen", total : 2.5 },{ "owner" : "Dixie", total : 3.5 },
             { "owner" : "Jon", total : 5 }, { "owner" : "Sarah", total : 20.5 }]

for(var i=0;i<Object.length;i++){
document.getElementById(`person${i+1}-total`).innerHTML = "$"+Object[i].total.toFixed(2);
document.getElementById(`person${i+1}`).innerHTML =Object[i].owner+"'s Total";
}
