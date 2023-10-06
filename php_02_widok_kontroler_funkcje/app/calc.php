<?php
// KONTROLER strony kalkulatora
require_once dirname(__FILE__).'/../config.php';

// W kontrolerze niczego nie wysyła się do klienta.
// Wysłaniem odpowiedzi zajmie się odpowiedni widok.
// Parametry do widoku przekazujemy przez zmienne.

// 1. pobranie parametrów
// include _ROOT_PATH.'/app/security/check.php';

function getParams(&$x,&$y,&$p){
	$x = isset($_REQUEST['x']) ? $_REQUEST['x'] : null;
	$y = isset($_REQUEST['y']) ? $_REQUEST['y'] : null;
	$p = isset($_REQUEST['p']) ? $_REQUEST['p'] : null;	
}

// 2. walidacja parametrów z przygotowaniem zmiennych dla widoku

// sprawdzenie, czy parametry zostały przekazane
function validate(&$x,&$y,&$p,&$messages){
	// sprawdzenie, czy parametry zostały przekazane
	if ( ! (isset($x) && isset($y) && isset($p))) {
		// sytuacja wystąpi kiedy np. kontroler zostanie wywołany bezpośrednio - nie z formularza
		// teraz zakładamy, ze nie jest to błąd. Po prostu nie wykonamy obliczeń
		return false;
	}

// sprawdzenie, czy potrzebne wartości zostały przekazane
if ( $x == "") {
	$messages [] = 'Nie podano kwoty lokaty';
}
if ( $y == "") {
	$messages [] = 'Nie podano okresu lokaty';
}
if ( $p == "") {
	$messages [] = 'Nie podano oprocentowania lokaty';
}
//nie ma sensu walidować dalej gdy brak parametrów
if (empty( $messages )) {
	
	// sprawdzenie, czy $x i $y są liczbami całkowitymi
	if (! is_numeric( $x )) {
		$messages [] = 'Kwota lokaty nie jest liczbą całkowitą';
	}
	if (! is_numeric( $p )) {
		$messages [] = 'Okres lokaty nie jest liczbą';
	}
	if (! is_numeric( $y )) {
		$messages [] = 'Oprocentowanie  lokaty nie jest liczbą całkowitą';
	}	
}
if (count ( $messages ) != 0) return false;
else return true;
}
// 3. wykonaj zadanie jeśli wszystko w porządku

function process(&$x,&$y,&$p,&$messages,&$result,&$result2,&$result3){
	global $role;
if (empty ( $messages )) { // gdy brak błędów
	
	//konwersja parametrów na int
	$xx = intval($x);
	$yy = intval($y);
	$pp = intval($p)/100;
	
	//wykonanie operacji
	for($i=0; $i<$y; $i++){
		$xx= $xx*$pp+$xx;

	}
		$result = round($xx, 2);
		$result2 = $result-$x;
		$m=$yy*12;
		$result3 = round($result2/$m, 2);
	}
}

$xx = null;
$yy = null;
$pp = null;
$result = null;
$result2 = null;
$result3 = null;
$messages = array();

getParams($x,$y,$p,$result,$result2,$result3);
if ( validate($x,$y,$p,$messages) ) { // gdy brak błędów
	process($x,$y,$p,$messages,$result,$result2,$result3);
}

// 4. Wywołanie widoku z przekazaniem zmiennych
// - zainicjowane zmienne ($messages,$x,$y,$operation,$result)
//   będą dostępne w dołączonym skrypcie
include 'calc_view.php';