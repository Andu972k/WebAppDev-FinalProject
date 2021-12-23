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
    $('#modal').empty();
    $('#modal').hide();
}

$('input#inputConfirmPurchase').on('click', function () {
    const customerId = $('#inputCustomerId').val();
    const billingAddress = $('#inputBillingAddress').val();
    const billingCity = $('#inputBillingState').val();
    const billingCountry = $('#inputBillingCountry').val();
    const billingPostalCode = $('#inputBillingPostalCode').val();
    const billingTotalPrice = $('#inputTotalPrice').val();
    const cart = new Array();

    $('#tableCart').children('tr').each(function () {
        const trackId = $(this).find('#inputTrackId').val();
        const unitPrice = $(this).find('#spanUnitPrice').text();
        const quantity = $(this).find('#spanQuantity').text();
        const cartItem = {"TrackId": trackId, "UnitPrice": unitPrice, "Quantity": quantity};
        cart.push(cartItem);
    });

    const formdata = new FormData();
    formdata.append('CustomerId', customerId);
    formdata.append('BillingAddress', billingAddress);
    formdata.append('BillingCity', billingCity);
    formdata.append('BillingCountry', billingCountry);
    formdata.append('BillingPostalCode', billingPostalCode);
    formdata.append('Total', billingTotalPrice);
    formdata.append('Cart', cart);

    

    $.ajax({
        type: "POST",
        url: baseURI + `customers/${customerId}/cart/checkout`,
        data: formdata,
        dataType: "json",
        success: function (data) {
            console.log(data);
        },
        error: function (data) {
            console.log(data);
        }
    })
});