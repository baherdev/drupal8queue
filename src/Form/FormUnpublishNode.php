<?php
/**
 * @file
 * Contains \Drupal\Learning\Form\FormUnpublishNode.
 */

namespace Drupal\queue\Form;
use Drupal\Core\Form\FormBase;
use Drupal\Core\Form\FormStateInterface;

/**
 * Contribute form.
 */
class FormUnpublishNode extends FormBase {
  /**
   * {@inheritdoc}
   */
  public function getFormId() {
     return 'unpublish_node_forms';
  }

  /**
   * {@inheritdoc}
   */
  public function buildForm(array $form, FormStateInterface $form_state) {

    $node_storage = \Drupal::entityTypeManager()->getStorage('node');
    $nodes = $node_storage->loadMultiple();

    foreach ($nodes as $node){
      $content[$node->get('nid')->value]= $node->get('title')->value;
    }

    $form['node'] = [
      '#type' => 'select',
      '#title' => $this->t('Node'),
      '#options' => $content,
      '#required'=> 'TRUE',
    ];

    $form['status'] = [
      '#type' => 'select',
      '#title' => $this->t('Status'),
      '#options' => [
        true => $this->t('Publish'),
        false => $this->t('Unpublish'),
      ],
      '#required'=> 'TRUE',
    ];

    $form['submit'] = array(
      '#type' => 'submit',
      '#value' => t('Submit'),
    );
    return $form;
  }

  /**
   * {@inheritdoc}
   */
  public function validateForm(array &$form, FormStateInterface $form_state) {

  }

  /**
   * {@inheritdoc}
   */
  public function submitForm(array &$form, FormStateInterface $form_state) {
    $node_storage = \Drupal::entityTypeManager()->getStorage('node');
    drupal_set_message($this->t('The node @node will @status on next Queue Processing',
      array('@node' => $node_storage->load($form_state->getValue('node'))->get('title')->value,
        '@status' => $form_state->getValue('status')== true ? 'Publish':'Unpublish'  ,
        )));
    $data['nid'] = $form_state->getValue('node');
    $data['status'] = $form_state->getValue('status');
    $queue = \Drupal::queue('node_queue');
    $queue->createQueue();
    $queue->createItem($data);
  }
}
?>
