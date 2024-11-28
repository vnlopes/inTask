<?php 
// Define que a resposta será em formato JSON
header('Content-Type: application/json');

// Configuração de acesso ao banco de dados
$host = 'localhost'; // O servidor onde o banco de dados está hospedado
$dbname = 'intask'; // Nome do banco de dados
$user = 'root'; // Nome de usuário do banco de dados
$pass = ''; // Senha do banco de dados

try {
    // Tenta estabelecer uma conexão com o banco de dados usando PDO (uma forma segura de interagir com bancos)
    $conn = new PDO("mysql:host=$host;dbname=$dbname", $user, $pass);
    // Configura o PDO para lançar erros caso haja problemas
    $conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

    // Classe que define como uma "Nota" será estruturada
    class Note {
        public $id; // ID da nota (identificador único)
        public $user_id; // ID do usuário que criou a nota
        public $title; // Título da nota
        public $content; // Conteúdo da nota
        public $priority; // Prioridade da nota

        // Método que é chamado para criar uma nova instância de Nota
        public function __construct($id, $user_id, $title, $content, $priority, $datan) {
            $this->id = $id;
            $this->user_id = $user_id;
            $this->title = $title;
            $this->content = $content;
            $this->priority = $priority;
            $this->datan = $datan;
        }
    }

    // Classe que lida com a comunicação com o banco de dados para as notas
    class NoteRepository {
        private $conn;

        // Método construtor que inicializa a conexão com o banco de dados
        public function __construct($conn) {
            $this->conn = $conn;
        }

        // Método que adiciona uma nova nota no banco de dados
        public function addNote($user_id, $title, $content, $priority) {
            // Prepara uma consulta SQL para inserir os dados no banco
            $stmt = $this->conn->prepare("INSERT INTO notes (user_id, title, content, priority) VALUES (:user_id, :title, :content, :priority, :datan)");
            // Executa a consulta e passa os dados para o banco
            $stmt->execute(['user_id' => $user_id, 'title' => $title, 'content' => $content, 'priority' => $priority, 'datan' => $datan]);
            // Retorna o ID da nova nota adicionada
            return $this->conn->lastInsertId();
        }

        // Método que obtém todas as notas de um usuário
        public function getNotes($user_id) {
            // Prepara uma consulta para pegar todas as notas do usuário
            $stmt = $this->conn->prepare("SELECT * FROM notes WHERE user_id = :user_id");
            $stmt->execute(['user_id' => $user_id]);
            // Obtém todas as linhas de resultados como um array
            $rows = $stmt->fetchAll(PDO::FETCH_ASSOC);

            // Cria um array para armazenar as notas
            $notes = [];
            // Para cada linha de resultado, cria um objeto Note
            foreach ($rows as $row) {
                $notes[] = new Note(
                    $row['id'],
                    $row['user_id'],
                    $row['title'],
                    $row['content'],
                    $row['priority'],
                    $row['datan']
                );
            }
            // Retorna o array de notas
            return $notes;
        }

        // Método que atualiza uma nota existente no banco
        public function updateNote($id, $user_id, $title, $content) {
            // Prepara uma consulta para atualizar o título e conteúdo da nota
            $stmt = $this->conn->prepare("UPDATE notes SET title = :title, content = :content WHERE id = :id AND user_id = :user_id");
            $stmt->execute(['title' => $title, 'content' => $content, 'id' => $id, 'user_id' => $user_id]);
            // Retorna o número de linhas afetadas pela consulta (se alguma linha foi modificada)
            return $stmt->rowCount();
        }

        // Método que deleta uma nota pelo ID
        public function deleteNote($id) {
            // Prepara uma consulta para deletar a nota com o ID fornecido
            $stmt = $this->conn->prepare("DELETE FROM notes WHERE id = :id");
            $stmt->execute(['id' => $id]);
            // Retorna o número de linhas afetadas pela consulta (se a nota foi deletada)
            return $stmt->rowCount();
        }
    }

    // Instancia o repositório de notas para poder usá-lo nas operações
    $noteRepo = new NoteRepository($conn);

    // Captura os parâmetros enviados na URL (como 'action' e 'user_id')
    $action = $_GET['action'] ?? ''; // Ação a ser executada (adicionar, atualizar, obter, deletar)
    $user_id = $_GET['user_id'] ?? ''; // ID do usuário para quem as notas pertencem

    // Executa a ação solicitada
    if ($action == 'add') {
        // Pega os dados da nova nota (título, conteúdo, prioridade)
        $title = $_GET['title'] ?? '';
        $content = $_GET['content'] ?? '';
        $priority = $_GET['priority'] ?? '';
        // Chama o método para adicionar a nota no banco
        $note_id = $noteRepo->addNote($user_id, $title, $content, $priority);
        // Retorna uma resposta em JSON confirmando o sucesso
        echo json_encode(['status' => 'success', 'message' => 'Nota adicionada com sucesso.', 'id' => $note_id]);

    } elseif ($action == 'get') {
        // Chama o método para obter todas as notas do usuário
        $notes = $noteRepo->getNotes($user_id);
        // Retorna as notas em formato JSON
        echo json_encode($notes);

    } elseif ($action == 'update') {
        // Pega os dados da nota que será atualizada (ID, título, conteúdo)
        $id = $_GET['id'] ?? 0;
        $title = $_GET['title'] ?? '';
        $content = $_GET['content'] ?? '';
        // Chama o método para atualizar a nota no banco
        $updated = $noteRepo->updateNote($id, $user_id, $title, $content);
        // Retorna uma mensagem confirmando se a atualização foi realizada
        echo json_encode(['status' => 'success', 'message' => $updated ? 'Nota atualizada com sucesso.' : 'Nenhuma alteração feita.']);

    } elseif ($action == 'delete') {
        // Pega o ID da nota que será deletada
        $id = $_GET['id'] ?? 0;
        // Chama o método para deletar a nota do banco
        $deleted = $noteRepo->deleteNote($id);
        // Retorna uma mensagem confirmando se a nota foi deletada
        echo json_encode(['status' => 'success', 'message' => $deleted ? 'Nota deletada com sucesso.' : 'Nota não encontrada.']);

    } else {
        // Se a ação não for reconhecida, retorna um erro
        echo json_encode(['status' => 'error', 'message' => 'Ação não reconhecida.']);
    }

} catch (PDOException $e) {
    // Se ocorrer algum erro ao tentar conectar ao banco ou executar a consulta, exibe a mensagem de erro
    echo json_encode(['status' => 'error', 'message' => $e->getMessage()]);
}

?>