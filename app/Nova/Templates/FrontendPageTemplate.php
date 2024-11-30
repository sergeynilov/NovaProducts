<?php

namespace App\Nova\Templates;

use Illuminate\Http\Request;
use Laravel\Nova\Fields\Text;
use Laravel\Nova\Panel;
use Outl1ne\PageManager\Template;

class FrontendPageTemplate extends Template
{
    // Name displayed in CMS
    public function name(Request $request): string
    {
        return 'My custom cms';
//        return parent::name($request);
    }

    // Fields displayed in CMS
    public function fields(Request $request): array
    {
        return [
            Panel::make('Some panel', [
                Text::make('Somethingsomething'),
                Text::make('Sub-translatable', 'subtranslatable')
                    ->translatable(),
            ])
                ->translatable(false),
        ];
    }

    // Resolve data for serialization
    public function resolve($page, $params): array
    {
        // Modify data as you please (ie turn ID-s into models)
        return $page->data ?? [];
    }

    // Optional suffix to the route (ie {blogPostName})
    public function pathSuffix(): string|null
    {
        return null;
    }
}
