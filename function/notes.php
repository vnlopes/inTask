<?php
header('Content-Type: application/json'); // Define que o conteúdo retornado será no formato JSON
$host = 'localhost'; // Endereço do servidor do banco de dados
$dbname = 'intask'; // Nome do banco de dados
$user = 'root'; // Nome de usuário para conectar ao banco
$pass = ''; // Senha para conectar ao banco

try {
    // Criação de conexão com o banco de dados usando PDO
    $conn = new PDO("mysql:host=$host;dbname=$dbname", $user, $pass);
    $conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION); // Define o modo de erro para lançar exceções

    // Captura o valor dos parâmetros de ação e ID do usuário
    $action = $_GET['action'] ?? ''; // Obtém o valor de 'action' ou um valor vazio se não for passado
    $user_id = $_GET['user_id'] ?? ''; // Obtém o valor de 'user_id' ou um valor vazio se não for passado

    // Verifica qual ação será realizada
    if ($action == 'add') { // Caso a ação seja "adicionar"
        $title = $_GET['title'] ?? ''; // Obtém o título ou um valor vazio se não for passado
        $content = $_GET['content'] ?? ''; // Obtém o conteúdo ou um valor vazio se não for passado
        $priority = $_GET['priority'] ?? ''; // Obtém a prioridade ou um valor vazio se não for passado
        // Prepara a consulta SQL para inserir uma nova nota
        $stmt = $conn->prepare("INSERT INTO notes (user_id, title, content, priority) VALUES (:user_id, :title, :content, :priority)");
        $stmt->execute(['user_id' => $user_id, 'title' => $title, 'content' => $content, 'priority' => $priority]); // Executa a consulta com os valores fornecidos
        echo json_encode(['status' => 'success', 'message' => 'Nota adicionada com sucesso.']); // Retorna uma resposta de sucesso

    } elseif ($action == 'get') { // Caso a ação seja "obter"
        // Prepara a consulta SQL para buscar notas do usuário
        $stmt = $conn->prepare("SELECT * FROM notes WHERE user_id = :user_id");
        $stmt->execute(['user_id' => $user_id]); // Executa a consulta passando o ID do usuário
        $notes = $stmt->fetchAll(PDO::FETCH_ASSOC); // Recupera as notas como um array associativo
        echo json_encode($notes); // Retorna as notas em formato JSON

    } elseif ($action == 'update') { // Caso a ação seja "atualizar"
        $id = $_GET['id'] ?? 0; // Obtém o ID da nota ou um valor padrão de 0
        $title = $_GET['title'] ?? ''; // Obtém o título ou um valor vazio se não for passado
        $content = $_GET['content'] ?? ''; // Obtém o conteúdo ou um valor vazio se não for passado
        // Prepara a consulta SQL para atualizar a nota com os valores fornecidos
        $stmt = $conn->prepare("UPDATE notes SET title = :title, content = :content WHERE id = :id AND user_id = :user_id");
        $stmt->execute(['title' => $title, 'content' => $content, 'id' => $id, 'user_id' => $user_id]); // Executa a consulta com os valores fornecidos
        echo json_encode(['status' => 'success', 'message' => 'Nota atualizada com sucesso.']); // Retorna uma resposta de sucesso

    } elseif ($action == 'delete') { // Caso a ação seja "deletar"
        $id = $_GET['id'] ?? 0; // Obtém o ID da nota ou um valor padrão de 0
        // Prepara a consulta SQL para deletar a nota com o ID fornecido
        $stmt = $conn->prepare("DELETE FROM notes WHERE id = :id");
        $stmt->execute(['id' => $id]); // Executa a consulta com o ID fornecido
        echo json_encode(['status' => 'success', 'message' => 'Nota deletada com sucesso.']); // Retorna uma resposta de sucesso

    } else {
        // Responde com um erro caso a ação não seja reconhecida
        echo json_encode(['status' => 'error', 'message' => 'Ação não reconhecida.']);
    }

} catch (PDOException $e) {
    // Captura e trata erros do PDO
    echo json_encode(['status' => 'error', 'message' => $e->getMessage()]); // Retorna o erro em formato JSON
}
?>
