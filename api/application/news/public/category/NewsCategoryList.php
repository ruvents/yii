<?php
AutoLoader::Import('news.source.*');

class NewsCategoryList extends ApiCommand
{

  /**
   * Основные действия комманды
   * @return void
   */
  protected function doExecute()
  {
    $result = $this->Account->DataBuilder()->CreateAllCategories();
    $this->SendJson($result);
  }
}
