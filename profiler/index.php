<?php
require __DIR__ . '/../vendor/autoload.php';

$profiles = glob(__DIR__ . '/memory/*.txt');
if (!count($profiles)) {
    throw new \Symfony\Component\HttpKernel\Exception\NotFoundHttpException('Profiler has no available data');
}

asort($profiles);

?><!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport"
          content="width=device-width, user-scalable=no, initial-scale=1.0, maximum-scale=1.0, minimum-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>Profiler Graphs (<?=count($profiles)?>)</title>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/Chart.js/2.4.0/Chart.bundle.js"></script>
    <style>
        body, html {
            width: 100%;
            height: 100%;
            margin: 0;
            padding: 0;
            position: absolute;
        }
    </style>
</head>
<body>
    <canvas id="chart" style="width: 100%; height: 100%; position: absolute"></canvas>

    <script>
        let chart = document.getElementById('chart');
        let ctx   = chart.getContext('2d');

        let myLineChart = new Chart(ctx, {
            type:    'line',
            options: {
                scales: {
                    xAxes: [
                        {
                            type:     'linear',
                            position: 'bottom'
                        }
                    ]
                }
            },
            data:    {
                datasets: [
                    <?php
                    foreach ($profiles as $profile):
                        $c = [random_int(50, 200), random_int(50, 200), random_int(50, 200)];
                        $c1 = 'rgba(' . implode(', ', $c) . ', 0.4)';
                        $c2 = 'rgba(' . implode(', ', $c) . ', 1)';

                        $i = 1;
                        $points = array_map(function ($y) use (&$i) {
                            return [
                                'y' => (float)$y,
                                'x' => $i++,
                            ];
                        }, array_splice(explode("\n", file_get_contents($profile)), 0, 1000));
                    ?>
                    {
                        fill:                 false,
                        backgroundColor:      "<?=$c1?>",
                        borderColor:          "<?=$c2?>",
                        label:                "<?= basename($profile, '.txt') ?>",
                        pointRadius:          0,
                        pointBorderColor:     "rgba(0,0,0,0)",
                        pointBackgroundColor: "rgba(0,0,0,0)",
                        data:<?=json_encode($points)?>,
                    },
                    <?php endforeach; ?>
                ]
            }
        });
    </script>
</body>
</html>
