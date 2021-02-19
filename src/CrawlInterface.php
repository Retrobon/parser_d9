<?php

namespace Drupal\parser;

use Drupal\parser\Entity\ParserEntity;
use Drupal\parser\Entity\ParserEntityInterface;

/**
 * Interface for crawl plugins.
 */
interface CrawlInterface {

  /**
   * Returns the translated plugin label.
   *
   * @return string
   *   The translated title.
   */
  public function label();

  /**
   * @return array|null
   */
  public function urls();

  /**
   * @param ParserEntityInterface $entity
   * @return mixed
   */
  public function setEntity(ParserEntityInterface $entity);

}
