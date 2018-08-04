<?php
/**
 * @author SnoringNinja
 * @site https://snoring.ninja
 */
require __DIR__.'/../backend/config.php';

class database
{
    private static function connectToDatabase()
    {
        /*
         * @TODO: We should display on the homepage if the database wasn't accessible, only if the user has Admin rights
         *        within the SMF forum permissions system
         */
        try {
            global $DBServer, $DBName, $DBUser, $DBPass, $page_start, $page_limit;
            $conn = new PDO('mysql:host='.$DBServer.'; dbname='.$DBName, $DBUser, $DBPass);
            $conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
            $conn->setAttribute(PDO::ATTR_EMULATE_PREPARES, false);
        } catch (PDOException $ex) {
            $conn = null;
        }

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
        // I wonder if we should just do one single try..catch around where we set $conn; just return right away if the
        // connection isn't valid
        global $page_start, $page_limit;
        $conn = self::connectToDatabase();
        $data_array = [];

        /*
         * If there aren't more entries than $page_limit, set $page_limit to the count
         * and set $page_start to 0
         *
         */
        $count = self::getTotalRows();
        if ($count < $page_limit) {
            $page_limit = $count;
            $page_start = 0;
        }

        if ($conn) {
            try {
                $sql = $conn->prepare("SELECT `id`, `offer_type`, `forum_username`, `item`, `amount`, `price`,
                                                `post_date` FROM `sales` ORDER BY `ID` DESC LIMIT $page_start, $page_limit");
                $sql->execute();
                while ($row = $sql->fetch(PDO::FETCH_ASSOC)) {
                    array_push($data_array, $row);
                }

                // Close the connection
                self::closeConnection($conn);
            } catch (PDOException $ex) {
                $data_array = [];
            }
        } else {
            $data_array = [];
        }

        return $data_array;
    }

    /**
     * @return int
     */
    public static function getTotalRows()
    {
        $conn = self::connectToDatabase();

        if ($conn) {
            try {
                $sql = $conn->prepare('SELECT * FROM `sales`');
                $sql->execute();
                $count = $sql->rowCount();

                // Close the connection
                self::closeConnection($conn);
            } catch (PDOException $ex) {
                $count = 0;
            }
        } else {
            $count = 0;
        }

        return $count;
    }
}
