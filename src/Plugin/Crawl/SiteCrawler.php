<?php

namespace Drupal\parser\Plugin\Crawl;

use Drupal\parser\CrawlPluginBase;

/**
 * Plugin implementation of the crawl.
 *
 * @Crawl(
 *   id = "site_crawler",
 *   label = @Translation("Site Crawler"),
 *   description = @Translation("SiteCrawler")
 * )
 */
class SiteCrawler extends CrawlPluginBase {

  public function urls()
  {
    // TODO: from https://github.com/spatie/crawler
  }
}
