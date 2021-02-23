// Black Sabbath, Bowie, Led Zeppelin, Queen
const supergroup = array ["5M52tdBnJaKSvOpJGz8mfZ","0oSGxfWSnnOXhD2fKuz2Gy","7dGJo4pcD2V6oG8kP0tJRR","36QJpDe2go2KgaRleHCDTp"];

"GET" "https://api.spotify.com/v1/artists?ids=5M52tdBnJaKSvOpJGz8mfZ%2C0oSGxfWSnnOXhD2fKuz2Gy%2C7dGJo4pcD2V6oG8kP0tJRR%2C36QJpDe2go2KgaRleHCDTp" -H "Accept: application/json" -H "Content-Type: application/json" -H "Authorization: Bearer BQC2mN3jG5wpGMgukx8pyOWA_TbRTExZoR5BgFp8-IOc59ZGxdFW_JAUBE2WA7MoFygR5EOYZEBD7vwoIXSPN8-1xkQaVlePUHkA0HSJGS6xQdiro3tpRyqX4VREzEy-isZ7_pPbQgyZLw"

function getAllArtists () {
    fetch("everyone.php").then(function(response){          
        return response.json();
    }).then(function(response){ 
        filteredCandidates = filterCandidates(response, myFilter);
        wrangleWinners (filteredCandidates);
        getGeoJSON();    
    });
} // end of 


/* Create artist HTML and add it to the webpage */
function createArtistHTML (artist) {
  const cr = document.getElementById('artist-container');
  // cr.append(fillArtistHTML(artist));
};

/* Fill artist HTML */
const fillArtistHTML = (artist) => {
  cr.className = 'artist-container';
  const image = document.createElement('img');
  image.className = 'artist-img';
  image.src = Artist.imageUrlForArtist(artist);
  image.alt = Artist.altTextForArtistImage(artist);
  cr.append(image);

  const name = document.createElement('h2');
  name.innerHTML = artist.name;
  cr.append(name);

  const neighborhood = document.createElement('p');
  neighborhood.innerHTML = artist.neighborhood;
  cr.append(neighborhood);

  const address = document.createElement('p');
  address.innerHTML = artist.address;
  cr.append(address);

  // Maybe use the following for albums
  /*
  const more = document.createElement('a');
  more.innerHTML = 'More Info';
  more.href = Artist.urlForArtist(artist);
  cr.append(more);
  */
 
  return cr;
};

/* Create artist HTML and add it to the webpage */
const createArtistGallery = (artists = self.artists) => {
  const ul = document.getElementById('artists-list');
  artists.forEach(artist => {
    ul.append(fillGalleryHTML(artist));
  });
};

/* Fill artist HTML */
const fillGalleryHTML = (artist) => {
  const li = document.createElement('li');
  li.className = 'artist-container';
  const image = document.createElement('img');
  image.className = 'artist-img';
  image.src = Artist.imageUrlForArtist(artist);
  image.alt = Artist.altTextForArtistImage(artist);
  li.append(image);

  const name = document.createElement('h2');
  name.innerHTML = artist.name;
  li.append(name);

  const neighborhood = document.createElement('p');
  neighborhood.innerHTML = artist.neighborhood;
  li.append(neighborhood);

  const address = document.createElement('p');
  address.innerHTML = artist.address;
  li.append(address);

  // This creates the hyperlink. I want 
  const more = document.createElement('a');
  more.innerHTML = 'More Info';
  more.href = Artist.urlForArtist(artist);
  li.append(more);

  return li;
};

class Artist {
  
  /* artist page URL */
  static urlForArtist(artist) {
    return (`./artist.html?id=${artist.id}`);
  }

  /* artist image URL */
  static imageUrlForArtist(artist) {
    if (artist.photograph) {
      return (`/img/${artist.photograph}` + '.jpg');
    } else {
      return ('http://localhost:8000/img/404.jpg');
    }
  }

  /* Alt-text for artist image */
  static altTextForArtistImage(artist) {
    return (`${artist.artistName}`);
  }

  
/* Get current artist from page URL. */
fetchArtistFromURL = (callback) => {
  if (self.artist) { // artist already fetched!
    callback(null, self.artist)
    return;
  }
  const id = getParameterByName('id');
  if (!id) { // no id found in URL
    error = 'No artist id in URL'
    callback(error, null);
  } else {
    Artist.fetchArtistById(id, (error, artist) => {
      self.artist = artist;
      if (!artist) {
        console.error(error);
        return;
      }
      console.log(artist);
      // createArtistHTML();
      // callback(null, artist)
    });
  }
}

  /* Fetch an artist by its ID */
  static fetchArtistById(id, callback) {
    const artistID = id;
    if (artistID) { // Got the artist
      callback(null, artist);
    } else { // artist does not exist in the database
      callback('artist does not exist', null);
    }
  }
 
  
}
