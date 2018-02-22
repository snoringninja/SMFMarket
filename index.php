<?php
/**
 * Created by PhpStorm.
 * User: Ryan
 * Date: 1/19/2018
 * Time: 9:50 PM.
 */
require __DIR__.'/vendor/autoload.php';
require __DIR__.'/functions/database.php';

$loader = new Twig_Loader_Filesystem(__DIR__.'/views');
$twig = new Twig_Environment($loader, [
    'cache' => __DIR__.'/cache',
]);

try {
    echo $twig->render('index.twig', [
        'name'         => '',
        'title'        => 'SMF Market',
        'market_items' => Database::displayCurrentEntries(),
    ]);
} catch (Twig_Error_Loader $e) {
} catch (Twig_Error_Runtime $e) {
} catch (Twig_Error_Syntax $e) {
}
