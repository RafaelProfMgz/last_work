<!DOCTYPE html>
<html lang="pt-br">
<head>
  <meta charset="UTF-8" />
  <title>Login Noturno</title>
  <link rel="stylesheet" href="css/styles.css">
</head>
<body>

  <canvas id="nightSky"></canvas>

  <form id="login-form">
    <h2>Login</h2>
    <input type="email" name="email" placeholder="Email" required />
    <input type="password" name="senha" placeholder="Senha" required />
    <button type="submit">Entrar</button>
    <div id="erro" class="erro"></div>
  </form>

  <script>
    const form = document.getElementById('login-form');
    const erroDiv = document.getElementById('erro');

    form.addEventListener('submit', async (e) => {
      e.preventDefault();

      const email = form.email.value;
      const senha = form.senha.value;
      const response = await fetch('/lastwork/backend/api/login', {
        method: 'POST',
        headers: { 'Content-Type': 'application/json' },
        body: JSON.stringify({ email, senha })
      });

      if (response.ok) {
        const result = await response.json();
        if (result.success) {
          window.location.href = 'http://localhost/lastwork/backend/test/dashboard.php';
        } else {
          erroDiv.textContent = result.errors?.join(', ') || 'Erro desconhecido no login.';

          erroDiv.classList.add('visible');
        }
      } else {
        erroDiv.textContent = 'Erro de conexão com o servidor. Status: ' + response.status;
        erroDiv.classList.add('visible');
        console.error(response.status, response.statusText);
      }
    });


    const canvas = document.getElementById('nightSky');
    const ctx = canvas.getContext('2d');
    let stars = [];
    const numStars = 200;

    function resizeCanvas() {
      canvas.width = window.innerWidth;
      canvas.height = window.innerHeight;
      createStars();
    }

    class Star {
      constructor(x, y, radius, twinkleSpeed) {
        this.x = x;
        this.y = y;
        this.radius = radius;
        this.originalRadius = radius;
        this.twinkleSpeed = twinkleSpeed;
        this.alpha = Math.random();
        this.direction = Math.random() > 0.5 ? 1 : -1;
      }

      draw() {
        ctx.beginPath();
        ctx.arc(this.x, this.y, this.radius, 0, Math.PI * 2, false);
        ctx.fillStyle = `rgba(255, 255, 255, ${this.alpha})`;
        ctx.fill();
      }

      update() {
        this.alpha += this.twinkleSpeed * this.direction;

        if (this.alpha > 1 || this.alpha < 0) {
          this.direction *= -1;
        }

        this.alpha = Math.max(0, Math.min(1, this.alpha));

        this.radius = this.originalRadius * (0.8 + 0.2 * this.alpha);
      }
    }

    function createStars() {
      stars = [];
      for (let i = 0; i < numStars; i++) {
        const x = Math.random() * canvas.width;
        const y = Math.random() * canvas.height;
        const radius = Math.random() * 1.5 + 0.5;
        const twinkleSpeed = Math.random() * 0.02 + 0.005;
        stars.push(new Star(x, y, radius, twinkleSpeed));
      }
    }

    function animate() {
      requestAnimationFrame(animate);
      ctx.clearRect(0, 0, canvas.width, canvas.height);

      ctx.fillStyle = '#1a237e';
      ctx.fillRect(0, 0, canvas.width, canvas.height);

      stars.forEach(star => {
        star.update();
        star.draw();
      });
    }

    window.addEventListener('resize', resizeCanvas);
    resizeCanvas();
    animate();
  </script>

</body>
</html>

