<?php
namespace oauth\components\social;

interface ISocial{
  const Facebook = '14';
  const Twitter = '13';
  const Vkontakte = '15';

  public function getOAuthUrl($redirectUrl = null);
  public function isHasAccess();

  /**
   * @return void
   */
  public function renderScript();

  /**
   * @return Data
   */
  public function getData();

  /**
   * @return int
   */
  public function getSocialId();

  /**
   * @return string
   */
  public function getSocialTitle();
}