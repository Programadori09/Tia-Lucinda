<?php
require_once '../config/database.php';

// Verificar se está logado
if(!isset($_SESSION['admin_id'])) {
    header('Location: login.php');
    exit();
}

// Processar formulário de nova notícia
if($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_POST['acao'])) {
    if($_POST['acao'] == 'adicionar') {
        $titulo = trim($_POST['titulo']);
        $conteudo = trim($_POST['conteudo']);
        $destaque = isset($_POST['destaque']) ? 1 : 0;
        
        $stmt = $pdo->prepare("INSERT INTO noticias (titulo, conteudo, destaque) VALUES (?, ?, ?)");
        $stmt->execute([$titulo, $conteudo, $destaque]);
        $sucesso = "Notícia adicionada com sucesso!";
    }
    
    if($_POST['acao'] == 'editar') {
        $id = $_POST['id'];
        $titulo = trim($_POST['titulo']);
        $conteudo = trim($_POST['conteudo']);
        $destaque = isset($_POST['destaque']) ? 1 : 0;
        
        $stmt = $pdo->prepare("UPDATE noticias SET titulo = ?, conteudo = ?, destaque = ? WHERE id = ?");
        $stmt->execute([$titulo, $conteudo, $destaque, $id]);
        $sucesso = "Notícia atualizada com sucesso!";
    }
    
    if($_POST['acao'] == 'excluir') {
        $id = $_POST['id'];
        $stmt = $pdo->prepare("DELETE FROM noticias WHERE id = ?");
        $stmt->execute([$id]);
        $sucesso = "Notícia excluída com sucesso!";
    }
}

// Buscar notícias existentes
$noticias = $pdo->query("SELECT * FROM noticias ORDER BY criado_em DESC")->fetchAll();

// Buscar notícia para editar
$editar = null;
if(isset($_GET['editar'])) {
    $stmt = $pdo->prepare("SELECT * FROM noticias WHERE id = ?");
    $stmt->execute([$_GET['editar']]);
    $editar = $stmt->fetch();
}
?>
<!DOCTYPE html>
<html lang="pt">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Painel Admin - Colégio Tia Lucinda</title>
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
        .admin-header {
            background: #0d0d4d;
            color: white;
            padding: 15px 5%;
            display: flex;
            justify-content: space-between;
            align-items: center;
        }
        .admin-header h1 {
            font-size: 20px;
        }
        .admin-header a {
            color: white;
            text-decoration: none;
            background: #c62828;
            padding: 8px 15px;
            border-radius: 5px;
        }
        .container {
            max-width: 1200px;
            margin: 30px auto;
            padding: 0 20px;
        }
        .form-card, .noticias-list {
            background: white;
            border-radius: 10px;
            padding: 25px;
            margin-bottom: 30px;
            box-shadow: 0 2px 10px rgba(0,0,0,0.1);
        }
        .form-card h2, .noticias-list h2 {
            color: #0d0d4d;
            margin-bottom: 20px;
        }
        input, textarea {
            width: 100%;
            padding: 12px;
            margin-bottom: 15px;
            border: 1px solid #ddd;
            border-radius: 5px;
            font-size: 14px;
        }
        textarea {
            resize: vertical;
            min-height: 150px;
        }
        button {
            background: #0d0d4d;
            color: white;
            padding: 12px 25px;
            border: none;
            border-radius: 5px;
            cursor: pointer;
            font-weight: bold;
        }
        button:hover {
            background: #1a1a6e;
        }
        .checkbox {
            display: flex;
            align-items: center;
            gap: 10px;
            margin-bottom: 15px;
        }
        .checkbox input {
            width: auto;
            margin: 0;
        }
        table {
            width: 100%;
            border-collapse: collapse;
        }
        th, td {
            padding: 12px;
            text-align: left;
            border-bottom: 1px solid #ddd;
        }
        th {
            background: #0d0d4d;
            color: white;
        }
        .acoes a, .acoes button {
            display: inline-block;
            padding: 5px 10px;
            margin: 0 3px;
            font-size: 12px;
        }
        .btn-excluir {
            background: #c62828;
        }
        .btn-editar {
            background: #ff9800;
        }
        .sucesso {
            background: #4caf50;
            color: white;
            padding: 10px;
            border-radius: 5px;
            margin-bottom: 20px;
        }
        .voltar-site {
            background: #2196f3;
            padding: 8px 15px;
            border-radius: 5px;
            color: white;
            text-decoration: none;
        }
    </style>
</head>
<body>
    <div class="admin-header">
        <h1>Painel Administrativo - Colégio Tia Lucinda</h1>
        <div>
            <a href="../index.php" class="voltar-site">Ver Site</a>
            <a href="logout.php">Sair</a>
        </div>
    </div>
    
    <div class="container">
        <?php if(isset($sucesso)): ?>
            <div class="sucesso"><?php echo $sucesso; ?></div>
        <?php endif; ?>
        
        <!-- Formulário -->
        <div class="form-card">
            <h2><?php echo $editar ? 'Editar Notícia' : 'Adicionar Nova Notícia'; ?></h2>
            <form method="POST">
                <input type="hidden" name="acao" value="<?php echo $editar ? 'editar' : 'adicionar'; ?>">
                <?php if($editar): ?>
                    <input type="hidden" name="id" value="<?php echo $editar['id']; ?>">
                <?php endif; ?>
                
                <input type="text" name="titulo" placeholder="Título da Notícia" required value="<?php echo $editar ? htmlspecialchars($editar['titulo']) : ''; ?>">
                
                <textarea name="conteudo" placeholder="Conteúdo da notícia..." required><?php echo $editar ? htmlspecialchars($editar['conteudo']) : ''; ?></textarea>
                
                <div class="checkbox">
                    <input type="checkbox" name="destaque" id="destaque" <?php echo ($editar && $editar['destaque']) ? 'checked' : ''; ?>>
                    <label for="destaque">Destacar esta notícia</label>
                </div>
                
                <button type="submit"><?php echo $editar ? 'Atualizar Notícia' : 'Publicar Notícia'; ?></button>
                
                <?php if($editar): ?>
                    <a href="dashboard.php" style="margin-left: 10px; display: inline-block;">Cancelar edição</a>
                <?php endif; ?>
            </form>
        </div>
        
        <!-- Lista de Notícias -->
        <div class="noticias-list">
            <h2>Notícias Publicadas</h2>
            <?php if(count($noticias) > 0): ?>
                <table>
                    <thead>
                        <tr><th>ID</th><th>Título</th><th>Data</th><th>Destaque</th><th>Ações</th></tr>
                    </thead>
                    <tbody>
                        <?php foreach($noticias as $noticia): ?>
                        <tr>
                            <td><?php echo $noticia['id']; ?></td>
                            <td><?php echo htmlspecialchars($noticia['titulo']); ?></td>
                            <td><?php echo date('d/m/Y H:i', strtotime($noticia['criado_em'])); ?></td>
                            <td><?php echo $noticia['destaque'] ? '⭐ Sim' : 'Não'; ?></td>
                            <td class="acoes">
                                <a href="?editar=<?php echo $noticia['id']; ?>" class="btn-editar" style="color:white; text-decoration:none;">✏️ Editar</a>
                                <form method="POST" style="display:inline;" onsubmit="return confirm('Tem certeza que deseja excluir esta notícia?');">
                                    <input type="hidden" name="acao" value="excluir">
                                    <input type="hidden" name="id" value="<?php echo $noticia['id']; ?>">
                                    <button type="submit" class="btn-excluir" style="color:white;">🗑️ Excluir</button>
                                </form>
                            </td>
                        </tr>
                        <?php endforeach; ?>
                    </tbody>
                </table>
            <?php else: ?>
                <p style="text-align: center; color: #999;">Nenhuma notícia cadastrada ainda.</p>
            <?php endif; ?>
        </div>
    </div>
</body>
</html>