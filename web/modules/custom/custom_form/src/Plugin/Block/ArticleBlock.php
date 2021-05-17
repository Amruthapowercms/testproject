<?php
/**
 * @file
 * Contains \Drupal\article\Plugin\Block\XaiBlock.
 */
namespace Drupal\custom_form\Plugin\Block;
use Drupal\Core\Block\BlockBase;
//use Drupal\Core\Form\FormInterface;

/**
 * Provides a 'article' block.
 *
 * @Block(
 *   id = "article",
 *   admin_label = @Translation("Article block"),
 *   category = @Translation("article block example")
 * )
 */

class ArticleBlock extends BlockBase {
  /**
   * {@inheritdoc}
   */
  public function build() {
    $node = \Drupal\node\Entity\Node::create(['type' => 'article']);
     $form = \Drupal::service('entity.form_builder')->getForm($node);
    return $form; 
  }
  
}
