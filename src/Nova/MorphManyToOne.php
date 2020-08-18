<?php

namespace GeneaLabs\LaravelNovaMorphManyToOne\Nova;

use Laravel\Nova\Nova;
use Laravel\Nova\Fields\Field;
use Laravel\Nova\Http\Requests\NovaRequest;

class MorphManyToOne extends Field
{
    public $component = 'laravel-nova-morph-many-to-one';

    public function __construct($name, $attribute = null)
    {
        parent::__construct($name, $attribute);

        $this->withMeta(['types' => []]);

        $this->displayUsing(function ($value) {
            foreach ($this->meta['types'] as $type) {
                if ($this->mapToKey($type['value']) == $this->mapToKey($value)) {
                    return $type['label'];
                }
            }

            return null;
        });
    }

    public function resolveForDisplay($resource, $attribute = null)
    {
        parent::resolveForDisplay($resource, $attribute);

        if ($resource && ! $this->value) {
            $attribute = $attribute
                ?: $this->attribute;
            $class = $this->getResource($resource, $attribute);

            if ($resource->$attribute && $class && $class::$title) {
                $this->value = $resource->$attribute->{$class::$title};
            }
        }
    }

    protected function resolveAttribute($resource, $attribute)
    {
        $this->addOptions($resource, $attribute);

        $result = parent::resolveAttribute($resource, $attribute);

        if (! $result) {
            $result = $resource->{$attribute};
        }

        if (! method_exists($result, "getKey")) {
            return;
        }

        return $result->getKey();
    }

    protected function getResource($resource, string $attribute) : string
    {
        return collect(Nova::$resources)
            ->filter(function ($item) use ($resource, $attribute) {
                return $resource->$attribute
                    && $item::$model === get_class($resource->$attribute)::model();
            })
            ->first()
            ?? "";
    }

    protected function addOptions($resource, string $attribute) : void
    {
        $class = $this->getResource($resource, $attribute);

        if ($class && $class::$model) {
            $items = (new $class::$model)
                ->orderBy($class::$title)
                ->get();

            $this->withMeta([
                'options' => $items,
            ]);
        }
    }

    protected function fillAttributeFromRequest(
        NovaRequest $request,
        $requestAttribute,
        $model,
        $attribute
    ) {
        $model->$attribute()->sync($request->$requestAttribute);
    }

    public function hideTypeWhenUpdating()
    {
        return $this->withMeta([
            'hideTypeWhenUpdating' => false,
        ]);
    }

    public function disableTypeWhenUpdating()
    {
        return $this->withMeta([
            'disableTypeWhenUpdating' => false,
        ]);
    }
}
