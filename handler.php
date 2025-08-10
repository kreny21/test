<?php
if($_SERVER['REQUEST_METHOD'] === 'POST') {
    $client_id = filter_var($_GET['client_id'] ?? null, FILTER_VALIDATE_INT);
    $quiz_id = filter_var($_GET['quiz_id'] ?? null, FILTER_VALIDATE_INT);
    
    if($quiz_id && $client_id) {
        require_once 'DBUtils.php';
        try {
            if(DBUtils::is_user_or_quiz_exist($quiz_id, $client_id)) {
                if (DBUtils::is_user_reviewed($quiz_id, $client_id)) {
                    http_response_code(400);
                    $status = 'Вы уже оставляли отзыв ранее!';
                    include 'status_template.php';
                    exit;
                }
                
                $grade = $_POST['grade'];
                $comment = $_POST['comment'] ?? null;
                
                if(in_array($grade, ['1','2','3','4','5'])) {
                    $insert_query = DBUtils::getConnection()->prepare('INSERT INTO reviews(client_id, grade, comment, quiz_id) VALUES (?, ?, ?, ?)');
                    $insert_query->execute([$client_id, $grade, $comment, $quiz_id]);
                    http_response_code(200);
                    $status = 'Успех!';
                    include 'status_template.php';
                    exit;
                }
                else {
                    http_response_code(400);
                    $status = 'Некорректная оценка!';
                    include 'status_template.php';
                    exit;
                }
            }
            else {
                http_response_code(400);
                $status = 'Пользователь или опрос не существует!';
                include 'status_template.php';
                exit;
            }
        }
        catch(PDOException $e) {
            error_log('Ошибка при работе с БД: '. $e->getMessage());
            http_response_code(500);
            $status = 'Ошибка при работе с БД';
            include 'status_template.php';
            die;
        }
    }
    else {
        http_response_code(400);
        $status = 'Некорректный id клиента или опроса!';
        include 'status_template.php';
        exit;
    }
}
else {
    http_response_code(405);
    $status = 'Метод не разрешен!';
    include 'status_template.php';
    exit;
}
?>