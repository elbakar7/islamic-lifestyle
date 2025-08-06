<?php

namespace App\Models;

/**
 * Base Model
 * 
 * Contains common database operations for all models
 */
abstract class BaseModel
{
    protected $table;
    protected $primaryKey = 'id';
    protected $fillable = [];
    protected $hidden = [];
    protected $timestamps = true;
    
    /**
     * Get database connection
     */
    protected function db()
    {
        return $GLOBALS['db'];
    }
    
    /**
     * Find record by ID
     */
    public function find($id)
    {
        $stmt = $this->db()->prepare("SELECT * FROM {$this->table} WHERE {$this->primaryKey} = ? LIMIT 1");
        $stmt->execute([$id]);
        return $stmt->fetch();
    }
    
    /**
     * Find record by column
     */
    public function findBy($column, $value)
    {
        $stmt = $this->db()->prepare("SELECT * FROM {$this->table} WHERE {$column} = ? LIMIT 1");
        $stmt->execute([$value]);
        return $stmt->fetch();
    }
    
    /**
     * Get all records
     */
    public function all($orderBy = null)
    {
        $sql = "SELECT * FROM {$this->table}";
        
        if ($orderBy) {
            $sql .= " ORDER BY {$orderBy}";
        }
        
        $stmt = $this->db()->prepare($sql);
        $stmt->execute();
        return $stmt->fetchAll();
    }
    
    /**
     * Get records with conditions
     */
    public function where($conditions, $orderBy = null, $limit = null)
    {
        $where = [];
        $params = [];
        
        foreach ($conditions as $column => $value) {
            if (is_array($value)) {
                $operator = $value[0];
                $val = $value[1];
                $where[] = "{$column} {$operator} ?";
                $params[] = $val;
            } else {
                $where[] = "{$column} = ?";
                $params[] = $value;
            }
        }
        
        $sql = "SELECT * FROM {$this->table} WHERE " . implode(' AND ', $where);
        
        if ($orderBy) {
            $sql .= " ORDER BY {$orderBy}";
        }
        
        if ($limit) {
            $sql .= " LIMIT {$limit}";
        }
        
        $stmt = $this->db()->prepare($sql);
        $stmt->execute($params);
        return $stmt->fetchAll();
    }
    
    /**
     * Create new record
     */
    public function create($data)
    {
        // Filter fillable fields
        $data = $this->filterFillable($data);
        
        // Add timestamps
        if ($this->timestamps) {
            $data['created_at'] = date('Y-m-d H:i:s');
            $data['updated_at'] = date('Y-m-d H:i:s');
        }
        
        $columns = array_keys($data);
        $values = array_values($data);
        $placeholders = array_fill(0, count($columns), '?');
        
        $sql = sprintf(
            "INSERT INTO %s (%s) VALUES (%s)",
            $this->table,
            implode(', ', $columns),
            implode(', ', $placeholders)
        );
        
        $stmt = $this->db()->prepare($sql);
        $stmt->execute($values);
        
        return $this->find($this->db()->lastInsertId());
    }
    
    /**
     * Update record
     */
    public function update($id, $data)
    {
        // Filter fillable fields
        $data = $this->filterFillable($data);
        
        // Update timestamp
        if ($this->timestamps) {
            $data['updated_at'] = date('Y-m-d H:i:s');
        }
        
        $sets = [];
        $values = [];
        
        foreach ($data as $column => $value) {
            $sets[] = "{$column} = ?";
            $values[] = $value;
        }
        
        $values[] = $id;
        
        $sql = sprintf(
            "UPDATE %s SET %s WHERE %s = ?",
            $this->table,
            implode(', ', $sets),
            $this->primaryKey
        );
        
        $stmt = $this->db()->prepare($sql);
        return $stmt->execute($values);
    }
    
    /**
     * Delete record
     */
    public function delete($id)
    {
        $stmt = $this->db()->prepare("DELETE FROM {$this->table} WHERE {$this->primaryKey} = ?");
        return $stmt->execute([$id]);
    }
    
    /**
     * Count records
     */
    public function count($conditions = [])
    {
        if (empty($conditions)) {
            $stmt = $this->db()->prepare("SELECT COUNT(*) as total FROM {$this->table}");
            $stmt->execute();
        } else {
            $where = [];
            $params = [];
            
            foreach ($conditions as $column => $value) {
                $where[] = "{$column} = ?";
                $params[] = $value;
            }
            
            $sql = "SELECT COUNT(*) as total FROM {$this->table} WHERE " . implode(' AND ', $where);
            $stmt = $this->db()->prepare($sql);
            $stmt->execute($params);
        }
        
        return $stmt->fetch()['total'];
    }
    
    /**
     * Check if record exists
     */
    public function exists($column, $value, $excludeId = null)
    {
        $sql = "SELECT COUNT(*) as total FROM {$this->table} WHERE {$column} = ?";
        $params = [$value];
        
        if ($excludeId) {
            $sql .= " AND {$this->primaryKey} != ?";
            $params[] = $excludeId;
        }
        
        $stmt = $this->db()->prepare($sql);
        $stmt->execute($params);
        
        return $stmt->fetch()['total'] > 0;
    }
    
    /**
     * Filter fillable fields
     */
    protected function filterFillable($data)
    {
        if (empty($this->fillable)) {
            return $data;
        }
        
        return array_intersect_key($data, array_flip($this->fillable));
    }
    
    /**
     * Hide sensitive fields
     */
    public function hideFields($data)
    {
        if (empty($this->hidden)) {
            return $data;
        }
        
        if (isset($data[0]) && is_array($data[0])) {
            // Multiple records
            return array_map(function($item) {
                return array_diff_key($item, array_flip($this->hidden));
            }, $data);
        } else {
            // Single record
            return array_diff_key($data, array_flip($this->hidden));
        }
    }
    
    /**
     * Begin transaction
     */
    public function beginTransaction()
    {
        return $this->db()->beginTransaction();
    }
    
    /**
     * Commit transaction
     */
    public function commit()
    {
        return $this->db()->commit();
    }
    
    /**
     * Rollback transaction
     */
    public function rollback()
    {
        return $this->db()->rollBack();
    }
}