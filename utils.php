<?php
class DBUtils{
    public static function is_user_exist(PDO $db, $client_id):bool {
        $check_user_query = $db->prepare("select client_id from clients where client_id = ?");
        $check_user_query->execute([$client_id]);
        return (bool) $check_user_query->fetch();
    }
    public static function is_user_reviewed(PDO $db, $client_id, $quiz_id):bool {
        $check_query = $db->prepare('select client_id from reviews where client_id = ? and quiz_id = ? limit 1');
        $check_query->execute([$client_id, $quiz_id]);
        return (bool) $check_query->fetch();
    }
}
?>