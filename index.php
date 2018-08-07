<?php
/**
 * @author SnoringNinja
 * @site https://snoring.ninja
 */
define('PROJECT_ROOT', __DIR__.'./');

include __DIR__.'/backend/config.php';
include __DIR__.'/backend/controller.php';
require __DIR__.'/functions/database.php';

try {
    echo $twig->render('index.twig', [
        'name'         => '',
        'title'        => 'SMF Market',
        'market_items' => Database::displayCurrentEntries(),
        'user_name'    => $context['user']['username'],
        'is_guest'     => $context['user']['is_guest'],
    ]);
} catch (Twig_Error_Loader $e) {
} catch (Twig_Error_Runtime $e) {
} catch (Twig_Error_Syntax $e) {
}
