<?php
class DBUtils{
    public static function is_user_or_quiz_exist(PDO $db, $quiz_id, $client_id):bool {
        $check_user_query = $db->prepare("select client_id from clients where client_id = ?");
        $check_user_query->execute([$client_id]);
        $user_exist = $check_user_query->fetch();

        $check_quiz_query = $db->prepare("select quiz_id from quizzes where quiz_id = ?");
        $check_quiz_query->execute([$quiz_id]);
        $quiz_exist = $check_quiz_query->fetch();
        return $user_exist && $quiz_exist;
    }
    public static function is_user_reviewed(PDO $db, $client_id, $quiz_id):bool {
        $check_query = $db->prepare('select client_id from reviews where client_id = ? and quiz_id = ? limit 1');
        $check_query->execute([$client_id, $quiz_id]);
        return (bool) $check_query->fetch();
    }
}
?>