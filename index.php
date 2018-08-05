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
    ssi_logout();
});

$twig = new Twig_Environment($loader, [
    //'cache' => __DIR__.'/cache',
    'cache' => false,
]);

$twig->addFunction($ssi_logout_function);

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
