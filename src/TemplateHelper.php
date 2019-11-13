<?php

namespace Rjvandoesburg\NovaTemplating;

use Illuminate\Support\Str;

class TemplateHelper
{
    /**
     * @param  \Laravel\Nova\Resource  $resource
     *
     * @return array
     * @throws \ReflectionException
     */
    public function forResource(\Laravel\Nova\Resource $resource): array
    {
        $resourceName = Str::slug((new \ReflectionClass($resource))->getShortName());
        $modelName = Str::slug((new \ReflectionClass($resource->resource))->getShortName());

        if ($resourceName !== $modelName) {
            return [
                "{$resourceName}-{$resource->resource->getKey()}",
                "{$modelName}-{$resource->resource->getKey()}",
                $resourceName,
                $modelName,
                'resource',
                'model',
                'index',
            ];
        }

        return [
            "{$modelName}-{$resource->resource->getKey()}",
            $modelName,
            'resource',
            'model',
            'index',
        ];
    }

    /**
     * @return array
     */
    public function defaultTemplates(): array
    {
        return [
            'home',
            'index',
        ];
    }

    /**
     * @return array
     */
    public function notFound(): array
    {
        return [
            '404',
            '500',
            'index',
        ];
    }

    /**
     * @return array
     */
    public function maintenance(): array
    {
        return [
            '503',
            '500',
            'index',
        ];
    }

    /**
     * @return array
     */
    public function serverError(): array
    {
        return [
            '500',
            'index',
        ];
    }
}
