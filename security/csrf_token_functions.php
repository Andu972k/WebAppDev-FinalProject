<?php
// Must call session_start() before this loads

// Generate a token for use with CSRF protection.
// Does not store the token.
function csrf_token() {
    return md5(uniqid(rand(), TRUE));
}

// Generate and store CSRF token in user session.
// Requires session to have been started already.
function create_csrf_token() {
  if (!isset($_SESSION['csrf_token'])) {
    $token = csrf_token();
    $_SESSION['csrf_token'] = $token;
    $_SESSION['csrf_token_time'] = time();
  }
  else {
    $token = $_SESSION['csrf_token'];
  }
    return $token;
}

// Return an HTML tag including the CSRF token
// for use in a form.
// Usage: echo csrf_token_tag();
function csrf_token_tag() {
    if (!isset($_SESSION['csrf_token'])) {
        $token = create_csrf_token();
    }
    else {
        $token = $_SESSION['csrf_token'];
    }
  
  return "<input type=\"hidden\" name=\"csrf_token\" value=\"".$token."\">";
}

// Returns true if user-submitted POST token is
// identical to the previously stored SESSION token.
// Returns false otherwise.
function csrf_token_is_valid() {
  if(isset($_POST['csrf_token'])) {
    $user_token = $_POST['csrf_token'];
    $stored_token = $_SESSION['csrf_token'];
    return $user_token === $stored_token;
  } else {
    return false;
  }
}

// You can simply check the token validity and
// handle the failure yourself, or you can use
// this "stop-everything-on-failure" function.
function die_on_csrf_token_failure() {
  if(!csrf_token_is_valid()) {
    die("CSRF token validation failed.");
  }
}



?>
