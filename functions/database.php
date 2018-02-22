<?php
/**
 * Created by PhpStorm.
 * User: Ryan
 * Date: 1/20/2018
 * Time: 12:46 AM.
 */
require __DIR__.'/../backend/config.php';

class database
{
    private static function connectToDatabase()
    {
        global $DBServer, $DBName, $DBUser, $DBPass, $page_start, $page_limit;
        $conn = new PDO('mysql:host='.$DBServer.'; dbname='.$DBName, $DBUser, $DBPass);
        $conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
        $conn->setAttribute(PDO::ATTR_EMULATE_PREPARES, false);

        return $conn;
    }

    private static function closeConnection(&$conn)
    {
        $conn = null;
    }

    /**
     * @return array
     */
    public static function displayCurrentEntries()
    {
        global $page_start, $page_limit;
        $conn = self::connectToDatabase();
        $data_array = [];

        /*
         * If there aren't more entries than $page_limit, set $page_limit to the count
         * and set $page_start to 0
         */
        $count = self::getTotalRows();
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
        self::closeConnection($conn);

        return $data_array;
    }

    /**
     * @return int
     */
    public static function getTotalRows()
    {
        $conn = self::connectToDatabase();

        $sql = $conn->prepare('SELECT * FROM `entries`');
        $sql->execute();
        $count = $sql->rowCount();

        // Close the connection
        self::closeConnection($conn);

        return $count;
    }
}
