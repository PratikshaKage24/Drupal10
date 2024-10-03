<?php

namespace Drupal\latest_articles\Plugin\Block;

use Drupal\Core\Block\BlockBase;

/**
 * Provides a 'ServerTime' Block.
 *
 * @Block(
 *   id = "server_time_block",
 *   admin_label = @Translation("Server Time Block"),
 * )
 */
class ServerTimeBlock extends BlockBase {

  /**
   * {@inheritdoc}
   */
  public function build() {
    // Get the current server time.
    $current_time = \Drupal::service('date.formatter')->format(time(), 'custom', 'd-m-Y H:i:s');

    return [
      '#type' => 'markup',
    '#markup' => 'The current server time is:' . $current_time,
    ];
  }

}
