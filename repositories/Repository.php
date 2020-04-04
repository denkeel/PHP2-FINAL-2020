<?php
namespace App\repositories;

use App\entities\Entity;
use App\main\App;
use App\services\DB;

/**
 * Class Model
 * @property $id
 */
abstract class Repository
{
    /**
     * @var DB
     */
    protected $db;

    abstract protected function getTableName():string;
    abstract protected function getEntityName():string;

    /**
     * Model constructor.
     * @param DB $db
     */
    public function __construct(DB $db)
    {
        $this->db = $db;
    }

    public function getOne($id)
    {
        $tableName = $this->getTableName();
        $sql = "SELECT * FROM {$tableName} WHERE id = :id";
        return $this->db->findObject(
            $sql,
            $this->getEntityName(),
            [':id' => $id]
        );
    }

    public function getAll()
    {
        $tableName = $this->getTableName();
        $sql = "SELECT * FROM {$tableName}";
        return $this->db->findObjects($sql, $this->getEntityName());
    }

    protected function insert(Entity $entity)
    {
        $columns = [];
        $params = [];
        foreach ($entity as $key => $value) {
            if ($key === 'db') {
                continue;
            }
            $columns[] = $key;
            $params[":{$key}"] = $value;
        }

        $tableName = $this->getTableName();
        $fields = implode(',', $columns);
        $placeholders = implode(',', array_keys($params));
        $sql = "INSERT INTO {$tableName} ({$fields}) VALUES ($placeholders)";
        $this->db->execute($sql, $params);

        $entity->id = $this->db->lastInsertId();
    }

    protected function update(Entity $entity)
    {
        $columns = [];
        $params = [];
        foreach ($entity as $key => $value) {
            if ($key === 'db') {
                continue;
            }

            $params[":{$key}"] = $value;
            if ($key == 'id') {
                continue;
            }

            $columns[] = "{$key} = :{$key}";
        }

        $tableName = $this->getTableName();
        $placeholders = implode(',', $columns);

        $sql = "UPDATE {$tableName} SET {$placeholders} WHERE id = :id";
        $this->db->execute($sql, $params);
    }

    public function delete(Entity $entity)
    {
        $sql = "DELETE FROM {$this->getTableName()} WHERE id = :id";
        $this->db->execute($sql, [':id' => $entity->id]);
    }

    public function save(Entity $entity)
    {
        if (!$entity->getId()) {
            $this->insert($entity);
            return;
        }

        $this->update($entity);
    }
}