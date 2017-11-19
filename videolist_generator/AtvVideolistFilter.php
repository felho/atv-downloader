<?php
class AtvVideolistFilter
{
  static private function cliFormat($string, $color) {
    return "\033[".$color."m".$string."\033[0m";
  }

  const red = '0;31';
  const green = '0;32';

  static public function removeUnwanted($video)
  {

    $isKnownShow   = true;
    $isFilteredOut = false;

  	switch ($video['show']) {
  	  case 'No comment':
  	  case 'Egyenes Beszéd':
  	  case 'ATV START':
  	  case 'ATV Newsroom':
  	  case 'Friderikusz':
  	  case 'HAVAS a pályán':
  	  case 'Szabad szemmel':
  	  case 'CSATT':
  	  case 'Voks':
  	  case 'Húzós':
  	  case 'Újságíróklub':
  	  case 'Az atv.hu bemutatja':
      case 'A tét':
      case 'ATV START':
      case 'Start +':
      case 'Hírvita':
      case 'Valóság fővárosa':
      case 'Heti Napló':
      case 'KrizShow':
        break;
  	  case 'Szókincs':
      case 'Vidám Vasárnap':
  	  case 'Hetiszakasz':
      case 'A világ arcai':
  	  case 'Fő az egészség!':
  	  case 'Hazahúzó':
  	  case 'Képlet':
  	  case 'Új Mezőgazdasági Magazin':
  	  case '700-as klub':
  	  case 'Világhíra':
  	  case 'Külvil�':
      case 'Tetthely':
      case 'Esti Frizbi':
      case '#Bochkor':
      case 'A nap híre':
      case 'Fórum':
      case 'Kőbánya Híradó':
      case 'Időjárás-jelentés':
        $isFilteredOut = true;
  	    break;
  	  case 'ATV Híradó':
        $isFilteredOut = !preg_match('/(ATV Híradó( Este)? - \d{4}\.\d{2}\.\d{2}).*/', $video['title']);
        break;
  	  case 'Világhíradó':
        $isFilteredOut = preg_match('/.*reggel.*/', $video['title']);
        break;
  	  default:
        $isKnownShow = false;
        break;
  	}

    $color = $isKnownShow ? self::green : self::red;
    echo ($isFilteredOut ? '- ' : '# ').self::cliFormat($video['show'].'-'.$video['title'], $color)."\n";

    return !$isFilteredOut;
  }
}
?>
