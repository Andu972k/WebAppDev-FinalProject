<?php
session_start();

if (!isset($_SESSION['userRole']) || !isset($_SESSION['customerID'])) {
    header('Location: view/login.php');
}

if (isset($_POST['action'])) {
    switch ($_POST['action']) {
        case 'remove':
            unset($_SESSION['cart'][$_POST['trackId']]);
            break;
        case 'add':
            $_SESSION['cart'][$_POST['trackId']]['quantity']++;
            break;
        case 'subtract':
            $_SESSION['cart'][$_POST['trackId']]['quantity']--;
            if ($_SESSION['cart'][$_POST['trackId']]['quantity'] == 0) {
                unset($_SESSION['cart'][$_POST['trackId']]);
            }
            break;
        default:
            echo 'Unsupported action';
            break;
    }
}

$cart = <<<'CART'
    <table id="tableCart">
    <tr>
        <th>Track</th>
        <th>Album</th>
        <th>Mediatype</th>
        <th>Genre</th>
        <th>unit price</th>
        <th>Quantity</th>
    </tr>
CART;

$TotalPrice = 0;

foreach ($_SESSION['cart'] as &$cartItem) {
    $cart .= '<td>'. $cartItem['trackName'] .'</td>';
    $cart .= '<td>'. $cartItem['albumTitle'] .'</td>';
    $cart .= '<td>'. $cartItem['mediaType'] . '</td>';
    $cart .= '<td>'. $cartItem['genre'] .'</td>';
    $cart .= '<td><span id="spanUnitPrice">'. $cartItem['price'] .'</span></td>';
    $cart .= '<td><span id="spanQuantity">'. $cartItem['quantity'] .'</span></td>';
    $cart .= '<td><form action="cart.php" method="post"><input type="hidden" name="action" value="add"><input type="hidden" name="trackId" value="'. $cartItem['trackId'] .'"><input type="submit" value="+"></form><form action="cart.php" method="post"><input type="hidden" name="action" value="subtract"><input type="hidden" name="trackId" value="'. $cartItem['trackId'] .'"><input type="submit" value="-"></form><form action="cart.php" method="post"><input type="hidden" name="action" value="remove"><input id="inputTrackId" type="hidden" name="trackId" value="'. $cartItem['trackId'] .'"><input type="submit" value="Remove"></form></td></tr>';
    $TotalPrice = $TotalPrice + ($cartItem['price'] * $cartItem['quantity']);
    
}

$cart .= '</table>';

?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <script src="https://code.jquery.com/jquery-3.6.0.js" defer></script>
    <script src="../js/cart.js" defer></script>
    <link rel="stylesheet preload" as="style" href="../css/Variables.css" crossorigin="anonymous" type="text/css">
    <link rel="stylesheet" href="../css/general.css">
    <link rel="stylesheet" href="../css/cart.css">
    <title>Checkout</title>
</head>
<body>
    <header>
        <h1>
            Cart
        </h1>
    </header>
    <main>
        <section>
            <?php 
                echo $cart;
            ?>
            <input id="btnOpenCheckout" type="button" value="Checkout">
            <input type="button" id="btnCartCancel" value="Back">
            <div id="modal" class="modal">
                <header>
                    <h1>
                        Checkout
                    </h1>
                </header>
                <div class="modalContent">
                    <span class="closeModal">&times;</span>
                    <br>
                    <header>
                        <p>
                            Billing information has been filled using the accounts information,
                            if different billing information is preffered, then please correct it below,
                            this action will not change the accounts default information.
                        </p>
                    </header>
                    <br>
                    <input id="inputCustomerId" type="hidden" value="<?php echo $_SESSION['customerID'] ?>">
                    <label for="inputBillingAddress">BillingAddress</label>
                    <input id="inputBillingAddress" type="text" value="<?php echo $_SESSION['address'] ?>"><br>
                    <label for="inputBillingCity">BillingCity</label>
                    <input id="inputBillingCity" type="text" value="<?php echo $_SESSION['city'] ?>"><br>
                    <label for="inputBillingState">BillingState</label>
                    <input id="inputBillingState" type="text" value="<?php echo $_SESSION['state'] ?>"><br>
                    <label for="inputBillingCountry">BillingCountry</label>
                    <input id="inputBillingCountry" type="text" value="<?php echo $_SESSION['country'] ?>"><br>
                    <label for="inputBillingPostalCode">BillingPostalCode</label>
                    <input id="inputBillingPostalCode" type="text" value="<?php echo $_SESSION['postalCode'] ?>"><br>
                    <label for="inputTotalPrice">TotalPrice</label>
                    <input id="inputTotalPrice" type="text" value="<?php echo $TotalPrice ?>" disabled><br>
                    <input id="inputConfirmPurchase" type="button" value="Purchase">
                </div>
            </div>
        </section>
    </main>
</body>

<?php
include_once('../footer.html');
?>
</html>