<?php

use App\Modules\Connection\Database;

/**
 * @param $table
 * @param $fields
 * @param array $filter
 * @param array $order
 * @param null $group
 * @param int $limit
 * @param int $init
 * @return string
 */
function buildQuery($table, $fields, $filter = [], $order = [], $group = null, $limit = 0, $init = 0) : string {

    $where = buildWhereQuery($filter);

    $sql = "SELECT $fields FROM $table WHERE $where";

    if(!is_null($group)){
        $sql .= " GROUP BY " . $group . " ";
    }

    if(!empty($order)){
        $sql .= ' ORDER BY ';
        $i = 0;
        foreach ($order as $field => $direction) {
            if ($i++ != 0) $sql .= ', ';
            $sql .= "$field $direction";
        }
    }

    if($init > 0 && $limit > 0) {
        $sql .= " LIMIT $init, $limit";
    }

    if($limit > 0 && $init == 0) {
        $sql .= " LIMIT $limit";
    }

    return $sql;
}

/**
 * @param $str
 * @return string|string[]|null
 */
function sql_escape($str) {
    return preg_replace("/\'/", "\'", $str);
}

/**
 * @param $table
 * @param array $data
 * @param bool $lastId
 * @return bool|mixed
 * @throws Exception
 */
function _insert($table, $data = [], $lastId = false) {
    if(empty($table) || empty($data)) return false;

    $db = Database::getInstance();

    $parametros = [
        'table' => $table,
        'fields' => $data
    ];
    $sql = $db->getSQLInsert($parametros);

    $rs  = $db->getQuery($sql);

    return $lastId ? $db->getLastInsertId() : $db->getAffectedRows($rs);
}

/**
 * @param $table
 * @param array $data
 * @param string $where
 * @return bool|mixed
 * @throws Exception
 */
function _update($table, $data = [], $where = "1 = 1") {
    if(empty($table) || empty($data)) return false;
    $db = Database::getInstance();

    $parametros = [
        'table' => $table,
        'fields' => $data,
        'filters' => $where
    ];
    $sql = $db->getSQLUpdate($parametros);

    $rs = $db->getQuery($sql);

    return $db->getAffectedRows($rs);
}

/**
 * @param $table
 * @param string $where
 * @return bool|mixed
 * @throws Exception
 */
function _delete($table, $where = '1 = 1') {
    if(empty($table) || empty($where)) return false;
    $db = Database::getInstance();

    $parametros = [
        'table' => $table,
        'filters' => $where
    ];
    $sql = $db->getSQLDelete($parametros);
    $rs = $db->getQuery($sql);

    return $db->getAffectedRows($rs);
}

/**
 * @param array $filter
 * @return string
 */
function buildWhereQuery($filter = []) : string {

    $where = '1=1';

    if (!empty($filter)) {
        $i = 0;
        $where = '';
        foreach ($filter as $field => $value) {

            if (is_array($value) && array_key_exists('or', $value)){
                $t = array_keys($value);
                $value = $value['or'];
            }

            if ($i++ != 0){
                if (isset($t)&& ($t[0] == 'or')){
                    $where .= ' OR ';
                }else{
                    $where .= ' AND ';
                }
            }

            if (is_array($value)) {
                $list = '';
                $i = 0;
                foreach($value as $val) {
                    if ($i++ != 0) $list .= ', ';
                    $val = sql_escape($val);
                    $list .= "'$val'";
                }
                $where .= "($field IN ($list))";
            } else if (preg_match('/^[#]+/',$value)) {
                $value = escape_quotes_substring($value);
                $op_value = preg_replace('/^[#]+/', '', $value);
                $where .= "($field $op_value)";
            } else if (preg_match('/^[%]+/',$value)) {
                $value = preg_replace('/^[%]+/', '', $value);
                $value = sql_escape($value);
                $op_value = '%'.preg_replace('/\s+/','%', trim($value)).'%';
                $where .= "($field LIKE '$op_value')";

            } else if (preg_match('/^[!]+/',$value)) {
                $op_value = preg_replace('/^[!]+/', '', $value);
                $where .= "$op_value";
            } else {
                $value = sql_escape($value);
                $where .= is_numeric($value) ? "($field = $value)" : "($field = '$value')";
            }
        }
    }

    return $where;
}

/**
 * @param $str
 * @return string
 */
function escape_quotes_substring($str) : string
{
    $operator = '';
    if(strpos(mb_strtoupper($str), ' OR ') !== false) {
        $operator = ' OR ';
    }elseif (strpos(mb_strtoupper($str), ' AND ') !== false) {
        $operator = ' AND ';
    }elseif (strpos(mb_strtoupper($str), ' IN') !== false){
        $operator = ',';
    }

    $concat = '';
    if($operator != '') {
        $splitted = explode($operator, mb_strtoupper($str));
        $remainingOperators = count($splitted)-1;
        foreach ($splitted as $key => $value) {
            preg_match("/(?<=\').*(?=\')/", $value, $match);
            $begin = substr($value,0, strpos($value,"'")+1);
            $end = substr($value, strrpos($value,"'"));

            if($match) {
                $escaped = preg_replace("/.{0}(?<!\\\)'/", "\'", $match[0]);
                $value = $begin.$escaped.$end;
            }

            $concat.=$value;

            if($remainingOperators > 0){
                $concat.="$operator";
                $remainingOperators--;
            }
        }
    } else {
        preg_match("/(?<=\').*(?=\')/", $str, $match);
        $begin = substr($str,0, strpos($str,"'")+1);
        $end = substr($str, strrpos($str,"'"));

        $concat = $str;

        if($match)
        {
            $escaped = preg_replace("/.{0}(?<!\\\)'/", "\'", $match[0]);
            $concat = $begin.$escaped.$end;
        }

    }

    return $concat;
}

/**
 * @param string $url
 * @param int $exitcode
 */
function redirect(string $url, $exitcode = 0) {
    session_write_close();
    header("Location: " . APP_URL . "$url");
    exit($exitcode);
}


