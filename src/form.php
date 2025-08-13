<head>
    <link rel="stylesheet" href="style.css">
</head>
<main>
    <?php $client_id = filter_var($_GET['client_id'] ?? null,FILTER_VALIDATE_INT);
    $quiz_id = filter_var($_GET['quiz_id'] ?? null,FILTER_VALIDATE_INT);
    require_once 'DBUtils.php';
    if(DBUtils::isUserOrQuizExist($quiz_id,$client_id)):
    ?>
    <h1>Оцените качество обслуживания</h1>
    <form action = "handler.php?client_id=<?php echo $client_id ?>&quiz_id=<?php echo $quiz_id ?>" method = "POST">
        <input type = "radio" name = "grade" id = "one" value = "1">
            <label for = "one">1</label>
        <input type = "radio" name = "grade" id = "two" value = "2">
            <label for = "two">2</label>
        <input type = "radio" name = "grade" id = "three" value = "3">
            <label for = "three">3</label>
        <input type = "radio" name = "grade" id = "four" value = "4">
            <label for = "four">4</label>
        <input type = "radio" name = "grade" id = "five" value = "5" checked>
            <label for = "five">5</label>
        <h2>Комментарий к оценке(не обязательно)</h2>
        <input type="text" name = "comment"> <br>
        <br>
        <button class = "btn" type = "submit">Отправить форму</button>
    </form>
    <?php else:?>
        <h1>Ссылка на голосование недоступна, свяжитесь с нами по телефону</h1>
    <?php endif; ?>

</main>