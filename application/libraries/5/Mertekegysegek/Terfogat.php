<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Terfogat {

	// Class constructor
	function __construct() {

		$CI =& get_instance();
		$CI->load->helper('maths');
		$CI->load->helper('language');

		return;
	}

	function Generate($level) {

		$units = array(
			array(
				'short' => 'mm',
				'long'	=> 'köbmilliméter',
				'mult'	=> 1000
				),
			array(
				'short' => 'cm',
				'long'	=> 'köbcentiméter',
				'mult'	=> 1000
				),
			array(
				'short' => 'dm',
				'long'	=> 'köbdeciméter',
				'mult'	=> 1000
				),
			array(
				'short' => 'm',
				'long'	=> 'köbméter'
				)
			);

		if ($level <= 1) {
			$indexFrom 	= rand(1,2);
			$indexTo 	= $indexFrom - rand(1,1);
		} elseif ($level <= 2) {
			$indexFrom 	= rand(2,3);
			$indexTo 	= $indexFrom - rand(1,2);
		} else {
			$indexFrom 	= rand(3,3);
			$indexTo 	= $indexFrom - rand(2,3);
		}

		$value = rand(1,20) * pow(10, rand(0,2));

		// Calculate multiplier
		$mult = 1;
		for ($i=$indexFrom; $i > $indexTo; $i--) { 
			$mult *= $units[$i-1]['mult'];
		}

		// Switch direction
		if (rand(1,2) == 1) {
			list($indexFrom, $indexTo) = array($indexTo, $indexFrom);
			$correct = $value;
			$value *= $mult;
		} else {
			$correct = $value * $mult;
		}

		$valueText		= BigNum($value);
		$correctText	= BigNum($correct);

		$unitFrom	= $units[$indexFrom];
		$unitTo 	= $units[$indexTo];

		$question = 'Számoljuk ki, hogy $'.$valueText.'$ '.$unitFrom['long'].' hány '.$unitTo['long'].'nek felel meg!';

		$solution = '$'.$correctText.'\,\text{'.$unitTo['long'].'}$';

		$hints = $this->Hints($units, $indexFrom, $indexTo, $value);

		return array(
			'question' 	=> $question,
			'correct' 	=> $correct,
			'solution'	=> $solution,
			'hints'		=> $hints,
			'labels'	=> ['right' => '$\text{'.$unitTo['short'].'}^3$']
		);
	}

	function Hints($units, $indexFrom, $indexTo, $value) {

		// First page with details
		$page[] = $this->UnitsSummary($units);

		$details = 'Ez azt jelenti, hogy:<ul>';

		for ($i=0; $i < count($units)-1; $i++) {

			$mult 		= BigNum($units[$i]['mult']);
			$unitFrom1 	= $units[$i]['short'];
			$unitFrom2 	= $units[$i]['long'];
			$unitTo1 	= $units[$i+1]['short'];
			$unitTo2 	= $units[$i+1]['long'];
			$end 		= ($i<count($units)-2 ? ',' : '.');

			$details .= '<li>$'.$mult.'\,\text{'.$unitFrom1.'}^3=1\,\text{'.$unitTo1.'}^3$, azaz $'.$mult.'$ '.$unitFrom2.' $1$ '.$unitTo2.'nek felel meg'.$end.'</li>';

		}

		$details .= '</ul>';

		$page[] = [$details];
		$hints[] = $page;

		// Additional pages

		if ($indexFrom > $indexTo) {

			for ($i=$indexFrom; $i > $indexTo; $i--) {

				$mult 		= BigNum($units[$i-1]['mult']);
				$unitFrom 	= $units[$i]['short'];
				$unitTo 	= $units[$i-1]['short'];
				$valueNew 	= $value * $mult;

				$valueText		= BigNum($value);
				$valueNewText	= BigNum($valueNew);

				$result 	= ($i == $indexTo+1 ? '$<span class="label label-success">$'.$valueNewText.'$</span>$' : $valueNewText); 

				$hints[][] 	= $this->UnitsSummary($units).'Az ábráról leolvasható, hogy $1\,\text{'.$unitFrom.'}^3='.$mult.'\,\text{'.$unitTo.'}^3$, azaz<br /><br /><div class="text-center">$'.$valueText.'\,\text{'.$unitFrom.'}^3='.$valueText.'\cdot'.$mult.'\,\text{'.$unitTo.'}^3='.$result.'\,\text{'.$units[$i-1]['short'].'}^3$</div>';

				$value = $valueNew;
			}

		} else {

			for ($i=$indexFrom; $i < $indexTo; $i++) { 

				$mult 		= BigNum($units[$i]['mult']);
				$unitTo 	= $units[$i+1]['short'];
				$unitFrom 	= $units[$i]['short'];
				$valueNew 	= $value / $mult;

				$valueText		= BigNum($value);
				$valueNewText	= BigNum($valueNew);

				$result 	= ($i == $indexTo-1 ? '$<span class="label label-success">$'.$valueNewText.'$</span>$' : $valueNewText); 

				$hints[][] = $this->UnitsSummary($units).'Az ábráról leolvasható, hogy $'.$mult.'\,\text{'.$unitFrom.'}^3=1\,\text{'.$unitTo.'}^3$, azaz<br /><br /><div class="text-center">$'.$valueText.'\,\text{'.$unitFrom.'}^3='.$valueText.':'.$mult.'\,\text{'.$unitTo.'}^3='.$result.'\,\text{'.$unitTo.'}^3$</div>';

				$value = $valueNew;
			}
		}

		return $hints;
	}

	function UnitsSummary($units) {

		$text = '<div class="alert alert-info"><strong>Hosszúság-mértékegységek</strong>$$';

		foreach ($units as $index => $unit) {
			$text .= '\text{'.$unit['short'].'}^3';
			if ($index < count($units)-1) {
				$text .= '\overset{\small{\cdot'.BigNum($unit['mult']).'}}{\longrightarrow}';
			}
		}

		$text .= '$$</div>';

		return $text;
	}
}

?>