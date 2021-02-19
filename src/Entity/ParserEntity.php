<?php

namespace Drupal\parser\Entity;

use Drupal\Core\Config\Entity\ConfigEntityBase;

/**
 * Defines the Parser entity.
 *
 * @ConfigEntityType(
 *   id = "parser_entity",
 *   label = @Translation("Parser"),
 *   handlers = {
 *     "view_builder" = "Drupal\Core\Entity\EntityViewBuilder",
 *     "list_builder" = "Drupal\parser\ParserEntityListBuilder",
 *     "form" = {
 *       "add" = "Drupal\parser\Form\ParserEntityForm",
 *       "edit" = "Drupal\parser\Form\ParserEntityForm",
 *       "delete" = "Drupal\parser\Form\ParserEntityDeleteForm"
 *     },
 *     "route_provider" = {
 *       "html" = "Drupal\parser\ParserEntityHtmlRouteProvider",
 *     },
 *   },
 *   config_prefix = "parser_entity",
 *   admin_permission = "administer site configuration",
 *   entity_keys = {
 *     "id" = "id",
 *     "label" = "label",
 *     "uuid" = "uuid",
 *   },
 *   links = {
 *     "canonical" = "/admin/structure/parser_entity/{parser_entity}",
 *     "add-form" = "/admin/structure/parser_entity/add",
 *     "edit-form" = "/admin/structure/parser_entity/{parser_entity}/edit",
 *     "delete-form" = "/admin/structure/parser_entity/{parser_entity}/delete",
 *     "collection" = "/admin/structure/parser_entity"
 *   },
 *   config_export = {
 *     "id",
 *     "label",
 *     "siteUrl",
 *     "nodeType",
 *     "parserPlugin",
 *     "crawlerPlugin",
 *   }
 * )
 */
class ParserEntity extends ConfigEntityBase implements ParserEntityInterface {

  /**
   * The Parser ID.
   *
   * @var string
   */
  protected $id;

  /**
   * The Parser label.
   *
   * @var string
   */
  protected $label;

  /**
   * The Parser siteUrl.
   *
   * @var string
   */
  protected $siteUrl;

  /**
   * The Parser nodeType.
   *
   * @var string
   */
  protected $nodeType;

  /**
   * The Parser parserPlugin.
   *
   * @var string
   */
  protected $parserPlugin;

  /**
   * The Parser crawlerPlugin.
   *
   * @var string
   */
  protected $crawlerPlugin;
}
