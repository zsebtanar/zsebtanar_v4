<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');



// --- FRACTIONS

// Define numerator
function fraction_numerator($level)
{
  if ($level <= 3) {
    $num = rand(1,3);
    $denom = rand(3,5);
  } elseif ($level <= 6) {
    $num = rand(3,10);
    $denom = rand(10,20);
  } else {
    $num = rand(5,20);
    $denom = rand(30,100);
  }

  $question = 'Mekkora a számláló az alábbi törtben?$$\\frac{'.$num.'}{'.$denom.'}$$';
  $correct = $num;
  $solution = '$'.$correct.'$';

	return array(
		'question' 	=> $question,
		'correct' 	=> $correct,
		'solution'	=> $solution
	);
}

// Define denominator
function fraction_denominator($level)
{
  if ($level <= 3) {
    $num = rand(1,3);
    $denom = rand(3,5);
  } elseif ($level <= 6) {
    $num = rand(3,10);
    $denom = rand(10,20);
  } else {
    $num = rand(5,20);
    $denom = rand(30,100);
  }

  $question = 'Mekkora a nevező az alábbi törtben?$$\\frac{'.$num.'}{'.$denom.'}$$';
  $correct = $denom;
  $solution = '$'.$correct.'$';

	return array(
		'question' 	=> $question,
		'correct' 	=> $correct,
		'solution'	=> $solution
	);
}

// Define fraction from rectangle
function fraction_rectangle($level)
{
  if ($level <= 3) {
    $row = 1;
    $col = rand(5,8);
  } elseif ($level <= 6) {
    $row = rand(2,3);
    $col = rand(5,8);
  } else {
    $row = rand(5,10);
    $col = rand(5,10);
  }

  $width = floor(100/11*$col);
  $denom = $row*$col;
  $num = rand(1, $denom);

  $question = 'Az alábbi téglalap hányad része kék?
        <div class="text-center"><br />
          <img class="img-question" width="'.$width.'%" src="'.RESOURCES_URL.'/fraction/create_image.php?function=fraction_rectangle&row='.$row.'&col='.$col.'&num='.$num.'">
        </div>';
  $type = 'fraction';
  $correct = array($num, $denom);
  $solution = '$\\\\frac{'.$num.'}{'.$denom.'}$';

	return array(
		'question' 	=> $question,
		'correct' 	=> $correct,
		'solution'	=> $solution,
		'type'	 	=> $type,
	);
}

// Define reciprocal of fraction
function fraction_reciprocal($level)
{
  if ($level <= 3) {
    $num = rand(1,3);
    $denom = rand(3,5);
  } elseif ($level <= 6) {
    $num = rand(3,10);
    $denom = rand(10,20);
  } else {
    $num = rand(5,20);
    $denom = rand(30,100);
  }

  $question = 'Számítsd ki a reciprokát!$$\\frac{'.$num.'}{'.$denom.'}$$';
  $type = 'fraction';
  $correct = array($num, $denom);
  $solution = '$\\\\frac{'.$num.'}{'.$denom.'}$';

	return array(
		'question' 	=> $question,
		'correct' 	=> $correct,
		'solution'	=> $solution,
		'type'	 	=> $type,
	);
}

// Convert fraction to integer
function fraction_to_int($level)
{
  if ($level <= 3) {
    $denom = rand(1,3);
    $integer = rand(1,5);
  } elseif ($level <= 6) {
    $denom = rand(3,10);
    $integer = rand(5,10);
  } else {
    $denom = rand(5,20);
    $integer = rand(10,20);
  }

  $num = $denom * $integer;

  $question = 'Alakítsd egésszé!$$\\frac{'.$num.'}{'.$denom.'}$$';
  $correct = $integer;
  $solution = '$'.$correct.'$';

	return array(
		'question' 	=> $question,
		'correct' 	=> $correct,
		'solution'	=> $solution
	);
}

// Convert fraction from integer
function fraction_from_int($level)
{
  if ($level <= 3) {
    $denom = 1;
    $integer = rand(1,5);
  } elseif ($level <= 6) {
    $denom = rand(3,5);
    $integer = rand(5,10);
  } else {
    $denom = rand(10,20);
    $integer = rand(10,20);
  }

  $num = $denom * $integer;

  $question = 'Melyik szám áll a kérdőjel helyén?$$'.$integer.'=\\frac{?}{'.$denom.'}$$';
  $correct = $num;
  $solution = '$'.$correct.'$';

	return array(
		'question' 	=> $question,
		'correct' 	=> $correct,
		'solution'	=> $solution
	);
}

// Expand fraction
function fraction_expand($level)
{
  if ($level <= 3) {
    $num = rand(1,3);
    $denom = rand(3,5);
    $expand = 2;
  } elseif ($level <= 6) {
    $num = rand(3,10);
    $denom = rand(10,20);
    $expand = rand(3,5);
  } else {
    $num = rand(5,20);
    $denom = rand(30,100);
    $expand = rand(5,10);
  }

  $question = 'Melyik szám áll a kérdőjel helyén?$$\\frac{'.$num.'}{'.$denom.'}=\\frac{?}{'.$denom*$expand.'}$$';
  $correct = $num*$expand;
  $solution = '$'.$correct.'$';

  return array(
    'question'  => $question,
    'correct'   => $correct,
    'solution'  => $solution
  );
}

// Simplify fraction
function fraction_simplify($level)
{
  if ($level <= 3) {
    $num = rand(1,3);
    $denom = rand(3,5);
    $expand = 2;
  } elseif ($level <= 6) {
    $num = rand(3,10);
    $denom = rand(10,20);
    $expand = rand(3,5);
  } else {
    $num = rand(5,20);
    $denom = rand(30,100);
    $expand = rand(5,10);
  }

  $question = 'Melyik szám áll a kérdőjel helyén?$$\\frac{'.$num*$expand.'}{'.$denom*$expand.'}=\\frac{?}{'.$denom.'}$$';
  $correct = $num;
  $solution = '$'.$correct.'$';

  return array(
    'question'  => $question,
    'correct'   => $correct,
    'solution'  => $solution
  );
}
?>