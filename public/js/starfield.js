const canvas = document.getElementById("starfield");
const ctx = canvas.getContext("2d");

canvas.width = window.innerWidth;
canvas.height = window.innerHeight;

const stars = [];
for (let i = 0; i < 200; i++) {
   stars.push({
      x: Math.random() * canvas.width,
      y: Math.random() * canvas.height,
      r: Math.random() * 1.5 + 0.5,
      dx: (Math.random() - 0.5) * 0.5,
      dy: (Math.random() - 0.5) * 0.5,
   });
}

function draw() {
   ctx.clearRect(0, 0, canvas.width, canvas.height);
   ctx.fillStyle = "white";
   for (let star of stars) {
      ctx.beginPath();
      ctx.arc(star.x, star.y, star.r, 0, Math.PI * 2);
      ctx.fill();

      star.x += star.dx;
      star.y += star.dy;

      if (star.x < 0 || star.x > canvas.width) star.dx *= -1;
      if (star.y < 0 || star.y > canvas.height) star.dy *= -1;
   }
   requestAnimationFrame(draw);
}

draw();
