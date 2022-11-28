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
    protected PackageVersion $packageVersion;
    protected Filesystem $filesystem;
    protected string $basedir;
    const KEY_NAVIGATION = "navigation.serialized";

    public function __construct(
        protected AnalyzeProjectAction $analyzeProjectAction,
        protected RetrieveIndexAction  $retrieveIndexAction,
    )
    {
        $this->filesystem = Storage::disk('cached');
    }

    public function set(PackageVersion $packageVersion): self
    {
        $this->packageVersion = $packageVersion;
        $this->basedir = $this->packageVersion->id;
        return $this;
    }

    public function cache(): void
    {
        $analyzeResult = $this->analyzeProjectAction->execute($this->packageVersion);
        $index = $this->retrieveIndexAction->index($analyzeResult);
        $nav = Navigation::fromIndex($index);
        $this->storeNavigation($nav);

        $this->packageVersion->cached_at = now();
        $this->packageVersion->save();

    }

    protected function storeNavigation(Navigation $navigation): void
    {
        $encoded = gzencode(serialize($navigation));
        $this->filesystem->put($this->basedir . DIRECTORY_SEPARATOR . self::KEY_NAVIGATION, $encoded);
    }

    public function getNavigation(): Navigation
    {
        $encoded = $this->filesystem->get($this->basedir . DIRECTORY_SEPARATOR . self::KEY_NAVIGATION);
        return unserialize(gzdecode($encoded));
    }

}
