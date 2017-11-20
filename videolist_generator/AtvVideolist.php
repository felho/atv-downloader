<?php
class AtvVideolist
{
  public $filename = null;

  public function __construct($filename)
  {
    $this->filename = $filename;
  }

  public function add($videos)
  {
    $entries = array();

    foreach ($videos as $video) {
      $entries[] = $this->generateListEntry($video['videoPath'], $video['serverIp']);
    }

    $this->addToFile($entries);
  }

  private function addToFile($entries)
  {
    $currentEntries = array_map('trim', file($this->filename));

    $newEntries = $this->generateDiff($currentEntries, $entries);

    if (!empty($newEntries)) {
      echo implode("\n", $newEntries)."\n";
      file_put_contents($this->filename, implode("\n", $newEntries)."\n", FILE_APPEND);
    }
  }

  private function generateDiff($currentEntries, $entries)
  {
    $currentEntries = $this->normalizeEntries($currentEntries);
    $entries = $this->normalizeEntries($entries);

    return array_diff_key($entries, $currentEntries);
  }

  private function normalizeEntries($entries)
  {
    $ret = array();

    foreach ($entries as $entry) {
      list(, $fileName, ) = explode('__', $entry);
      $ret[$fileName] = $entry;
    }

    return $ret;
  }

  private function generateListEntry($videoPath, $serverIp)
  {
    list($month, $videoFile) = explode('/', $videoPath);
    $videoFile = str_replace('.mp4', '', $videoFile);

    // Pelda: 201309/20130928_havas_2__20130928_havas_e02_2__195.228.156.68
    return $month.'/'.$videoFile.'__'.$videoFile.'__'.$serverIp;
  }
}
?>
