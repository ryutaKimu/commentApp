<?php
date_default_timezone_set('Asia/Tokyo');
$comment_array = array();
$pdo = null;
$stmt = null;
$host = "mysql:host=localhost;dbname=bbs_database";
$user = "root";
$password = "root";
$error = [];

try {
    $pdo = new PDO($host, $user, $password);
} catch (PDOException $e) {
    echo $e->getMessage();
}

if (!empty($_POST['submit'])) {
    $post_date = date("Y-m-d H:i:s");


    if (empty($_POST['name'])) {
        $error['name'] = 'blank';
    };

    if (empty($_POST['comment'])) {
        $error['comment'] = 'blank';
    };

    if (empty($error)) {
        $stmt = $pdo->prepare("INSERT INTO bbs_table (name, comment,postDate) VALUES (:name, :comment,:postDate)");
        try {
            $stmt->bindParam(':name', $_POST['name'], PDO::PARAM_STR);
            $stmt->bindParam(':comment', $_POST['comment'], PDO::PARAM_STR);
            $stmt->bindParam(':postDate', $post_date, PDO::PARAM_STR);
            $stmt->execute();
        } catch (PDOException $e) {
            $e->getMessage();
        }
    }
}

function validation($value)
{
    return htmlspecialchars($value, ENT_QUOTES, 'utf-8');
}



$select = "SELECT `id`, `name`, `comment`, `postDate` FROM `bbs_table`;";

$comment_array = $pdo->query($select);






?>


<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="channel.css">
    <title>PHP 掲示板</title>
</head>

<body>
    <h1 class="title">掲示板アプリ</h1>
    <div class="boardWrapper">
        <section>
            <?php foreach ($comment_array as $comment) : ?>
                <article>
                    <div class="wrapper">
                        <div class="nameArea">
                            <span>名前</span>
                            <p class="username"><?php echo validation($comment['name']) ?></p>
                            <time><?php echo $comment['postDate'] ?></time>
                        </div>
                        <p class="comment"><?php echo validation($comment['comment']) ?></p>
                    </div>
                <?php endforeach; ?>
                </article>
        </section>
        <form action="" class="formWrapper" method="post">
            <div>
                <input type="submit" name="submit" value="書き込む">
                <?php if (isset($error['name']) && $error['name'] === 'blank') : ?>
                    <p class="error_message">名前を入力してください</p>
                <?php endif; ?>
                <?php if (isset($error['comment']) && $error['comment'] === 'blank') : ?>
                    <p class="error_message">何か入力してください</p>
                <?php endif; ?>
                <label for="">名前：</label>
                <input type="text" name="name">
            </div>
            <div>
                <textarea name="comment" class="commentTextArea" id="" cols="30" rows="10"></textarea>
            </div>
        </form>
    </div>
    <hr />

</body>

</html>