<?php

require 'roulettesimulator.class.php';

$RouletteSimulator = new RouletteSimulator();

$bets = [
    ["bet_type" => "straight_up", "value" => [1], "fiches" => 1],
    ["bet_type" => "six_line_1", "fiches" => 1]
    ["bet_type" => "straight_up", "value" => [2], "fiches" => 3],
    ["bet_type" => "red", "fiches" => 1],
    ["bet_type" => "split", "value" => [0,3], "fiches" => 1]
];

$drawnNumber = $RouletteSimulator->extract_number();
$result = $RouletteSimulator->get_results($bets, $drawnNumber);

print_r($result);
