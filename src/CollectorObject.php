<?php

namespace Humblebrag\Collector;

class CollectorObject implements \ArrayAccess, \Countable, \JsonSerializable
{
	protected $_values;

    protected function __construct($values)
    {
        foreach($values as $key => $value) {
            $this->_values[$key] = $value;
        }
    }
    
    public static function create($values = [])
    {
        return new static($values);
    }

    public function offsetSet($offset, $value) {
        if (is_null($offset)) {
            $this->_values[] = $value;
        } else {
            $this->_values[$offset] = $value;
        }
    }

    public function offsetExists($offset) {
        return isset($this->_values[$offset]);
    }

    public function offsetUnset($offset) {
        unset($this->_values[$offset]);
    }

    public function offsetGet($offset) {
        return isset($this->_values[$offset]) ? $this->_values[$offset] : null;
    }

    public function count()
    {
    	return count($this->_values);
    }

    public function jsonSerialize()
    {
    	return $this->toArray();
    }

    public function toArray()
    {
    	$maybeToArray = function ($value) {
            if (is_null($value)) {
                return null;
            }
            return method_exists($value, 'toArray') ? $value->toArray() : $value;
        };
        
        return array_reduce(array_keys($this->_values), function ($acc, $k) use ($maybeToArray) {
            if ($k[0] == '_') {
                return $acc;
            }
            $v = $this->_values[$k];
            if (self::isList($v)) {
                $acc[$k] = array_map($maybeToArray, $v);
            } else {
                $acc[$k] = $maybeToArray($v);
            }
            return $acc;
        }, []);
    }

    public static function isList($array)
    {
        if (!is_array($array)) {
            return false;
        }
        if ($array === []) {
            return true;
        }
        if (array_keys($array) !== range(0, count($array) - 1)) {
            return false;
        }
        return true;
    }


}