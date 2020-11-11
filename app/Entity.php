<?php

namespace App;

use App\Modules\Connection\Database;

/**
 * \Entity
 *
 * @since 2018-10-15
 * @author Filipe Voges <filipe@emsventura.com.br>
 */
abstract class Entity extends \stdClass{

    /**
     * @var int $aI;
     * @access private
     */
    private static $aI;

    /**
     * @var varchar $table;
     * @access protected
     */
    protected $table;

    /**
     * @var int $identifier
     * @access protected
     */
    protected $idClass;

    /**
     * @var bool
     * @access protected
     */
    protected $hasConn = true;

    /**
     * @var \App\Modules\Connection\Database
     * @access protected
     */
    protected $db;

    /**
     * @var string
     * @access protected
     */
    protected $key = 'id';

    /**
     * @var array
     * @access protected
     */
    protected $privateAttrs = [
        'db',
        'idClass',
        'table',
        'key',
        'equals',
        'hasConn'
    ];

    /**
     * @var array
     * @access protected
     */
    protected $equals = [];

    /**
     * Return Table
     *
     * @return string
     */
    public static function table() : string {
        $className = get_called_class();

        $class = new $className();

        try {
            return $class->get('table');
        }catch (Exception $e) {
            return '';
        }
    }

    /**
     * Returns all data
     *
     * @param array $fields
     * @param array $filter
     * @return array
     * @throws Exception
     */
    public static function getAll(array $fields = ['*'], array $filter = []) : array {
        $result = [];

        $db = Database::getInstance();

        $fields = implode(', ', $fields);
        $sql = buildQuery(self::table(), $fields, $filter);

        $stmt = $db->getQuery($sql);

        $dados = $db->getFetchAll($stmt);

        $className = get_called_class();
        foreach ($dados as $dado) {
            $result[] = new $className($dado);
        }

        return $result;
    }

    /**
     * Find Object
     *
     * @param mixed $search
     * string $key
     * @return Entity
     * @throws Exception
     */
    public static function find($search, string $key = 'id') {
        $db = Database::getInstance();

        $className = get_called_class();
        $class = new $className();

        $sql = buildQuery($class->get('table'), '*', [$key => $search], [], NULL, 1);

        $stmt = $db->getQuery($sql);

        $data = $db->getFetchAssoc($stmt);

        if(!empty($data)) {
            $class->populate($data);
        }

        return $class;
    }

    /**
     * Construct Class
     */
    public function __construct(){
        Entity::$aI = intval(Entity::$aI) + 1;
        $this->set('idClass', Entity::$aI);

        if($this->get('hasConn')) {
            $this->db = Database::getInstance();
        }
    }

    /**
     * Getter Generic
     *
     * @param $property | String
     * @return mixed
     * @throws Exception
     */
    public function get($property){
        if(property_exists($this, $property)){
            return $this->$property;
        }else{
            throw new Exception("Atributo inexistente." . "-> {$property}", 500);
        }
    }

    /**
     * Setter Generic
     *
     * @param $property | String
     * @param $value | mixed
     * @return void
     */
    public function set($property, $value){
        $this->$property = $value;
    }

    /**
     * returns a Attributes of Class
     *
     * @param bool $ignoreEmptys
     * @param bool $ignoreNulls
     * @return array
     * @throws Exception
     */
    public function getAttributes(bool $ignoreEmptys = false, bool $ignoreNulls = false){
        $attrs = get_object_vars($this);

        foreach ($attrs as $key => $value) {
            if(in_array($key, $this->get('privateAttrs'))){
                unset($attrs[$key]);
            }
        }

        if(array_key_exists('privateAttrs', $attrs)){
            unset($attrs['privateAttrs']);
        }

        foreach($attrs as $key => $attr) {
            if($ignoreEmptys) {
                if ($attr == '') {
                    unset($attrs[$key]);
                }
            }
            if($ignoreNulls) {
                if (is_null($attr)) {
                    unset($attrs[$key]);
                }
            }
        }

        return $attrs;
    }

    /**
     * Returns the Object Equality filters
     *
     * @return array
     * @throws Exception
     */
    public function filter() : array {
        $where = $this->getAttributes(true);
        if(!empty($this->get('equals'))) {
            $newWhere = [];
            foreach ($this->get('equals') as $e) {
                if(array_key_exists($e, $where)){
                    $newWhere[$e] = $where[$e];
                }
            }
            if(!empty($newWhere)) {
                $where = $newWhere;
            }
        }

        return $where;
    }

    /**
     * Checks whether data exists
     *
     * @return bool
     * @throws Exception
     */
    public function exists() : bool {

        $key = $this->get('key');

        if(boolval($this->get($key))) {
            return true;
        }

        $sql = buildQuery($this->get('table'), 'count(*) as exists', $this->filter());
        $stmt = $this->db->getQuery($sql);

        $result = $this->db->getFetchAssoc($stmt);

        return isset($result['exists']) && $result['exists'] > 0;
    }

    /**
     * Create or update a database record.
     *
     * @return bool
     */
    public function save() : bool {
        try {
            $key = $this->get('key');
            if($this->exists()){
                _update($this->get('table'), $this->getAttributes(false, true), buildWhereQuery($this->filter()));
            }else{
                $id = _insert($this->get('table'), $this->getAttributes(true), false, true);
                $this->set($key, $id);
            }
            return true;
        } catch (\Exception $e) {
            return false;
        }
    }

    /**
     * delete a database record.
     *
     * @return bool
     */
    public function delete(){
        try {
            $attributes = $this->getAttributes(true, true);
            _delete($this->get('table'), buildWhereQuery($attributes));
            $this->populate([]);
            return $this;
        } catch (\Exception $e) {
            return false;
        }
    }


    /**
     * Populates the class attributes with the results obtained from the database
     *
     * @param array $dados
     * @return void
     * @throws Exception
     */
    protected function populate(array $dados){
        foreach ($dados as $key => $value) {
            if(property_exists($this, $key)){
                $this->set($key, $value);
            }
        }
    }

    /**
     * Compare Classes
     *
     * @param \Entity $obj
     * @return bool
     * @throws Exception
     */
    public function equals(Entity $obj) : bool {
        if(get_class($this) != get_class($obj)) return false;
        if($obj == NULL) return false;

        $id = $this->get('idClass');
        $idObj = $obj->get('idClass');
        $obj->set('idClass', NULL);
        $this->set('idClass', NULL);
        $rs = ($this == $obj);
        $this->set('idClass', $id);
        $obj->set('idClass', $idObj);

        return $rs;
    }

}
