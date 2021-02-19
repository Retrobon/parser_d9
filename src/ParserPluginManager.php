<?php

namespace Drupal\parser;

use Drupal\Core\Cache\CacheBackendInterface;
use Drupal\Core\Extension\ModuleHandlerInterface;
use Drupal\Core\Plugin\DefaultPluginManager;

/**
 * Parser plugin manager.
 */
class ParserPluginManager extends DefaultPluginManager {

  /**
   * Constructs ParserPluginManager object.
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
      'Plugin/Parser',
      $namespaces,
      $module_handler,
      'Drupal\parser\ParserInterface',
      'Drupal\parser\Annotation\Parser'
    );
    $this->alterInfo('parser_info');
    $this->setCacheBackend($cache_backend, 'parser_plugins');
  }

}
