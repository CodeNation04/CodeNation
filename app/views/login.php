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
    <canvas id="constellation"></canvas>

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

    <script>
    // 마우스 반응 별자리 캔버스 효과
    const canvas = document.getElementById("constellation");
    const ctx = canvas.getContext("2d");
    let w = canvas.width = window.innerWidth;
    let h = canvas.height = window.innerHeight;

    const stars = Array.from({
        length: 100
    }, () => ({
        x: Math.random() * w,
        y: Math.random() * h,
        vx: (Math.random() - 0.5) * 0.3,
        vy: (Math.random() - 0.5) * 0.3
    }));

    let mouse = {
        x: w / 2,
        y: h / 2
    };

    canvas.addEventListener("mousemove", e => {
        mouse.x = e.clientX;
        mouse.y = e.clientY;
    });

    function animate() {
        ctx.clearRect(0, 0, w, h);
        ctx.fillStyle = "#fff";
        stars.forEach(star => {
            star.x += star.vx;
            star.y += star.vy;

            if (star.x < 0 || star.x > w) star.vx *= -1;
            if (star.y < 0 || star.y > h) star.vy *= -1;

            ctx.beginPath();
            ctx.arc(star.x, star.y, 1.2, 0, Math.PI * 2);
            ctx.fill();

            // 선 그리기
            if (Math.hypot(star.x - mouse.x, star.y - mouse.y) < 150) {
                ctx.strokeStyle = "rgba(255, 255, 255, 0.28)";
                ctx.beginPath();
                ctx.moveTo(star.x, star.y);
                ctx.lineTo(mouse.x, mouse.y);
                ctx.stroke();
            }
        });

        requestAnimationFrame(animate);
    }

    animate();

    window.addEventListener("resize", () => {
        w = canvas.width = window.innerWidth;
        h = canvas.height = window.innerHeight;
    });
    </script>
</body>

</html>