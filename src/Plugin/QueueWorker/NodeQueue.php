<?php
/**
 * @file
 * Contains \Drupal\queue\Plugin\QueueWorker\NodeQueue.
 */
namespace Drupal\queue\Plugin\QueueWorker;
use Drupal\Core\Queue\QueueWorkerBase;
/**
 * Processes Tasks for Node Publishing.
 *
 * @QueueWorker(
 *   id = "node_queue",
 *   title = @Translation("HOW TO USE QUEUE ON DRUPAL 8"),
 *   cron = {"time" = 60}
 * )
 */
class NodeQueue extends QueueWorkerBase {
  /**
   * {@inheritdoc}
   */
 public function processItem($data){
  $node_storage = \Drupal::entityTypeManager()->getStorage('node');
  $node = $node_storage->load($data['nid']);
  $node->setPublished($data['status']);

  return $node->save();
 }
}
