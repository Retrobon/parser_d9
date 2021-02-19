<?php

namespace Drupal\parser\Form;

use Drupal\Core\Entity\EntityForm;
use Drupal\Core\Form\FormStateInterface;
use Drupal\Core\Url;
use Drupal\node\Entity\NodeType;
use Symfony\Component\DependencyInjection\ContainerInterface;

/**
 * Class ParserEntityForm.
 */
class ParserEntityForm extends EntityForm
{
  /**
   * @var object|null
   */
  private $entityFieldManager;

  /**
   * {@inheritdoc}
   */
  public static function create(ContainerInterface $container)
  {
    $instance = parent::create($container);
    $instance->entityFieldManager = $container->get('entity_field.manager');

    return $instance;
  }


  /**
   * {@inheritdoc}
   */
  public function form(array $form, FormStateInterface $form_state)
  {
    $form = parent::form($form, $form_state);

    $parser_entity = $this->entity;
    $config = $this->config('parser.entity');

    $form['label'] = [
      '#type' => 'textfield',
      '#title' => $this->t('Label'),
      '#maxlength' => 255,
      '#default_value' => $parser_entity->label(),
      '#description' => $this->t("Label for the Parser."),
      '#required' => TRUE,
    ];
    $form['siteUrl'] = [
      '#type' => 'textfield',
      '#title' => $this->t('siteUrl'),
      '#maxlength' => 255,
      '#default_value' => $parser_entity->get('siteUrl'),
      '#description' => $this->t("siteUrl for the Parser."),
      '#required' => TRUE,
    ];
    $node_types = NodeType::loadMultiple();
    $options = [];
    foreach ($node_types as $node_type) {
      $options[$node_type->id()] = $node_type->label();
    }
    $form['nodeType'] = [
      '#type' => 'select',
      '#title' => $this->t('Node Type'),
      '#default_value' => $parser_entity->get('nodeType'),
      '#options' => $options,
      '#required' => TRUE,
    ];

    $form['parserPlugin'] = [
      '#title' => $this->t('Template for site'),
      '#type' => 'select',
      '#required' => TRUE,
      '#options' => $this->getPluginParserList(),
      '#empty_option' => '- Select -',
      '#default_value' => $parser_entity->get('parserPlugin'),
    ];

    $form['crawlerPlugin'] = [
      '#title' => $this->t('Template for site'),
      '#type' => 'select',
      '#required' => TRUE,
      '#options' => $this->getPluginCrawlerList(),
      '#empty_option' => '- Select -',
      '#default_value' => $parser_entity->get('crawlerPlugin'),
    ];

    $form['id'] = [
      '#type' => 'machine_name',
      '#default_value' => $parser_entity->id(),
      '#machine_name' => [
        'exists' => '\Drupal\parser\Entity\ParserEntity::load',
      ],
      '#disabled' => !$parser_entity->isNew(),
    ];

    /* You will need additional form elements for your custom properties. */

    return $form;
  }

  /**
   * {@inheritdoc}
   */
  public function save(array $form, FormStateInterface $form_state)
  {

    $parser_entity = $this->entity;
    $status = $parser_entity->save();

    switch ($status) {
      case SAVED_NEW:
        $this->messenger()->addMessage($this->t('Created the %label Parser.', [
          '%label' => $parser_entity->label(),
        ]));
        break;

      default:
        $this->messenger()->addMessage($this->t('Saved the %label Parser.', [
          '%label' => $parser_entity->label(),
        ]));
    }
   //$form_state->setRedirectUrl(Url::fromRoute('parser.parser_entity.parse', ['parser_entity' => $parser_entity->id()]));
  }

  protected function getPluginParserList() {
    $definitions = \Drupal::service('plugin.manager.parser')->getDefinitions();

    $plugin_list = [];
    foreach ($definitions as $plugin_id => $plugin) {
      $plugin_list[$plugin_id] = $plugin['label'];
    }

    return $plugin_list;
  }

  protected function getPluginCrawlerList() {
    $definitions = \Drupal::service('plugin.manager.crawl')->getDefinitions();

    $plugin_list = [];
    foreach ($definitions as $plugin_id => $plugin) {
      $plugin_list[$plugin_id] = $plugin['label'];
    }

    return $plugin_list;
  }

}
