<?php
class DateUtil
{
  public function getNextDay($day)
  {
    $timestamp = strtotime($day);
    return date('Y-m-d', mktime(0, 0, 0, date('m', $timestamp), date('d', $timestamp)+1, date('Y', $timestamp)));
  }

  public function getPrevDay($day)
  {
    $timestamp = strtotime($day);
    return date('Y-m-d', mktime(0, 0, 0, date('m', $timestamp), date('d', $timestamp)-1, date('Y', $timestamp)));
  }

  public function getToday()
  {
    return date('Y-m-d');
  }
}
?>