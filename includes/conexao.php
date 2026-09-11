<?php
// ============================================================
// MathPlay Solutions — Conexão com o Banco de Dados
// ============================================================
// Este arquivo cria a conexão com o MySQL usando a extensão
// mysqli (MySQL Improved). É incluído em todas as páginas
// que precisam acessar o banco de dados.
// ============================================================

// Configurações de conexão
$host    = 'localhost';   // Endereço do servidor MySQL (XAMPP usa localhost)
$usuario = 'root';        // Usuário padrão do XAMPP
$senha   = '';            // Senha padrão do XAMPP é vazia
$banco   = 'mathplay';    // Nome do banco de dados

// Cria a conexão
// new mysqli() tenta conectar ao MySQL com os dados acima
$conn = new mysqli($host, $usuario, $senha, $banco);

// Verifica se houve erro na conexão
if ($conn->connect_error) {
    // connect_error retorna a mensagem de erro se a conexão falhar
    die('<div style="text-align:center;padding:50px;font-family:sans-serif;">
            <h2 style="color:#e74c3c;">⚠️ Erro de Conexão</h2>
            <p>Não foi possível conectar ao banco de dados.</p>
            <p><small>' . $conn->connect_error . '</small></p>
            <p>Verifique se o XAMPP está rodando (Apache + MySQL).</p>
         </div>');
}

// Define o charset como UTF-8 para suportar acentos e caracteres especiais
$conn->set_charset('utf8');
?>

