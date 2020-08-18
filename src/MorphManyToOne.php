<?php

namespace GeneaLabs\LaravelNovaMorphManyToOne;

use Illuminate\Database\Eloquent\Relations\Concerns\InteractsWithPivotTable;
use Illuminate\Database\Eloquent\Relations\MorphToMany;
use Illuminate\Database\Eloquent\Relations\Pivot;

class MorphManyToOne extends MorphToMany
{
    use InteractsWithPivotTable;

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

    public function getResults()
    {
        $pivot = $this->newPivotQuery()
            ->get()
            ->map(function ($record) {
                $class = $this->using ? $this->using : Pivot::class;
                $pivot = $class::fromRawAttributes($this->parent, (array) $record, $this->getTable(), true);

                return $pivot->setPivotKeys($this->foreignPivotKey, $this->relatedPivotKey);
            })
            ->first();

        return $this->getModel()->find($pivot->{$pivot->getRelatedKey()});
    }
}
