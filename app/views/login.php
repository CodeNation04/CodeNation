<?php
if (!empty($_SESSION['admin_id'])) {
    header("Location: /?url=MainController/index");
    exit();
}
?>

<!DOCTYPE html>
<html lang="ko">

<head>
    <meta charset="UTF-8" />
    <title>로그인</title>
    <link rel="stylesheet" href="css/main.css" />
    <link rel="stylesheet" href="css/login.css" />
</head>

<body>
    <div class="login-container">
        <div class="login-image">
            <img src="asset/sample.jpg" alt="로그인 이미지">
        </div>
        <div class="login-form">
            <p class="login-title">i-Mon 관리자 페이지</p>
            <h2>로그인</h2>
            <?php if (!empty($error)) echo "<p style='color:red;'>$error</p>"; ?>
            <form method="post" action="/?url=AuthController/login">
                <input type="text" name="id" placeholder="아이디" required><br>
                <input type="password" name="pw" placeholder="비밀번호" required><br>
                <button type="submit">로그인</button>
            </form>
        </div>
    </div>
</body>

</html>