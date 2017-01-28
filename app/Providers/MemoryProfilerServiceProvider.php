<?php declare(strict_types=1);
/**
 * This file is part of KarmaBot package.
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */
namespace App\Providers;

use Illuminate\Support\ServiceProvider;
use App\Bot\Support\MemoryProfiler;

/**
 * Class MemoryProfilerServiceProvider
 * @package KarmaBot\Providers
 */
class MemoryProfilerServiceProvider extends ServiceProvider
{
    /**
     * @return void
     */
    public function register()
    {
        $this->app->singleton(MemoryProfiler::class, function () {
            $profiler = new MemoryProfiler();

            $fileCreated = null;

            $profiler->setOutput(function ($delta, $sum) use (&$fileCreated) {
                if (!$fileCreated) {
                    $path = base_path('../profiler/memory/' . date('Y_m_d_His') . '_memory_usage.txt');
                    $fileCreated = fopen($path, 'a+b');
                }

                fwrite($fileCreated, $sum . "\n");
            });

            return $profiler;
        });
    }
}