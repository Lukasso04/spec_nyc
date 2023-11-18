<?php
// KONTROLER strony kalkulatora
require_once dirname(__FILE__).'/../config.php';
//załaduj Smarty
require_once _ROOT_PATH.'/lib/smarty/Smarty.class.php';

//pobranie parametrów
function getParams(&$form){
	$form['kw'] = isset($_REQUEST['kw']) ? $_REQUEST['kw'] : null;
	$form['op'] = isset($_REQUEST['op']) ? $_REQUEST['op'] : null;
	$form['os'] = isset($_REQUEST['os']) ? $_REQUEST['os'] : null;	
}

//walidacja parametrów z przygotowaniem zmiennych dla widoku
function validate(&$form,&$infos,&$messages){

	//sprawdzenie, czy parametry zostały przekazane - jeśli nie to zakończ walidację
	if ( ! (isset($form['kw']) && isset($form['op']) && isset($form['os']) ))	return false;	
	
	//parametry przekazane zatem
	//nie pokazuj wstępu strony gdy tryb obliczeń (aby nie trzeba było przesuwać)
	// - ta zmienna zostanie użyta w widoku aby nie wyświetlać całego bloku itro z tłem 

	$infos [] = 'Przekazano parametry.';
// przerób
	if ( $form["kw"]== "") {
		$messages [] = 'Nie podano kwoty pożyczki';
	}
	if ( $form["op"] == "") {
		$messages [] = 'Nie podano oprocentowania';
	}
	if ( $form["os"] == "") {
		$messages [] = 'Nie podano okresu spłaty';
	}

	if (count ( $messages ) != 0) return false;
	
	if (! is_numeric( $form["kw"] )) {
		$messages [] = 'Kwota pożyczki nie jest liczbą';
	}
	
	if (! is_numeric( $form["op"] )) {
		$messages [] = 'Oprocentowanie nie jest liczbą całkowitą';
	}	
	if (! is_numeric( $form["os"] )) {
		$messages [] = 'Okres spłaty nie jest liczbą całkowitą';
	}	

	if ( $form["kw"] < 0) {
		$messages [] = 'Kwota pożyczki musi być liczbą dodatnią';
	}
	if ( $form["op"] < 0) {
		$messages [] = 'Oprocentowanie musi być liczbą dodatnią';
	}
	if ( $form["os"] < 0) {
		$messages [] = 'Okresu spłaty musi być liczbą dodatnią';
	}
		if (count ( $messages ) != 0) return false;
else return true;

}

function process(&$form,&$infos,&$messages,&$result){
	$kw = intval($form["kw"]);
	$os = intval($form["os"]);
	$op = floatval($form["op"]);

	$lata = $op/12;
	$opr = $os/100;

	$result = round($kw + ($kw*$opr*$lata) , 2);
}
$infos = array();
$form = array();
$result = null;
$messages = array();


getParams($form);
	if(validate($form,$infos,$messages)) {
		process($form,$infos, $messages, $result);
	}
	
// wzór
// 	// sprawdzenie, czy potrzebne wartości zostały przekazane
// 	if ( $form['x'] == "") $msgs [] = 'Nie podano liczby 1';
// 	if ( $form['y'] == "") $msgs [] = 'Nie podano liczby 2';
	
// 	//nie ma sensu walidować dalej gdy brak parametrów
// 	if ( count($msgs)==0 ) {
// 		// sprawdzenie, czy $x i $y są liczbami całkowitymi
// 		if (! is_numeric( $form['x'] )) $msgs [] = 'Pierwsza wartość nie jest liczbą';
// 		if (! is_numeric( $form['y'] )) $msgs [] = 'Druga wartość nie jest liczbą';
// 	}
	
// 	if (count($msgs)>0) return false;
// 	else return true;
// }
	
// wykonaj obliczenia
// function process(&$form,&$infos,&$msgs,&$result){
// 	$infos [] = 'Parametry poprawne. Wykonuję obliczenia.';
	
// 	//konwersja parametrów na int
// 	$form['x'] = floatval($form['x']);
// 	$form['y'] = floatval($form['y']);
	
// 	//wykonanie operacji
// 	switch ($form['op']) {
// 	case 'minus' :
// 		$result = $form['x'] - $form['y'];
// 		$form['op_name'] = '-';
// 		break;
// 	case 'times' :
// 		$result = $form['x'] * $form['y'];
// 		$form['op_name'] = '*';
// 		break;
// 	case 'div' :
// 		$result = $form['x'] / $form['y'];
// 		$form['op_name'] = '/';
// 		break;
// 	default :
// 		$result = $form['x'] + $form['y'];
// 		$form['op_name'] = '+';
// 		break;
// 	}
// }

//inicjacja zmiennych
// $form = null;
// $infos = array();
// $messages = array();
// $result = null;
	
// getParams($form);
// if ( validate($form,$infos,$messages,$hide_intro) ){
// 	process($form,$infos,$messages,$result);
// }

// 4. Przygotowanie danych dla szablonu

$smarty = new Smarty();

$smarty->assign('app_url',_APP_URL);
$smarty->assign('root_path',_ROOT_PATH);
$smarty->assign('page_title','Przykład 04');
$smarty->assign('page_description','Profesjonalne szablonowanie oparte na bibliotece Smarty');
$smarty->assign('page_header','Szablony Smarty');

//pozostałe zmienne niekoniecznie muszą istnieć, dlatego sprawdzamy aby nie otrzymać ostrzeżenia
$smarty->assign('form',$form);
$smarty->assign('result',$result);
$smarty->assign('messages',$messages);
$smarty->assign('infos',$infos);

// 5. Wywołanie szablonu
$smarty->display(_ROOT_PATH.'/app/calc.html');