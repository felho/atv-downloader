<?php
class AtvVideoSiteScraper
{
  const ATV_HOST = 'http://www.atv.hu';

  private $goutte = null;

  public function __construct(Goutte\Client $goutte)
  {
    $this->goutte = $goutte;
  }

  public function getVideosForDay($day)
  {
    $videoSelector = 'ul.thumbnail-wrapper li:not(:contains("További videók betöltése"))';
    $page1Url = self::ATV_HOST.'/videok?category=&datefrom='.$day.'&dateto='.$day;
    $page2Url = self::ATV_HOST.'/videok?page=2&category=&datefrom='.$day.'&dateto='.$day;
    $page3Url = self::ATV_HOST.'/videok?page=3&category=&datefrom='.$day.'&dateto='.$day;

    $videos = array();

    foreach (array($page1Url, $page2Url, $page3Url) as $url) {
      $result = $this->goutte->request('GET', $url)->filter($videoSelector);
      $result->each(function ($node, $i) use (&$videos) {
        $videos[] = array(
          'url' => $node->filter('div.text h4 a')->attr('href'),
          'show' => $node->filter('a.image-wrapper div.image-footer span.footer-text')->text(),
          'title' => $node->filter('div.text h4 a')->text()
        );
      });
    }

    // A page2-n van ismetlodes, ezert kiszurjuk a duplikatumokat.
    return array_map('unserialize', array_unique(array_map('serialize', $videos)));
  }

  public function getVideosDownloadingMetadata($videos)
  {
    $metadata = array();

    foreach ($videos as $video) {
      list($videoPath, $serverIp) = $this->getVideoDownloadingMetadata($video['url']);
      $metadata[] = array('videoPath' => $videoPath, 'serverIp' => $serverIp);
    }

    return $metadata;
  }

  private function getVideoDownloadingMetadata($videoUrl)
  {
    $page = $this->goutte->request('GET', self::ATV_HOST.$videoUrl);

    try {
      $videoFile = $page->filter('#flw-player')->attr('data-streamurl');
    } catch (Exception $e) {
      var_dump($videoUrl);
    }

    $host = null;
    $script = $page->filterXPath('//body/script')->filter(':contains(streamServerUrl)')->text();

    if (preg_match('/streamServerUrl = "([^"]+)"/', $script, $match)) {
      $host = $match[1];
    } else {
      trigger_error("Unable to get the host of the media server.", E_USER_ERROR);
    }

    return array($videoFile, $host);
  }
}
?>
