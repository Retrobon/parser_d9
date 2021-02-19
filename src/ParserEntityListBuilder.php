<?php

namespace Drupal\parser;

use Drupal\Core\Config\Entity\ConfigEntityListBuilder;
use Drupal\Core\Entity\EntityInterface;

use Drupal\Core\Link;
use Drupal\Core\Render\Markup;
use Drupal\Core\Routing\RedirectDestinationInterface;
use Drupal\Core\Url;

/**
 * Provides a listing of Parser entities.
 */
class ParserEntityListBuilder extends ConfigEntityListBuilder {

  /**
   * {@inheritdoc}
   */
  public function buildHeader() {
    $header['label'] = $this->t('Parser');
    $header['nodeType'] = $this->t('Type');
    $header['parserPlugin'] = $this->t('parser');
    $header['crawlerPlugin'] = $this->t('crawler');
    $header['button'] = '';
    return $header + parent::buildHeader();
  }

  /**
   * {@inheritdoc}
   */
  public function buildRow(EntityInterface $entity) {
    $row['label'] = $entity->label();
    $row['nodeType'] = $entity->get('nodeType');
    $row['parserPlugin'] = $entity->get('parserPlugin');
    $row['crawlerPlugin'] = $entity->get('crawlerPlugin');
    $row['button'] = Link::fromTextAndUrl($this->t('start'), Url::fromRoute('parser.parser_entity.parse', ['parser_entity' => $entity->id()]));
    // You probably want a few more properties here...
    return $row + parent::buildRow($entity);
  }

  public function getOperations(EntityInterface $entity)
  {
    $operations = parent::getOperations($entity);
    $operations['parse'] = [
      'title' => Markup::create($this->t('Parse')),
      'weight' => 1000,
      'url' => Url::fromRoute('parser.parser_entity.parse', ['parser_entity' => $entity->id()])
    ];
    return $operations;
  }

}
