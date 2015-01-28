<?php

class DbCriteria extends CDbCriteria
{
    public function addSearchCondition($column, $keyword, $escape = true,
        $operator = 'AND', $like = 'LIKE')
    {
        if ($keyword == '') {
            return $this;
        }

        if ($escape) {
            $keyword = strtr(trim($keyword), array('%' => '\%', '_' => '\_', '\\' => '\\\\'));
            $oldLength = strlen($keyword);
            while ($oldLength !== strlen($keyword = str_replace('  ', ' ', $keyword))) {
                $oldLength = strlen($keyword);
            }
            $keyword = str_replace(' ', '%', $keyword);
            $keyword = '%'.$keyword.'%';
        }

        $valueParamName = self::PARAM_PREFIX.self::$paramCount++;

        $condition = $column." $like ".$valueParamName;
        if ($escape) {
            $escapeParamName = self::PARAM_PREFIX.self::$paramCount++;
            $condition .= ' ESCAPE '.$escapeParamName;
            $this->params[$escapeParamName] = '\\';
        }

        $this->params[$valueParamName] = $keyword;
        return $this->addCondition($condition, $operator);
    }
}
