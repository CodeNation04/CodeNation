const canvas = document.getElementById("constellation");
const ctx = canvas.getContext("2d");

canvas.width = window.innerWidth;
canvas.height = window.innerHeight;

let stars = [];
const starCount = 100;
let mouse = { x: null, y: null };

window.addEventListener("resize", () => {
   canvas.width = window.innerWidth;
   canvas.height = window.innerHeight;
});

window.addEventListener("mousemove", (e) => {
   mouse.x = e.x;
   mouse.y = e.y;
});

class Star {
   constructor() {
      this.x = Math.random() * canvas.width;
      this.y = Math.random() * canvas.height;
      this.vx = (Math.random() - 0.5) * 0.5;
      this.vy = (Math.random() - 0.5) * 0.5;
      this.radius = Math.random() * 2 + 0.5;
   }

   draw() {
      ctx.beginPath();
      ctx.arc(this.x, this.y, this.radius, 0, Math.PI * 2);
      ctx.fillStyle = "white";
      ctx.fill();
   }

   update() {
      this.x += this.vx;
      this.y += this.vy;

      // 반사
      if (this.x < 0 || this.x > canvas.width) this.vx *= -1;
      if (this.y < 0 || this.y > canvas.height) this.vy *= -1;

      this.draw();
   }
}

function init() {
   stars = [];
   for (let i = 0; i < starCount; i++) {
      stars.push(new Star());
   }
}

function connect() {
   for (let a = 0; a < starCount; a++) {
      for (let b = a + 1; b < starCount; b++) {
         const dx = stars[a].x - stars[b].x;
         const dy = stars[a].y - stars[b].y;
         const dist = dx * dx + dy * dy;

         if (dist < 2000) {
            ctx.beginPath();
            ctx.strokeStyle = "rgba(255, 255, 255, 0.1)";
            ctx.moveTo(stars[a].x, stars[a].y);
            ctx.lineTo(stars[b].x, stars[b].y);
            ctx.stroke();
         }
      }

      // 마우스와 연결
      if (mouse.x !== null && mouse.y !== null) {
         const dx = stars[a].x - mouse.x;
         const dy = stars[a].y - mouse.y;
         const dist = dx * dx + dy * dy;
         if (dist < 3000) {
            ctx.beginPath();
            ctx.strokeStyle = "rgba(255, 255, 255, 0.85)";
            ctx.moveTo(stars[a].x, stars[a].y);
            ctx.lineTo(mouse.x, mouse.y);
            ctx.stroke();
         }
      }
   }
}

function animate() {
   ctx.clearRect(0, 0, canvas.width, canvas.height);
   stars.forEach((star) => star.update());
   connect();
   requestAnimationFrame(animate);
}

init();
animate();
