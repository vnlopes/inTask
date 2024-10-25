<?php
$host = 'localhost'; // ou o endereço do seu banco de dados
$dbname = 'inTask';
$username = 'seu_usuario';
$password = 'sua_senha';

try {
    // Conexão com o banco de dados
    $pdo = new PDO("mysql:host=$host;dbname=$dbname;charset=utf8mb4", $username, $password);
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

    // Verifica se é uma requisição POST
    if ($_SERVER['REQUEST_METHOD'] === 'POST') {
        $usuario_id = $_POST['usuario_id'];
        $conteudo = $_POST['conteudo'];

        // Verifica se os dados necessários foram fornecidos
        if (!empty($usuario_id) && !empty($conteudo)) {
            // Insere a nova nota no banco de dados
            $stmt = $pdo->prepare("INSERT INTO notas (usuario_id, conteudo) VALUES (:usuario_id, :conteudo)");
            $stmt->bindParam(':usuario_id', $usuario_id);
            $stmt->bindParam(':conteudo', $conteudo);

            if ($stmt->execute()) {
                echo json_encode(['message' => 'Nota adicionada com sucesso']);
            } else {
                echo json_encode(['message' => 'Erro ao adicionar nota']);
            }
        } else {
            echo json_encode(['message' => 'Dados inválidos']);
        }
    } elseif ($_SERVER['REQUEST_METHOD'] === 'GET') {
        // Lógica para obter as notas do usuário
        $usuario_id = $_GET['usuario_id'];

        $stmt = $pdo->prepare("SELECT id, conteudo, data_criacao FROM notas WHERE usuario_id = :usuario_id");
        $stmt->bindParam(':usuario_id', $usuario_id);
        $stmt->execute();

        $notas = $stmt->fetchAll(PDO::FETCH_ASSOC);
        echo json_encode($notas);
    }
} catch (PDOException $e) {
    echo json_encode(['message' => 'Erro na conexão: ' . $e->getMessage()]);
}
?>
