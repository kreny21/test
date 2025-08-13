<?php
function processRequest(){
    if($_SERVER['REQUEST_METHOD'] === 'POST') {
        $client_id = filter_var($_GET['client_id'] ?? null, FILTER_VALIDATE_INT);
        $quiz_id = filter_var($_GET['quiz_id'] ?? null, FILTER_VALIDATE_INT);
        
        if($quiz_id && $client_id) {
            require_once 'DBUtils.php';
            try {
                if(DBUtils::isUserOrQuizExist($quiz_id, $client_id)) {
                    if (DBUtils::isUserReviewed($quiz_id, $client_id)) {
                        return json_encode(['code' => 400, 'status' => 'Вы уже оставляли отзыв ранее!']);
                    }
                    
                    $grade = $_POST['grade'] ?? null;
                    $comment = $_POST['comment'] ?? null;
                    
                    if(in_array($grade, ['1','2','3','4','5'])) {
                        $insert_query = DBUtils::getConnection()->prepare('INSERT INTO reviews(client_id, grade, comment, quiz_id) VALUES (?, ?, ?, ?)');
                        $insert_query->execute([$client_id, $grade, $comment, $quiz_id]);
                        return json_encode(['code' => 200, 'status' => 'Успех!']);
                    }
                    else {
                        return json_encode(['code' => 400, 'status' => 'Некорректная оценка!']);
                    }
                }
                else {
                    return json_encode(['code' => 400, 'status' => 'Пользователь или опрос не существует!']);
                }
            }
            catch(PDOException $e) {
                error_log('Ошибка при работе с БД: '. $e->getMessage());
                return json_encode(['code' => 500, 'status' => 'Ошибка при работе с БД']);
            }
        }
        else {
            return json_encode(['code' => 400, 'status' => 'Некорректный id клиента или опроса!']);
        }
    }
    else {
        return json_encode(['code' => 405, 'status' => 'Метод не разрешен!']);
    }
}
if (basename(__FILE__) == basename($_SERVER['SCRIPT_FILENAME'])) {
    $response = json_decode(processRequest(), true);
    http_response_code($response['code']);
    $status = $response['status'];
    include 'status_template.php';
}
?>