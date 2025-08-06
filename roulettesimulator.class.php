<?php

/*
 * This file is part of a program licensed under the GNU General Public License.
 *
 * Copyright (C) 2025 Salvatore Fresta
 *
 * This program is free software: you can redistribute it and/or modify
 * it under the terms of the GNU General Public License as published by
 * the Free Software Foundation, either version 3 of the License, or
 * (at your option) any later version.
 *
 * This program is distributed in the hope that it will be useful,
 * but WITHOUT ANY WARRANTY; without even the implied warranty of
 * MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
 * GNU General Public License for more details.
 *
 * You should have received a copy of the GNU General Public License
 * along with this program.  If not, see <https://www.gnu.org/licenses/>.
 */

class RouletteSimulator {

  private $betting_list = array();

	function __construct() {

		$this->betting_list = [
			"straight_up" => [
			    "numbers" => array_map( fn($n) => [$n], range(0, 36) ),
			    "win" => 36
			],
			"split" => [
			    "numbers" => array_merge([[0,1],[0,2],[0,3]], [
			        [1,2],[2,3],[1,4],[2,5],[3,6],[4,5],[5,6],[4,7],[5,8],[6,9],
			        [7,8],[8,9],[7,10],[8,11],[9,12],[10,11],[11,12],[10,13],[11,14],
			        [12,15],[13,14],[14,15],[13,16],[14,17],[15,18],[16,17],[17,18],
			        [16,19],[17,20],[18,21],[19,20],[20,21],[19,22],[20,23],[21,24],
			        [22,23],[23,24],[22,25],[23,26],[24,27],[25,26],[26,27],[25,28],
			        [26,29],[27,30],[28,29],[29,30],[28,31],[29,32],[30,33],[31,32],
			        [32,33],[31,34],[32,35],[33,36]
			    ]),
			    "win" => 18
			],
			"corner" => [
			    "numbers" => [
			        [1,2,4,5],[2,3,5,6],[4,5,7,8],[5,6,8,9],[7,8,10,11],[8,9,11,12],
			        [10,11,13,14],[11,12,14,15],[13,14,16,17],[14,15,17,18],
			        [16,17,19,20],[17,18,20,21],[19,20,22,23],[20,21,23,24],
			        [22,23,25,26],[23,24,26,27],[25,26,28,29],[26,27,29,30],
			        [28,29,31,32],[29,30,32,33],[31,32,34,35],[32,33,35,36]
			    ],
			    "win" => 9
			],
			"street_1"    => ["numbers" => [1, 2, 3], "win" => 12],
			"street_2"    => ["numbers" => [4, 5, 6], "win" => 12],
			"street_3"    => ["numbers" => [7, 8, 9], "win" => 12],
			"street_4"    => ["numbers" => [10, 11, 12], "win" => 12],
			"street_5"    => ["numbers" => [13, 14, 15], "win" => 12],
			"street_6"    => ["numbers" => [16, 17, 18], "win" => 12],
			"street_7"    => ["numbers" => [19, 20, 21], "win" => 12],
			"street_8"    => ["numbers" => [22, 23, 24], "win" => 12],
			"street_9"    => ["numbers" => [25, 26, 27], "win" => 12],
			"street_10"   => ["numbers" => [28, 29, 30], "win" => 12],
			"street_11"   => ["numbers" => [31, 32, 33], "win" => 12],
			"street_12"   => ["numbers" => [34, 35, 36], "win" => 12],
			"six_line_1"  => ["numbers" => range(1, 6), "win" => 6],
			"six_line_2"  => ["numbers" => range(7, 12), "win" => 6],
			"six_line_3"  => ["numbers" => range(13, 18), "win" => 6],
			"six_line_4"  => ["numbers" => range(19, 24), "win" => 6],
			"six_line_5"  => ["numbers" => range(25, 30), "win" => 6],
			"six_line_6"  => ["numbers" => range(31, 36), "win" => 6],
			"column_1"    => ["numbers" => [1,4,7,10,13,16,19,22,25,28,31,34], "win" => 3],
			"column_2"    => ["numbers" => [2,5,8,11,14,17,20,23,26,29,32,35], "win" => 3],
			"column_3"    => ["numbers" => [3,6,9,12,15,18,21,24,27,30,33,36], "win" => 3],
			"dozen_1"     => ["numbers" => range(1,12), "win" => 3],
			"dozen_2"     => ["numbers" => range(13,24), "win" => 3],
			"dozen_3"     => ["numbers" => range(25,36), "win" => 3],
			"red"         => ["numbers" => [1,3,5,7,9,12,14,16,18,19,21,23,25,27,30,32,34,36], "win" => 2],
			"black"       => ["numbers" => [2,4,6,8,10,11,13,15,17,20,22,24,26,28,29,31,33,35], "win" => 2],
			"even"        => ["numbers" => array_filter(range(1,36), fn($n) => $n % 2 === 0), "win" => 2],
			"odd"         => ["numbers" => array_filter(range(1,36), fn($n) => $n % 2 === 1), "win" => 2],
			"high"        => ["numbers" => range(1,18), "win" => 2],
			"low"         => ["numbers" => range(19,36), "win" => 2]
		];

	}

	public function extract_number(): int {

		try {
		    return random_int(0, 36);
    }
		catch (Exception $e) {
		    return mt_rand(0, 36);
    }

	}

	public function get_results(array $bets, int $drawnNumber): array {

		$totalBet = 0;
		$totalWin = 0;
		$anyWin = false;
		$details = [];

		foreach ($bets as $single_bet) {
		    $bet_type = $single_bet['bet_type'];
		    $fiches = $single_bet['fiches'] ?? 0;
		    $value = $single_bet['value'] ?? [];

		    $totalBet += $fiches;
		    $isWinner = false;
		    $win = 0;

		    if (!isset($this->betting_list[$bet_type])) {
		        $details[] = [
		            'bet_type' => $bet_type,
		            'value' => $value,
		            'fiches' => $fiches,
		            'is_win' => false,
		            'win_amount' => 0
		        ];
		        continue;
		    }

		    $define = $this->betting_list[$bet_type];
		    $gruppinumbers = $define['numbers'];
		    $multiplicator = $define['win'];

		    if (in_array($bet_type, ['straight_up', 'split', 'corner'])) {
		        if (empty($value)) {
		            $details[] = [
		                'bet_type' => $bet_type,
		                'value' => [],
		                'fiches' => $fiches,
		                'is_win' => false,
		                'win_amount' => 0
		            ];
		            continue;
		        }

		        foreach ($gruppinumbers as $group) {
		            $gr = $group;
		            $val = $value;
		            sort($gr);
		            sort($val);
		            if ($gr === $val && in_array($drawnNumber, $gr)) {
		                $isWinner = true;
		                break;
		            }
		        }
		    } else {
		        if (in_array($drawnNumber, $gruppinumbers)) {
		            $isWinner = true;
		        }
		    }

		    if ($isWinner) {
		        $anyWin = true;
		        $win = $fiches * $multiplicator;
		        $totalWin += $win;
		    }

		    $details[] = [
		        'betType' => $bet_type,
		        'value' => $value,
		        'fiches' => $fiches,
		        'isWin' => $isWinner,
		        'winAmount' => $win
		    ];
		}

		return [
		    'number' => $drawnNumber,
		    'win' => $anyWin,
		    'totalBet' => $totalBet,
		    'totalWin' => $totalWin,
		    'netWin' => $totalWin - $totalBet,
		    'details' => $details
		];

	}

}
