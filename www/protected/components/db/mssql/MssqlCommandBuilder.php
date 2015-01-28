<?php
class MssqlCommandBuilder extends CMssqlCommandBuilder
{
    /**
     * Checks if the criteria has an order by clause when using offset/limit.
     * Override parent implementation to check if an orderby clause if specified when querying with an offset/limit.
     * If not, order it by pk.
     * @param CMssqlTableSchema $table table schema
     * @param CDbCriteria $criteria criteria
     * @return CDbCriteria the modified criteria
     */
    protected function checkCriteria($table, $criteria)
    {
        if (($criteria->offset > 0 || $criteria->limit > 0) && $criteria->order === '') {
            $criteria->order = is_array($table->primaryKey)
                ? implode(',', $table->primaryKey)
                : $table->primaryKey;
        }
        return $criteria;
    }

    public function applyLimit($sql, $limit, $offset)
    {
        $limit = $limit !== null ? (int)$limit : -1;
        $offset = $offset !== null ? (int)$offset : -1;
        if ($limit > 0 && $offset <= 0) {//just limit
            $sql = preg_replace('/^([\s(])*SELECT( DISTINCT)?(?!\s*TOP\s*\()/i',"\\1SELECT\\2 TOP $limit", $sql);
        } elseif ($offset > 0) {
            $sql = $this->rewriteLimitOffsetSql($sql, $limit, $offset);
        }
        return $sql;
    }

    protected function removeOrdering($sql)
    {
        if (!preg_match('/ORDER BY/i', $sql)) {
            return $sql;
        }

        return preg_replace('/(ORDER BY)[\s"\[](.*)(ASC|DESC)?(?:[\s"\[]|$|COMPUTE|FOR)/i', '', $sql);
    }

    protected function joinOrdering($orders, $newPrefix = false)
    {
        if (count($orders) === 0) {
            return '';
        }
        if ($newPrefix !== false) {
            return parent::joinOrdering($orders, $newPrefix);
        }

        $str = array();
        foreach($orders as $column => $direction) {
            $str[] = $column.' '.$direction;
        }
        return 'ORDER BY '.implode(', ', $str);
    }

    /**
     * @param string $sql
     * @param string $alias
     * @return boolean|string column name or false if not founded
     */
    protected function findColumnByAlias($sql, $alias)
    {
        $alias = preg_quote(trim($alias, '[]'));
        $matches = array();
        if (preg_match('/[\s,]+([\[]?\w+[\]]?\.[\[]?\w+[\]]?)\s*(AS)?\s*[\[]?'.$alias.'[\]]?/i', $sql, $matches)) {
            return $matches[1];
        }
        return false;
    }

    /**
     * @param string $sql sql query
     * @param integer $limit $limit >= 0 | -1
     * @param integer $offset $offset > 0
     * @return string modified sql query applied with limit and offset.
     */
    protected function rewriteLimitOffsetSql($sql, $limit, $offset)
    {
        $ordering = $newOrdering = $this->findOrdering($sql);
        if (!count($newOrdering)) {
            throw new CException('You must define order clause for using offsets in MSSQL database.');
        }

        $rowNumAlias = '[__row_num__]';
        $innerAlias = '[__inner__]';
        if ($limit < 0) {
            $whereCondition = '('.$innerAlias.'.'.$rowNumAlias.' > '.($offset + 1).')';
        } elseif ($limit === 0) {
            $whereCondition = '(1 <> 1)';
        } else {
            $whereCondition = '('.$innerAlias.'.'.$rowNumAlias.' BETWEEN '.($offset + 1).' AND '.($offset + $limit).')';
        }

        if ($limit > 0) {
            $topSelect = ' TOP '.($offset + $limit);
        } else {
            $sql = $this->removeOrdering($sql);
            $topSelect = '';
        }

        $overOrdering = array();
        foreach ($ordering as $alias => $dest) {
            if (($rawFieldName = $this->findColumnByAlias($sql, $alias)) !== false) {
                $overOrdering[$rawFieldName] = $dest;
            } else {
                $overOrdering[$alias] = $dest;
            }
        }

        $selectRowNumber = 'ROW_NUMBER() OVER('.$this->joinOrdering($overOrdering).') AS '.$rowNumAlias.', ';
        $sql = preg_replace('/^([\s(])*SELECT( DISTINCT)?(?!\s*TOP\s*\()/i',"\\1SELECT\\2 $topSelect $selectRowNumber", $sql);
        $sql = "SELECT * FROM ($sql) AS $innerAlias WHERE $whereCondition ".$this->joinOrdering($ordering, $innerAlias);
        return $sql;
    }
}
