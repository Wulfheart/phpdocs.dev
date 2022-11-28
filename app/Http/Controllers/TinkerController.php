<?php

namespace App\Http\Controllers;

use App\Actions\ApiGen\RetrieveIndexAction;
use App\Models\Package;
use App\ViewModels\Navigation;
use Illuminate\Http\Request;

class TinkerController extends Controller
{
    public function __construct(
        protected RetrieveIndexAction $retrieveIndexAction,
    ){

    }
    public function __invoke(Request $request)
    {

        $package = Package::with('versions')->where(['vendor' => 'apigen', 'package' => 'apigen'])->firstOrFail();

        //$package->versions->pluck('name')->dd();
        $version = $package->versions->where('name', 'v7.0.0-alpha.3')->firstOrFail();

        $index = $this->retrieveIndexAction->execute($version);
        //dd($index->namespace);
        $nav = Navigation::fromIndex($index);
        $nav->activate(['ApiGen', 'Renderer'], new Navigation\ContentInfo("Filter"));


        return view('docs.index', [
            'index' => $nav,
        ]);

    }
}
