<?php
require_once 'config/database.php';

// Buscar notícias do banco de dados
$stmt = $pdo->query("SELECT * FROM noticias ORDER BY destaque DESC, criado_em DESC LIMIT 6");
$noticias = $stmt->fetchAll();

// Contador simples de visitas (opcional)
$arquivo_contador = 'contador.txt';
if(file_exists($arquivo_contador)) {
    $visitas = file_get_contents($arquivo_contador);
    $visitas = (int)$visitas + 1;
} else {
    $visitas = 1;
}
file_put_contents($arquivo_contador, $visitas);
?>
<!DOCTYPE html>
<html lang="pt">
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<title>Colégio Tia Lucinda</title>
<style>
*{
    margin:0;
    padding:0;
    box-sizing:border-box;
    font-family:Arial, Helvetica, sans-serif;
}

body{
    background:#f5f5f5;
    opacity:0;
    animation: fadeInBody 0.8s ease forwards;
}

@keyframes fadeInBody {
    to { opacity: 1; }
}

/* MENU */
header{
    background:#0d0d4d;
    padding:15px 5%;
    display:flex;
    justify-content:space-between;
    align-items:center;
    position:sticky;
    top:0;
    z-index:100;
    box-shadow:0 2px 10px rgba(0,0,0,0.2);
}

.logo{
    color:white;
    font-size:22px;
    font-weight:bold;
    display:flex;
    align-items:center;
    gap:10px;
}

.logo img{
    height:50px;
    transition:transform 0.3s;
}

.logo img:hover{
    transform:scale(1.05);
}

nav a{
    color:white;
    text-decoration:none;
    margin:15px;
    font-weight:bold;
    transition:color 0.3s;
}

nav a:hover{
    color:gold;
}

/* HERO */
.hero{
    height:100vh;
    background:linear-gradient(rgba(0,0,0,0.5),rgba(0,0,0,0.5)), url('images/4.png');
    background-size:cover;
    background-position:center;
    display:flex;
    align-items:center;
    padding:50px;
    color:white;
    position:relative;
}

.hero-text{
    max-width:600px;
    animation: slideInLeft 1s ease;
}

@keyframes slideInLeft {
    from {
        opacity:0;
        transform:translateX(-50px);
    }
    to {
        opacity:1;
        transform:translateX(0);
    }
}

.hero-text h1{
    font-size:60px;
    margin-bottom:20px;
}

.hero-text p{
    font-size:20px;
    margin-bottom:20px;
}

.btn{
    display:inline-block;
    padding:15px 30px;
    background:gold;
    color:black;
    text-decoration:none;
    border-radius:5px;
    font-weight:bold;
    transition:all 0.3s;
    cursor:pointer;
    border:none;
}

.btn:hover{
    background:#ffd700;
    transform:scale(1.05);
    box-shadow:0 5px 15px rgba(0,0,0,0.2);
}

/* SEÇÕES */
section{
    padding:80px 10%;
}

.titulo{
    text-align:center;
    margin-bottom:40px;
    font-size:40px;
    color:#0d0d4d;
    position:relative;
}

.titulo:after{
    content:'';
    display:block;
    width:80px;
    height:4px;
    background:gold;
    margin:15px auto 0;
    border-radius:2px;
}

/* SOBRE */
.sobre-container{
    display:flex;
    gap:40px;
    align-items:center;
    flex-wrap:wrap;
}

.sobre-imagens{
    width:45%;
    display:flex;
    gap:15px;
    flex-wrap:wrap;
}

.sobre-imagens img{
    width:48%;
    border-radius:10px;
    object-fit:cover;
    height:250px;
    transition:transform 0.3s;
}

.sobre-imagens img:hover{
    transform:scale(1.02);
}

.sobre-imagens img:first-child{
    width:100%;
}

.sobre-texto{
    width:50%;
}

.sobre-texto p{
    line-height:1.8;
    margin-bottom:20px;
    color:#333;
}

.diferenciais{
    display:grid;
    grid-template-columns:repeat(2,1fr);
    gap:15px;
    margin-top:25px;
}

.diferencial-item{
    display:flex;
    align-items:center;
    gap:10px;
    font-size:14px;
    background:#f0f0f0;
    padding:10px;
    border-radius:8px;
    transition:transform 0.3s;
}

.diferencial-item:hover{
    transform:translateX(5px);
    background:#e0e0e0;
}

.diferencial-item span{
    font-size:20px;
}

/* CARDS */
.cards{
    display:grid;
    grid-template-columns:repeat(auto-fit,minmax(250px,1fr));
    gap:20px;
}

.card{
    background:white;
    padding:30px;
    border-radius:10px;
    box-shadow:0 0 10px rgba(0,0,0,0.1);
    text-align:center;
    transition:all 0.3s;
}

.card:hover{
    transform:translateY(-8px);
    box-shadow:0 15px 30px rgba(0,0,0,0.15);
}

.card h3{
    margin:20px 0;
    color:#0d0d4d;
}

/* NOTÍCIAS - DESTAQUE EM AMARELO */
.noticias-header{
    display:flex;
    justify-content:space-between;
    align-items:center;
    margin-bottom:30px;
    flex-wrap:wrap;
}

.btn-ver-todas{
    background:#0d0d4d;
    color:white;
    padding:10px 20px;
    border-radius:25px;
    text-decoration:none;
    font-size:14px;
    transition:all 0.3s;
}

.btn-ver-todas:hover{
    background:gold;
    color:#0d0d4d;
}

.noticias-grid{
    display:grid;
    grid-template-columns:repeat(auto-fit,minmax(350px,1fr));
    gap:25px;
}

/* CARD DE NOTÍCIA COM FUNDO AMARELO/DOURADO */
.noticia-card{
    background: #FFF8DC;  /* Fundo amarelo claro */
    border-radius:10px;
    padding:25px;
    box-shadow:0 2px 10px rgba(0,0,0,0.1);
    transition:all 0.3s;
    cursor:pointer;
    border-left:5px solid gold;
}

.noticia-card:hover{
    transform:translateY(-5px);
    box-shadow:0 15px 30px rgba(0,0,0,0.15);
    background: #FFFACD;  /* Amarelo mais forte ao passar o mouse */
}

.noticia-card h3{
    color:#0d0d4d;
    margin-bottom:15px;
    font-size:18px;
}

.noticia-card p{
    color:#333;
    line-height:1.5;
    margin-bottom:15px;
}

.noticia-data{
    font-size:12px;
    color:#666;
}

/* Notícia em destaque (mais escura) */
.noticia-card.destaque{
    background: #FFE4B5;  /* Laranja/amarelo mais forte para destaque */
    border-left:5px solid #ff8c00;
}

/* GALERIA */
.galeria{
    display:grid;
    grid-template-columns:repeat(auto-fit,minmax(250px,1fr));
    gap:20px;
}

.galeria img{
    width:100%;
    height:220px;
    object-fit:cover;
    border-radius:10px;
    transition:all 0.3s;
}

.galeria img:hover{
    transform:scale(1.05);
    box-shadow:0 10px 20px rgba(0,0,0,0.2);
}

/* CONTACTO */
.contacto{
    background:#0d0d4d;
    color:white;
    border-radius:10px;
    text-align:center;
    padding:50px;
}

.contacto input,
.contacto textarea{
    width:80%;
    padding:15px;
    border:none;
    border-radius:5px;
    font-size:16px;
    margin:10px 0;
    transition:box-shadow 0.3s;
}

.contacto input:focus,
.contacto textarea:focus{
    outline:none;
    box-shadow:0 0 0 3px gold;
}

.contacto button{
    border:none;
    cursor:pointer;
}

#sucesso{
    color:lightgreen;
    font-size:20px;
    font-weight:bold;
}

/* FOOTER */
footer{
    background:#080830;
    color:white;
    padding:40px 10% 20px;
}

.footer-content{
    display:grid;
    grid-template-columns:repeat(auto-fit,minmax(250px,1fr));
    gap:30px;
    margin-bottom:30px;
}

.footer-section h4{
    margin-bottom:20px;
    color:gold;
}

.footer-section a{
    color:#ccc;
    text-decoration:none;
    display:block;
    margin:10px 0;
    transition:color 0.3s;
}

.footer-section a:hover{
    color:gold;
}

.footer-bottom{
    text-align:center;
    padding-top:20px;
    border-top:1px solid #1a1a4a;
    font-size:12px;
}

.admin-link{
    position:fixed;
    bottom:20px;
    right:20px;
    background:#0d0d4d;
    color:white;
    padding:10px 15px;
    border-radius:5px;
    text-decoration:none;
    font-size:12px;
    opacity:0.6;
    transition:opacity 0.3s;
    z-index:99;
}

.admin-link:hover{
    opacity:1;
}

/* BOTÃO VOLTAR AO TOPO */
.back-to-top{
    position:fixed;
    bottom:20px;
    left:20px;
    background:#0d0d4d;
    color:white;
    width:45px;
    height:45px;
    border-radius:50%;
    display:flex;
    align-items:center;
    justify-content:center;
    text-decoration:none;
    font-size:20px;
    opacity:0;
    visibility:hidden;
    transition:all 0.3s;
    z-index:99;
}

.back-to-top.show{
    opacity:0.6;
    visibility:visible;
}

.back-to-top:hover{
    opacity:1;
}

/* RESPONSIVO */
@media(max-width:900px){
    .sobre-container{
        flex-direction:column;
    }
    .sobre-imagens, .sobre-texto{
        width:100%;
    }
    .hero-text h1{
        font-size:40px;
    }
    nav a{
        margin:8px;
        font-size:12px;
    }
    .diferenciais{
        grid-template-columns:1fr;
    }
}

@media(max-width:480px){
    header{
        flex-direction:column;
        gap:10px;
    }
    .logo{
        font-size:16px;
    }
    .logo img{
        height:35px;
    }
    .hero-text h1{
        font-size:28px;
    }
    .titulo{
        font-size:28px;
    }
}
</style>
</head>
<body>

<!-- MENU -->
<header>
    <div class="logo">
        <img src="images/logo-lucinda.jpeg" alt="Logo" style="border-radius: 5px;" onerror="this.style.display='none'">
        COLÉGIO TIA LUCINDA
    </div>
    <nav>
        <a href="#inicio">Início</a>
        <a href="#sobre">Sobre Nós</a>
        <a href="#ensino">Ensino</a>
        <a style="color:#ffd700 ;" href="#noticias">Notícias</a>
        <a href="#galeria">Galeria</a>
        <a href="#contacto">Contacto</a>
    </nav>
</header>

<!-- PAGINA INICIAL -->
<section class="hero" id="inicio">
    <div class="hero-text">
        <h1>COLÉGIO TIA LUCINDA</h1>
        <p>Explorando horizontes, construindo futuros.</p>
        <a href="#sobre" class="btn">Conheça Nossa Escola</a>
    </div>
</section>

<!-- SOBRE -->
<section id="sobre">
    <h2 class="titulo">Sobre o Colégio</h2>
    
    <div class="sobre-container">
        <div class="sobre-imagens">
            <img src="images/6.png" alt="Fachada do Colégio">
            <img src="images/2.png" alt="Alunos em sala de aula">
            <img src="images/4.png" alt="Atividades pedagógicas">
        </div>
        
        <div class="sobre-texto">
            <p><strong>🏫 Mais de 20 anos formando cidadãos</strong><br>
            Fundado com o propósito de oferecer ensino de excelência, o <strong>Colégio Tia Lucinda</strong> é referência em educação de qualidade, unindo tradição e inovação para preparar seus alunos para os desafios do futuro.</p>
            
            <p><strong>🎯 Nossa Missão</strong><br>
            Oferecer uma formação acadêmica sólida, aliada ao desenvolvimento social e humano, formando cidadãos críticos, éticos e preparados para transformar a sociedade.</p>
            
            <p><strong>⭐ Nossos Valores</strong><br>
            Trabalhamos com base no respeito, responsabilidade, compromisso com a educação e valorização de cada aluno como ser único e especial.</p>
            
            <div class="diferenciais">
                <div class="diferencial-item"><span>✅</span> Ensino de qualidade reconhecido</div>
                <div class="diferencial-item"><span>✅</span> Corpo docente qualificado</div>
                <div class="diferencial-item"><span>✅</span> Infraestrutura moderna e acolhedora</div>
                <div class="diferencial-item"><span>✅</span> Atividades extracurriculares</div>
            </div>
            
            <p><br><em>Este website foi desenvolvido como projeto de defesa de fim de ano, com o objetivo de modernizar a apresentação da instituição e aproximar a comunidade escolar.</em></p>
        </div>
    </div>
</section>

<!-- ENSINO -->
<section id="ensino">
    <h2 class="titulo">Áreas de Ensino</h2>
    <div class="cards">
        <div class="card">
            <h3>📚 Educação Infantil</h3>
            <p>Ensino para os primeiros anos escolares, desenvolvendo habilidades fundamentais de forma lúdica e acolhedora.</p>
        </div>
        <div class="card">
            <h3>✏️ Ensino Primário</h3>
            <p>Formação académica sólida, com foco no desenvolvimento social, cognitivo e emocional dos alunos.</p>
        </div>
        <div class="card">
            <h3>🎓 Ensino Secundário</h3>
            <p>Preparação completa para o futuro profissional e acadêmico, com disciplinas que estimulam o pensamento crítico.</p>
        </div>
    </div>
</section>

<!-- NOTÍCIAS COM FUNDO AMARELO -->
<section id="noticias">
    <div class="noticias-header">
        <h2 class="titulo" style="margin-bottom:0;">📰 Últimas Notícias</h2>
        <a href="noticias.php" class="btn-ver-todas">Ver todas →</a>
    </div>
    <div class="noticias-grid">
        <?php if(count($noticias) > 0): ?>
            <?php foreach($noticias as $noticia): ?>
                <div class="noticia-card <?php echo $noticia['destaque'] ? 'destaque' : ''; ?>" onclick="window.location='noticia-single.php?id=<?php echo $noticia['id']; ?>'">
                    <h3><?php echo htmlspecialchars($noticia['titulo']); ?></h3>
                    <p><?php echo nl2br(htmlspecialchars(substr($noticia['conteudo'], 0, 150))); ?>...</p>
                    <div class="noticia-data">📅 <?php echo date('d/m/Y', strtotime($noticia['criado_em'])); ?></div>
                </div>
            <?php endforeach; ?>
        <?php else: ?>
            <div class="noticia-card">
                <h3>Em breve novidades!</h3>
                <p>Em breve teremos novidades e eventos do nosso colégio. Fique atento!</p>
            </div>
        <?php endif; ?>
    </div>
</section>

<!-- GALERIA -->
<section id="galeria">
    <h2 class="titulo">Galeria</h2>
    <div class="galeria">
        <img src="images/1.png" alt="Foto 1">
        <img src="images/2.png" alt="Foto 2">
        <img src="images/3.png" alt="Foto 3">
        <img src="images/4.png" alt="Foto 4">
        <img src="images/5.png" alt="Foto 5">
        <img src="images/6.png" alt="Foto 6">
    </div>
</section>

<!-- CONTACTO -->
<section id="contacto">
    <div class="contacto">
        <h2 class="titulo" style="color:white;">Contacto</h2>
        <form id="formulario">
            <input type="text" id="nome" placeholder="Digite o seu nome" required>
            <br>
            <input type="email" id="email" placeholder="Digite o seu email" required>
            <br>
            <input type="tel" id="telefone" placeholder="Digite o seu número" required>
            <br>
            <textarea id="mensagem" placeholder="Digite a sua mensagem" rows="5" required></textarea>
            <br>
            <button type="submit" class="btn">Enviar Mensagem</button>
        </form>
        <br>
        <p id="sucesso"></p>
    </div>
</section>

<!-- FOOTER -->
<footer>
    <div class="footer-content">
        <div class="footer-section">
            <h4>📚 Colégio Tia Lucinda</h4>
            <p>Educação de qualidade há mais de 20 anos, formando cidadãos preparados para o futuro.</p>
        </div>
        <div class="footer-section">
            <h4>🔗 Links Rápidos</h4>
            <a href="#inicio">Início</a>
            <a href="#sobre">Sobre Nós</a>
            <a href="#ensino">Ensino</a>
            <a href="noticias.php">Notícias</a>
            <a href="#galeria">Galeria</a>
        </div>
        <div class="footer-section">
            <h4>📞 Contacto</h4>
            <p>📍 Luanda, Angola</p>
            <p>📧 contato@colegiotialucinda.com</p>
            <p>📱 +244 900 000 000</p>
        </div>
        <div class="footer-section">
            <h4>⏰ Horário de Funcionamento</h4>
            <p>Segunda a Sexta: 7h - 17h</p>
            <p>Sábado: 8h - 12h</p>
        </div>
    </div>
    <div class="footer-bottom">
        <p>© 2026 Colégio Tia Lucinda - Todos os direitos reservados</p>
        <p>Visitas: <?php echo $visitas; ?> | Projeto de Defesa Final</p>
    </div>
</footer>

<!-- BOTÃO VOLTAR AO TOPO -->
<a href="#" class="back-to-top" id="backToTop">↑</a>

<!-- LINK ADMIN -->
<a href="admin/login.php" class="admin-link">🔐 Admin</a>

<script>
// Formulário de contacto
const formulario = document.getElementById("formulario");
formulario.addEventListener("submit", function(event){
    event.preventDefault();
    document.getElementById("sucesso").innerHTML = "✅ Mensagem enviada com sucesso!";
    formulario.reset();
    setTimeout(() => {
        document.getElementById("sucesso").innerHTML = "";
    }, 3000);
});

// Botão voltar ao topo
const backToTop = document.getElementById("backToTop");
window.addEventListener("scroll", function() {
    if(window.scrollY > 300) {
        backToTop.classList.add("show");
    } else {
        backToTop.classList.remove("show");
    }
});

// Scroll suave para links internos
document.querySelectorAll('a[href^="#"]').forEach(anchor => {
    anchor.addEventListener('click', function(e) {
        const target = document.querySelector(this.getAttribute('href'));
        if(target) {
            e.preventDefault();
            target.scrollIntoView({ behavior: 'smooth' });
        }
    });
});

// Animação ao rolar (fade-in)
const observerOptions = {
    threshold: 0.1,
    rootMargin: "0px 0px -50px 0px"
};

const observer = new IntersectionObserver((entries) => {
    entries.forEach(entry => {
        if(entry.isIntersecting) {
            entry.target.style.opacity = "1";
            entry.target.style.transform = "translateY(0)";
        }
    });
}, observerOptions);

document.querySelectorAll("section").forEach(section => {
    section.style.opacity = "0";
    section.style.transform = "translateY(30px)";
    section.style.transition = "all 0.6s ease";
    observer.observe(section);
});
</script>
</body>
</html>