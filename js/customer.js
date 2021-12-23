const baseURI = "src/";

//#########################################
//General methods
//#########################################

//Close modal
function closeModal() {
    $('#modal').empty();
    $('#modal').hide();
}

$(document).on('click', 'span.closeModal', closeModal);

//Search for the given entity
$(document).on('submit', 'form#formSearch', function (e) {
    e.preventDefault();
    const entity = $(this).parent().siblings('input[type=hidden]').val();
    const searchText = $('#searchText').val();

    switch (entity) {
        case 'artist':
            ArtistSearch(searchText);
            break;
        case 'album':
            AlbumSearch(searchText);
            break;
        case 'track':
            TrackSearch(searchText);
            break;
        default:
            console.log('Error occured reload page');
            alert('Error occured reload page');
            break;
    }
});

//Display artists when page loads
$(document).on('load', GetAllArtists());


//Change displayed and enabled buttons
function ChangeEntityElements() {
    const btnDisplayArtists = $('#btnArtists');

    const btnDisplayAlbums = $('#btnAlbums');

    const btnDisplayTracks = $('#btnTracks');
    const selectGenre = $('#selectGenre');
    const selectMediaType = $('#selectMediaType');

    const entity = $('#displayedEntity').siblings('input[type=hidden]').val();

    switch (entity) {
        case 'artist':
            btnDisplayArtists.prop('disabled', true);
            btnDisplayAlbums.prop('disabled', false);
            btnDisplayTracks.prop('disabled', false);
            selectGenre.hide();
            selectMediaType.hide();
            break;
        case 'album':
            btnDisplayArtists.prop('disabled', false);
            btnDisplayAlbums.prop('disabled', true);
            btnDisplayTracks.prop('disabled', false)
            selectGenre.hide();
            selectMediaType.hide();
            break;
        case 'track':
            btnDisplayArtists.prop('disabled', false);
            btnDisplayAlbums.prop('disabled', false);
            btnDisplayTracks.prop('disabled', true);
            selectGenre.show();
            selectMediaType.show();
            break;
        default:
            console.log(entity);
            break;
    }
    
}

//Prevent opening of modal when add to cart form is clicked
$(document).on('click', 'form#formAddToCart', function (e) {
    e.stopPropagation();
});


//#########################################
//Artist methods
//#########################################

//Get All artists
async function GetAllArtists(){
    const output = $('<div></div>');
    console.log('Gets called')
    await $.ajax({
        type: "GET",
        url: baseURI + "artists", //Check if this works on cloud host
        dataType: "json",
        success: function (data) {
            data['Response'].forEach(artist => {
                const {ArtistId, Name} = artist;

                $(`<article class="artistObject">
                        <input type="hidden" value="${ArtistId}">
                        Name: ${Name}
                    </article>`).appendTo(output);
            });
        },
        error: function (data) {
            console.log(data);
            output.text('Could not retrieve artists');
        }
    });
    $('#displayedEntity').text('Artists');
    $('#displayedEntity').siblings('input[type=hidden]').val('artist');
    ChangeEntityElements();
    $('main#mainOutput').empty()
    output.appendTo('main#mainOutput');
}

//Display all artists when button is clicked
$('input#btnArtists').on('click', GetAllArtists);


//Search for artists
async function ArtistSearch(searchText) {
    const output = $('<div></div>');
    console.log('Gets called')
    await $.ajax({
        type: "GET",
        url: baseURI + `artists?search-text=${searchText}`, //Check if this works on cloud host
        dataType: "json",
        success: function (data) {
            data['Response'].forEach(artist => {
                const {ArtistId, Name} = artist;

                $(`<article class="artistObject">
                        <input type="hidden" value="${ArtistId}">
                        Name: ${Name}
                    </article>`).appendTo(output);
            });
        },
        error: function (data) {
            console.log(data);
            output.text('Search error. Could not retrieve artists');
        }
    });
    $('#btnArtists').prop('disabled', false);
    $('main#mainOutput').empty()
    output.appendTo('main#mainOutput');
}


//#########################################
//Album methods
//#########################################

//Get all albums
async function GetAllAlbums() {
    const output = $('<div></div>');
    console.log('Gets called')
    await $.ajax({
        type: "GET",
        url: baseURI + "albums", //Check if this works on cloud host
        dataType: "json",
        success: function (data) {
            data['Response'].forEach(album => {
                const {AlbumId, Title, Name} = album;

                $(`<article class="albumObject">
                        <input type="hidden" value="${AlbumId}">
                        Title: ${Title} <br>
                        Artist name: ${Name}
                    </article>`).appendTo(output);
            });
        },
        error: function (data) {
            console.log(data);
            output.text('Could not retrieve albums');
        }
    });
    $('#displayedEntity').text('Albums');
    $('#displayedEntity').siblings('input[type=hidden]').val('album');
    ChangeEntityElements();
    $('main#mainOutput').empty()
    output.appendTo('main#mainOutput');
}

//Display all albums when button is clicked
$('input#btnAlbums').on('click', GetAllAlbums);

//Search for albums
async function AlbumSearch(searchText) {
    const output = $('<div></div>');
    console.log('Gets called')
    await $.ajax({
        type: "GET",
        url: baseURI + `albums?search-text=${searchText}`, //Check if this works on cloud host
        dataType: "json",
        success: function (data) {
            data['Response'].forEach(album => {
                const {AlbumId, Title, Name} = album;

                $(`<article class="albumObject">
                        <input type="hidden" value="${AlbumId}">
                        Title: ${Title} <br>
                        Artist name: ${Name}
                    </article>`).appendTo(output);
            });
        },
        error: function (data) {
            console.log(data);
            output.text('Could not retrieve albums');
        }
    });
    $('#btnAlbums').prop('disabled', false);
    $('main#mainOutput').empty()
    output.appendTo('main#mainOutput');
}


//#########################################
//Track methods
//#########################################

//Get all tracks
async function GetAllTracks() {
    const output = $('<div></div>');
    console.log('Gets called')
    await $.ajax({
        type: "GET",
        url: baseURI + "tracks", //Check if this works on cloud host
        dataType: "json",
        success: function (data) {
            data['Response'].forEach(track => {
                const {TrackId, Name, Title, MediaType, Genre, UnitPrice} = track;

                $(`<article class="trackObject">
                                    Name: ${Name} <br>
                                    Album: ${Title}<br>
                                    Mediatype: ${MediaType}<br>
                                    Genre: ${Genre}<br>
                                    Price: ${UnitPrice} <br>
                                    <form id="formAddToCart" action="index.php" method="post">
                                        <input id="inputTrackId" type="hidden" name="trackId" value="${TrackId}">
                                        <input type="hidden" name="trackName" value="${Name}">
                                        <input type="hidden" name="albumTitle" value="${Title}">
                                        <input type="hidden" name="mediaType" value="${MediaType}">
                                        <input type="hidden" name="genre" value="${Genre}">
                                        <input type="hidden" name="price" value="${UnitPrice}">
                                        <input type="number" name="quantity" min="1" required>
                                        <input type="submit" value="Add to Cart">
                                    </form>
                                </article>`).appendTo(output);
                
            });
        },
        error: function (data) {
            console.log(data);
            output.text('Could not retrieve tracks');
        }
    });
    $('#displayedEntity').text('Tracks');
    $('#displayedEntity').siblings('input[type=hidden]').val('track');
    ChangeEntityElements();
    $('main#mainOutput').empty()
    output.appendTo('main#mainOutput');
}

//Display all artists when button is clicked
$('input#btnTracks').on('click', GetAllTracks);

//Search for tracks
async function TrackSearch(searchText) {
    let url = baseURI + 'tracks?';
    const selectedGenre = await $('select#selectGenre').find(':selected').val();
    const selectedMediaType = await $('select#selectMediaType').find(':selected').val();
    console.log(searchText);
    if (searchText !== '' || searchText !== null) {
        url+= 'search-text=' + searchText + '&';
    }
    if (selectedGenre !== 'all') {
        url += 'genre=' + selectedGenre + '&';
    }
    if (selectedMediaType !== 'all') {
        url += 'media-type=' + selectedMediaType + '&';
    }
    url = url.substring(0, url.length - 1);
    console.log(url)
    const output = $('<div></div>');
    console.log('Gets called')
    await $.ajax({
        type: "GET",
        url: url, //Check if this works on cloud host
        dataType: "json",
        success: function (data) {
            data['Response'].forEach(track => {
                const {TrackId, Name, Title, MediaType, Genre, UnitPrice} = track;

                $(`<article class="trackObject">
                        Name: ${Name} <br>
                        Album: ${Title}<br>
                        Mediatype: ${MediaType}<br>
                        Genre: ${Genre}<br>
                        Price: ${UnitPrice} <br>
                        <form id="formAddToCart" action="index.php" method="post">
                            <input id="inputTrackId" type="hidden" name="trackId" value="${TrackId}">
                            <input type="hidden" name="trackName" value="${Name}">
                            <input type="hidden" name="albumTitle" value="${Title}">
                            <input type="hidden" name="mediaType" value="${MediaType}">
                            <input type="hidden" name="genre" value="${Genre}">
                            <input type="hidden" name="price" value="${UnitPrice}">
                            <input type="number" name="quantity" min="1" required>
                            <input type="submit" value="Add to Cart">
                        </form>
                    </article>`).appendTo(output);
            });
        },
        error: function (data) {
            console.log(data);
            output.text('Could not retrieve tracks');
        }
    });
    $('#btnTracks').prop('disabled', false);
    $('main#mainOutput').empty()
    output.appendTo('main#mainOutput');
}


//Display track
$(document).on('click', 'article.trackObject', function () {
    const id = $(this).children('form').eq(0).children('input#inputTrackId').val();

    const modalContent = $(`<div class="modalContent">
                                <span class="closeModal">&times;</span>
                                <header>
                                    <h3>
                                        Track Information
                                    </h3>
                                </header>
                                <main>
                                    <input id="inputTrackId" type="hidden" value="${id}">
                                    <label for="txtAreaTrackName">Name: </label>
                                    <textarea name="txtAreaTrackName" id="txtAreaTrackName" cols="30" rows="2" disabled></textarea><br>
                                    <label for="inputAlbumId">Album: </label>
                                    <input name="inputAlbumId" id="inputAlbumId" type="text" disabled><br>
                                    <label for="inputMediaTypeId">Media type: </label>
                                    <input name="inputMediaTypeId" id="inputMediaTypeId" type="text" disabled><br>
                                    <label for="inputGenreId">Genre: </label>
                                    <input name="inputGenreId" id="inputGenreId" type="text" disabled><br>
                                    <label for="txtAreaComposer">Composer: </label>
                                    <textarea name="txtAreaComposer" id="txtAreaComposer" cols="30" rows="2" disabled></textarea><br>
                                    <label for="inputMilliseconds">Duration in milliseconds: </label>
                                    <input name="inputMilliseconds" id="inputMilliseconds" type="number" disabled><br>
                                    <label for="inputBytes">Size in bytes: </label>
                                    <input name="inputBytes" id="inputBytes" type="number" disabled><br>
                                    <label for="inputUnitPrice">Price: </label>
                                    <input name="inputUnitPrice" id="inputUnitPrice" type="number" disabled><br>
                                </main>
                            </div>`);

    $.ajax({
        type: "GET",
        url: baseURI + `tracks/${id}`,
        dataType: "json",
        success: function (data) {
            const {Name, Title, MediaType, Genre, Composer, Milliseconds, Bytes, UnitPrice} = data['Response'];
            modalContent.find('#txtAreaTrackName').text(Name);
            modalContent.find('#inputAlbumId').val(Title);
            modalContent.find('#inputMediaTypeId').val(MediaType);
            modalContent.find('#inputGenreId').val(Genre);
            modalContent.find('#txtAreaComposer').text(Composer);
            modalContent.find('#inputMilliseconds').val(Milliseconds);
            modalContent.find('#inputBytes').val(Bytes);
            modalContent.find('#inputUnitPrice').val(UnitPrice);
        },
        error: function (data) {
            $('<div>An error occured</div>').appendTo(modalContent);
        }
    });
    modalContent.appendTo('div#modal');
    $('div#modal').show();
});