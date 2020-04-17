<?php
namespace App\services;
use App\repositories\GoodRepository;

/**
 * Class DB
 * @package App\services
 * @property GoodRepository goodRepository
 */
class DB
{
    protected $repositories = [];
    protected $connection;

    private $config = [];

    public function __construct($config)
    {
        $this->config = $config;
    }

    /**
     * @return \PDO
     */
    protected function getConnection()
    {
        if (empty($this->connection)) {
            $this->connection = new \PDO(
                $this->getDsn(),
                $this->config['username'],
                $this->config['password'], 
                [ \PDO::ATTR_ERRMODE => \PDO::ERRMODE_EXCEPTION ]
            );

            $this->connection->setAttribute(
                \PDO::ATTR_DEFAULT_FETCH_MODE,
                \PDO::FETCH_ASSOC
            );
        }

        return $this->connection;
    }

    private function getDsn()
    {
        return sprintf(
            '%s:host=%s;dbname=%s;charset=%s',
                $this->config['driver'],
                $this->config['host'],
                $this->config['dbname'],
                $this->config['charset']
        );
    }

    /**
     * @param string $sql
     * @param array $params
     * @return bool|\PDOStatement
     */
    protected function query(string $sql, array $params = [])
    {
        $PDOStatement = $this->getConnection()->prepare($sql);
        $PDOStatement->execute($params);
        return $PDOStatement;
    }

    public function find($sql, $params = [])
    {
        return $this->query($sql, $params)->fetch();
    }

    public function findAll($sql, $params = [])
    {
        return $this->query($sql, $params)->fetchAll();
    }

    public function findObject($sql, $class, $params = [])
    {
        $PDOStatement = $this->query($sql, $params);
        $PDOStatement->setFetchMode(\PDO::FETCH_CLASS, $class);
        return $PDOStatement->fetch();
    }

    public function findObjects($sql, $class, $params = [])
    {
        $PDOStatement = $this->query($sql, $params);
        $PDOStatement->setFetchMode(\PDO::FETCH_CLASS, $class);
        return $PDOStatement->fetchAll();
    }

    public function execute(string $sql, array $params = [])
    {
        return $this->query($sql, $params);
    }

    public function executeQueryBool(string $sql, array $params = [])
    {
        $PDOStatement = $this->getConnection()->prepare($sql);
        return $PDOStatement->execute($params);
    }

    public function lastInsertId()
    {
        return $this->getConnection()->lastInsertId();
    }

    public function __get($name)
    {
        if (array_key_exists($name, $this->repositories)) {
            return $this->repositories[$name];
        }

        $repositoryName = "\\App\\repositories\\{$name}";

        if (!class_exists($repositoryName)) {
            return null;
        }

        $repositoryClass = new $repositoryName($this);

        $this->repositories[$name] = $repositoryClass;

        return $repositoryClass;
    }
}
