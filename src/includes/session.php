<?php
  //session_set_cookie_params(0, '/', 'www.fe.up.pt', true, true);
  ini_set('use_only_cookies', '1');
  ini_set('use_trans_sid', '0');
  session_start();
  // session_regenerate_id(true);

  function generate_random_token() {
    return bin2hex(openssl_random_pseudo_bytes(32));
  }

  if (!isset($_SESSION['csrf'])) {
    $_SESSION['csrf'] = generate_random_token();
  }
?>