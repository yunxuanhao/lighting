<?php
/**
 * Created by PhpStorm.
 * User: yunxuan
 * Date: 2019-03-28
 * Time: 19:43
 */

namespace Yunxuan\Lighting;

class Orm
{
    use InstanceTrait;

    protected static $connPool;

    protected $dbConfig;
    protected $table;

    protected $master;
    protected $slaves;      // slaveList

    protected $fields = [];
    protected $where;
    protected $page = 1;
    protected $count;
    protected $order;

    public function __construct()
    {

    }

    public function getConn($key)
    {
        if(self::$connPool[$key] === null) {
            self::$connPool[$key] = $this->_getConn($key);
        }
        return self::$connPool[$key];
    }

    // 筛选条件
    // = ，> , < , >= , <=
    public function where($key, $op, $v = null)
    {
        if($v === null) {
            $v = $op;
            $op = '=';
        }
        $this->where['where'][$key] = [$op, $v];
        return $this;
    }

    private function _getWhereList()
    {
        $params = [];
        if(empty($this->where)) {
            return ['', $params];
        }
        $list = [];
        // 处理where
        foreach ($this->where as $key => $where) {
            switch ($key) {
                case 'where' :
                    foreach ($where as $k => $v) {
                        $list[] = "{$k} {$v[0]} :{$k}";
                        $params[":{$k}"] = $v[1];
                    }
                    break;
                case 'between' :
                    foreach ($where as $k => $v) {
                        $list[] = "{$k} between :{$k}_min and :{$k}_max";
                        $params[":{$k}_min"] = $v[0];
                        $params[":{$k}_max"] = $v[1];
                    }
                    break;
                case 'in' :
                    foreach ($where as $k => $v) {
                        $op = $v[0] ? 'not in' : 'in';
                        $whereItem = "{$k} {$op} (";
                        foreach ($v[1] as $i => $m) {
                            $whereItem .= ":{$k}_{$i},";
                            $params[":{$k}_{$i}"] = $m;
                        }
                        $whereItem = trim($whereItem, ',') . ')';
                        $list[] = $whereItem;
                    }
                    break;
            }
        }

        return ['where ' . implode(' and ', $list), $params];
    }

    public function in($key, $v, $not = false)
    {
        $this->where['in'][$key] = [$not, array_values($v)];
        return $this;
    }

    public function between($key, $min, $max)
    {
        $this->where['between'][$key] = [$min, $max];
        return $this;
    }

    public function fields(...$fields)
    {
        foreach ($fields as $field) {
            if(is_array($field)) {
                $this->fields = array_merge($this->fields, $field);
            } else {
                $this->fields[] = $field;
            }
        }
        return $this;
    }

    // 获取长度
    public function page($page)
    {
        $this->page = $page;
        return $this;
    }

    public function count($count)
    {
        $this->count = $count;
        return $this;
    }

    protected function _getPageSql()
    {
        if(empty($this->count)) {
            return '';
        }
        $offset = $this->count * ($this->page - 1);
        return "limit {$offset},{$this->count}";
    }

    public function asc($key)
    {
        $this->order[] = ['asc', $key];
        return $this;
    }

    public function desc($key)
    {
        $this->order[] = ['desc', $key];
        return $this;
    }

    protected function _getOrderSql()
    {
        if(empty($this->order)) {
            return '';
        }
        $orderList = [];
        foreach ($this->order as $v) {
            $orderList[] = "{$v[1]} {$v[0]}";
        }
        return 'order by ' . implode(',', $orderList);
    }

    public function getOne()
    {
        $result = $this->page(1)->count(1)->select();
        return $result[0] ?: [];
    }

    public function select()
    {
        $fieldsSql = empty($this->fields) ? '*' : implode(',', $this->fields);
        list($whereSql, $params) = $this->_getWhereList();
        $orderSql = $this->_getOrderSql();
        $pageSql = $this->_getPageSql();

        $sql = "SELECT {$fieldsSql} FROM {$this->table} {$whereSql} {$orderSql} {$pageSql}";

        $conn = $this->_getConn();
        $sth = $conn->prepare($sql);
        $sth->execute($params);
        return $sth->fetchAll(\PDO::FETCH_ASSOC);
    }

    /**
     * @return \PDO
     */
    protected function _getConn()
    {
        $configHash = md5(json_encode($this->dbConfig));
        if(empty(self::$connPool[$configHash])) {
            $config = config($this->dbConfig);
            try {
                self::$connPool[$configHash] = new \PDO(
                    "mysql:host={$config['host']};dbname={$config['dbname']};port={$config['port']}",
                    $config['user'],
                    $config['password']
                );
            } catch (PDOException $e) {
                echo "Error!: " . $e->getMessage() . "<br/>";
                die();
            }
        }
        return self::$connPool[$configHash];
    }

    public function _exec()
    {
        $pdo = $this->_getConn();
        $sql = 'SELECT * FROM student WHERE age > :age';
        $sth = $pdo->prepare($sql, array(\PDO::ATTR_CURSOR => \PDO::CURSOR_FWDONLY));
        $sth->execute([':age' => 15]);
        $result = $sth->fetchAll(\PDO::FETCH_ASSOC);

        var_dump($result);die;
    }


}