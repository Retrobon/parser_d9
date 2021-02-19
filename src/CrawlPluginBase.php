<?php

namespace Drupal\parser;

use Drupal\Component\Plugin\PluginBase;
use Drupal\parser\Entity\ParserEntity;
use Drupal\parser\Entity\ParserEntityInterface;

/**
 * Base class for crawl plugins.
 */
abstract class CrawlPluginBase extends PluginBase implements CrawlInterface {
  /**
   * @var ParserEntity
   */
  protected $entity;

  /**
   * {@inheritdoc}
   */
  public function label() {
    // Cast the label to a string since it is a TranslatableMarkup object.
    return (string) $this->pluginDefinition['label'];
  }

  public function setEntity(ParserEntityInterface $entity)
  {
    $this->entity = $entity;
  }

}
