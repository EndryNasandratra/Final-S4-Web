 <?php

    require_once __DIR__ . '/../db.php';

    class AppModel
    {
        public static function  getAll($table_name)
        {
            $stmt = getDB()->query("SELECT * FROM {$table_name} ");
            return $stmt->fetchAll(PDO::FETCH_ASSOC);
        }
        public static function  getById($table_name, $columnName, $id)
        {
            $sql = "SELECT * FROM {$table_name} WHERE {$columnName} = :id";
            $stmt = getDB()->prepare($sql);
            $stmt->execute(['id' => intval($id)]);
            return $stmt->fetch();
        }
        public static function  getAllById($table_name, $columnName, $id)
        {
            $sql = "SELECT * FROM {$table_name} WHERE {$columnName} = :id";
            $stmt = getDB()->prepare($sql);
            $stmt->execute(['id' => intval($id)]);
            return $stmt->fetchAll();
        }

        public static function  getLastRow($table_name, $id_name = "id")
        {
            $sql = "SELECT * FROM {$table_name} ORDER BY {$id_name} DESC LIMIT 1";
            $stmt = getDB()->query($sql);
            return $stmt->fetch(PDO::FETCH_ASSOC);
        }
        public static function  deleteById($table_name, $id)
        {
            $sql = "DELETE FROM {$table_name} WHERE id = :id";
            $stmt = getDB()->prepare($sql);
            $stmt->execute(['id' => intval($id)]);

            return $stmt->rowCount() > 0;
        }
        public static function  getWhere($table, $conditions)
        {
            $sql = "SELECT * FROM {$table}";
            $params = [];
            $where = [];

            foreach ($conditions as $key => $value) {
                if (str_contains($key, ' BETWEEN')) {
                    [$start, $end] = $value;
                    $column = str_replace(' BETWEEN', '', $key);
                    $where[] = "$column BETWEEN ? AND ?";
                    $params[] = $start;
                    $params[] = $end;
                } else if (str_contains($key, 'LIKE')) {
                    $column = trim(str_replace(' LIKE', '', $key));
                    $where[] = "$column LIKE ?";
                    $params[] = $value;
                } else {
                    $where[] = "$key = ?";
                    $params[] = $value;
                }
            }

            if (!empty($where)) {
                $sql .= ' WHERE ' . implode(' AND ', $where);
            }

            $stmt = getDB()->prepare($sql);
            $stmt->execute($params);
            return $stmt->fetchAll(PDO::FETCH_ASSOC);
        }

        public static function  filterKeyWord($table_name, $columnName, $filter)
        {
            $sql = "SELECT * FROM {$table_name} WHERE {$columnName} LIKE '%{:filter}%'";
            $stmt = getDB()->prepare($sql);
            $stmt->execute(["filter" => $filter]);
            return $stmt->fetchAll(PDO::FETCH_ASSOC);
        }
        public static function  getColumns($table_name)
        {
            $stmt = getDB()->query("SELECT column_name
 FROM information_schema.columns
 WHERE table_schema = 'public'
 AND table_name = '{$table_name}'");
            return $stmt->fetchAll();
        }

        public static function  insert($table_name, $data)
        {
            $columns = AppModel::getColumns($table_name);

            $insertable = array_intersect_key($data, array_flip(array_column($columns, 'column_name')));

            $fields = implode(', ', array_keys($insertable));
            $placeholders = ':' . implode(', :', array_keys($insertable));

            $sql = "INSERT INTO {$table_name} ({$fields}) VALUES ({$placeholders})";
            $stmt = getDB()->prepare($sql);
            return $stmt->execute($insertable);
        }

        public static function  update($table_name, $data, $id_column_name = 'id')
        {
            $columns = AppModel::getColumns($table_name);

            if (!isset($data['id'])) {
                throw new \Exception("L'ID est requis pour mettre à jour un enregistrement.");
            }
            $setClause = [];
            $params = [];

            foreach ($data as $key => $value) {
                if ($key !== 'id' && in_array($key, array_column($columns, 'column_name')) && $value !== null && $value !== '') {
                    $setClause[] = "{$key} = :{$key}";
                    $params[$key] = $value;
                }
            }
            if (empty($setClause)) {
                return false;
            }

            $params['id'] = intval($data['id']);
            $setStr = implode(', ', $setClause);
            $sql = "UPDATE {$table_name} SET {$setStr} WHERE {$id_column_name} = :id";
            $stmt = getDB()->prepare($sql);
            $stmt->execute($params);
            return $stmt->rowCount();
        }

        public static function  delete($table_name, $id, $id_column_name = 'id')
        {
            $sql = "DELETE FROM {$table_name} WHERE {$id_column_name} = :id";
            $stmt = getDB()->prepare($sql);
            $stmt->execute(['id' => intval($id)]);
            return $stmt->rowCount() > 0;
        }

        public static function  disableForeignKeysCheck()
        {
            getDB()->exec("SET FOREIGN_KEY_CHECKS = 0");
        }

        public static function  enableForeignKeysCheck()
        {
            getDB()->exec("SET FOREIGN_KEY_CHECKS = 1");
        }
    }
