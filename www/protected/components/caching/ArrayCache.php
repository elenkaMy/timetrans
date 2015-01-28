<?php

class ArrayCache extends CCache
{
    /**
     * @var array[] in key => value format. Each values is array with
     * value, expire elements. Expire is the Unix timestamp - expire datatime.
     * Null means never expire.
     */
    private $_data = array();

    /**
     * Retrieves a value from array with a specified key.
     * @param string $key a unique key identifying the cached value
     * @return string|boolean the value stored in cache, false if the value is not in the cache or expired.
     * @throws CException if this method is not overridden by child classes
     */
    protected function getValue($key)
    {
        if (!isset($this->_data[$key])) {
            return false;
        }

        if (isset($this->_data[$key]['expire'])) {
            if ($this->_data[$key]['expire'] <= time()) {
                unset($this->_data[$key]);
                return false;
            }
        }

        return $this->_data[$key]['value'];
    }

    /**
     * Stores a value identified by a key in array.
     *
     * @param string $key the key identifying the value to be cached
     * @param string $value the value to be cached
     * @param integer $expire the number of seconds in which the cached value will expire. 0 means never expire.
     * @return boolean true if the value is successfully stored into cache, false otherwise
     * @throws CException if this method is not overridden by child classes
     */
    protected function setValue($key, $value, $expire)
    {
        $this->_data[$key] = array(
            'value'     =>  $value,
            'expire'    =>  !$expire ? null : (time() + (int) $expire),
        );
        return true;
    }

    /**
     * Stores a value identified by a key into array if the array does not contain this key.
     * @param string $key the key identifying the value to be cached
     * @param string $value the value to be cached
     * @param integer $expire the number of seconds in which the cached value will expire. 0 means never expire.
     * @return boolean true if the value is successfully stored into cache, false otherwise
     * @throws CException if this method is not overridden by child classes
     */
    protected function addValue($key, $value, $expire)
    {
        if (isset($this->_data[$key])) {
            return false;
        }
        return $this->setValue($key, $value, $expire);
    }

    /**
     * Deletes a value with the specified key from array
     * @param string $key the key of the value to be deleted
     * @return boolean if no error happens during deletion
     * @throws CException if this method is not overridden by child classes
     */
    protected function deleteValue($key)
    {
        if (isset($this->_data)) {
            unset($this->_data[$key]);
        }
        return true;
    }

    /**
     * Deletes all values from array.
     * @return boolean whether the flush operation was successful.
     * @throws CException if this method is not overridden by child classes
     * @since 1.1.5
     */
    protected function flushValues()
    {
        $this->_data = array();
        return true;
    }
}
