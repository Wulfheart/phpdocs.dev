<?php

namespace App\Jobs\Packagist;

use App\Actions\Packagist\InitializePackageFromPackagistAction;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldBeUnique;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;

class InitializePackageJob implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    /**
     * Create a new job instance.
     *
     * @return void
     */
    public function __construct(
        protected string $vendor,
        protected string $package,
    )
    {
        //
    }

    /**
     * Execute the job.
     *
     * @return void
     */
    public function handle(
        InitializePackageFromPackagistAction $initializePackageFromPackagistAction
    )
    {
        $initializePackageFromPackagistAction->execute($this->vendor, $this->package);


    }
}
