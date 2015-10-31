<?php

/**
 * This file is part of GitterBot package.
 *
 * @author Serafim <nesk@xakep.ru>
 * @date 24.09.2015 15:27
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace App\Console\Commands;


use App\Karma;
use Illuminate\Console\Command;
use Illuminate\Support\Collection;

/**
 * Class StatsGitterKarma
 * @package App\Console\Commands
 */
class StatsGitterKarma extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'gitter:karma';


    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Get karma stats.';


    /**
     * Execute the console command.
     *     *
     * @return mixed
     */
    public function handle()
    {
        // Karma stats
        $query = Karma::query()
            ->where('status', 'inc')
            ->groupBy('user_target_id')
            ->get();

        $karma = new Collection([]);

        foreach ($query as $record) {
            $karma[] = (object)[
                'user'  => $record->target,
                'count' => $record->target->karma,
            ];
        }

        $karma = $karma
            ->sortBy(function ($item) {
                return $item->count;
            }, SORT_REGULAR, true)
            ->take(10);


        $this->render($karma, 'Получили благодарностей');


        // Thanks stats

        $query = Karma::query()
            ->where('status', 'inc')
            ->groupBy('user_id')
            ->get();

        $thanks = new Collection([]);

        foreach ($query as $record) {
            $thanks[] = (object)[
                'user'  => $record->user,
                'count' => $record->user->thanks,
            ];
        }

        $thanks = $thanks
            ->sortBy(function ($item) {
                return $item->count;
            }, SORT_REGULAR, true)
            ->take(10);

        $this->render($thanks, 'Сказали спасибо');
    }

    /**
     * @param $collection
     * @param $title
     */
    protected function render($collection, $title)
    {
        $this->line(str_repeat('=', 77));
        $this->line(sprintf('| %30s%s%s |', '', $title, str_repeat(' ', 43 - mb_strlen($title))));
        $this->line(str_repeat('-', 77));

        foreach ($collection as $result) {
            $this->line(sprintf('| %-35s | %-35s |', $result->user->login, $result->count));
        }

        $this->line(str_repeat('=', 77));
    }
}
