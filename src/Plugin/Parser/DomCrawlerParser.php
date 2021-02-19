<?php

namespace Drupal\parser\Plugin\Parser;

use Drupal\parser\Entity\ParserEntityInterface;
use Drupal\parser\ParserPluginBase;
use Symfony\Component\DomCrawler\Crawler;

/**
 * Plugin implementation of the parser.
 *
 * @Parser(
 *   id = "dom_crawler_parser",
 *   label = @Translation("Dom Crawler"),
 *   description = @Translation("DomCrawler")
 * )
 */
class DomCrawlerParser extends ParserPluginBase {

  protected $entity;
  protected $url;

  /**
   * @param ParserEntityInterface $entity
   */
  public function setEntity(ParserEntityInterface $entity)
  {
    $this->entity = $entity;
  }


  public function test($url)
  {
    $html = file_get_contents($url);
    $crawler = new Crawler(null, $url);
    $crawler->addHtmlContent($html, 'UTF-8');

    return $crawler->filterXpath("//title")->text();
  }



}
