<?php
require_once 'config/database.php';

// Paginação
$pagina = isset($_GET['pagina']) ? (int)$_GET['pagina'] : 1;
$por_pagina = 9;
$offset = ($pagina - 1) * $por_pagina;

// Buscar total de notícias
$total_stmt = $pdo->query("SELECT COUNT(*) as total FROM noticias");
$total_noticias = $total_stmt->fetch()['total'];
$total_paginas = ceil($total_noticias / $por_pagina);

// Buscar notícias com paginação
$stmt = $pdo->prepare("SELECT * FROM noticias ORDER BY criado_em DESC LIMIT ? OFFSET ?");
$stmt->bindValue(1, $por_pagina, PDO::PARAM_INT);
$stmt->bindValue(2, $offset, PDO::PARAM_INT);
$stmt->execute();
$noticias = $stmt->fetchAll();
?>
<!DOCTYPE html>
<html lang="pt">
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<title>Todas as Notícias - Colégio Tia Lucinda</title>
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

/* BANNER DA PÁGINA */
.banner-noticias {
    background: linear-gradient(rgba(0,0,0,0.7), rgba(0,0,0,0.7)), url('images/4.png');
    background-size: cover;
    background-position: center;
    padding: 60px 10%;
    text-align: center;
    color: white;
}

.banner-noticias h1 {
    font-size: 48px;
    margin-bottom: 15px;
}

.banner-noticias h1 span {
    color: gold;
}

.banner-noticias p {
    font-size: 18px;
    opacity: 0.9;
}

/* CONTEÚDO PRINCIPAL */
.container-noticias {
    max-width: 1200px;
    margin: 60px auto;
    padding: 0 20px;
}

/* GRADE DE NOTÍCIAS */
.grid-noticias {
    display: grid;
    grid-template-columns: repeat(auto-fill, minmax(350px, 1fr));
    gap: 30px;
    margin-bottom: 50px;
}

/* CARD DE NOTÍCIA */
.card-noticia {
    background: white;
    border-radius: 12px;
    overflow: hidden;
    box-shadow: 0 5px 20px rgba(0,0,0,0.08);
    transition: all 0.3s ease;
}

.card-noticia:hover {
    transform: translateY(-8px);
    box-shadow: 0 15px 35px rgba(0,0,0,0.15);
}

.card-noticia.destaque {
    border-bottom: 4px solid gold;
}

/* ÁREA DA IMAGEM (ÍCONE) */
.imagem-noticia {
    background: linear-gradient(135deg, #0d0d4d 0%, #1a1a6e 100%);
    height: 180px;
    display: flex;
    align-items: center;
    justify-content: center;
    font-size: 64px;
    color: white;
}

/* CONTEÚDO DO CARD */
.conteudo-card {
    padding: 25px;
}

.info-card {
    display: flex;
    justify-content: space-between;
    align-items: center;
    margin-bottom: 15px;
    font-size: 13px;
}

.data-card {
    color: #888;
}

.data-card::before {
    content: "📅 ";
}

.badge-destaque {
    background: gold;
    color: #0d0d4d;
    padding: 4px 10px;
    border-radius: 20px;
    font-size: 11px;
    font-weight: bold;
}

.titulo-card {
    font-size: 20px;
    color: #0d0d4d;
    margin-bottom: 15px;
    line-height: 1.4;
}

.resumo-card {
    color: #666;
    line-height: 1.6;
    margin-bottom: 20px;
    font-size: 14px;
}

.btn-ler {
    display: inline-block;
    background: #0d0d4d;
    color: white;
    text-decoration: none;
    padding: 10px 20px;
    border-radius: 25px;
    font-size: 13px;
    font-weight: bold;
    transition: background 0.3s;
}

.btn-ler:hover {
    background: gold;
    color: #0d0d4d;
}

/* PAGINAÇÃO */
.paginacao {
    display: flex;
    justify-content: center;
    gap: 10px;
    flex-wrap: wrap;
    margin-top: 20px;
}

.paginacao a, .paginacao span {
    padding: 10px 16px;
    background: white;
    border-radius: 8px;
    text-decoration: none;
    color: #0d0d4d;
    font-weight: bold;
    transition: all 0.3s;
    box-shadow: 0 2px 5px rgba(0,0,0,0.05);
}

.paginacao a:hover {
    background: #0d0d4d;
    color: white;
}

.paginacao .ativo {
    background: #0d0d4d;
    color: white;
}

.paginacao .disabled {
    opacity: 0.5;
    cursor: not-allowed;
}

/* SEM NOTÍCIAS */
.sem-noticias {
    text-align: center;
    padding: 80px 20px;
    background: white;
    border-radius: 12px;
}

.sem-noticias .emoji {
    font-size: 64px;
    margin-bottom: 20px;
}

.sem-noticias h3 {
    color: #0d0d4d;
    margin-bottom: 10px;
}

.sem-noticias p {
    color: #666;
}

/* BOTÕES */
.btn-voltar-site {
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

.btn-voltar-site:hover {
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
    transition: opacity 0.3s;
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
    
    .banner-noticias h1 {
        font-size: 32px;
    }
    
    .banner-noticias p {
        font-size: 14px;
    }
    
    .grid-noticias {
        grid-template-columns: 1fr;
    }
    
    .container-noticias {
        padding: 0 15px;
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
    
    .logo img {
        height: 35px;
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

<!-- BANNER -->
<section class="banner-noticias">
    <h1>📰 <span>Notícias</span></h1>
    <p>Fique por dentro de todas as novidades do Colégio Tia Lucinda</p>
</section>

<!-- CONTEÚDO PRINCIPAL -->
<main class="container-noticias">
    
    <?php if(count($noticias) > 0): ?>
        
        <!-- GRADE DE NOTÍCIAS -->
        <div class="grid-noticias">
            <?php foreach($noticias as $noticia): ?>
                <article class="card-noticia <?php echo $noticia['destaque'] ? 'destaque' : ''; ?>">
                    
                    <div class="imagem-noticia">
                        <?php echo $noticia['destaque'] ? '⭐' : '📖'; ?>
                    </div>
                    
                    <div class="conteudo-card">
                        <div class="info-card">
                            <span class="data-card"><?php echo date('d/m/Y', strtotime($noticia['criado_em'])); ?></span>
                            <?php if($noticia['destaque']): ?>
                                <span class="badge-destaque">⭐ Destaque</span>
                            <?php endif; ?>
                        </div>
                        
                        <h2 class="titulo-card"><?php echo htmlspecialchars($noticia['titulo']); ?></h2>
                        
                        <p class="resumo-card">
                            <?php echo nl2br(htmlspecialchars(substr($noticia['conteudo'], 0, 130))); ?>...
                        </p>
                        
                        <a href="noticia-single.php?id=<?php echo $noticia['id']; ?>" class="btn-ler">
                            Ler mais →
                        </a>
                    </div>
                </article>
            <?php endforeach; ?>
        </div>
        
        <!-- PAGINAÇÃO -->
        <?php if($total_paginas > 1): ?>
            <div class="paginacao">
                <?php if($pagina > 1): ?>
                    <a href="?pagina=<?php echo $pagina - 1; ?>">« Anterior</a>
                <?php else: ?>
                    <span class="disabled">« Anterior</span>
                <?php endif; ?>
                
                <?php for($i = 1; $i <= $total_paginas; $i++): ?>
                    <?php if($i == $pagina): ?>
                        <span class="ativo"><?php echo $i; ?></span>
                    <?php else: ?>
                        <a href="?pagina=<?php echo $i; ?>"><?php echo $i; ?></a>
                    <?php endif; ?>
                <?php endfor; ?>
                
                <?php if($pagina < $total_paginas): ?>
                    <a href="?pagina=<?php echo $pagina + 1; ?>">Próxima »</a>
                <?php else: ?>
                    <span class="disabled">Próxima »</span>
                <?php endif; ?>
            </div>
        <?php endif; ?>
        
    <?php else: ?>
        
        <!-- SEM NOTÍCIAS -->
        <div class="sem-noticias">
            <div class="emoji">📭</div>
            <h3>Nenhuma notícia publicada ainda</h3>
            <p>Em breve teremos novidades do Colégio Tia Lucinda.</p>
            <p style="margin-top: 10px;">Fique atento!</p>
        </div>
        
    <?php endif; ?>
    
    <!-- BOTÃO VOLTAR -->
    <div class="text-center">
        <a href="index.php" class="btn-voltar-site">← Voltar para a página inicial</a>
    </div>
    
</main>

<!-- FOOTER -->
<footer>
    <p>© 2026 Colégio Tia Lucinda - Todos os direitos reservados</p>
    <p style="margin-top: 10px; font-size: 12px; opacity: 0.7;">Projeto de Defesa Final</p>
</footer>

<!-- LINK ADMIN -->
<a href="admin/login.php" class="admin-link">🔐 Área Administrativa</a>

</body>
</html>