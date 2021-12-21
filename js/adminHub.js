
//const currentPagePath = document.location.pathname.split('/');

//const apiPath = currentPagePath.slice(0, currentPagePath.length - 2).join('/') + "/src";

//const baseURI = document.location.origin + apiPath;

const baseURI = "../src/";

//baseURI+"/artists"

//#########################################
//General methods
//#########################################

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


//#########################################
//Artist methods
//#########################################

//Get All artists
function GetAllArtists(){
    const output = $('<div></div>');
    console.log('Gets called')
    $.ajax({
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
    $('main#mainOutput').empty()
    output.appendTo('main#mainOutput');
}

//Display artists when page loads
$(document).on('load', GetAllArtists());

//Display all artists when button is clicked
$('input#btnArtists').on('click', GetAllArtists);

//Search for artists

function ArtistSearch(searchText) {
    const output = $('<div></div>');
    console.log('Gets called')
    $.ajax({
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

//Close modal

function closeModal() {
    $('#modal').empty();
    $('#modal').hide();
}

$(document).on('click', 'span.closeModal', closeModal);

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
                                        <input type="submit" value="Create">
                                    </form>
                                </main>
                            </div>`);
    
    modalContent.appendTo('div#modal');
    $('div#modal').show();
})

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
                $('<div>Creation failed</div>').appendTo('#modalContent');
            }
            else {
                closeModal();
                GetAllArtists();
            }
            
        },
        error: function (data) {
            console.log(data);
        }
    })

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
            if (response === false) {
                $('<div>Update failed</div>').appendTo('#modalContent');
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
$(document).on('click', 'button.delete', function (e) {
    e.stopPropagation();
    const id = $(this).siblings('input').eq(0).val();

    $.ajax({
        type: "DELETE",
        url: baseURI + `artists/${id}`,
        dataType: "json",
        success: function (data) {
            const response = data['Response'];

            if (response === false) {
                $('<div>Cannot delete artist with album(s)</div>').appendTo('#modalContent');
            }
            else {
                const artists = $('#mainOutput').children('div').eq(0);
                $('#mainOutput').empty();

                artists.find(`input[value=${id}]`).parent().remove();

                artists.appendTo('#mainOutput');
            }
        }
    })
});


//#########################################
//Album methods
//#########################################

//Get all albums
function GetAllAlbums() {
    
}

//Search for albums
function AlbumSearch(searchText) {

}

//#########################################
//Track methods
//#########################################


//Search for tracks
function TrackSearch(searchText) {

}