<?php
/**
*
* One Page Checkout plugin for VirtueMart 2
* @author LineLab
*
* @link http://www.linelab.org
* @copyright Copyright (c) 2011 - 2012 Linelab.org Team. All rights reserved.
* @license http://www.gnu.org/licenses/gpl-3.0.html GNU/GPL3
* @version $Id: onepage.php 2.0. 2012-06-03
* @technical Support: http://www.linelab.org/download/joomla-templates-forum
*/
defined('_JEXEC') or die('Restricted access');
defined('DS') or define('DS', DIRECTORY_SEPARATOR);

jimport('joomla.plugin.plugin');

class plgSystemOnepage_generic extends JPlugin {
	function __construct($config,$params) {
		parent::__construct($config,$params);
	}
	
	function onAfterRoute() {
		if(JFactory::getApplication()->isAdmin()) {
			return;
		}
		
		
		if(JRequest::getCmd('type')=='onepage') {
			define('JPATH_COMPONENT',JPATH_SITE.DS.'components'.DS.'com_virtuemart');
			require_once(dirname(__FILE__) . DS . 'cart' . DS . 'tmpl'.DS.'helper.php');
				
			$helper=new CartHelper();
			switch(JRequest::getCmd('opc_task')) {
				case 'set_coupon':
					$ret=$helper->setCoupon();
					echo json_encode($ret);
					break;
				case 'update_form':
					if(JRequest::getInt('update_address',1)==1) {
						$helper->setAddress();
					}
					$ret=$helper->setPayment();
					if(is_array($ret)) {
						echo json_encode(array('error'=>1,'message'=>implode($ret)));
						break;
					} 
					$ret=$helper->setShipment();
					if(is_array($ret)) {
						echo json_encode(array('error'=>1,'message'=>implode($ret)));
						break;
					}
					$helper->lSelectShipment();
					$helper->lSelectPayment();
					$data=array();
					$data["shipments"]=$helper->shipments_shipment_rates;
					$data["payments"]=$helper->paymentplugins_payments;
					$data["paymentsnew"]=$helper->getpayments();
					$data["price"]=$helper->getPrices();
					
					echo json_encode($data);
					break;
				case 'update_product':
					$helper->setAddress();
					$helper->updateProduct();
					$helper->lSelectShipment();
					$helper->lSelectPayment();
					$data=array();
					$data["shipments"]=$helper->shipments_shipment_rates;
					$data["payments"]=$helper->paymentplugins_payments;
					$data["paymentsnew"]=$helper->getpayments();
					$data["price"]=$helper->getPrices();
					echo json_encode($data);
					break;
				case 'remove_product':
					$helper->setAddress();
					$helper->removeProduct();
					$helper->lSelectShipment();
					$helper->lSelectPayment();
					$data=array();
					
					$data["shipments"]=$helper->shipments_shipment_rates;
					$data["payments"]=$helper->paymentplugins_payments;
					$data["paymentsnew"]=$helper->getpayments();
					$data["price"]=$helper->getPrices();
					echo json_encode($data);
					break;
				case 'register':
					$ret=$helper->register();
					echo json_encode($ret);
					break;
				case 'set_checkout':
					$helper->setAddress();
					$ret=$helper->setPayment();
					$ret=$helper->setShipment();
					echo json_encode(array());
					break;
				case 'login':
			
					if ($return = JRequest::getVar('return')) 
    				  {
						$return = base64_decode($return);
						if (!JURI::isInternal($return)) 
				        {
							$return = '';
						}
					}		
					
					$options = array();				
					$options['remember'] = false;				
					$options['return'] = $return;		
					$credentials = array();				
					$credentials['username'] = JRequest::getVar('username');				
					$credentials['password'] = JRequest::getString('passwd');				
					$mainframe = JFactory::getApplication();
					//preform the login action
					$response = $mainframe->login($credentials, $options);
					if($response == false)
					{
					echo "error";
					}
					break;	
				
				
				
			}
				
			JFactory::getApplication()->close();
		}
		 
		       $document = JFactory::getDocument();
			   $app = JFactory::getApplication();
			   $template = $app->getTemplate(true);
				if (!class_exists ('VmConfig')) {
					require(JPATH_ADMINISTRATOR . DS . 'components' . DS . 'com_virtuemart' . DS . 'helpers' . DS . 'config.php');
				}
				VmConfig::loadConfig();
				$uri = JFactory::getURI();
				$post = JRequest::get('post');
				$_option = JRequest::getString('option');
				$_view = JRequest::getString('view');
				$_format = JRequest::getString('format', '');
				$_task = JRequest::getString('task', '');
				$_tmpl = JRequest::getString('tmpl', '');
			    if ($_option == 'com_virtuemart' && $_view == 'cart' && $_format != 'json') 
				{
					require_once(dirname(__FILE__) . DS . 'cart' . DS . 'cartview.html.php');
					
	     	    }
				
	}
}
?>
