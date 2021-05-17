<?php
/**
 * @file
 * Contains \Drupal\article\Plugin\Block\ArticleBlock.
 */

namespace Drupal\custom_form\Plugin\Block;

use Drupal\Core\Block\BlockBase;
use Drupal\Core\Form\FormInterface;

/**
 * Provides a 'custom' block.
 *
 * @Block(
 *   id = "CustomFormBlock1",
 *   admin_label = @Translation("Custom Form block"),
 *   category = @Translation("Custom Form example")
 * )
 */
class CustomFormBlock extends BlockBase {

  /**
   * {@inheritdoc}
   */
  public function build() {

    $form = \Drupal::formBuilder()->getForm('Drupal\custom_form\Form\CustomForm');

    return $form;
   }
}