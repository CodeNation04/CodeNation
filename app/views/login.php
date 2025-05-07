<?php
session_start();

if ($_SERVER["REQUEST_METHOD"] === "POST") {
    $id = $_POST['id'] ?? '';
    $pw = $_POST['pw'] ?? '';

    if ($id === 'admin' && $pw === '1234') {
        $_SESSION['user_id'] = $id;
        $_SESSION['user_role'] = 'super';
        header("Location: main.php");
        exit;
    } else {
        $error = "아이디 또는 비밀번호가 올바르지 않습니다.";
    }
}
?>

<!DOCTYPE html>
<html lang="ko">
<head>
    <meta charset="UTF-8" />
    <title>로그인</title>
</head>
<body>
    <h2>로그인</h2>
    <?php if (!empty($error)) echo "<p style='color:red;'>$error</p>"; ?>
    <form method="post" action="/?url=AuthController/login">
        <input type="text" name="id" placeholder="아이디" required><br>
        <input type="password" name="pw" placeholder="비밀번호" required><br>
        <button type="submit">로그인</button>
    </form>
</body>
</html>
