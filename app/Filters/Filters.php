<?php


namespace App\Filters;


use Illuminate\Http\Request;

abstract class Filters
{

    protected $request, $builder;

    protected $filters = [];

    public function __construct(Request $request)
    {
        $this->request = $request;
    }

    public function apply($builder)
    {
        $this->builder = $builder;

        foreach ($this->getFilters() as $filter => $value) {
            if (!$this->hasFilter($filter)) return null;

            $this->$filter($value);
        }

        return $this->builder;
    }

    /**
     * @param $filter
     * @return bool
     */
    public function hasFilter($filter): bool
    {
        return method_exists($this, $filter);
    }

    /**
     * @return array
     */
    public function getFilters(): array
    {
        return $this->request->only($this->filters);
    }
}