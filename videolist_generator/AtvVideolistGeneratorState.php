<?php
class AtvVideolistGeneratorState
{
  private $stateFile = null;

  private $state = array();

  public function __construct($stateFile)
  {
    $this->stateFile = $stateFile;
    $this->loadState();
  }

  public function setLastProcessedDay($day)
  {
    $this->state['lastProcessedDay'] = $day;
    $this->saveState();
  }

  public function getLastProcessedDay()
  {
    return isset($this->state['lastProcessedDay']) ? $this->state['lastProcessedDay'] : null;
  }

  private function loadState()
  {
    if (file_exists($this->stateFile)) {
      $this->state = json_decode(file_get_contents($this->stateFile), true);
    }
  }

  private function saveState()
  {
    file_put_contents($this->stateFile, json_encode($this->state));
  }
}
?>