<?php  
/**
 * Superclass for handling all database connections and queries.
 * Uses PDO for secure database interactions.
 */
class Database {
    protected $pdo;
    private $host = 'localhost';
    private $db = 'fiverr_clone';
    private $user = 'root';
    private $pass = '';
    private $charset = 'utf8mb4';

    /**
     * Constructor establishes the PDO connection.
     */
    public function __construct() {
        $dsn = "mysql:host=$this->host;dbname=$this->db;charset=$this->charset";
        $options = [
            PDO::ATTR_ERRMODE            => PDO::ERRMODE_EXCEPTION,
            PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,
            PDO::ATTR_EMULATE_PREPARES   => false,
        ];
        try {
            $this->pdo = new PDO($dsn, $this->user, $this->pass, $options);
            $this->pdo->exec("SET time_zone = '+08:00';");
        } catch (\PDOException $e) {
            throw new \PDOException($e->getMessage(), (int)$e->getCode());
        }
    }

    /**
     * Executes a prepared statement and returns the result.
     * @param string $sql The SQL query to execute.
     * @param array $params The parameters to bind to the query.
     * @return array The fetched data.
     */
    protected function executeQuery($sql, $params = []) {
        $stmt = $this->pdo->prepare($sql);
        $stmt->execute($params);
        return $stmt->fetchAll();
    }

    /**
     * Executes a prepared statement and returns a single row.
     * @param string $sql The SQL query to execute.
     * @param array $params The parameters to bind to the query.
     * @return array|null The single fetched row, or null if not found.
     */
    protected function executeQuerySingle($sql, $params = []) {
        $stmt = $this->pdo->prepare($sql);
        $stmt->execute($params);
        return $stmt->fetch();
    }

    /**
     * Executes a non-query statement (INSERT, UPDATE, DELETE).
     * @param string $sql The SQL query to execute.
     * @param array $params The parameters to bind to the query.
     * @return int The number of affected rows.
     */
    protected function executeNonQuery($sql, $params = []) {
        $stmt = $this->pdo->prepare($sql);
        return $stmt->execute($params);
    }

    /**
     * Returns the ID of the last inserted row.
     * @return string
     */
    protected function lastInsertId() {
        return $this->pdo->lastInsertId();
    }
}
?>