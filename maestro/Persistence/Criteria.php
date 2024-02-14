<?php

namespace Maestro\Persistence;

class Criteria extends \Orkester\Persistence\Criteria\Criteria
{

    public function select($columns = ['*']): Criteria
    {
        if (is_string($columns)) {
            $columns = explode(',', str_replace(' ', '', $columns));
        }
        return parent::select($columns);
    }

    public function where($column, $operator = null, $value = null, $boolean = 'and'): static
    {
        if (func_num_args() == 1) {
            if (is_string($column)) {
                parent::whereRaw($column);
                return $this;
            }
        }
        parent::where($column, $operator,$value, $boolean);
        return $this;
    }

    public function asQuery(): Criteria {
        return $this;
    }

    public function getResult() {
        return parent::getResult()->toArray();
    }

}