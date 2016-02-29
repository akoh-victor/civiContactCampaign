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
function contactcampaign_civicrm_dashboard( $contactID, &$contentPlacement )
{
    $contentPlacement = 3;

    try {
        //define Params
       $params = array(
            'contact_id' => $contactID,

            'api.ContributionSoft.get' => [
                'pcp_id' => '$value.id'
            ],
            'api.ContributionPage.get' => [
                'id' => '$value.page_id'
            ]
        );

               // Making Api Calls
               $campaigns = civicrm_api3('PCP', 'get', $params);
        if ($campaigns['count']){

            $campaignList = '<table>';
            $campaignList .= '<tr><th>Title</th><th>Status</th><th>Contribution Page / Event</th><th>Number of Contributions</th>
                                     <th>Amount Raised</th><th>Target Amount</th>
                                      <th></th></tr>';

            foreach ($campaigns['values'] as $campaign) {
                $pcpStatus = CRM_Contribute_PseudoConstant::pcpStatus();

                // page id i.e contribution page or event
                $PageId = $campaign['page_id'];

                // page type  i.e contribution page or event
                $page_type = $campaign['page_type'];

                //link to edit page
                $editAction = CRM_Utils_System::url('civicrm/pcp/info', 'action=update&reset=1&id=' . $PageId . '&context=dashboard');

                if ($page_type == 'contribute') {
                    $pageUrl = CRM_Utils_System::url('civicrm/' . $page_type . '/transact', 'reset=1&id=' . $PageId);
                } else {
                    $pageUrl = CRM_Utils_System::url('civicrm/' . $page_type . '/register', 'reset=1&id=' . $PageId);
                }

                //PCP title
                $title = $campaign['title'];

                //add status
                $status = $pcpStatus[$campaign['status_id']];

                #check/get number of contribution for campaign
                $thereIs_contributions = $campaign["api.ContributionSoft.get"]['count'];

                if($thereIs_contributions){
                    //contributions
                    $contributions = $campaign["api.ContributionSoft.get"]['values'];

                    //amount raised
                    $amountRaised = array_sum(array_column($contributions, 'amount'));
                    //raisedAmount
                    $raisedAmount = CRM_Utils_Money::format($amountRaised, $campaign['currency']);

                    //targetAmount
                    $targetAmount   = CRM_Utils_Money::format($campaign['goal_amount'], $campaign['currency']);

                    //no of contributions
                    $noContributions = $thereIs_contributions;
                }

                $campaignList .= '<tr>';
                $campaignList .= '<td><a href="' . $pageUrl . '">' . $title . '</a></td>';
                $campaignList .= '<td>' . $status . '</td>';
                $campaignList .= '<td>'. $page_type.'</td>';
                $campaignList .= '<td>'. $noContributions.'</td>';
                $campaignList .=  '<td>'.$raisedAmount.'</td>';
                $campaignList .=  '<td>'.$targetAmount.'</td>';
                $campaignList .= '<td><a href="' . $editAction . '"> Edit </a></td>';

                $campaignList .= '</tr>';

            };

            $campaignList .= '</table>';


        }else{
            $campaignList = 'user has not created a pcp';
        }


    } catch (CiviCRM_API3_Exception $e) {
        $error = $e->getMessage();
    }

    return array(
        $campaignList
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
