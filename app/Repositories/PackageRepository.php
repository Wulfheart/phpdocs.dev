<?php

namespace App\Repositories;

use App\Actions\ApiGen\AnalyzeProjectAction;
use App\Actions\ApiGen\RetrieveIndexAction;
use App\Models\PackageVersion;
use App\ViewModels\Navigation;
use Illuminate\Contracts\Filesystem\Filesystem;
use Illuminate\Support\Facades\Storage;

class PackageRepository
{
    protected Filesystem $filesystem;
    protected string $basedir;
    const KEY_NAVIGATION = "navigation.serialized";

    public function __construct(
        protected PackageVersion $packageVersion,
        protected AnalyzeProjectAction $analyzeProjectAction,
        protected RetrieveIndexAction $retrieveIndexAction,
    )
    {
        $this->basedir = $this->packageVersion->id;
        $this->filesystem = Storage::disk('cached');
    }

    public function cache(): void
    {
        $analyzeResult = $this->analyzeProjectAction->execute($this->packageVersion);
        $index = $this->retrieveIndexAction->index($analyzeResult);
        $nav = Navigation::fromIndex($index);
        $this->storeNavigation($nav);

    }

    protected function storeNavigation(Navigation $navigation): void
    {
        $encoded = gzencode(serialize($navigation));
        $this->filesystem->put($this->basedir . DIRECTORY_SEPARATOR . self::KEY_NAVIGATION, $encoded);
    }

    public function getNavigation(): Navigation
    {
        $encoded = $this->filesystem->get($this->basedir . DIRECTORY_SEPARATOR . self::KEY_NAVIGATION);
        return unserialize(gzdecode($encoded), ['allowed_classes' => Navigation::class]);
    }

}
