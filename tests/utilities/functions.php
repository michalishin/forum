<?php

/**
 * @param $class
 * @param array $attributes
 * @param null $count
 * @return object $class
 */
function create ($class, $attributes = [], $count = null) {
    return factory($class, $count)->create($attributes);
}

function make ($class, $attributes = [], $count = null) {
    return factory($class, $count)->make($attributes);
}