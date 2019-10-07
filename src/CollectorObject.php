<?php

namespace Humblebrag\Collector;

use Humblebrag\Collector\Exceptions\ValidationException;

class CollectorObject implements \ArrayAccess, \Countable, \JsonSerializable
{
	protected $_values;
    protected $_castFields = [];
    protected $_requiredFields = [];

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

    public function validate()
    {
        foreach($this->_requiredFields as $key) {
            if(!isset($this->_values[$key])  || $this->_values[$key] === null) {
                throw new ValidationException(get_class($this) . "->$key is a required field to submit the checkout.");
            }

            if($this->_values[$key] instanceof CollectorObject) {
                ($this->_values[$key])->validate();
            }
        }
    }

    public function castFields()
    {
        foreach($this->_castFields as $key => $value) {
            if(isset($this->_values[$key]) && !($this->_values[$key] instanceof $value)) {
                $this->_values[$key] = $value::create($this->_values[$key]);
            }
        }
    }

    public function __set($k, $v)
    {
        $this->_values[$k] = $v;
    }

    public function &__get($k)
    {
        return $this->_values[$k];
    }

    public function __isset($k)
    {
        return $this->_values[$k];
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