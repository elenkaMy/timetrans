<?php

class MysqlCommandBuilder extends CMysqlCommandBuilder
{
    public function applyJoin($sql,$join)
    {
        if ($join == '') {
            return $sql;
        }

        if (strpos($sql, 'UPDATE') === 0 && ($pos = strpos($sql, 'SET')) !== false) {
            return substr($sql, 0, $pos).$join.' '.substr($sql, $pos);
        } elseif (strpos($sql, 'DELETE FROM ') === 0) {
            $tableName = substr($sql, 12);
            return "DELETE {$tableName} FROM {$tableName} ".$join;
        } else {
            return $sql.' '.$join;
        }
    }
}
