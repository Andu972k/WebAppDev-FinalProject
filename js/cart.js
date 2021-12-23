const baseURI = "../src/";

$(function() {

    // Cancel user sign up
    $("input#btnCartCancel").on("click", function() { 
        window.location.replace('login.php') 
    });    
});

//Open Checkout modal
$('input#btnOpenCheckout').on('click', function () {
    $('div.modal').show();
});

//Close modal when the x on screen is pressed
$('span.closeModal').on('click', closeModal);

//Close modal
function closeModal() {
    $('#modal').hide();
}

$('form#formPurchase').on('submit', function (e) {
    e.preventDefault();
    const customerId = $('#inputCustomerId').val();
    const billingAddress = $('#inputBillingAddress').val();
    const billingCity = $('#inputBillingCity').val();
    const billingState = $('#inputBillingState').val();
    const billingCountry = $('#inputBillingCountry').val();
    const billingPostalCode = $('#inputBillingPostalCode').val();
    const billingTotalPrice = $('#inputTotalPrice').val();
    const cart = new Array();
    const form = $(this)

    
    $('#tableCart').children('tr').each(function () {
        const trackId = $(this).find('#inputTrackId').val();
        const unitPrice = $(this).find('#spanUnitPrice').text();
        const quantity = $(this).find('#spanQuantity').text();
        const cartItem = {"TrackId": trackId, "UnitPrice": unitPrice, "Quantity": quantity};
        cart.push(cartItem);
    });
    /*
    let formdata = new FormData();
    formdata.append('CustomerId', customerId);
    formdata.append('BillingAddress', billingAddress);
    formdata.append('BillingCity', billingCity);
    formdata.append('BillingState', billingState);
    formdata.append('BillingCountry', billingCountry);
    formdata.append('BillingPostalCode', billingPostalCode);
    formdata.append('Total', billingTotalPrice);
    formdata.append('Cart', JSON.stringify(cart));
    */
    
    

    $.ajax({
        type: "POST",
        url: baseURI + `customers/${customerId}/cart/checkout`,
        data: JSON.stringify({"BillingAddress": billingAddress, "BillingCity": billingCity, "BillingState": billingState, "BillingCountry": billingCountry, "BillingPostalCode": billingPostalCode, "Total": billingTotalPrice, "Cart": cart}),
        dataType: "json",
        success: function (data) {
            console.log(data);
            console.log(form.serialize());
        },
        error: function (data) {
            console.log(data);
        }
    })
});