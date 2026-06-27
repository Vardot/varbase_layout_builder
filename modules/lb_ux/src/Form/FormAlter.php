<?php

namespace Drupal\lb_ux\Form;

use Drupal\Component\Utility\Html;
use Drupal\Core\Ajax\AjaxHelperTrait;
use Drupal\Core\Form\FormStateInterface;

/**
 * Alters forms, delegated by hook_form_alter() implementations.
 */
class FormAlter {

  use AjaxHelperTrait;

  /**
   * Alters the section configuration form.
   *
   * @param array $form
   *   An associative array containing the structure of the form.
   * @param \Drupal\Core\Form\FormStateInterface $form_state
   *   The current state of the form.
   */
  public function alterConfigureForm(array &$form, FormStateInterface $form_state) {
    if ($this->isAjax()) {
      // Allow the forms loaded into off-canvas to display status messages.
      if (!isset($form['status_messages'])) {
        $form['status_messages'] = [
          '#type' => 'status_messages',
        ];
      }
      // @todo static::ajaxSubmit() requires data-drupal-selector to be the same
      //   between the various Ajax requests. A bug in
      //   \Drupal\Core\Form\FormBuilder prevents that from happening unless
      //   $form['#id'] is also the same. Normally, #id is set to a unique HTML
      //   ID via Html::getUniqueId(), but here we bypass that in order to work
      //   around the data-drupal-selector bug. This is okay so long as we
      //   assume that this form only ever occurs once on a page. Remove this
      //   workaround in https://www.drupal.org/node/2897377.
      $form['#id'] = Html::getId($form_state->getBuildInfo()['form_id']);
    }
  }

}
