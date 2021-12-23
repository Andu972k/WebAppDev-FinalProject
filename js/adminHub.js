
//const currentPagePath = document.location.pathname.split('/');

//const apiPath = currentPagePath.slice(0, currentPagePath.length - 2).join('/') + "/src";

//const baseURI = document.location.origin + apiPath;

const baseURI = "../src/";

//baseURI+"/artists"

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
    const btnArtistCreation = $('#btnOpenArtistCreation');

    const btnDisplayAlbums = $('#btnAlbums');
    const btnAlbumCreation = $('#btnOpenAlbumCreation');

    const btnDisplayTracks = $('#btnTracks');
    const btnTrackCreation = $('#btnOpenTrackCreation');

    const entity = $('#displayedEntity').siblings('input[type=hidden]').val();

    switch (entity) {
        case 'artist':
            btnDisplayArtists.prop('disabled', true);
            btnArtistCreation.show();
            btnDisplayAlbums.prop('disabled', false);
            btnAlbumCreation.hide();
            btnDisplayTracks.prop('disabled', false);
            btnTrackCreation.hide();
            break;
        case 'album':
            btnDisplayArtists.prop('disabled', false);
            btnArtistCreation.hide();
            btnDisplayAlbums.prop('disabled', true);
            btnAlbumCreation.show();
            btnDisplayTracks.prop('disabled', false);
            btnTrackCreation.hide();
            break;
        case 'track':
            btnDisplayArtists.prop('disabled', false);
            btnArtistCreation.hide();
            btnDisplayAlbums.prop('disabled', false);
            btnAlbumCreation.hide();
            btnDisplayTracks.prop('disabled', true);
            btnTrackCreation.show();
            break;
        default:
            console.log(entity);
            break;
    }
    
}

//Delete Entity
$(document).on('click', 'button.delete', function (e) {
    e.stopPropagation();
    const id = $(this).siblings('input[type=hidden]').eq(0).val();
    const entity = $('#displayedEntity').siblings('input[type=hidden]').val();

    switch (entity) {
        case 'artist':
            DeleteArtist(id);
            break;
        case 'album':
            DeleteAlbum(id);
            break;
        case 'track':
            DeleteTrack(id);
            break;
        default:
            console.log('Error Delete entity');
            break;
    }
    
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
                        <button class="delete">&ndash;</button>
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
                        <button class="delete">&ndash;</button>
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

//Display Artist
$(document).on('click', 'article.artistObject', function () {
    const id = $(this).children('input').eq(0).val();
    console.log(id);
    const modalContent = $(`<div class="modalContent">
                                <span class="closeModal">&times;</span>
                                <header>
                                    <h3>
                                        Artist Information
                                    </h3>
                                </header>
                                <main>
                                    <input id="inputArtistId" type="hidden" value="${id}">
                                    <label for="txtAreaArtistName">Name</label>
                                    <textarea name="txtAreaArtistName" id="txtAreaArtistName" cols="30" rows="2"></textarea>
                                    <button id="btnUpdateArtist">Update</button>
                                </main>
                            </div>`);
    
    $.ajax({
        type: "GET",
        url: baseURI + `artists/${id}`,
        dataType: "json",
        success: function (data) {
            const {Name} = data['Response'];
            modalContent.find('#txtAreaArtistName').text(Name);
        },
        error: function (data) {
            console.log(data);
            $('<div>An error occured</div>').appendTo(modalContent);
        }
    });

    modalContent.appendTo('div#modal');
    $('div#modal').show();
});



//Open Artist Creation modal
$(document).on('click', 'input#btnOpenArtistCreation', function () {
    const modalContent = $(`<div class="modalContent">
                                <span class="closeModal">&times;</span>
                                <header>
                                    <h3>
                                        Enter Artist Information
                                    </h3>
                                </header>
                                <main>
                                    <form id="formCreateArtist" name="formCreateArtist" method="POST">
                                        <label for="Name">Name</label>
                                        <textarea name="Name" id="Name" cols="30" rows="2"></textarea>
                                        <?php echo csrf_token_tag() ?>
                                        <input type="submit" value="Create">
                                    </form>
                                </main>
                            </div>`);
    
    modalContent.appendTo('div#modal');
    $('div#modal').show();
});

//Create Artist
$(document).on('submit', 'form#formCreateArtist', function (e) {
    e.preventDefault();
    
    const form = $(this);

    $.ajax({
        type: "POST",
        url: baseURI + "artists",
        data: form.serialize(),
        dataType: "json",
        success: function (data) {
            const response = data['Response'];
            console.log(response)
            if (response === -1) {
                $('<div>Creation failed</div>').appendTo('.modalContent');
            }
            else {
                closeModal();
                GetAllArtists();
            }
            
        },
        error: function (data) {
            console.log(data);
        }
    });

});

//Update artist
$(document).on('click', 'button#btnUpdateArtist', function () {
    console.log('Update gets called')
    const id = $('#inputArtistId').val();
    const name = $('#txtAreaArtistName').val();

    $.ajax({
        type: "PUT",
        url: baseURI + `artists/${id}`,
        data: JSON.stringify({"NewName": name}),
        dataType: "json",
        contentType: "application/json",
        success: function (data) {
            const response = data['Response'];
            console.log(response)
            if (response === false) {
                $('<div>Update failed</div>').appendTo('.modalContent');
            }
            else {
                closeModal();
                GetAllArtists();
            }
            
        },
        error: function (data) {
            $(`<div>${data}</div>`).appendTo('.modalContent');
            console.log(data);
        }
    })
});


//Delete Artist
async function DeleteArtist(id) {

    await $.ajax({
        type: "DELETE",
        url: baseURI + `artists/${id}`,
        dataType: "json",
        success: function (data) {
            const response = data['Response'];

            if (response === false) {
                $('<div>Cannot delete artist with album(s)</div>').appendTo('.modalContent');
            }
            else {
                const artists = $('#mainOutput').children('div').eq(0);
                $('#mainOutput').empty();

                artists.find(`input[value=${id}]`).parent().remove();

                artists.appendTo('#mainOutput');
            }
        },
        error: function (data) {
            console.log(data);
            alert('Deletion failed');
        }
    });
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
                const {AlbumId, Title, ArtistId} = album;

                $(`<article class="albumObject">
                        <input type="hidden" value="${AlbumId}">
                        Title: ${Title} <br>
                        Artist Id: ${ArtistId}
                        <button class="delete">&ndash;</button>
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
                const {AlbumId, Title, ArtistId} = album;

                $(`<article class="albumObject">
                        <input type="hidden" value="${AlbumId}">
                        Title: ${Title} <br>
                        Artist Id: ${ArtistId}
                        <button class="delete">&ndash;</button>
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

//Display album
$(document).on('click', 'article.albumObject', function () {
    const id = $(this).children('input').eq(0).val();
    const modalContent = $(`<div class="modalContent">
                                <span class="closeModal">&times;</span>
                                <header>
                                    <h3>
                                        Album Information
                                    </h3>
                                </header>
                                <main>
                                    <input id="inputAlbumId" type="hidden" value="${id}">
                                    <label for="txtAreaAlbumTitle">Title</label>
                                    <textarea name="txtAreaAlbumTitle" id="txtAreaAlbumTitle" cols="30" rows="2"></textarea>
                                    <label for="inputArtistId">Artist id</label>
                                    <input name="inputArtistId" id="inputArtistId" type="number">
                                    <button id="btnUpdateAlbum">Update</button>
                                </main>
                            </div>`);

    $.ajax({
        type: "GET",
        url: baseURI + `albums/${id}`,
        dataType: "json",
        success: function (data) {
            const {Title, ArtistId} = data['Response'];
            modalContent.find('#txtAreaAlbumTitle').text(Title);
            modalContent.find('#inputArtistId').val(ArtistId);
        },
        error: function (data) {
            console.log(data);
            $('<div>An error occured</div>').appendTo(modalContent);
        }
    });

    modalContent.appendTo('div#modal');
    $('div#modal').show();
});




//Open Album Creation modal
$(document).on('click', 'input#btnOpenAlbumCreation', function () {
    const modalContent = $(`<div class="modalContent">
                                <span class="closeModal">&times;</span>
                                <header>
                                    <h3>
                                        Enter Artist Information
                                    </h3>
                                </header>
                                <main>
                                    <form id="formCreateAlbum" name="formCreateAlbum" method="POST">
                                        <label for="Name">Name</label>
                                        <textarea name="Title" id="Title" cols="30" rows="2"></textarea>
                                        <label for="ArtistId">Artist id</label>
                                        <input name="ArtistId" type="number"></input>
                                        <?php echo csrf_token_tag() ?>
                                        <input type="submit" value="Create">
                                    </form>
                                </main>
                            </div>`);
    
    modalContent.appendTo('div#modal');
    $('div#modal').show();
});

//Create Album
$(document).on('submit', 'form#formCreateAlbum', function (e) {
    e.preventDefault();
    
    const form = $(this);

    $.ajax({
        type: "POST",
        url: baseURI + "albums",
        data: form.serialize(),
        dataType: "json",
        success: function (data) {
            const response = data['Response'];
            console.log(response)
            if (response === -1) {
                $('<div>Creation failed</div>').appendTo('.modalContent');
            }
            else {
                closeModal();
                GetAllAlbums();
            }
            
        },
        error: function (data) {
            console.log(data);
        }
    });
});


//Update album
$(document).on('click', 'button#btnUpdateAlbum', function () {
    console.log('Update gets called')
    const id = $('#inputAlbumId').val();
    const title = $('#txtAreaAlbumTitle').val();
    const artistId = $('#inputArtistId').val();
    console.log(title)
    console.log(artistId)

    $.ajax({
        type: "PUT",
        url: baseURI + `albums/${id}`,
        data: JSON.stringify({"NewTitle": title, "NewArtistId": artistId}),
        dataType: "json",
        contentType: "application/json",
        success: function (data) {
            const response = data['Response'];
            if (response === false) {
                $('<div>Update failed</div>').appendTo('.modalContent');
            }
            else {
                closeModal();
                GetAllAlbums();
            }
            
        },
        error: function (data) {
            $(`<div>${data}</div>`).appendTo('.modalContent');
            console.log(data);
        }
    });
});


//Delete Album
async function DeleteAlbum(id) {

    await $.ajax({
        type: "DELETE",
        url: baseURI + `albums/${id}`,
        dataType: "json",
        success: function (data) {
            const response = data['Response'];

            if (response === false) {
                $('<div>Cannot delete album with tracks(s)</div>').appendTo('.modalContent');
            }
            else {
                const albums = $('#mainOutput').children('div').eq(0);
                $('#mainOutput').empty();

                albums.find(`input[value=${id}]`).parent().remove();

                albums.appendTo('#mainOutput');
            }
        },
        error: function (data) {
            console.log(data);
            alert('Deletion failed');
        }
    })
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
                const {TrackId, Name, Milliseconds} = track;

                $(`<article class="trackObject">
                        <input type="hidden" value="${TrackId}">
                        Name: ${Name} <br>
                        Duration: ${Milliseconds}
                        <button class="delete">&ndash;</button>
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
    const output = $('<div></div>');
    console.log('Gets called')
    await $.ajax({
        type: "GET",
        url: baseURI + `tracks?search-text=${searchText}`, //Check if this works on cloud host
        dataType: "json",
        success: function (data) {
            data['Response'].forEach(track => {
                const {TrackId, Name, Milliseconds} = track;

                $(`<article class="trackObject">
                        <input type="hidden" value="${TrackId}">
                        Name: ${Name} <br>
                        Duration: ${Milliseconds}
                        <button class="delete">&ndash;</button>
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
    const id = $(this).children('input').eq(0).val();
    const modalContent = $(`<div class="modalContent">
                                <span class="closeModal">&times;</span>
                                <header>
                                    <h3>
                                        Track Information
                                    </h3>
                                </header>
                                <main>
                                    <input id="inputTrackId" type="hidden" value="${id}">
                                    <label for="txtAreaTrackName">Name</label>
                                    <textarea name="txtAreaTrackName" id="txtAreaTrackName" cols="30" rows="2"></textarea><br>
                                    <label for="inputAlbumId">Album id</label>
                                    <input name="inputAlbumId" id="inputAlbumId" type="number"><br>
                                    <label for="inputMediaTypeId">Media type id</label>
                                    <input name="inputMediaTypeId" id="inputMediaTypeId" type="number"><br>
                                    <label for="inputGenreId">Genre id</label>
                                    <input name="inputGenreId" id="inputGenreId" type="number"><br>
                                    <label for="txtAreaComposer">Composer</label>
                                    <textarea name="txtAreaComposer" id="txtAreaComposer" cols="30" rows="2"></textarea><br>
                                    <label for="inputMilliseconds">Duration in milliseconds</label>
                                    <input name="inputMilliseconds" id="inputMilliseconds" type="number"><br>
                                    <label for="inputBytes">Size in bytes</label>
                                    <input name="inputBytes" id="inputBytes" type="number"><br>
                                    <label for="inputUnitPrice">Price</label>
                                    <input name="inputUnitPrice" id="inputUnitPrice" type="number"><br>
                                    <button id="btnUpdateTrack">Update</button>
                                </main>
                            </div>`);

    $.ajax({
        type: "GET",
        url: baseURI + `tracks/${id}`,
        dataType: "json",
        success: function (data) {
            const {Name, AlbumId, MediaTypeId, GenreId, Composer, Milliseconds, Bytes, UnitPrice} = data['Response'];
            modalContent.find('#txtAreaTrackName').text(Name);
            modalContent.find('#inputAlbumId').val(AlbumId);
            modalContent.find('#inputMediaTypeId').val(MediaTypeId);
            modalContent.find('#inputGenreId').val(GenreId);
            modalContent.find('#txtAreaComposer').text(Composer);
            modalContent.find('#inputMilliseconds').val(Milliseconds);
            modalContent.find('#inputBytes').val(Bytes);
            modalContent.find('#inputUnitPrice').val(UnitPrice);
            console.log(AlbumId);
        },
        error: function (data) {
            $('<div>An error occured</div>').appendTo(modalContent);
        }
    });
    modalContent.appendTo('div#modal');
    $('div#modal').show();
});


//Open Track Creation modal
$(document).on('click', 'input#btnOpenTrackCreation', function () {
    const modalContent = $(`<div class="modalContent">
                                <span class="closeModal">&times;</span>
                                <header>
                                    <h3>
                                        Enter Track Information
                                    </h3>
                                </header>
                                <main>
                                    <form id="formCreateTrack" name="formCreateTrack" method="POST">
                                        <label for="Name">Name</label>
                                        <textarea name="Name" id="Name" cols="30" rows="2"></textarea><br>
                                        <label for="AlbumId">Album id</label>
                                        <input name="AlbumId" id="AlbumId" type="number"><br>
                                        <label for="MediaTypeId">Media type id</label>
                                        <input name="MediaTypeId" id="MediaTypeId" type="number"><br>
                                        <label for="GenreId">Genre id</label>
                                        <input name="GenreId" id="GenreId" type="number"><br>
                                        <label for="Composer">Composer</label>
                                        <textarea name="Composer" id="Composer" cols="30" rows="2"></textarea><br>
                                        <label for="Milliseconds">Duration in milliseconds</label>
                                        <input name="Milliseconds" id="Milliseconds" type="number"><br>
                                        <label for="Bytes">Size in bytes</label>
                                        <input name="Bytes" id="Bytes" type="number"><br>
                                        <label for="UnitPrice">Price</label>
                                        <input name="UnitPrice" id="UnitPrice" type="number">
                                        <?php echo csrf_token_tag() ?>
                                        <input type="submit" value="Create">
                                    </form>
                                </main>
                            </div>`);

    modalContent.appendTo('div#modal');
    $('div#modal').show();
});

//Create Track
$(document).on('submit', 'form#formCreateTrack', function (e) {
    e.preventDefault();

    const form = $(this);

    console.log(form.serialize());

    $.ajax({
        type: "POST",
        url: baseURI + "tracks",
        data: form.serialize(),
        dataType: "json",
        success: function (data) {
            const response = data['Response'];
            console.log(response)
            if (response === -1) {
                $('<div>Creation failed</div>').appendTo('.modalContent');
            }
            else {
                closeModal();
                GetAllTracks();
            }
            
        },
        error: function (data) {
            console.log(data);
        }
    });
});


//Update Track
$(document).on('click', 'button#btnUpdateTrack', function () {
    const id = $('#inputTrackId').val();
    const name = $('#txtAreaTrackName').val();
    const albumId = $('#inputAlbumId').val();
    const mediaTypeId = $('#inputMediaTypeId').val();
    const genreId = $('#inputGenreId').val();
    const composer = $('#txtAreaComposer').val();
    const milliseconds = $('#inputMilliseconds').val();
    const bytes = $('#inputBytes').val();
    const unitPrice = $('#inputUnitPrice').val();
    
    $.ajax({
        type: "PUT",
        url: baseURI + `tracks/${id}`,
        data: JSON.stringify({"Name": name, "AlbumId": albumId, "MediaTypeId": mediaTypeId, "GenreId": genreId, "Composer": composer, "Milliseconds": milliseconds, "Bytes": bytes, "UnitPrice": unitPrice}),
        dataType: "json",
        contentType: "application/json",
        success: function (data) {
            const response = data['Response'];
            if (response === false) {
                $('<div>Update failed</div>').appendTo('.modalContent');
            }
            else {
                closeModal();
                GetAllTracks();
            }
        },
        error: function (data) {
            $(`<div>${data}</div>`).appendTo('.modalContent');
            console.log(data);
        }
    });
});



//Delete track
async function DeleteTrack(id) {

    await $.ajax({
        type: "DELETE",
        url: baseURI + `tracks/${id}`,
        dataType: "json",
        success: function (data) {
            const response = data['Response'];

            if (response === false) {
                $('<div>Cannot delete track that have been ordered</div>').appendTo('.modalContent');
            }
            else {
                const tracks = $('#mainOutput').children('div').eq(0);
                $('#mainOutput').empty();

                tracks.find(`input[value=${id}]`).parent().remove();

                tracks.appendTo('#mainOutput');
            }
        },
        error: function (data) {
            console.log(data);
            alert('Deletion failed');
        }
    })
}