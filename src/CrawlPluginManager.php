<?php

namespace Drupal\parser;

use Drupal\Core\Cache\CacheBackendInterface;
use Drupal\Core\Extension\ModuleHandlerInterface;
use Drupal\Core\Plugin\DefaultPluginManager;

/**
 * Crawl plugin manager.
 */
class CrawlPluginManager extends DefaultPluginManager {

  /**
   * Constructs CrawlPluginManager object.
   *
   * @param \Traversable $namespaces
   *   An object that implements \Traversable which contains the root paths
   *   keyed by the corresponding namespace to look for plugin implementations.
   * @param \Drupal\Core\Cache\CacheBackendInterface $cache_backend
   *   Cache backend instance to use.
   * @param \Drupal\Core\Extension\ModuleHandlerInterface $module_handler
   *   The module handler to invoke the alter hook with.
   */
  public function __construct(\Traversable $namespaces, CacheBackendInterface $cache_backend, ModuleHandlerInterface $module_handler) {
    parent::__construct(
      'Plugin/Crawl',
      $namespaces,
      $module_handler,
      'Drupal\parser\CrawlInterface',
      'Drupal\parser\Annotation\Crawl'
    );
    $this->alterInfo('crawl_info');
    $this->setCacheBackend($cache_backend, 'crawl_plugins');
  }

}
