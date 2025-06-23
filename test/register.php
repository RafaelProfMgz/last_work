<!DOCTYPE html>
<html lang="pt-br">
<head>
  <meta charset="UTF-8" />
  <title>Cadastro</title>
  <link rel="stylesheet" href="css/styles.css">
</head>
<body>

  <canvas id="nightSky"></canvas>

  <form id="register-form">
    <h2>Cadastro de Usuário</h2>
    <input type="text" name="nome" placeholder="Nome completo" required />
    <input type="email" name="email" placeholder="Email" required />
    <input type="password" name="senha" placeholder="Senha (mín. 8 caracteres)" required />
    <input type="password" name="confirm_senha" placeholder="Confirmar senha" required />
    <button type="submit">Cadastrar</button>
    <div id="mensagem" class="mensagem"></div>
  </form>

<script>
  const form = document.getElementById('register-form');
  const mensagem = document.getElementById('mensagem');

  form.addEventListener('submit', async (e) => {
    e.preventDefault();

    const nome = form.nome.value;
    const email = form.email.value;
    const senha = form.senha.value;
    const confirm_senha = form.confirm_senha.value;

    const formData = {
        nome: nome,
        email: email,
        senha: senha,
        confirm_senha: confirm_senha
    };

    try {
        const response = await fetch('http://localhost/lastwork/backend/register', {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json'
            },
            body: JSON.stringify(formData)
        });

        if (response.ok) {
            const result = await response.json();
            if (result.success) {
                mensagem.textContent = result.message || 'Cadastro realizado com sucesso!';
                mensagem.style.color = 'green';
                setTimeout(() => {
                     window.location.href = 'http://localhost/lastwork/backend/test/login.php';
                }, 1000);
            } else {
                mensagem.textContent = result.errors?.join(', ') || 'Erro no cadastro.';
                mensagem.style.color = 'red';
            }
        } else {
             const errorData = await response.json();
             mensagem.textContent = errorData.errors?.join(', ') || `Erro ${response.status}: ${response.statusText}`;
             mensagem.style.color = 'red';
             console.error('Erro na resposta do servidor:', response.status, response.statusText, errorData);
        }
    } catch (error) {
        mensagem.textContent = 'Erro de conexão com o servidor.';
        mensagem.style.color = 'red';
        console.error('Erro ao enviar requisição:', error);
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

