<?php

namespace Rjvandoesburg\NovaTemplating\Contracts;

use Illuminate\Http\Request;

interface Templatable
{
    /**
     * Indicates if the resource is templatable via the front-end.
     *
     * @param  \Illuminate\Http\Request  $request
     *
     * @return bool
     */
    public function isTemplatable(Request $request): bool;
}
