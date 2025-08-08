<?php
if($_SERVER['REQUEST_METHOD'] === 'POST'){
    $client_id = filter_var($_GET['client_id'] ?? null,FILTER_VALIDATE_INT);
    $quiz_id = filter_var($_GET['quiz_id'] ?? null,FILTER_VALIDATE_INT);
    if($quiz_id && $client_id){
        require_once 'config.php';
        require_once 'utils.php';
        try{
            if(DBUtils::is_user_or_quiz_exist($db,$quiz_id,$client_id)){
                if (DBUtils::is_user_reviewed($db,$client_id, $quiz_id)) {
                    http_response_code(400);
                    die('Вы уже оставляли отзыв ранее');
                }
                $grade = $_POST['grade'];
                $comment = $_POST['comment'] ?? null;
                if(in_array($grade,['1','2','3','4','5'])){
                    $insert_query = $db->prepare('insert into reviews(client_id, grade, comment, quiz_id) values (?, ?, ?, ?)');
                    $insert_query->execute([$client_id, $grade, $comment, $quiz_id]);
                    http_response_code(200);
                    die('Успех!');
                }
                else{
                    http_response_code(400);
                    die('Некорректная оценка');
                }
            }
            else{
                http_response_code(400);
                die('Пользователь не существует');
            }
    }
    catch(PDOException $e){
        error_log('Ошибка при работе с БД'. $e->getMessage());
        die;
    }
    }
    else{
        http_response_code(400);
        die('Некорректный id клиента или опроса');
    }
}
else{
    http_response_code(405);
    die('Метод не разрешен');
}
?>