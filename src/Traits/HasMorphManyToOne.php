<?php

namespace GeneaLabs\LaravelNovaMorphManyToOne\Traits;

use Illuminate\Support\Str;
use Illuminate\Support\Facades\Route;
use Laravel\Nova\Fields\FieldCollection;
use Illuminate\Database\Eloquent\Builder;
use Laravel\Nova\Http\Requests\NovaRequest;
use GeneaLabs\LaravelNovaMorphManyToOne\MorphManyToOne;

trait HasMorphManyToOne
{
    public function morphManyToOne(
        $related,
        $name,
        $table = null,
        $foreignPivotKey = null,
        $relatedPivotKey = null,
        $parentKey = null,
        $relatedKey = null,
        $inverse = false
    ) {
        $caller = $this->guessBelongsToManyRelation();

        // First, we will need to determine the foreign key and "other key" for the
        // relationship. Once we have determined the keys we will make the query
        // instances, as well as the relationship instances we need for these.
        $instance = $this->newRelatedInstance($related);

        $foreignPivotKey = $foreignPivotKey ?: $name . '_id';

        $relatedPivotKey = $relatedPivotKey ?: $instance->getForeignKey();

        // Now we're ready to create a new query builder for this related model and
        // the relationship instances for this relation. This relations will set
        // appropriate query constraints then entirely manages the hydrations.
        if (! $table) {
            $words = preg_split('/(_)/u', $name, -1, PREG_SPLIT_DELIM_CAPTURE);

            $lastWord = array_pop($words);

            $table = implode('', $words)
                . Str::plural($lastWord);
        }

        return $this->newMorphManyToOne(
            $instance->newQuery(),
            $this,
            $name,
            $table,
            $foreignPivotKey,
            $relatedPivotKey,
            $parentKey ?: $this->getKeyName(),
            $relatedKey ?: $instance->getKeyName(),
            $caller,
            $inverse
        );
    }

    protected function newMorphManyToOne(
        Builder $query,
        self $parent,
        $name,
        $table,
        $foreignPivotKey,
        $relatedPivotKey,
        $parentKey,
        $relatedKey,
        $relationName = null,
        $inverse = false
    ) {
        return new MorphManyToOne(
            $query,
            $parent,
            $name,
            $table,
            $foreignPivotKey,
            $relatedPivotKey,
            $parentKey,
            $relatedKey,
            $relationName,
            $inverse
        );
    }

    public function availableFields(NovaRequest $request)
    {
        $fields = $this->fields($request);
        $availableFields = [];

        foreach ($fields as $field) {
            $availableFields[] = $field;

            if ($field instanceof MorphManyToOne) {
                foreach ($field->meta['types'] as $type) {
                    if ($this->requestIsAssociateRequest()) {
                        $availableFields = array_merge($availableFields, $type['fields']);
                    }
                }
            }
        }

        return new FieldCollection(array_values($this->filter($availableFields)));
    }

    protected function requestIsAssociateRequest() : bool
    {
        return Str::endsWith(
            Route::currentRouteAction(),
            'AssociatableController@index'
        );
    }
}
