<?php
require __DIR__.'/../vendor/autoload.php';

require_once PROJECT_ROOT.'/../'.$forum_root.'/SSI.php';

// This is taken from the SMF SSI.php, but to include the Font Awesome icon, we global the same variables from SMF
// and then build the link ourselves; this is THE EXACT string from SMF, with the addition of the Font Awesome icon
global $scripturl, $context, $txt;

$loader = new Twig_Loader_Filesystem(__DIR__.'/../views');

$ssi_logout_function = new Twig_SimpleFunction('ssi_logout_custom', function () {
    $protocol = (!empty($_SERVER['HTTPS']) && $_SERVER['HTTPS'] !== 'off' || $_SERVER['SERVER_PORT'] == 443) ? 'https://' : 'http://';
    $return_link = $protocol.$_SERVER['HTTP_HOST'].$_SERVER['REQUEST_URI'];
    ssi_logout($redirect_to = $return_link);
});

$ssi_login_function = new Twig_SimpleFunction('ssi_login_custom', function() {
    $protocol = (!empty($_SERVER['HTTPS']) && $_SERVER['HTTPS'] !== 'off' || $_SERVER['SERVER_PORT'] == 443) ? 'https://' : 'http://';
    $return_link = $protocol.$_SERVER['HTTP_HOST'].$_SERVER['REQUEST_URI'];
    ssi_login($redirect_to = $return_link);
});

$twig = new Twig_Environment($loader, [
    //'cache' => __DIR__.'/cache',
    'cache' => false,
]);

$twig->addFunction($ssi_logout_function);
$twig->addFunction($ssi_login_function);