<?php

namespace Drupal\parser\Plugin\Crawl;

use Drupal\parser\CrawlPluginBase;
use Drupal\parser\Entity\ParserEntity;
use GuzzleHttp\Psr7\Uri;
use vipnytt\SitemapParser;
use vipnytt\SitemapParser\Exceptions\SitemapParserException;
use function DI\string;

/**
 * Plugin implementation of the crawl.
 *
 * @Crawl(
 *   id = "site_maps",
 *   label = @Translation("Site Maps"),
 *   description = @Translation("SiteMaps")
 * )
 */
class SiteMaps extends CrawlPluginBase {

  /**
   * @return array
   */
  public function urls(): array
  {
    $urls = [];
    try {
      $parser = new SitemapParser();
      $parser->parseRecursive($this->entity->get('siteUrl').'/sitemap.xml');
      foreach ($parser->getURLs() as $url => $tags) {
        $urls[] = new Uri($url);
      }
    } catch (SitemapParserException $e) {
      echo $e->getMessage();
    }

    return $urls;
  }

  /**
   * @param $url
   * @return string
   */
  function url($url): string
  {
    $result = parse_url($url);
    return $result['scheme']."://".$result['host'];
  }
}
