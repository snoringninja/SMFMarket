<?php
/**
 * Created by PhpStorm.
 * User: Ryan
 * Date: 1/19/2018
 * Time: 9:50 PM
 */

require __DIR__.'\vendor\autoload.php';
require __DIR__.'\backend\config.php';

function displayCurrentEntries() {
    global $DBServer, $DBName, $DBUser, $DBPass, $page_start, $page_limit;
    $data_array = array();
    $conn = new PDO('mysql:host='. $DBServer .'; dbname='.$DBName, $DBUser, $DBPass);
    $conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
    $conn->setAttribute(PDO::ATTR_EMULATE_PREPARES, false);

    /*
     * If there aren't more entries than $page_limit, set $page_limit to the count
     * and set $page_start to 0
     */
    $sql = $conn->prepare("SELECT * FROM `entries`");
    $sql->execute();
    $count = $sql->rowCount();
    if ($count < $page_limit) {
        $page_limit = $count;
        $page_start = 0;
    }

    $sql = $conn->prepare("SELECT `id`, `offer_type`, `forum_username`, `item`, `amount`, `price`,
                                    `post_date` FROM `entries` ORDER BY `ID` DESC LIMIT $page_start, $page_limit");
    $sql->execute();
    while ($row = $sql->fetch(PDO::FETCH_ASSOC)) {
        array_push($data_array, $row);
    }
    $conn = null;
    return $data_array;
}

$loader = new Twig_Loader_Filesystem(__DIR__.'\views');
$twig = new Twig_Environment($loader, array(
    'cache' => __DIR__.'\cache',
));

echo $twig->render('index.twig', array(
    'name' => '',
    'title' => 'SMF Market',
    'market_items' => displayCurrentEntries(),
));