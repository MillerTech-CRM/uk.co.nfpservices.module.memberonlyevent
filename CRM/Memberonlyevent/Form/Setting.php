<?php
require_once 'CRM/Core/Form.php';

/**
 * Form controller class
 *
 * @see http://wiki.civicrm.org/confluence/display/CRMDOC43/QuickForm+Reference
 */
class CRM_Memberonlyevent_Form_Setting extends CRM_Core_Form
{

  /**
   * civicrm Form Preprocess
   */
  function preProcess()
  {
    $session = CRM_Core_Session::singleton();
    $url = CRM_Utils_System::url('civicrm/memberonlyevent/setting', 'reset=1');
    $session->pushUserContext($url);
    
    CRM_Utils_System::setTitle(ts('Member Only Event Settings'));
  }

  /**
   * civicrm Form Building
   */
  function buildQuickForm()
  {
    if ($this->_action & (CRM_Core_Action::DELETE)) {
      $this->addButtons(array(
        array(
          'type' => 'next',
          'name' => ts('Delete'),
          'isDefault' => TRUE
        ),
        array(
          'type' => 'cancel',
          'name' => ts('Cancel')
        )
      ));
      return;
    }
    
    $settingsStr = CRM_Core_BAO_Setting::getItem('Member Only Event Settings', 'custom_memberonlyevent_settings');
    $settingsArray = unserialize($settingsStr);
    
    // add form elements
    $this->add('select', 'memberonly_statuses', 'Select membership statuses', $this->getCurrentMembershipStatuses(), TRUE, array(
      'id' => 'memberonly_statuses',
      'multiple' => 'multiple',
      'class' => 'crm-select2'
    ));
    
    // $this->addYesNo('allow_administrator', ts('Always allow administrator?'), TRUE, TRUE);
    $this->add('checkbox', 'display_memberonly_message', ts('Display a message to users not eligible to register for this event?'), FALSE, FALSE, array(
      'onclick' => "return showHideByValue('display_memberonly_message','','mem-only-message','block','radio',false);"
    ));
    
    $this->add('wysiwyg', 'memberonly_message', ts('&nbsp;'), array(
      'rows' => 4,
      'cols' => 60
    ), FALSE);
    
    $this->addButtons(array(
      array(
        'type' => 'submit',
        'name' => ts('Save'),
        'isDefault' => TRUE
      ),
      array(
        'type' => 'cancel',
        'name' => ts('Cancel')
      )
    ));
    
    $defaults = array();
    $defaults['memberonly_statuses'] = $settingsArray['memberonly_statuses'];
    $defaults['display_memberonly_message'] = $settingsArray['display_memberonly_message'];
    $defaults['memberonly_message'] = ! empty(trim($settingsArray['memberonly_message'])) ? $settingsArray['memberonly_message'] : 'Sorry, only members can register for this event!';
    $this->setDefaults($defaults);
    
    // export form elements
    $this->assign('elementNames', $this->getRenderableElementNames());
    parent::buildQuickForm();
  }

  /**
   * civicrm Form postProcess
   */
  function postProcess()
  {
    $values = $this->exportValues();
    $settingsStr = CRM_Core_BAO_Setting::getItem('Member Only Event Settings', 'custom_memberonlyevent_settings');
    $settingsArray = unserialize($settingsStr);
    
    $settingsArray['memberonly_statuses'] = $values['memberonly_statuses'];
    $settingsArray['display_memberonly_message'] = isset($values['display_memberonly_message']) ? TRUE : FALSE;
    $settingsArray['memberonly_message'] = $values['memberonly_message'];
    $message = "Member Only Event Settings saved.";
    
    $settingsStr = serialize($settingsArray);
    CRM_Core_BAO_Setting::setItem($settingsStr, 'Member Only Event Settings', 'custom_memberonlyevent_settings');
    
    CRM_Core_Session::setStatus($message, 'Member Only Event Settings', 'success');
  }

  /**
   * Get the fields/elements defined in this form.
   *
   * @return array (string)
   */
  function getRenderableElementNames()
  {
    // The _elements list includes some items which should not be
    // auto-rendered in the loop -- such as "qfKey" and "buttons". These
    // items don't have labels. We'll identify renderable by filtering on
    // the 'label'.
    $elementNames = array();
    foreach ($this->_elements as $element) {
      /** @var HTML_QuickForm_Element $element */
      $label = $element->getLabel();
      if (! empty($label)) {
        $elementNames[] = $element->getName();
      }
    }
    return $elementNames;
  }

  /**
   * get all current membership statuses
   */
  function getCurrentMembershipStatuses()
  {
    $options = array();
    $result = civicrm_api3('MembershipStatus', 'get', array(
      'sequential' => 1,
      /* 'is_current_member' => 1, */
      'options' => array(
        'limit' => 0
      )
    ));
    if ($result['count'] > 0) {
      foreach ($result['values'] as $item) {
        $mem_id = $item['id'];
        $mem_name = $item['label'];
        $options[$mem_id] = $mem_name;
      }
    }
    return $options;
  }
}
