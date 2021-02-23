
/* Create all albums HTML and add them to the webpage */
const createDiscographyHTML = (albums = self.albums) => {
  const ul = document.getElementById('albums-list');
  albums.forEach(album => {
    ul.append(createAlbumHTML(album));
  });
};

/* Create album HTML */
const createAlbumHTML = (album) => {
  const li = document.createElement('li');
  li.className = 'album-container';
  const image = document.createElement('img');
  image.className = 'album-img';
  image.src = DBHelper.imageUrlForAlbum(album);
  image.alt = DBHelper.altTextForAlbumImage(album);
  li.append(image);

  const name = document.createElement('h2');
  name.innerHTML = album.name;
  li.append(name);

  const neighborhood = document.createElement('p');
  neighborhood.innerHTML = album.neighborhood;
  li.append(neighborhood);

  const address = document.createElement('p');
  address.innerHTML = album.address;
  li.append(address);

  const more = document.createElement('a');
  more.innerHTML = 'More Info';
  more.href = DBHelper.urlForAlbum(album);
  li.append(more);

  return li;
};

class Album {
  
  /* album page URL */
  static urlForAlbum(album) {
    return (`./album.html?id=${album.id}`);
  }

  /* album image URL */
  static imageUrlForAlbum(album) {
    if (album.photograph) {
      return (`/img/${album.photograph}` + '.jpg');
    } else {
      return ('http://localhost:8000/img/404.jpg');
    }
  }

  /* Alt-text for album image */
  static altTextForAlbumImage(album) {
    return (`${album.photodesc}`);
  }
  
}
