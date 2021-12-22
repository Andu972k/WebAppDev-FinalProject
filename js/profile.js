const baseURI = "../src/";

$(function() {

    // Cancel user sign up
    $("input#btnEditProfileCancel").on("click", function() { 
        window.location.replace('login.php') 
    });    
});

$('form#formEditProfile').on('submit', function (e) {
    e.preventDefault();
    const id = $('#customerId').val();
    const firstname = $('#txtFirstName').val();
    const lastName = $('#txtLastName').val();
    const email = $('#txtEmail').val();
    const oldPassword = $('#txtOldPassword').val();
    let password = $('#txtPassword').val();
    const company = $('#txtCompany').val();
    const address = $('#txtAddress').val();
    const city = $('#txtCity').val();
    const state = $('#txtState').val();
    const country = $('#txtCountry').val();
    const postalCode = $('#txtPostalCode').val();
    const phone = $('#txtPhone').val();
    const fax = $('#txtFax').val();

    if (oldPassword === password) {
        password = '';
    }

    $.ajax({
        type: "PUT",
        url: baseURI + `customers/${id}`,
        data: JSON.stringify({"FirstName": firstname, "LastName": lastName, "Password": oldPassword, "NewPassword": password, "Company": company, "Address": address, "City": city, "State": state, "Country": country, "PostalCode": postalCode, "Phone": phone, "Fax": fax, "Email": email}),
        dataType: "json",
        contentType: "application/json",
        success: async function (data) {
            console.log(data['Response']);
            if (data['Response'] === true) {
                $('<div>Profile edit successful, you will now be logged out<div>').appendTo('section');
                await setTimeout(function () {
                    $('#btnLogOut').trigger('click');
                }, 5000);
                
            }
            
        },
        error: function (data) {
            console.log(data);
        }
    });
});

