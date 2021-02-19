<?php

namespace Drupal\parser\Form;

use Drupal\Core\Entity\EntityForm;
use Drupal\Core\Entity\EntityTypeManager;
use Drupal\Core\Form\FormBase;
use Drupal\Core\Form\FormStateInterface;
use Drupal\node\Entity\NodeType;
use Drupal\parser\DomService;
use Drupal\parser\Entity\ParserEntity;
use Drupal\parser\Entity\ParserEntityInterface;
use Symfony\Component\DependencyInjection\ContainerInterface;
use Symfony\Component\DomCrawler\Crawler;
use Symfony\Component\Process\Exception\ProcessFailedException;
use Symfony\Component\Process\Process;
use function DI\string;

/**
 * Class ParserEntityForm.
 */
class ParserForm extends FormBase
{
  /**
   * @var object|null
   */
  private $entityFieldManager;
  /**
   * @var EntityTypeManager
   */
  private $entityTypeManager;
  /**
   * @var DomService
   */
  private $dom;

  /**
   * {@inheritdoc}
   */
  public static function create(ContainerInterface $container)
  {
    $instance = parent::create($container);
    $instance->entityFieldManager = $container->get('entity_field.manager');
    $instance->entityTypeManager = $container->get('entity_type.manager');
    $instance->dom = $container->get('parser.dom');

    return $instance;
  }


  public function getFormId()
  {
    return 'parser.parse';
  }

  protected function getPluginList() {
    $definitions = \Drupal::service('plugin.manager.parser')->getDefinitions();

    $plugin_list = [];
    foreach ($definitions as $plugin_id => $plugin) {
      $plugin_list[$plugin_id] = $plugin['label'];
    }

    return $plugin_list;
  }
  public function buildForm(array $form, FormStateInterface $form_state, $parser_entity = null)
  {
    $parser_entity = $this->entityTypeManager->getStorage('parser_entity')->load($parser_entity);

    $form['parser_plugin'] = [
      '#title' => $this->t('Template for site'),
      '#type' => 'select',
      '#required' => TRUE,
      '#options' => $this->getPluginList(),
      '#empty_option' => '- Select -',
    ];



    $form['submit'] = [
      '#type' => 'submit',
      '#value' => $this->t('Submit'),
    ];
    return $form;
  }

  public function submitForm(array &$form, FormStateInterface $form_state, $parser_entity = null)
  {
    $html = file_get_contents('https://sushi-jen.com.ua/');
    $li = $this->dom->filter($html, '.list-group.nav li');
    foreach ($this->dom->each($li) as $node) {
      kint($this->dom->matches($node, '.first'));
    }

//    $li->each(function (Crawler $children) {
//      kint($this->dom->matches($children, '.first'));
//    });



    die();
  }

  public function getFields(array $fieldDefinitions)
  {
    $unsetFields = ['langcode', 'uuid', 'nid', 'vid', 'type', 'revision_timestamp', 'revision_uid', 'revision_log', 'default_langcode', 'revision_default', 'revision_translation_affected',
    ];
    $formFields = [];
    foreach ($productFields = array_diff(array_keys($fieldDefinitions), $unsetFields) as $array_key) {
      if (!isset($fieldDefinitions[$array_key])) continue;
      if (method_exists($fieldDefinitions[$array_key]->getLabel(), '__toString')) {
        $fields[$array_key] = $fieldDefinitions[$array_key]->getLabel()->__toString();
      } else {
        $fields[$array_key] = $fieldDefinitions[$array_key]->getLabel();
      }
      $formFields[$array_key] = [
        '#type' => 'textfield',
        '#description' => $fieldDefinitions[$array_key]->getType(),
        '#title' => $fields[$array_key]
      ];
    }
    return $formFields;
  }

  protected function getEditableConfigNames()
  {
    return [];
  }
}
