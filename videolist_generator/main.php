<?php
chdir(__DIR__);

require __DIR__ . '/vendor/autoload.php';

include 'DateUtil.php';
include 'AtvVideolistGenerator.php';
include 'AtvVideolistGeneratorState.php';
include 'AtvVideoSiteScraper.php';
include 'AtvVideolistFilter.php';
include 'AtvVideolist.php';

$process = new AtvVideolistGenerator(
  new AtvVideolistGeneratorState('gen_state.json'),
  new DateUtil()
);
$scraper = new AtvVideoSiteScraper(new Goutte\Client());
$videolist = new AtvVideolist('../0.txt');

while ($process->hasDayToProcess()) {
  echo 'Processing: '.$process->getDayToProcess()."\n";

  $videos = $scraper->getVideosForDay($process->getDayToProcess());
  $videos = array_filter($videos, 'AtvVideolistFilter::removeUnwanted');

  $videosMeta = $scraper->getVideosDownloadingMetadata($videos);
  $videolist->add($videosMeta);

  $process->setDayProcessed();
}
?>
