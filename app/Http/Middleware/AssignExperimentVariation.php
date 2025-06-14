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

        // Check if an experiment_id is provided in the request (for previewing)
        $experimentId = $request->get('experiment_id');
        
        if ($experimentId) {
            // For preview mode, get experiment by ID
            $experiment = $this->experimentService->getActiveExperiment($experimentId);
            
            if ($experiment) {
                // If variation_id is also provided, use it for preview
                $variationId = $request->get('variation_id');
                if ($variationId) {
                    // Handle preview variations (e.g., 'preview_0', 'preview_1')
                    if (str_starts_with($variationId, 'preview_')) {
                        $index = (int) str_replace('preview_', '', $variationId);
                        $variation = $experiment->variations->skip($index)->first();
                    } else {
                        $variation = $experiment->variations()->find($variationId);
                    }
                } else {
                    $variation = $this->experimentService->getAssignedVariation($experiment->id);
                }

                // Share the experiment data with the request
                $request->attributes->add([
                    'experiment' => $experiment,
                    'variation' => $variation,
                ]);
            }
        } else {
            // Normal experiment assignment using route name
            $routeName = $request->route()?->getName();

            if ($routeName) {
                $experiment = $this->experimentService->getActiveExperimentByTarget($routeName);

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
        }

        return $next($request);
    }
}
