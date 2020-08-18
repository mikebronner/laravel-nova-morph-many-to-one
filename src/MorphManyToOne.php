<?php

namespace GeneaLabs\LaravelNovaMorphManyToOne;

use Illuminate\Database\Eloquent\Relations\MorphToMany;

class MorphManyToOne extends MorphToMany
{
    protected function addWhereConstraints()
    {
        parent::addWhereConstraints();

        $this->query->take(1);

        return $this;
    }

    public function addEagerConstraints(array $models)
    {
        parent::addEagerConstraints($models);

        $this->query->take(1);
    }

    public function newPivotQuery()
    {
        return parent::newPivotQuery()->take(1);
    }

    public function getResults()
    {
        return parent::getResults()->first();
    }
}
