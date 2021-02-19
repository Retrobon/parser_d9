<?php

namespace Drupal\parser\Controller;

use Drupal\Core\Batch\BatchBuilder;
use Drupal\Core\Controller\ControllerBase;
use Drupal\parser\CrawlPluginBase;
use Drupal\parser\Entity\ParserEntityInterface;

class BatchCrawler extends ControllerBase
{
  private $parser_entity;

  /**
   * @var CrawlPluginBase
   */
  private $crawlerPlugin;

  private $batch;
  /**
   * @var string
   */
  private $crawlerPluginId;
  /**
   * @var mixed
   */
  private $parserPluginId;
  private $parserPlugin;
  /**
   * @var BatchBuilder
   */
  private $batchBuilder;

  public function start(ParserEntityInterface $parser_entity)
  {
    $this->parser_entity = $parser_entity;
    $this->crawlerPluginId = $parser_entity->get('crawlerPlugin');
    $this->crawlerPlugin = \Drupal::service('plugin.manager.crawl')
      ->createInstance($this->crawlerPluginId);
    $this->crawlerPlugin->setEntity($parser_entity);
    $this->parserPluginId = $parser_entity->get('parserPlugin');
    $this->parserPlugin = \Drupal::service('plugin.manager.parser')
      ->createInstance($this->parserPluginId);
    $this->parserPlugin->setEntity($parser_entity);
    $this->batchBuilder = new BatchBuilder();

    $this->batchBuilder
      ->setTitle($this->t('Processing'))
      ->setInitMessage($this->t('Initializing.'))
      ->setProgressMessage($this->t('Completed @current of @total.'))
      ->setErrorMessage($this->t('An error has occurred.'));

    $this->batchBuilder->addOperation([$this, 'processItems'], [$this->crawlerPlugin->urls()]);
    $this->batchBuilder->setFinishCallback([$this, 'finished']);

    batch_set($this->batchBuilder->toArray());
    return batch_process('/admin/structure/parser_entity');
  }

  /**
   * Processor for batch operations.
   */
  public function processItems($urls, array &$context) {
    $limit = 20;

    // Set default progress values.
    if (empty($context['sandbox']['progress'])) {
      $context['sandbox']['progress'] = 0;
      $context['sandbox']['max'] = count($urls);
    }

    if (empty($context['sandbox']['items'])) {
      $context['sandbox']['items'] = $urls;
    }

    $counter = 0;
    if (!empty($context['sandbox']['items'])) {
      if ($context['sandbox']['progress'] != 0) {
        array_splice($context['sandbox']['items'], 0, $limit);
      }

      foreach ($context['sandbox']['items'] as $url) {
        if ($counter != $limit) {
          $title = $this->processItem($url);

          $counter++;
          $context['sandbox']['progress']++;

          $context['message'] = $this->t('Now processing node :progress of :count (:path)', [
            ':progress' => $context['sandbox']['progress'],
            ':count' => $context['sandbox']['max'],
            ':path' => $title
          ]);
          $context['results']['processed'] = $context['sandbox']['progress'];
        }
      }
    }

    if ($context['sandbox']['progress'] != $context['sandbox']['max']) {
      $context['finished'] = $context['sandbox']['progress'] / $context['sandbox']['max'];
    }
  }
  /**
   * {@inheritdoc}
   */
  public function finished($success, $results, $operations) {
    $message = $this->t('Number of urls processed by batch: @count', [
      '@count' => $results['processed'],
    ]);
    \Drupal::messenger()->addMessage($message);
  }

  /**
   * Import article node.
   *
   * @param $url
   * @param $context
   */
  public function processItem($url) {
    $title = $this->parserPlugin->test($url);
    return $title;
  }
}
