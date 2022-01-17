<?php

namespace Drupal\headscape_image_background\Plugin\Field\FieldFormatter;

use Drupal\Core\Field\FieldItemInterface;
use Drupal\Core\Field\FieldItemListInterface;
use Drupal\Core\Form\FormStateInterface;
use Drupal\file\Entity\File;
use Drupal\image\Plugin\Field\FieldFormatter\ImageFormatterBase;

/**
 * Plugin implementation of the 'headscape_image_background' formatter.
 *
 * @FieldFormatter(
 *   id = "headscape_image_background",
 *   label = @Translation("Background image"),
 *   field_types = {
 *     "image"
 *   }
 * )
 */
class HeadscapeImageBackground extends ImageFormatterBase
{

  public static function defaultSettings(): array
  {
    return [
        'wrapper_class' => ''
      ] + parent::defaultSettings();
  }

  public function settingsForm(array $form, FormStateInterface $form_state): array
  {
    $element['wrapper_class'] = [
      '#title' => t('Wrapper class'),
      '#type' => 'textfield',
      '#default_value' => $this->getSetting('wrapper_class'),
    ];
    return $element;
  }

  public function settingsSummary(): array
  {
    return [$this->t('Displays background image with given class(es) "@class"', ['@class' => $this->getSetting('wrapper_class')])];
  }

  public function viewElements(FieldItemListInterface $items, $langcode): array
  {
    $elements = [];

    foreach ($items as $delta => $item) {
      $elements[$delta] = $this->viewValue($item);
    }

    return $elements;
  }

  /**
   * Generate output for field item
   */
  protected function viewValue(FieldItemInterface $item): array
  {

    $class = $this->getSetting('wrapper_class');
    $image = $item->getValue('image');
    $file = File::load($image['target_id']);
    $url = $file->createFileUrl();

    return [
      '#theme' => 'background_image',
      '#wrapper_class' => $class,
      '#image_url' => $url,
    ];
  }
}
