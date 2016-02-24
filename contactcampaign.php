<?php

require_once 'contactcampaign.civix.php';












/**
 * Implements hook_civicrm_tabset().
 *
 * @link http://wiki.civicrm.org/confluence/display/CRMDOC/hook_civicrm_config
 */
function contactcampaign_civicrm_tabset($tabsetName, &$tabs, $context) {
  //check if the tabset is Contact Summary Page
  if ($tabsetName == 'civicrm/contact/view') {
          $contactId = $context['contact_id'];
    $url = 'civicrm/campaignlist';

    $tabs[] = array( 'id'    => 'contactCampaignTab',
        'url'   => $url,
        'qs' => 'cid=%%$contactId%%',
        'title' => 'Contact Campaign',
        'weight' => 300,
    );
  }
}

/**
 * Implements hook_civicrm_dashboard().
 *
 * @link http://wiki.civicrm.org/confluence/display/CRMDOC/hook_civicrm_config
 */
function contactcampaign_civicrm_dashboard( $contactID, &$contentPlacement ) {
    // REPLACE Activity Listing with custom content
    $contentPlacement = 3;
    //(int) CRM_Core_DAO::singleValueQuery('SELECT COUNT(*) FROM civicrm_campaign');
    $query = "SELECT id, title, parent_id, status_id, goal_general, goal_revenue
                  FROM civicrm_campaign
                  WHERE created_id = $contactID";
    $campaigns = CRM_Core_DAO::executeQuery($query);

    $campaignList ='<table>';
    $campaignList .= '<tr><th>Title</th><th>Contribution Page / Event</th><th>Status</th><th>Target Amount</th><th>Amount Raised</th><th>Number of Contributors</th></tr>';

    while ($campaigns->fetch()) {

     foreach($campaigns as $campaign){

        $title = $campaign->title;
        $targetAmount = $campaign->goal_general;
        $raisedAmount = $campaign->goal_revenue;

        $campaignList .=  '<tr>';
        $campaignList .='<td>'.$title.'</td>';
        $campaignList .= '<td>'.$targetAmount.'</td>';
        $campaignList .= '<td>'.$raisedAmount.'</td>';
        $campaignList .= '</tr>';
     }
    };
    $campaignList .= '</table>';

    return array (
       'Campaign List'=> $campaignList
    );
}
/**
 * Implements hook_civicrm_config().
 *
 * @link http://wiki.civicrm.org/confluence/display/CRMDOC/hook_civicrm_config
 */
function contactcampaign_civicrm_config(&$config) {
  _contactcampaign_civix_civicrm_config($config);
}

/**
 * Implements hook_civicrm_xmlMenu().
 *
 * @param array $files
 *
 * @link http://wiki.civicrm.org/confluence/display/CRMDOC/hook_civicrm_xmlMenu
 */
function contactcampaign_civicrm_xmlMenu(&$files) {
  _contactcampaign_civix_civicrm_xmlMenu($files);
}

/**
 * Implements hook_civicrm_install().
 *
 * @link http://wiki.civicrm.org/confluence/display/CRMDOC/hook_civicrm_install
 */
function contactcampaign_civicrm_install() {
  _contactcampaign_civix_civicrm_install();
}














/**
 * Implements hook_civicrm_uninstall().
 *
 * @link http://wiki.civicrm.org/confluence/display/CRMDOC/hook_civicrm_uninstall
 */
function contactcampaign_civicrm_uninstall() {
  _contactcampaign_civix_civicrm_uninstall();
}

/**
 * Implements hook_civicrm_enable().
 *
 * @link http://wiki.civicrm.org/confluence/display/CRMDOC/hook_civicrm_enable
 */
function contactcampaign_civicrm_enable() {
  _contactcampaign_civix_civicrm_enable();
}

/**
 * Implements hook_civicrm_disable().
 *
 * @link http://wiki.civicrm.org/confluence/display/CRMDOC/hook_civicrm_disable
 */
function contactcampaign_civicrm_disable() {
  _contactcampaign_civix_civicrm_disable();
}

/**
 * Implements hook_civicrm_upgrade().
 *
 * @param $op string, the type of operation being performed; 'check' or 'enqueue'
 * @param $queue CRM_Queue_Queue, (for 'enqueue') the modifiable list of pending up upgrade tasks
 *
 * @return mixed
 *   Based on op. for 'check', returns array(boolean) (TRUE if upgrades are pending)
 *                for 'enqueue', returns void
 *
 * @link http://wiki.civicrm.org/confluence/display/CRMDOC/hook_civicrm_upgrade
 */
function contactcampaign_civicrm_upgrade($op, CRM_Queue_Queue $queue = NULL) {
  return _contactcampaign_civix_civicrm_upgrade($op, $queue);
}

/**
 * Implements hook_civicrm_managed().
 *
 * Generate a list of entities to create/deactivate/delete when this module
 * is installed, disabled, uninstalled.
 *
 * @link http://wiki.civicrm.org/confluence/display/CRMDOC/hook_civicrm_managed
 */
function contactcampaign_civicrm_managed(&$entities) {
  _contactcampaign_civix_civicrm_managed($entities);
}

/**
 * Implements hook_civicrm_caseTypes().
 *
 * Generate a list of case-types.
 *
 * @param array $caseTypes
 *
 * Note: This hook only runs in CiviCRM 4.4+.
 *
 * @link http://wiki.civicrm.org/confluence/display/CRMDOC/hook_civicrm_caseTypes
 */
function contactcampaign_civicrm_caseTypes(&$caseTypes) {
  _contactcampaign_civix_civicrm_caseTypes($caseTypes);
}

/**
 * Implements hook_civicrm_angularModules().
 *
 * Generate a list of Angular modules.
 *
 * Note: This hook only runs in CiviCRM 4.5+. It may
 * use features only available in v4.6+.
 *
 * @link http://wiki.civicrm.org/confluence/display/CRMDOC/hook_civicrm_caseTypes
 */
function contactcampaign_civicrm_angularModules(&$angularModules) {
_contactcampaign_civix_civicrm_angularModules($angularModules);
}

/**
 * Implements hook_civicrm_alterSettingsFolders().
 *
 * @link http://wiki.civicrm.org/confluence/display/CRMDOC/hook_civicrm_alterSettingsFolders
 */
function contactcampaign_civicrm_alterSettingsFolders(&$metaDataFolders = NULL) {
  _contactcampaign_civix_civicrm_alterSettingsFolders($metaDataFolders);
}

/**
 * Functions below this ship commented out. Uncomment as required.
 *

/**
 * Implements hook_civicrm_preProcess().
 *
 * @link http://wiki.civicrm.org/confluence/display/CRMDOC/hook_civicrm_preProcess
 *
function contactcampaign_civicrm_preProcess($formName, &$form) {

} // */

/**
 * Implements hook_civicrm_navigationMenu().
 *
 * @link http://wiki.civicrm.org/confluence/display/CRMDOC/hook_civicrm_navigationMenu
 *
function contactcampaign_civicrm_navigationMenu(&$menu) {
  _contactcampaign_civix_insert_navigation_menu($menu, NULL, array(
    'label' => ts('The Page', array('domain' => 'com.idealitsolutions.contactcampaign')),
    'name' => 'the_page',
    'url' => 'civicrm/the-page',
    'permission' => 'access CiviReport,access CiviContribute',
    'operator' => 'OR',
    'separator' => 0,
  ));
  _contactcampaign_civix_navigationMenu($menu);
} // */
