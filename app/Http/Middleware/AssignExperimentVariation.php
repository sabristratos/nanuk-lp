<?php

namespace App\Http\Middleware;

use App\Services\ExperimentService;
use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class AssignExperimentVariation
{
    protected ExperimentService $experimentService;

    public function __construct(ExperimentService $experimentService)
    {
        $this->experimentService = $experimentService;
    }

    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle(Request $request, Closure $next): Response
    {
        // We only run experiments on GET requests to avoid interfering with form submissions.
        if (!$request->isMethod('get')) {
            return $next($request);
        }

        // Using the route name as the key for the experiment.
        // This is more reliable than a view name.
        $routeName = $request->route()?->getName();

        if ($routeName) {
            $experiment = $this->experimentService->getActiveExperiment($routeName);

            if ($experiment) {
                $variation = $this->experimentService->getAssignedVariation($experiment->id);

                // Share the experiment data with the request so it can be accessed
                // by controllers and view composers later in the lifecycle.
                $request->attributes->add([
                    'experiment' => $experiment,
                    'variation' => $variation,
                ]);
            }
        }

        return $next($request);
    }
}
