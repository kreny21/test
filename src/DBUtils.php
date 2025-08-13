<?php
class DBUtils{
    private static $db;

    public static function getConnection():PDO {
        if (!self::$db) {
            require_once 'config.php';
            self::$db = new PDO($dsn, $username, $password);
        }
        return self::$db;
    }
    public static function isUserOrQuizExist($quiz_id, $client_id):bool {
        $check_user_query = self::getConnection()->prepare('SELECT client_id FROM clients WHERE client_id = ?');
        $check_user_query->execute([$client_id]);
        $user_exist = $check_user_query->fetch();

        $check_quiz_query = self::getConnection()->prepare('SELECT quiz_id FROM quizzes WHERE quiz_id = ?');
        $check_quiz_query->execute([$quiz_id]);
        $quiz_exist = $check_quiz_query->fetch();
        return (bool) $user_exist && $quiz_exist;
    }
    public static function isUserReviewed($quiz_id, $client_id):bool {
        $check_query = self::getConnection()->prepare('SELECT client_id FROM reviews WHERE client_id = ? AND quiz_id = ? LIMIT 1');
        $check_query->execute([$client_id, $quiz_id]);
        return (bool) $check_query->fetch();
    }
}
?>