<?php
header('Content-Type: application/json');
$host = 'localhost';
$dbname = 'intask';
$user = 'root';
$pass = '';

try {
    // Conexão ao banco de dados
    $conn = new PDO("mysql:host=$host;dbname=$dbname", $user, $pass);
    $conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION); // Ativa o modo de erro do PDO

    // Captura as ações e parâmetros
    $action = $_GET['action'] ?? '';
    $user_id = $_GET['user_id'] ?? '';  // Corrigido para receber como string diretamente

    // Ação para adicionar notas
    if ($action == 'add') {
        $title = $_GET['title'] ?? ''; // Adicionando valor padrão para evitar erros
        $content = $_GET['content'] ?? ''; // Adicionando valor padrão para evitar erros
        $stmt = $conn->prepare("INSERT INTO notes (user_id, title, content) VALUES (:user_id, :title, :content)");
        $stmt->execute(['user_id' => $user_id, 'title' => $title, 'content' => $content]);
        echo json_encode(['status' => 'success', 'message' => 'Nota adicionada com sucesso.']);
        
    // Ação para obter notas
    } elseif ($action == 'get') {
        $stmt = $conn->prepare("SELECT * FROM notes WHERE user_id = :user_id");
        $stmt->execute(['user_id' => $user_id]);
        $notes = $stmt->fetchAll(PDO::FETCH_ASSOC);
        
        // Retorna as notas como JSON
        echo json_encode($notes);
        
    // Ação para atualizar notas
    } elseif ($action == 'updatePriority') {
        $taskId = $_GET['task_id'];
        $priority = $_GET['priority'];
        
        try {
            // Atualizar a prioridade da tarefa no banco de dados
            $stmt = $conn->prepare("UPDATE notes SET priority = :priority WHERE id = :task_id");
            $stmt->execute(['priority' => $priority, 'task_id' => $taskId]);
    
            // Retornar resposta JSON de sucesso
            echo json_encode(['status' => 'success', 'message' => 'Prioridade atualizada com sucesso.']);
        } catch (Exception $e) {
            // Caso ocorra um erro, retornar a mensagem de erro em formato JSON
            echo json_encode(['status' => 'error', 'message' => $e->getMessage()]);
        }
        exit;
      // Não continuar processando o script após retornar a resposta
    } elseif ($action == 'update') {
        $id = $_GET['id'] ?? 0; // Adicionando valor padrão para evitar erros
        $title = $_GET['title'] ?? ''; // Adicionando valor padrão para evitar erros
        $content = $_GET['content'] ?? ''; // Adicionando valor padrão para evitar erros
        $stmt = $conn->prepare("UPDATE notes SET title = :title, content = :content WHERE id = :id AND user_id = :user_id");
        $stmt->execute(['title' => $title, 'content' => $content, 'id' => $id, 'user_id' => $user_id]);
        echo json_encode(['status' => 'success', 'message' => 'Nota atualizada com sucesso.']);
        
    // Ação para deletar notas
    } elseif ($action == 'delete') {
        $id = $_GET['id'] ?? 0; // Adicionando valor padrão para evitar erros
        $stmt = $conn->prepare("DELETE FROM notes WHERE id = :id");
        $stmt->execute(['id' => $id]);
        echo json_encode(['status' => 'success', 'message' => 'Nota deletada com sucesso.']);
        
    } else {
        // Resposta para ação não reconhecida
        echo json_encode(['status' => 'error', 'message' => 'Ação não reconhecida.']);
    }

} catch (PDOException $e) {
    // Captura e exibe erros de conexão
    echo json_encode(['status' => 'error', 'message' => $e->getMessage()]);
}
?>
