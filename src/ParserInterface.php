<?php

namespace Drupal\parser;

/**
 * Interface for parser plugins.
 */
interface ParserInterface {

  /**
   * Returns the translated plugin label.
   *
   * @return string
   *   The translated title.
   */
  public function label();

}
