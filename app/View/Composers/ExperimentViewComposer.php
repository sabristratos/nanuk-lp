<?php

namespace App\View\Composers;

use Illuminate\View\View;

class ExperimentViewComposer
{
    public function compose(View $view): void
    {
        $request = request();

        $experiment = $request->attributes->get('experiment');
        $variation = $request->attributes->get('variation');
        
        $modifications = $variation ? $variation->modifications : collect();

        $view->with([
            'experimentData' => [
                'experiment' => $experiment,
                'variation' => $variation,
                'modifications' => $modifications,
            ],
        ]);
    }
} 