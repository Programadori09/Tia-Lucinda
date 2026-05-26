<?php
require_once 'config/database.php';

// Verificar se o ID foi passado
if(!isset($_GET['id']) || empty($_GET['id'])) {
    header('Location: noticias.php');
    exit();
}

$id = (int)$_GET['id'];

// Buscar notícia
$stmt = $pdo->prepare("SELECT * FROM noticias WHERE id = ?");
$stmt->execute([$id]);
$noticia = $stmt->fetch();

// Se não encontrar, redirecionar
if(!$noticia) {
    header('Location: noticias.php');
    exit();
}

// Buscar notícias recentes (sidebar)
$recentes = $pdo->query("SELECT * FROM noticias WHERE id != $id ORDER BY criado_em DESC LIMIT 4")->fetchAll();
?>
<!DOCTYPE html>
<html lang="pt">
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<title><?php echo htmlspecialchars($noticia['titulo']); ?> - Colégio Tia Lucinda</title>
<style>
* {
    margin: 0;
    padding: 0;
    box-sizing: border-box;
    font-family: Arial, Helvetica, sans-serif;
}

body {
    background: #f5f5f5;
}

/* MENU */
header {
    background: #0d0d4d;
    padding: 15px 5%;
    display: flex;
    justify-content: space-between;
    align-items: center;
    position: sticky;
    top: 0;
    z-index: 100;
}

.logo {
    color: white;
    font-size: 22px;
    font-weight: bold;
    display: flex;
    align-items: center;
    gap: 10px;
}

.logo img {
    height: 50px;
    border-radius: 5px;
}

nav a {
    color: white;
    text-decoration: none;
    margin: 15px;
    font-weight: bold;
}

nav a:hover {
    color: gold;
}

/* CONTAINER PRINCIPAL */
.container-noticia {
    max-width: 1200px;
    margin: 60px auto;
    padding: 0 20px;
    display: flex;
    gap: 40px;
    flex-wrap: wrap;
}

/* CONTEÚDO PRINCIPAL */
.conteudo-principal {
    flex: 2.5;
    min-width: 300px;
    background: white;
    border-radius: 15px;
    padding: 40px;
    box-shadow: 0 5px 20px rgba(0,0,0,0.08);
}

.cabecalho-noticia {
    margin-bottom: 30px;
    padding-bottom: 20px;
    border-bottom: 2px solid #f0f0f0;
}

.info-noticia {
    display: flex;
    justify-content: space-between;
    align-items: center;
    margin-bottom: 15px;
    flex-wrap: wrap;
    gap: 10px;
}

.data-noticia {
    color: #888;
    font-size: 14px;
}

.data-noticia::before {
    content: "📅 ";
}

.badge-destaque-single {
    background: gold;
    color: #0d0d4d;
    padding: 5px 12px;
    border-radius: 20px;
    font-size: 12px;
    font-weight: bold;
}

.cabecalho-noticia h1 {
    font-size: 36px;
    color: #0d0d4d;
    line-height: 1.3;
}

.imagem-noticia-single {
    background: linear-gradient(135deg, #0d0d4d 0%, #1a1a6e 100%);
    height: 280px;
    display: flex;
    align-items: center;
    justify-content: center;
    font-size: 80px;
    color: white;
    border-radius: 12px;
    margin-bottom: 30px;
}

.texto-noticia {
    font-size: 18px;
    line-height: 1.8;
    color: #333;
}

.texto-noticia p {
    margin-bottom: 20px;
}

/* SIDEBAR */
.sidebar {
    flex: 1.2;
    min-width: 280px;
}

.card-sidebar {
    background: white;
    border-radius: 15px;
    padding: 25px;
    margin-bottom: 30px;
    box-shadow: 0 5px 20px rgba(0,0,0,0.08);
}

.card-sidebar h3 {
    color: #0d0d4d;
    margin-bottom: 20px;
    padding-bottom: 10px;
    border-bottom: 3px solid gold;
    display: inline-block;
}

.noticia-recente {
    padding: 15px 0;
    border-bottom: 1px solid #eee;
}

.noticia-recente:last-child {
    border-bottom: none;
}

.noticia-recente .titulo-recente {
    font-weight: bold;
    color: #333;
    text-decoration: none;
    display: block;
    margin-bottom: 8px;
    font-size: 16px;
    transition: color 0.3s;
}

.noticia-recente .titulo-recente:hover {
    color: #0d0d4d;
}

.noticia-recente .data-recente {
    font-size: 12px;
    color: #999;
}

.contato-sidebar {
    text-align: center;
}

.contato-sidebar p {
    margin: 15px 0;
    color: #555;
}

.contato-sidebar .icone {
    font-size: 24px;
    margin-right: 10px;
}

/* BOTÕES */
.btn-voltar {
    display: inline-block;
    background: #0d0d4d;
    color: white;
    text-decoration: none;
    padding: 12px 25px;
    border-radius: 30px;
    margin-top: 30px;
    font-weight: bold;
    transition: all 0.3s;
}

.btn-voltar:hover {
    background: gold;
    color: #0d0d4d;
}

.btn-todas {
    display: inline-block;
    background: #0d0d4d;
    color: white;
    text-decoration: none;
    padding: 10px 20px;
    border-radius: 30px;
    font-size: 14px;
    margin-top: 15px;
    transition: all 0.3s;
}

.btn-todas:hover {
    background: gold;
    color: #0d0d4d;
}

.text-center {
    text-align: center;
}

/* FOOTER */
footer {
    background: #080830;
    color: white;
    text-align: center;
    padding: 25px;
    margin-top: 60px;
}

.admin-link {
    position: fixed;
    bottom: 20px;
    right: 20px;
    background: #0d0d4d;
    color: white;
    padding: 10px 15px;
    border-radius: 5px;
    text-decoration: none;
    font-size: 12px;
    opacity: 0.6;
}

.admin-link:hover {
    opacity: 1;
}

/* RESPONSIVO */
@media (max-width: 900px) {
    nav a {
        margin: 8px;
        font-size: 12px;
    }
    
    .cabecalho-noticia h1 {
        font-size: 28px;
    }
    
    .conteudo-principal {
        padding: 25px;
    }
    
    .texto-noticia {
        font-size: 16px;
    }
    
    .imagem-noticia-single {
        height: 180px;
        font-size: 50px;
    }
}

@media (max-width: 480px) {
    header {
        flex-direction: column;
        gap: 10px;
    }
    
    .logo {
        font-size: 16px;
    }
}
</style>
</head>
<body>

<!-- MENU -->
<header>
    <div class="logo">
        <img src="images/logo-lucinda.jpeg" alt="Logo">
        COLÉGIO TIA LUCINDA
    </div>
    <nav>
        <a href="index.php">Início</a>
        <a href="index.php#sobre">Sobre</a>
        <a href="index.php#ensino">Ensino</a>
        <a href="noticias.php" style="color: gold;">Notícias</a>
        <a href="index.php#galeria">Galeria</a>
        <a href="index.php#contacto">Contacto</a>
    </nav>
</header>

<!-- CONTEÚDO PRINCIPAL -->
<div class="container-noticia">
    
    <!-- NOTÍCIA COMPLETA -->
    <div class="conteudo-principal">
        <div class="cabecalho-noticia">
            <div class="info-noticia">
                <span class="data-noticia"><?php echo date('d/m/Y \à\s H:i', strtotime($noticia['criado_em'])); ?></span>
                <?php if($noticia['destaque']): ?>
                    <span class="badge-destaque-single">⭐ Notícia em Destaque</span>
                <?php endif; ?>
            </div>
            <h1><?php echo htmlspecialchars($noticia['titulo']); ?></h1>
        </div>
        
        <div class="imagem-noticia-single">
            📰
        </div>
        
        <div class="texto-noticia">
            <?php echo nl2br(htmlspecialchars($noticia['conteudo'])); ?>
        </div>
        
        <div class="text-center">
            <a href="noticias.php" class="btn-voltar">← Ver todas as notícias</a>
        </div>
    </div>
    
    <!-- SIDEBAR -->
    <div class="sidebar">
        <div class="card-sidebar">
            <h3>📰 Notícias Recentes</h3>
            <?php if(count($recentes) > 0): ?>
                <?php foreach($recentes as $recente): ?>
                    <div class="noticia-recente">
                        <a href="noticia-single.php?id=<?php echo $recente['id']; ?>" class="titulo-recente">
                            <?php echo htmlspecialchars($recente['titulo']); ?>
                        </a>
                        <div class="data-recente">📅 <?php echo date('d/m/Y', strtotime($recente['criado_em'])); ?></div>
                    </div>
                <?php endforeach; ?>
            <?php else: ?>
                <p style="color: #999;">Nenhuma outra notícia no momento.</p>
            <?php endif; ?>
            <div class="text-center">
                <a href="noticias.php" class="btn-todas">Ver todas →</a>
            </div>
        </div>
        
        <div class="card-sidebar">
            <h3>📞 Contacto</h3>
            <div class="contato-sidebar">
                <p><span class="icone">📧</span> contato@colegio.com</p>
                <p><span class="icone">📱</span> +244 900 000 000</p>
                <p><span class="icone">📍</span> Luanda, Angola</p>
            </div>
        </div>
        
        <div class="card-sidebar">
            <h3>🎯 Missão</h3>
            <p style="color: #555; line-height: 1.6;">Oferecer ensino de qualidade, formando cidadãos preparados para os desafios do futuro.</p>
        </div>
    </div>
</div>

<!-- FOOTER -->
<footer>
    <p>© 2026 Colégio Tia Lucinda - Todos os direitos reservados</p>
</footer>

<!-- LINK ADMIN -->
<a href="admin/login.php" class="admin-link">🔐 Admin</a>

</body>
</html>