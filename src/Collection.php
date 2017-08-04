<?php

namespace Privateer\Collections;


use ArrayIterator;
use Countable;
use IteratorAggregate;

class Collection implements Countable, IteratorAggregate
{

    /**
     * @var array
     */
    protected $items;

    /**
     * Collection constructor.
     * @param array $items
     */
    public function __construct($items = array())
    {
        $this->items = is_array($items) ? $items : $this->getItemsAsArray($items);
    }

    /**
     * @return array
     */
    public function all()
    {
        return $this->items;
    }

    /**
     * @return int
     */
    public function count()
    {
        return count($this->items);
    }

    /**
     * @return bool
     */
    public function isEmpty()
    {
        return empty($this->items);
    }

    /**
     * @param null $default
     * @return mixed|null
     */
    public function first($default = null)
    {
        return isset($this->items[0]) ? $this->items[0] : $default;
    }

    /**
     * @param null $default
     * @return null
     */
    public function last($default = null)
    {
        $reversed = array_reverse($this->items);
        return isset($reversed[0]) ? $reversed[0] : $default;
    }

    /**
     * @return static
     */
    public function keys()
    {
        return new static(array_keys($this->items));
    }

    /**
     * @param callable $callback
     * @return $this
     */
    public function each(callable $callback)
    {
        foreach ($this->items as $key => $item) {
            $callback($item, $key);
        }

        return $this;
    }

    /**
     * @param callable|null $callback
     * @return static
     */
    public function filter(callable $callback = null)
    {
        if ($callback) {
            return new static(array_filter($this->items, $callback));
        }

        return new static(array_filter($this->items));
    }

    /**
     * @param callable $callback
     * @return static
     */
    public function map(callable $callback)
    {
        $keys = $this->keys()->all();
        $items = array_map($callback, $this->items, $keys);

        return new static(array_combine($keys, $items));
    }

    /**
     * @param $items
     * @return static
     */
    public function merge($items)
    {
        return new static(array_merge($this->items, $this->getArrayableItems($items)));
    }

    /**
     * @return string
     */
    public function toJson()
    {
        return json_encode($this->items);
    }

    /**
     * @return string
     */
    public function __toString()
    {
        return $this->toJson();
    }

    /**
     * @return ArrayIterator
     */
    public function getIterator()
    {
        return new ArrayIterator($this->items);
    }

    /**
     * @param $items
     * @return array
     */
    protected function getItemsAsArray($items)
    {
        if ($items instanceof Collection) {
            return $items->all();
        }

        return $items;
    }
}