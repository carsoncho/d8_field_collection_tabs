<?php

namespace Drupal\field_collection_tabs\Plugin\Field\FieldFormatter;

use Drupal\Component\Utility\Html;
use Drupal\Core\Field\FieldItemInterface;
use Drupal\Core\Field\FieldItemListInterface;
use Drupal\Core\Field\FormatterBase;
use Drupal\Core\Form\FormStateInterface;
use Drupal\field\Entity\FieldConfig;

/**
 * Plugin implementation of the 'tabs' formatter.
 *
 * @FieldFormatter(
 *   id = "tabs",
 *   label = @Translation("Tabs"),
 *   field_types = {
 *     "field_collection"
 *   }
 * )
 */
class FieldCollectionTabsFormatter extends FormatterBase {

  /**
   * {@inheritdoc}
   */
  public static function defaultSettings() {
    // Implement default settings.
    return [
      'title_field' => FALSE,
      'view_mode' => FALSE,
    ] + parent::defaultSettings();
  }

  /**
   * {@inheritdoc}
   */
  public function settingsForm(array $form, FormStateInterface $form_state) {
    $elements = array();
    $options = array($this->t('No titles'));
    $fieldDefinition = $this->fieldDefinition;
    $fields = \Drupal::entityManager()->getFieldDefinitions('field_collection_item', $fieldDefinition->getName());
    foreach ($fields as $field_name => $field) {
      // Filter out any fields that are not a Base Field of the entity_type
      if ($field->getFieldStorageDefinition()->isBaseField() == FALSE) {
        // Build the options list of field_name => field_label
        // @TODO: Additional checking if it's a text "string" field before adding it to the options array
        //  We don't want any images to be tab titles or any wierdness for a title
        $options[$field_name] = $this->t($field->getLabel());
      }
    }

    $elements['title_field'] = array(
      '#type' => 'select',
      '#title' => ('Field to use for tab titles'),
      '#description' => t('Select the field to use for tab titles'),
      '#default_value' => $this->getSetting('title_field'),
      '#options' => $options
    );

    $displays = \Drupal::entityManager()->getAllViewModes();
    if (isset($displays['field_collection_item']) && !empty($displays['field_collection_item'])) {
      $displays = $displays['field_collection_item'];
      $options = array($this->t('Full'));
      foreach ($displays as $view_mode => $info) {
        $options[$view_mode] = $info['label'];
      }
      $elements['view_mode'] = array(
        '#type' => 'select',
        '#title' => t('View mode'),
        '#options' => $options,
        '#default_value' => $this->getSetting('view_mode'),
        '#description' => t('Select the view mode'),
      );
    }
    else {
      $elements['view_mode'] = array(
        '#markup' => $this->t('No custom view modes for Field Collection Items')
      );
    }


    return $elements;
  }

  /**
   * {@inheritdoc}
   */
  public function settingsSummary() {
    $summary = [];
    // Implement settings summary.
    $summary[] = $this->getSetting('title_field') ? $this->t('Title field: ' . $this->getSetting('title_field')) : $this->t('No title');
    $summary[] = $this->getsetting('view_mode')  ? $this->t('View Mode: ' . $this->getSetting('view_mode')) : $this->t('View Mode: Full');
    return $summary;
  }

  /**
   * {@inheritdoc}
   */
  public function viewElements(FieldItemListInterface $items, $langcode) {
    $titles = [];
    $tabs = [];
    $title_field = !empty($this->getSetting('title_field')) ? $this->getSetting('title_field') : FALSE;
    $view_mode = !empty($this->getSetting('view_mode')) ? $this->getSetting('view_mode') : 'full';

    foreach ($items as $delta => $item) {
      if ($item->value !== NULL) {
        // TODO: is there a better way to get the $title_field value?
        $field_collection_item = $item->getFieldCollectionItem();
        $title = $field_collection_item->get($title_field)->getValue();
        $title_value = $title[0]['value'];

        // Preventing a tab from not having a title
        // The $title_value value could be '' at this point or they didn't pick a $title_field
        if ($title_field == FALSE || $title_value == '') {
          $title_value = "Tab " . $delta;
        }

        $titles[] = $title_value;

        $render_item = \Drupal::entityTypeManager()->getViewBuilder('field_collection_item')->view($field_collection_item, $view_mode);
        $tabs[] = $render_item;
      }
    }
    $render_array =  [
      '#theme' => 'field_collection_tabs',
      '#titles' => $titles,
      '#tabs' => $tabs,
      '#field_name' => $title_field,
    ];

    return $render_array;
  }

  /**
   * Generate the output appropriate for one field item.
   *
   * @param \Drupal\Core\Field\FieldItemInterface $item
   *   One field item.
   *
   * @return string
   *   The textual output generated.
   */
  protected function viewValue(FieldItemInterface $item) {
    // The text value has no text format assigned to it, so the user input
    // should equal the output, including newlines.
    return nl2br(Html::escape($item->value));
  }

}
