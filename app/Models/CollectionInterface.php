<?php

namespace App\Models;

/**
 * Interface CollectionInterface
 * @package App\Models
 */
interface CollectionInterface
{
    /**
     * @param mixed           $item
     * @param null|string|int $key
     *
     * @return bool
     */
    public function add($item, $key = null);

    /**
     * @param string|int $key
     *
     * @return bool
     */
    public function has($key);

    /**
     * @param string|int $key
     *
     * @return bool
     */
    public function delete($key);

    /**
     * @param string|int $key
     *
     * @return mixed
     */
    public function get($key);
}
