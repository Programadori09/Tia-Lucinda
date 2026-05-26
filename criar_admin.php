<?php
require_once 'config/database.php';

// Verificar se a tabela existe
try {
    // Primeiro, verificar se a tabela administradores existe
    $stmt = $pdo->query("SHOW TABLES LIKE 'administradores'");
    if($stmt->rowCount() == 0) {
        // Criar a tabela
        $pdo->exec("CREATE TABLE IF NOT EXISTS administradores (
            id INT AUTO_INCREMENT PRIMARY KEY,
            usuario VARCHAR(50) NOT NULL UNIQUE,
            senha VARCHAR(255) NOT NULL,
            nome VARCHAR(100),
            criado_em TIMESTAMP DEFAULT CURRENT_TIMESTAMP
        )");
        echo "✅ Tabela 'administradores' criada com sucesso!<br>";
    }
    
    // Criar o usuário admin
    $senha_hash = password_hash('admin123', PASSWORD_DEFAULT);
    
    $stmt = $pdo->prepare("INSERT INTO administradores (usuario, senha, nome) VALUES (?, ?, ?)");
    $stmt->execute(['admin', $senha_hash, 'Administrador']);
    
    echo "✅ Usuário admin criado com sucesso!<br><br>";
    echo "🔐 <strong>Dados de acesso:</strong><br>";
    echo "📌 Usuário: <strong>admin</strong><br>";
    echo "🔑 Senha: <strong>admin123</strong><br><br>";
    
} catch(PDOException $e) {
    if(strpos($e->getMessage(), 'Duplicate') !== false) {
        echo "⚠️ Usuário admin já existe!<br>";
        echo "📌 Usuário: admin<br>";
        echo "🔑 Senha: admin123<br><br>";
        
        // Atualizar a senha para garantir
        $senha_hash = password_hash('admin123', PASSWORD_DEFAULT);
        $stmt = $pdo->prepare("UPDATE administradores SET senha = ? WHERE usuario = 'admin'");
        $stmt->execute([$senha_hash]);
        echo "✅ Senha do admin foi resetada!<br>";
    } else {
        echo "❌ Erro: " . $e->getMessage() . "<br>";
    }
}

echo '<hr>';
echo '<a href="admin/login.php" style="background:#0d0d4d; color:white; padding:10px 20px; text-decoration:none; border-radius:5px;">🔐 Ir para o Login →</a>';
?>