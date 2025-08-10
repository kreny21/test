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
    public static function is_user_or_quiz_exist($quiz_id, $client_id):bool {
        $check_user_query = self::getConnection()->prepare('select client_id from clients where client_id = ?');
        $check_user_query->execute([$client_id]);
        $user_exist = $check_user_query->fetch();

        $check_quiz_query = self::getConnection()->prepare('select quiz_id from quizzes where quiz_id = ?');
        $check_quiz_query->execute([$quiz_id]);
        $quiz_exist = $check_quiz_query->fetch();
        return (bool) $user_exist && $quiz_exist;
    }
    public static function is_user_reviewed($quiz_id, $client_id):bool {
        $check_query = self::getConnection()->prepare('select client_id from reviews where client_id = ? and quiz_id = ? limit 1');
        $check_query->execute([$client_id, $quiz_id]);
        return (bool) $check_query->fetch();
    }
}
?>