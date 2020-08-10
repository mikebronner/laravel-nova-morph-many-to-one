<?php

namespace GeneaLabs\LaravelNovaMorphManyToOne\Nova;

use Laravel\Nova\Fields\MorphToMany;

class MorphManyToOne extends MorphToMany
{
    public $component = 'laravel-nova-many-to-one-polymorphic-relationship';
}
