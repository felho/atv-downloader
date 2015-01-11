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
      case 'Világhíradó':
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
        $isFilteredOut = true;
  	    break;
  	  case 'ATV Híradó':
        //$isFilteredOut = preg_match('/(Reggeli|Esti|Késő esti|Kora esti|Déli) híradó.*/', $video['title']);
        $isFilteredOut = false;
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
