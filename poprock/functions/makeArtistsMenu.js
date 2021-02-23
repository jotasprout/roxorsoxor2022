let artists;

/* Fetch all artists and set artist menu HTML */
function makeArtistsMenu () {
  fetchMyArtists((error, artists) => {
    if (error) { // Got an error
      console.error(error);
    } else {
      const menu = document.getElementById('artistMenu');
      artists.forEach(artist => {
        console.log(artist);
        const option = document.createElement('option');
        option.innerHTML = artist.artistName;
        option.value = artist.artistSpotID;
        select.append(option);
        /*

      option.setAttribute('role','option');
      
      */
      });
    }
  });
};

/* Fetch artists as soon as the page is loaded. */
document.addEventListener('DOMContentLoaded', (event) => {
  makeArtistMenu();
  // fetchMyArtists();
/*
  if ('serviceWorker' in navigator) {
    navigator.serviceWorker.register('../../sw.js', {
      scope: './'
    }).then(function(reg) {
      if ('sync' in reg) {
        console.log("Sync is supported here");
      } else {
        console.log("Sync is NOT supported here");
      }
    }).catch(function(err) {
      console.error(err);
    });
  }
  */
});

function fetchMyArtists () {
  const url = 'functions/getAllMyArtists.php';
  
  const myArtistsOptions = {
    method: 'POST',
    headers: {
      'Content-Type': 'application/json'
    }
  };

  fetch(url, myArtistsOptions).then(response => response.json())
  .then(json => {
    return json;

  }).catch(err => {
    console.log (err);
  });
} // end of fetchMyArtists



// makeArtistsMenu ();

/*
function sendArtistToServer (artist) {
    const url = 'functions/add_new_artist.php';
    const artistToSend = {
        artist
    };  
    const artistOptions = {
        method: 'POST',
        body: JSON.stringify(artistToSend),
        headers: {
        'Content-Type': 'application/json'
        }
    };
    fetch(url, artistOptions).then(response => response.json())
    .then(json => {
        console.log(json);
        console.log("this artist is " + json.name);

        const table = document.getElementById('artistInfo');
        const tr1 = document.createElement('tr');
        const td1 = document.createElement('td');
        const image = document.createElement('img');
        const imageURL = json.images[0].url;
        image.src = imageURL;
        td1.append(image);
        tr1.append(td1);
        table.append(tr1);
        const tr2 = document.createElement('tr');
        const td2 = document.createElement('td');
        td2.innerHTML = json.name;
        tr2.append(td2);
        table.append(tr2);
        const tr3 = document.createElement('tr');
        const td3 = document.createElement('td');
        td3.innerHTML = json.popularity;
        tr3.append(td3);
        table.append(tr3);

    }).catch(err => {
        console.log (err);
    });
}
*/