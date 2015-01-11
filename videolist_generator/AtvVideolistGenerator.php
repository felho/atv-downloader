<?php
class AtvVideolistGenerator
{
  private $state = null;
  private $dateUtil = null;

  private $currentDay = null;
  private $hasDayToProcess = true;

  public function __construct(AtvVideolistGeneratorState $state, DateUtil $dateUtil)
  {
    $this->state = $state;
    $this->dateUtil = $dateUtil;

    $this->currentDay = $this->getFirstDayToProcess();
  }

  public function hasDayToProcess()
  {
    return $this->hasDayToProcess;
  }

  public function getDayToProcess()
  {
    return $this->currentDay;
  }

  public function setDayProcessed()
  {
    if ($this->dateUtil->getToday() > $this->currentDay) {
      $this->setCurrentDay($this->dateUtil->getNextDay($this->currentDay));
    } else {
      $this->hasDayToProcess = false;
    }
  }

  private function getFirstDayToProcess()
  {
    $lastProcessedDay = $this->state->getLastProcessedDay();
    if (is_null($lastProcessedDay) || $lastProcessedDay > $this->dateUtil->getToday()) {
      $lastProcessedDay = $this->dateUtil->getToday();
    }

    return $this->dateUtil->getPrevDay($lastProcessedDay);
  }

  private function setCurrentDay($day)
  {
    $this->currentDay = $day;
    $this->state->setLastProcessedDay($day);
  }
}
?>