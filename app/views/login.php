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
    <link rel="stylesheet" href="css/login.css" />
</head>

<body>
    <canvas id="starfield"></canvas>

    <div class="login-container">
        <div class="login-image">
            <img src="asset/sample.jpg" alt="로그인 이미지">
        </div>
        <div class="login-form">
            <p class="login-title">i-Mon 관리자 페이지</p>
            <h2>로그인</h2>
            <?php if (!empty($error)) echo "<p style='color:red;'>$error</p>"; ?>
            <form method="post" action="/?url=AuthController/login">
                <input type="text" name="id" placeholder="아이디" required>
                <input type="password" name="pw" placeholder="비밀번호" required>
                <button type="submit">로그인</button>
            </form>
        </div>
    </div>

    <script>
    const canvas = document.getElementById('starfield');
    const ctx = canvas.getContext('2d');
    let stars = [];

    function resizeCanvas() {
        canvas.width = window.innerWidth;
        canvas.height = window.innerHeight;
    }

    function createStars(count) {
        stars = [];
        for (let i = 0; i < count; i++) {
            stars.push({
                x: Math.random() * canvas.width,
                y: Math.random() * canvas.height,
                radius: Math.random() * 1.5,
                speed: Math.random() * 0.3 + 0.1
            });
        }
    }

    function animateStars() {
        ctx.clearRect(0, 0, canvas.width, canvas.height);
        ctx.fillStyle = 'white';
        stars.forEach(star => {
            star.y += star.speed;
            if (star.y > canvas.height) {
                star.y = 0;
                star.x = Math.random() * canvas.width;
            }
            ctx.beginPath();
            ctx.arc(star.x, star.y, star.radius, 0, Math.PI * 2);
            ctx.fill();
        });
        requestAnimationFrame(animateStars);
    }

    window.addEventListener('resize', () => {
        resizeCanvas();
        createStars(150);
    });

    resizeCanvas();
    createStars(150);
    animateStars();
    </script>
</body>

</html>