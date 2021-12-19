
$('#btnAlbums').on('click', function (e) {

    const currentPagePath = document.location.pathname.split('/');

    const apiPath = currentPagePath.slice(0, currentPagePath.length - 2).join('/') + "/src";

    const baseURI = document.location.origin + apiPath;
    console.log(baseURI)
    $.ajax({
        type: "GET",
        url: baseURI+"/artists",
        dataType: "json",
        success: function (data) {
            console.log(data);
        },
        error: function (data) {
            console.log(data);
        }
    })
});