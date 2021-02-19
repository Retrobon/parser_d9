<?php

namespace Drupal\parser;

use Drupal\Component\Plugin\PluginBase;

/**
 * Base class for parser plugins.
 */
abstract class ParserPluginBase extends PluginBase implements ParserInterface {

  /**
   * {@inheritdoc}
   */
  public function label() {
    // Cast the label to a string since it is a TranslatableMarkup object.
    return (string) $this->pluginDefinition['label'];
  }

}
