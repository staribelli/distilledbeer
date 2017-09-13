<?php

namespace BeerMarket\BeerService\Model;

/**
 * Class Collection
 *
 * @package BeerMarket\BeerService\Model
 */
// Not really my class, it's a utility class that I adapted.
class Collection implements \ArrayAccess, \Iterator
{
    private $items = [];
    public $length = 0;

    public function add($arg1, $arg2 = false)
    {
        if (!$arg2) {
            $this->items[] = $arg1;
        } else {
            if (!array_key_exists($arg1, $this->items)) {
                $this->items[$arg1] = $arg2;
            }
        }
        $this->count();

        return $this;
    }

    public function count()
    {
        $this->lenght = count($this->items);

        return $this->lenght;
    }

    public function next()
    {
        return next($this->items);
    }

    public function rewind()
    {
        return reset($this->items);
    }

    public function current()
    {
        return current($this->items);
    }

    public function currentKey()
    {
        return key($this->items);
    }

    public function key()
    {
        return $this->currentKey();
    }

    public function valid()
    {
        if (!is_null($this->key())) {
            return true;
        } else {
            return false;
        }
    }

    public function get($key)
    {
        return $this->items[$key];
    }

    public function offsetExists($offset)
    {
        return $this->contains($offset);
    }

    public function offsetGet($offset)
    {
        return $this->get($offset);
    }

    public function offsetSet($offset, $value)
    {
        return $this->set($offset, $value);
    }

    public function offsetUnset($offset)
    {
        return $this->remove($offset);
    }

    public function contains($obj)
    {
        foreach ($this->items as $element) {
            if ($element === $obj) {
                $this->rewind();

                return true;
            }
        }
        $this->rewind();

        return false;
    }
}
