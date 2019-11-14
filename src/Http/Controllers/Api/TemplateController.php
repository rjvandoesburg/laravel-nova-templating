<?php

namespace Rjvandoesburg\NovaTemplating\Http\Controllers\Api;

use Illuminate\Routing\Controller;
use Laravel\Nova\Http\Middleware\DispatchServingNovaEvent;
use Laravel\Nova\Http\Requests\NovaRequest;
use Rjvandoesburg\NovaTemplating\Contracts\Templatable;
use Rjvandoesburg\NovaTemplating\TemplateHelper;

class TemplateController extends Controller
{
    /**
     * @var \Rjvandoesburg\NovaTemplating\TemplateHelper
     */
    protected $templateHelper;

    /**
     * TemplateController constructor.
     *
     * @param  \Rjvandoesburg\NovaTemplating\TemplateHelper  $templateHelper
     */
    public function __construct(TemplateHelper $templateHelper)
    {
        $this->templateHelper = $templateHelper;

        $this->middleware(DispatchServingNovaEvent::class);
    }

    /**
     * @param  \Laravel\Nova\Http\Requests\NovaRequest  $request
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function resource(NovaRequest $request): \Illuminate\Http\JsonResponse
    {
        try {
            /** @var \Laravel\Nova\Resource $resource */
            $resource = $request->newResourceWith(tap($request->findModelQuery(), function ($query) use ($request) {
                $request->newResource()->detailQuery($request, $query);
            })->first());

            if (empty($resource->resource)
                || ($resource instanceof Templatable && ! $resource->isTemplatable($request))
            ) {
                return response()->json([
                    'templates' => $this->templateHelper->notFound(),
                ], 404);
            }

            return response()->json([
                'templates'  => $this->templateHelper->forResource($resource),
                'resource'   => $request->route('resource'),
                'resourceId' => $request->route('resourceId'),
            ]);
        } catch (\Exception $exception) {
            return response()->json([
                'templates' => $this->templateHelper->serverError(),
                'message'   => $exception->getMessage(),
            ], 500);
        }
    }
}
