<?php
/**
 * @author SnoringNinja
 * @site https://snoring.ninja
 */
define('PROJECT_ROOT', __DIR__.'./');

include(__DIR__.'/backend/config.php');
require __DIR__.'/vendor/autoload.php';
require __DIR__.'/functions/database.php';

require_once(PROJECT_ROOT . '/../' . $forum_root . '/SSI.php');

$loader = new Twig_Loader_Filesystem(__DIR__.'/views');

$ssi_logout_function = new Twig_SimpleFunction('ssi_logout_custom', function () {
    // This is taken from the SMF SSI.php, but to include the Font Awesome icon, we global the same variables from SMF
    // and then build the link ourselves; this is THE EXACT string from SMF, with the addition of the Font Awesome icon
    global $scripturl, $context, $txt;

    $protocol = (!empty($_SERVER['HTTPS']) && $_SERVER['HTTPS'] !== 'off' || $_SERVER['SERVER_PORT'] == 443) ? "https://" : "http://";
    $_SESSION['logout_url'] = $protocol . $_SERVER['HTTP_HOST'] . $_SERVER['REQUEST_URI'];

    $link = '<a href="' . $scripturl . '?action=logout;' . $context['session_var'] . '=' .
        $context['session_id'] . '">' . $txt['logout'] . ' <i class="fa fa-sign-out"></i></a>';

    echo $link;
});

$twig = new Twig_Environment($loader, [
    //'cache' => __DIR__.'/cache',
    'cache' => false,
    'debug' => true,
]);

$twig->addFunction($ssi_logout_function);
$twig->addExtension(new Twig_Extension_Debug());

try {
    echo $twig->render('index.twig', [
        'name'         => '',
        'title'        => 'SMF Market',
        'market_items' => Database::displayCurrentEntries(),
        'user_name' => $context['user']['username'],
        'is_guest' => $context['user']['is_guest'],
    ]);
} catch (Twig_Error_Loader $e) {
} catch (Twig_Error_Runtime $e) {
} catch (Twig_Error_Syntax $e) {
}
