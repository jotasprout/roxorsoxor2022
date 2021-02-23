const db = new PouchDB('poprock');

if ("ServiceWorker" in navigator) {
    navigator.serviceWorker.register ("sw.js")
        .then(function(registration){
            console.log("this page controlled by sw with scope: ", registration.scope);
        }).catch(function(err){
            console.log("sw reg failed cuz: ", err);
        });
}