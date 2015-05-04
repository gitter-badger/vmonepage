<?php defined('_JEXEC') or die('Restricted access');

/**
 *
 * Layout for the shopping cart
 *
 * @package	VirtueMart
 * @subpackage Cart
 * @author Max Milbers
 * @author Patrick Kohl
 * @link http://www.virtuemart.net
 * @copyright Copyright (c) 2004 - 2010 VirtueMart Team. All rights reserved.
 * @license http://www.gnu.org/copyleft/gpl.html GNU/GPL, see LICENSE.php
 * VirtueMart is free software. This version may have been modified pursuant
 * to the GNU General Public License, and as distributed it includes or
 * is derivative of works licensed under the GNU General Public License or
 * other free or open source software licenses.
 *
 */
 jimport('mowebso.joomla.thirdparty.virtuemart');


// Check to ensure this file is included in Joomla!
$plugin=JPluginHelper::getPlugin('system','vmuikit_onepage');
$params=new JRegistry($plugin->params);
?>
<script type="text/javascript"> 
selectedpaymentid = 0;
function checkpaymentval()
{
jQuery("#payments input").each(function(){
jQuery(this).attr('checked', false);
});
 for(var i=0;i<document.id('payment_ul').getElements('input').length;i++) 
   {
    if(document.id('payment_ul').getElements('input')[i].checked==true)
	  {
	    selectedpaymentid = document.id('payment_ul').getElements('input')[i].value;

	  }
   }
update_form();
}
</script>
<style>
#klarna-checkout-other-payment-options
{
 display:none;
}
</style>
<div id="cart-contents" class="opg-grid" data-opg-margin>

<div id="leftdiv" class="opg-width-1-1 opg-width-large-2-3 opg-width-medium-2-3 opg-width-small-1-1 opg-float-left   opg-border-rounded">

  
  <div class="opg-width-1-1">
    <h3 class="opg-h3"><?php echo JText::_('COM_VIRTUEMART_CART_TITLE'); ?></h3>
	
	 <?php
    $modules = JModuleHelper::getModules("onepage_promo_top");
	$document  = JFactory::getDocument();
	$renderer  = $document->loadRenderer('module');
	$attribs   = array();
	$attribs['style'] = 'xhtml';
	
	if(count($modules) > 0)
	{ 
	    
	    echo '<div class="opg-panel opg-panel-box opg-width-1-1 opg-margin-top">';
	 	foreach($modules as $mod)
	    {
		  echo JModuleHelper::renderModule($mod, $attribs);
	    }
	    echo '</div>';
		echo '<hr class="opg-margin" />';
	}
	?>
	<div class="opg-width-1-1" id="customerror" style="display:none;"> </div>
    <?php
		$i=1;
		foreach( $this->cart->products as $pkey =>$prow ) {
		$vmproduct_id = $prow->virtuemart_product_id;
		$cartitemid = $prow->cart_item_id;
		$pModel = VmModel::getModel('product');
		$tmpProduct = $pModel->getProduct($vmproduct_id, true, false,true,1);
		$pModel->addImages($tmpProduct,1);
		?>
 		<div class="product opg-width-1-1 opg-margin" id="product_row_<?php echo $cartitemid; ?>">
          <div class="spacer">
		    <div class="opg-width-1-5 opg-hidden-small opg-float-left">
				<?php // Output Product Image
				if ($tmpProduct->virtuemart_media_id) { ?>
                    <div class="opg-margin-right opg-text-center ">
						<?php
						 echo JHTML::_ ( 'link', JRoute::_ ( 'index.php?option=com_virtuemart&view=productdetails&virtuemart_product_id=' . $tmpProduct->virtuemart_product_id . '&virtuemart_category_id=' . $tmpProduct->virtuemart_category_id ), $tmpProduct->images[0]->displayMediaThumb( 'class="opg-thumbnail opg-thumbnail-mini" border="0"',false,'' ) ); ?>
                    </div>
		    	<?php } ?>
            </div>
         <div class="opg-width-large-4-5 opg-width-small-1-1 opg-float-left">
            <div class="top-row">
			  <div class="opg-text-large opg-text-bold opg-float-left opg-width-large-2-5 opg-width-small-1-1 opg-width-1-1">
                    <div class="spacer">
						<?php echo JHTML::link($prow->url, $prow->product_name, 'class="opg-link"') ?>
                    </div>
               </div>
			   <div class="opg-text-primary opg-text-bold opg-float-right opg-width-large-1-6 opg-width-small-3-6 opg-width-3-6 opg-text-right">
                    <div class="spacer" id="subtotal_with_tax_<?php echo $pkey; ?>">
						<?php echo $this->currencyDisplay->createPriceDiv('salesPrice','', $this->cart->pricesUnformatted[$pkey],true,false,$prow->quantity); //No quantity or you must use product_final_price ?>
                    </div>
               </div>
			       <div class="quantity opg-float-right opg-width-large-1-4 opg-width-small-3-6 opg-width-3-6 opg-text-left-small">
                    <div class="spacer" >
                             <input type="text" title="<?php echo  JText::_('COM_VIRTUEMART_CART_UPDATE') ?>" class="quantity-input js-recalculate opg-form-small opg-text-center" size="2" maxlength="4" value="<?php echo $prow->quantity ?>" id='quantity_<?php echo $vmproduct_id; ?>' name="quantity[]"/>
									  
				
                            <input type="hidden" name="view" value="cart" /> 
                            <input type="hidden" name="task" value="update" />
                            <input type="hidden" name="virtuemart_product_id[]" value="<?php echo $vmproduct_id;  ?>" />
                            <div class="opg-button-group">
                              <a href="javascript:void(0);" class="opg-button opg-button-primary quantity-minus opg-button-mini"><i class="opg-icon-minus"></i></a>
							 <a href="javascript:void(0);" name="update" title="<?php echo  JText::_('COM_VIRTUEMART_CART_UPDATE') ?>" class="opg-button opg-button-primary  opg-button-mini" onclick="javascript:update_form('update_product','<?php echo $vmproduct_id; ?>');"><i class="opg-icon-refresh"></i></a>
                                <a href="javascript:void(0);" class="opg-button opg-button-primary quantity-plus  opg-button-mini"><i class="opg-icon-plus"></i></a>
                         	</div>
                    </div>
                </div>
				
      <div class="opg-text-primary opg-hidden-small opg-text-bold opg-float-right opg-width-large-1-6 opg-width-small-2-6 opg-width-2-6 opg-text-left-small">

                    <div class="spacer" >
						<?php echo $this->currencyDisplay->createPriceDiv('salesPrice','', $this->cart->pricesUnformatted[$pkey],true,false,1); //No quantity or you must use product_final_price ?>
						<?php //echo $this->currencyDisplay->createPriceDiv('basePriceVariant','', $this->cart->pricesUnformatted[$pkey],false); ?>
                    </div>
               </div>
       	<div class="clear"></div>
        </div>
		<hr class="opg-margin-remove" />
            <div class="bottom-row opg-grid">
                <div class="opg-width-large-1-3 opg-width-small-1-2 opg-width-1-2 opg-text-left-small opg-hidden-small">
                    <div class="spacer">
                        <div class="sku">
							<?php echo JText::_('COM_VIRTUEMART_CART_SKU').': '.$prow->product_sku; ?>
                        </div>
						<?php // Output Custom Attributes
						if (!empty($prow->customfields)) { ?>
							<?php } ?>

                        <div class="cart-product-details">
							<?php echo JHTML::link($prow->url, JText::_('COM_VIRTUEMART_PRODUCT_DETAILS')) ?>
                        </div>

                    </div>
                </div>
                

                <div class="status opg-width-large-1-3 opg-width-small-1-2 opg-width-1-2">
                    <div class="spacer">
						<?php // Output The Tax For The Product
						$taxtAmount = $this->currencyDisplay->createPriceDiv('taxAmount','', $this->cart->pricesUnformatted[$pkey],true,false,$prow->quantity);
						if ( VmConfig::get('show_tax') && !empty($taxtAmount)) { 
						echo '<div><span class="opg-margin-small-right opg-float-left">'.JText::_('COM_VIRTUEMART_CART_SUBTOTAL_TAX_AMOUNT')." :</span>";
						?>
                            <span class="tax opg-text-left" id="subtotal_tax_amount_<?php echo $pkey; ?>"><?php $this->currencyDisplay->createPriceDiv('taxAmount','', $this->cart->pricesUnformatted[$pkey],true,false,$prow->quantity) ?></span></div>
							<?php } ?>

						<?php // Output The Discount For The Product
						$discountAmount = $this->currencyDisplay->createPriceDiv('discountAmount','', $this->cart->pricesUnformatted[$pkey],true,false,$prow->quantity);
						if(!empty($discountAmount)) {
						echo '<div><span class="opg-margin-small-right opg-float-left">'.JText::_('COM_VIRTUEMART_CART_SUBTOTAL_DISCOUNT_AMOUNT')." :</span>";
						?>
                            <span class="discount opg-float-left" id="subtotal_discount_<?php echo $pkey; ?>"><?php echo $this->currencyDisplay->createPriceDiv('discountAmount','', $this->cart->pricesUnformatted[$pkey],true,false,$prow->quantity);  //No quantity is already stored with it ?></span></div>
							<?php } ?>
                    </div>
                </div>
				<div class="status opg-width-large-1-3 opg-width-small-1-2 opg-width-1-2">
                    <div class="spacer">
					        <a class="remove-product" title="<?php echo JText::_('COM_VIRTUEMART_CART_DELETE') ?>" align="middle" href="javascript:void(0)" onclick="javascript:update_form('remove_product','<?php echo $cartitemid; ?>')" ><?php echo JText::_('COM_VIRTUEMART_CART_DELETE') ?> </a></td> 

                    </div>
                </div>
                <div class="clear"></div>
             </div>
        </div>
        <div class="clear"></div>
        <hr class="opg-margin-bottom-remove" />
   </div></div>
    <?php
			$i = 1 ? 2 : 1;
	} ?>
   </div>
   <?php
   $hidecoupondiv = "opg-hidden";
   if(VmConfig::get('coupons_enable', 0))
   {
     $hidecoupondiv = "";
   }
   
   ?>
   <div class="opg-clear"></div>
   <div class="opg-width-1-1 opg-margin-small-top <?php echo $hidecoupondiv; ?>">
	   <div class="opg-width-1-1 opg-text-center opg-panel-box">
    	 <?php 
		
					    echo $this->loadTemplate('coupon');
		?>
        <?php
				echo "<div id='coupon_code_txt' class='opg-width-1-2 opg-container-center'>".@$this->cart->cartData['couponCode'];
				echo @$this->cart->cartData['couponDescr'] ? (' (' . $this->cart->cartData['couponDescr'] . ')' ): '';
				echo "</div>";
				?>
		   
	  </div>
  </div>
  <?php
  foreach($this->helper->BTaddress["fields"] as $_field) 
  {
     if($_field['name']=='customer_note') 
	 {
	 ?>
	   <div id="extracommentss" class="opg-panel opg-panel-box opg-margin-small-top">
	   <h3 class="opg-panel-title"><?php echo JText::_('COM_VIRTUEMART_COMMENT_CART'); ?></h3>
		   <div class="opg-text-center">
		   <?php
			   echo str_replace("<textarea", '<textarea onblur="javascript:update_form();" ', $_field['formcode']);
		   ?>
		   </div>
	   </div>
	 <?php
	 }
  }
  ?>
  <div class="price-summary opg-content opg-margin-small-top">
     <div class="spacer">

     <div class="opg-width-1-1  opg-grid opg-text-right" id="couponpricediv">
		  <div class="price-type opg-width-large-3-4 opg-width-small-1-2 opg-width-1-2"><?php echo JText::_('COM_VIRTUEMART_COUPON_DISCOUNT').': '; ?></div>
            <div class="price-amount price-type opg-width-large-1-4 opg-width-small-1-2 opg-width-1-2" id="coupon_price"><?php echo $this->currencyDisplay->createPriceDiv('salesPriceCoupon','', $this->cart->pricesUnformatted['salesPriceCoupon'],true) ?></div>
            <div class="clear"></div>
        </div>

	 
	 
        <div class="product-subtotal  opg-grid opg-text-right" id="sales_pricedivfull">
		  <div class="price-type opg-width-large-3-4 opg-width-small-1-2 opg-width-1-2"><?php echo JText::_('COM_VIRTUEMART_ORDER_PRINT_PRODUCT_PRICES_TOTAL').': '; ?></div>
            <div class="price-amount price-type opg-width-large-1-4 opg-width-small-1-2 opg-width-1-2" id="sales_price"><?php echo $this->currencyDisplay->createPriceDiv('salesPrice','', $this->cart->pricesUnformatted,true) ?></div>
            <div class="clear"></div>
        </div>
		<div class="product-subtotal   opg-grid opg-text-right" id="shipmentdivfull">
		  <div class="price-type opg-width-large-3-4 opg-width-small-1-2 opg-width-1-2">
		          <?php echo JText::_('COM_VIRTUEMART_CART_SHIPPING').":"; ?>
			</div>
		        <div class="price-amount price-type opg-width-large-1-4 opg-width-small-1-2 opg-width-1-2" id="shipment"><?php echo strip_tags($this->currencyDisplay->createPriceDiv('salesPriceShipment','', $this->cart->pricesUnformatted['salesPriceShipment'],false)); ?></div>
            <div class="clear"></div>
        </div>
		
         <div class="product-subtotal opg-width-1-1 opg-hidden" id="coupon_taxdivfull">
		  <div class="price-type opg-width-large-3-4 opg-width-small-1-2 opg-width-1-2">
		          <?php echo JText::_('Coupon Tax').":"; ?>
			</div>
             <div class="price-amount price-type opg-width-large-1-4 opg-width-small-1-2 opg-width-1-2" id="coupon_tax"><?php echo $this->currencyDisplay->createPriceDiv('couponTax','', @$this->cart->pricesUnformatted['couponTax'],false); ?></div>
            <div class="clear"></div>
        </div>

			<?php
		foreach($this->cart->cartData['DBTaxRulesBill'] as $rule){ ?>
            <div class="opg-width-1-1  opg-grid opg-text-right">
                <div class="price-type price-type opg-width-large-3-4 opg-width-small-1-2 opg-width-1-2"><?php echo $rule['calc_name'].': ' ?></div>
                <div class="price-amount opg-width-large-1-4 opg-width-small-1-2 opg-width-1-2"><?php echo $this->currencyDisplay->createPriceDiv($rule['virtuemart_calc_id'].'Diff','', $this->cart->pricesUnformatted[$rule['virtuemart_calc_id'].'Diff'],true); ?></div>
                <div class="clear"></div>
            </div>
			<?php } ?>

		<?php
		foreach($this->cart->cartData['taxRulesBill'] as $rule){ ?>
            <div class=" opg-grid opg-text-right">
                <div class="price-type opg-width-large-3-4 opg-width-small-1-2 opg-width-1-2"><?php echo $rule['calc_name'].': ' ?></div>
                <div class="price-amount opg-width-large-1-4 opg-width-small-1-2 opg-width-1-2"><?php echo $this->currencyDisplay->createPriceDiv($rule['virtuemart_calc_id'].'Diff','', $this->cart->pricesUnformatted[$rule['virtuemart_calc_id'].'Diff'],true); ?></div>
                <div class="clear"></div>
            </div>
			<?php } ?>

		<?php
		foreach($this->cart->cartData['DATaxRulesBill'] as $rule){ ?>
            <div class=" opg-grid opg-text-right">
                <div class="price-type opg-width-large-3-4 opg-width-small-1-2 opg-width-1-2"><?php echo $rule['calc_name'].': ' ?></div>
                <div class="price-amount opg-width-large-1-4 opg-width-small-1-2 opg-width-1-2"><?php echo $this->currencyDisplay->createPriceDiv($rule['virtuemart_calc_id'].'Diff','', $this->cart->pricesUnformatted[$rule['virtuemart_calc_id'].'Diff'],true); ?></div>
                <div class="clear"></div>
            </div>
			<?php } ?>

		<?php if(!empty($this->cart->pricesUnformatted['billDiscountAmount'])) { ?>
        <div class=" opg-grid opg-text-right" id="total_amountfulldiv">
            <div class="price-type opg-width-large-3-4 opg-width-small-1-2 opg-width-1-2"><?php echo JText::_('COM_VIRTUEMART_CART_SUBTOTAL_DISCOUNT_AMOUNT').': ' ?></div>
            <div class="price-amount opg-width-large-1-4 opg-width-small-1-2 opg-width-1-2" id="total_amount"><?php echo $this->currencyDisplay->createPriceDiv('billDiscountAmount','', $this->cart->pricesUnformatted['billDiscountAmount'],true); ?></div>
            <div class="clear"></div>
        </div>
		<?php } ?>
		<?php // We Are in The Last Step
		if ( VmConfig::get('show_tax')) { ?>
            <div class="shipping-total opg-hidden">
                <div class="price-type opg-width-large-3-4 opg-width-small-1-2 opg-width-1-2">
					<?php echo JText::_('COM_VIRTUEMART_CART_SHIPPING_TAX').': ' ?>
                </div>
                <div class="price-amount opg-width-large-1-4 opg-width-small-1-2 opg-width-1-2" id="shipment_tax"><?php echo $this->currencyDisplay->createPriceDiv('salesPriceShipment','', $this->cart->pricesUnformatted['shipmentTax'],true); ?></div>

                <div class="clear"></div>
            </div>
	    <?php } ?>
		
		<?php if ( VmConfig::get('show_tax')) { ?>
        <div class=" opg-grid opg-text-right">
            <div class="price-type opg-width-large-3-4 opg-width-small-1-2 opg-width-1-2" id="total_taxfulldiv"><?php echo JText::_('COM_VIRTUEMART_CART_SUBTOTAL_TAX_AMOUNT').': ' ?></div>
            <div class="price-amount opg-width-large-1-4 opg-width-small-1-2 opg-width-1-2" id="total_tax" ><?php echo $this->currencyDisplay->createPriceDiv('billTaxAmount','', $this->cart->pricesUnformatted['billTaxAmount'],true) ?></div>
            <div class="clear"></div>
        </div>
		<?php } ?>

        <div class="total  opg-grid opg-text-right" id="bill_totalfulldiv">
            <div class="price-type opg-width-large-3-4 opg-width-small-1-2 opg-width-1-2 opg-text-large opg-text-primary opg-text-bold"><?php echo JText::_('COM_VIRTUEMART_CART_TOTAL').': ' ?></div>
            <div class="price-amount opg-width-large-1-4 opg-width-small-1-2 opg-width-1-2 opg-text-large opg-text-primary opg-text-bold" id="bill_total"><?php echo $this->currencyDisplay->createPriceDiv('billTotal','', $this->cart->pricesUnformatted['billTotal'],true); ?></div>
            <div class="clear"></div>
        </div>


		<?php
		if ( $this->totalInPaymentCurrency && !empty($this->cart->BTaddress['fields']['first_name']['value']) && !empty($this->cart->BTaddress['fields']['city']['value'])) { ?>
            <div class="">
                <div class="price-type opg-width-large-3-4 opg-width-small-1-2 opg-width-1-2"><?php echo JText::_('COM_VIRTUEMART_CART_TOTAL_PAYMENT').': ' ?></div>
                <div class="price-amount opg-width-large-1-4 opg-width-small-1-2 opg-width-1-2"><?php echo $this->totalInPaymentCurrency;   ?></div>
                <div class="clear"></div>
            </div>
			<?php } ?>
    </div>
   </div>
   <?php
    $modules = JModuleHelper::getModules("onepage_promo");
	$document  = JFactory::getDocument();
	$renderer  = $document->loadRenderer('module');
	$attribs   = array();
	$attribs['style'] = 'xhtml';
	
	if(count($modules) > 0)
	{ 
	    echo '<hr class="opg-margin" />';
	    echo '<div class="opg-width-1-1 opg-margin-top opg-panel opg-panel-box ">';
	 	foreach($modules as $mod)
	    {
		  echo JModuleHelper::renderModule($mod, $attribs);
	    }
	    echo '</div>';
	}
   ?>
  </div><!-- left Div Ended -->
  <div id="right_div" class="tm-sidebar-a opg-width-1-1 opg-width-large-1-3 opg-width-medium-1-3 opg-width-small-1-1 opg-float-right" >
  <div class="opg-width-1-1 opg-panel opg-panel-box">
  <?php
   foreach($this->helper->BTaddress["fields"] as $_field) 
    {
	  if($_field['name']=='virtuemart_country_id') 
	  {
	     echo '				<label class="' . $_field['name'] . '" for="' . $_field['name'] . '_field">' . "\n";
	     echo '					' . $_field['title'] . ($_field['required'] ? ' *' : '') . "\n";
 	     echo '				</label>' ;
	  	 $_field['formcode']=str_replace('<select','<select onchange="javascript:update_form();"',$_field['formcode']);
		 $_field['formcode']=str_replace('vm-chzn-select','',$_field['formcode']);
		 echo $_field['formcode'];
	  }
	}
  ?>
  </div>
  
  <?php
   $shipmenthideclass= "";
   if(count($this->helper->shipments_shipment_rates) == 1)
   {
     if($params->get('hide_oneshipment',0))
	 {
	     $shipmenthideclass= "opg-hidden";
	 }
   }
  ?>
  
  <div class="opg-width-1-1 opg-panel-box opg-margin-small-top <?php echo  $shipmenthideclass; ?>">
  <input type="hidden" name="auto_shipmentid" id="auto_shipmentid" value="<?php echo vmconfig::get("set_automatic_shipment");  ?>" />
		<h3 class="opg-panel-title"><?php echo JText::_('COM_VIRTUEMART_CART_EDIT_SHIPPING'); ?></h3>
		<div id="shipment_fulldiv" class="opg-width-1-1">
        <?php
				 
					 $shipmentmethod_id = $this->cart->virtuemart_shipmentmethod_id;
					 $selectedshipment = "";
					 $shipmentpresent = 0;
					 foreach($this->helper->shipments_shipment_rates as $rates) 
					 {
					     if(strpos($rates, "checked") !== false)
					  	 {
						
						    $tmpdis = strip_tags($rates , '<span>');
						    echo '<table class="opg-table opg-table-striped" id="shipmenttable"><tr id="shipmentrow"><td id="shipmentdetails">';
							$tmpdis =  str_replace("</span><span>" , "</span><br /><span>", $tmpdis);
							$tmpdis =  str_replace("vmshipment_description" , "vmshipment_description opg-text-small", $tmpdis);
							$tmpdis =  str_replace("vmshipment_cost" , "vmshipment_cost opg-text-small", $tmpdis);
						    echo $tmpdis;
							echo '</td>';
							if(count($this->helper->shipments_shipment_rates) > 1)
							{
							    echo '<td id="shipchangediv" class="opg-width-1-4">';
					            echo '<a class="opg-button opg-button-primary" href="#shipmentdiv" data-opg-modal>'.JText::_("PLG_SYSTEM_VMUIKIT_ONEPAGE_CHNAGE").'</a>';
					 			echo '</td>';
							}
							echo '</tr></table>';
							$shipmentpresent = 1;
						 }
					 }	
					 
					 if(!$shipmentpresent)
					 {
					    if(count($this->helper->shipments_shipment_rates) > 0)
						{
				            $tmpdis = strip_tags($this->helper->shipments_shipment_rates[0] , '<span>');
						    echo '<table class="opg-table opg-table-striped" id="shipmenttable"><tr id="shipmentrow"><td id="shipmentdetails">';
						    $tmpdis =  str_replace("</span><span>" , "</span><br /><span>", $tmpdis);
							$tmpdis =  str_replace("vmshipment_description" , "vmshipment_description opg-text-small", $tmpdis);
							$tmpdis =  str_replace("vmshipment_cost" , "vmshipment_cost opg-text-small", $tmpdis);
							echo $tmpdis;
							echo '</td>';
							if(count($this->helper->shipments_shipment_rates) > 1)
							{
							    echo '<td id="shipchangediv" class="opg-width-1-4">';
					            echo '<a class="opg-button opg-button-primary" href="#shipmentdiv" data-opg-modal>'.JText::_("PLG_SYSTEM_VMUIKIT_ONEPAGE_CHNAGE").'</a>';
					 			echo '</td>';
							}
							echo '</tr></table>'; 
							$shipmentpresent = 1;
						}
						else
						{
						  $text = "";
					  	  $shipmentnilltext = vmInfo('COM_VIRTUEMART_NO_SHIPPING_METHODS_CONFIGURED', $text);
					  	  echo '<p id="shipmentnill" class="opg-text-warning">'.$shipmentnilltext.'</p>';
						}
				    }
			?>
			</div>
			<?php
				  echo '<div id="shipmentdiv" class="opg-modal">';
				   echo '<div class="opg-modal-dialog">';
				    echo '<a class="opg-modal-close opg-close"></a>';
				     echo '<div class="opg-modal-header">Select Shipment Method</div>';
				      echo "<fieldset id='shipments'>";					
					   echo '<ul class="opg-list" id="shipment_ul">';
						foreach($this->helper->shipments_shipment_rates as $rates) 
						{
						     if(strpos("checked", $rates) !== false)
							 {
							   $actclass = "liselcted";
							 }
							 else
							 {
							   $actclass = "";
							 }
						     echo '<li class="'.$actclass.'">';
							 echo '<label class="opg-width-1-1">'.$rates.'</label>';
							 echo '</li><hr class="opg-margin-small-bottom opg-margin-small-top" />';
						}
					echo "</ul>";
					echo "</fieldset>";
					
			
				?>
				<div class="opg-modal-footer">
				<a class="opg-button opg-button-primary" onclick="javascript:update_form('custom');"><?php echo JText::_("PLG_SYSTEM_VMUIKIT_ONEPAGE_SUBMIT"); ?></a>
				<a id="shipmentclose" class="opg-modal-close opg-button"><?php echo JText::_("PLG_SYSTEM_VMUIKIT_ONEPAGE_CANCEL"); ?></a>
				</div>
				<?php
				echo '</div>';
				echo '</div>';
				?>
		
   </div>
   <?php
   $paymenthideclass= "";
   if(count($this->helper->paymentplugins_payments) == 1)
   {
     if($params->get('hide_onepayment',0))
	 {
	     $paymenthideclass= "opg-hidden";
	 }
   }
  ?>
   <div class="opg-width-1-1 opg-panel-box opg-margin-small-top <?php echo $paymenthideclass; ?>">
  
   <input type="hidden" name="auto_paymentid" id="auto_paymentid" value="<?php echo vmconfig::get("set_automatic_payment");  ?>" />
   <h3 class="opg-panel-title"><?php echo JText::_('COM_VIRTUEMART_CART_SELECTPAYMENT'); ?></h3>
	  <div id="payment_fulldiv" class="opg-width-1-1">
      <?php
				$paymentsarr = $this->helper->getpayments();
			    $paymentpresent = 0;

				foreach($this->helper->getpayments() as $tmppay) 
				{
				    $vmpayid = '"'.$this->cart->virtuemart_paymentmethod_id.'"';
			 	    if(strpos("checked", $tmppay) !== false)
				    {
						    $tmpdis = strip_tags($tmppay , '<span>');
						    echo '<table class="opg-table opg-table-striped" id="paymentable"><tr id="paymentrow"><td id="paymentdetails">';
						    $tmpdis =  str_replace("</span><span>" , "</span><br /><span>", $tmpdis);
							$tmpdis =  str_replace("vmpayment_description" , "vmpaymentt_description opg-text-small", $tmpdis);
							$tmpdis =  str_replace("vmpayment_cost" , "vmpayment_cost opg-text-small", $tmpdis);
						    echo $tmpdis;
							echo '</td>';
							if(count($this->helper->getpayments()) > 1)
							{
							    echo '<td id="paychangediv">';
					            echo '<a class="opg-button opg-button-primary" href="#paymentdiv" data-opg-modal>'.JText::_("PLG_SYSTEM_VMUIKIT_ONEPAGE_CHNAGE").'</a>';
					 			echo '</td>';
							}
							echo '</tr></table>'; 
							$paymentpresent = 1;
 				    }
				   
				}
				if(!$paymentpresent)
				{
					  if(count($this->helper->getpayments()) > 0)
					  {
					        $paym_arr = array();
					        $paym_arr = $this->helper->getpayments();
				            $tmpdis = strip_tags($paym_arr[0] , '<span>');
						    echo '<table class="opg-table opg-table-striped" id="paymentable"><tr id="paymentrow"><td id="paymentdetails">';
						    $tmpdis =  str_replace("</span><span>" , "</span><br /><span>", $tmpdis);
							$tmpdis =  str_replace("vmpayment_description" , "vmpayment_description opg-text-small", $tmpdis);
							$tmpdis =  str_replace("vmpayment_cost" , "vmpayment_cost opg-text-small", $tmpdis);
						    echo $tmpdis;
							echo '</td>';
							if(count($this->helper->getpayments()) > 1)
							{
							    echo '<td id="paychangediv" class="opg-width-1-4">';
					            echo '<a class="opg-button opg-button-primary" href="#paymentdiv" data-opg-modal>'.JText::_("PLG_SYSTEM_VMUIKIT_ONEPAGE_CHNAGE").'</a>';
					 			echo '</td>';
							}
							echo '</tr></table>'; 
							$paymentpresent = 1;
					}
					else
					{
					    
					    $text = "";
					    $paymentnilltext = vmInfo('COM_VIRTUEMART_NO_PAYMENT_METHODS_CONFIGURED', $text);
					    echo '<p id="paymentnill" class="opg-text-warning">'.$paymentnilltext.'</p>';
					}
				}
			?>
			</div>
			<?php
			 
				 echo '<div id="paymentdiv" class="opg-modal">';
				   echo '<div class="opg-modal-dialog">';
				    echo '<a class="opg-modal-close opg-close"></a>';
				      echo '<div class="opg-modal-header">Select Payment Method</div>';
				  	  $paymentsarr = $this->helper->getpayments();
					   echo '<div id="paymentsdiv">';
						echo '<ul class="opg-list" id="payment_ul">';
							foreach($paymentsarr as $pay)
							{
							  echo '<li>'.$pay.'<hr class="opg-margin-small-bottom opg-margin-small-top" /></li>';
							}
						echo '</ul>';
					  echo '</div>';

					
				?>
				
				<div class="opg-modal-footer">
				<a class="opg-button opg-button-primary" onclick="javascript:checkpaymentval();"><?php echo JText::_("PLG_SYSTEM_VMUIKIT_ONEPAGE_SUBMIT"); ?></a>
				<a id="paymentclose" class="opg-modal-close opg-button"><?php echo JText::_("PLG_SYSTEM_VMUIKIT_ONEPAGE_CANCEL"); ?></a>
				</div>
				<?php
				echo '</div>';
				echo '</div>';
				
				
	  
	   ?>
   </div>
   <div id="klarnadiv" class="opg-panel-box opg-margin-top" style="display:none">
   <?php
   echo "<fieldset id='payments'>"; 
   foreach($this->helper->paymentplugins_payments as $payments) {
				$display = str_replace('type="radio"','type="radio" class="opg-hidden" onclick="javascript:update_form();"',$payments);
				$display = str_replace('<label','<label class="opg-hidden"',$display);
				echo $display;
    }
	echo '</fieldset>';
   ?>
   </div>
   <div id="otherpay_buttons" class="opg-panel-box opg-margin-top"> <!-- Panel Box Started -->
     
	 <?php
	  $user = JFactory::getUser();
	  if($user->id == 0)	
	  { 
		 if (VmConfig::get('oncheckout_only_registered') == 1 && VmConfig::get('oncheckout_show_register') == 0)
	  	 {
			 $logindis = 'display:none;';
			 $logindiv = '';
	 	 }
		 else
		 {
		     $logindis = '';
			 $logindiv = 'display:none;';
		 
		 echo '<div class="opg-width-1-1 opg-button-group " id="loginbtns" data-opg-button-radio>';
		 echo '<a id="regbtn" href="javascript:void(0);"  onclick="changemode(2);" class="opg-button opg-width-1-2 opg-button-primary">'.JText::_("COM_VIRTUEMART_ORDER_REGISTER_GUEST_CHECKOUT").'</a>';
	     echo '<a id="loginbtn" href="javascript:void(0);" onclick="changemode(1);" class="opg-button opg-width-1-2">'.JText::_("COM_VIRTUEMART_LOGIN").'</a>';
		 echo '</div>';
		 echo '<hr />';
		 }
		 
		 
      }
	  else
	  {
        $logindis = '';
		$logindiv = 'display:none;';
	  }
	  $user = JFactory::getUser();
	  if (empty($this->url)){
		$uri = JFactory::getURI();
		$url = $uri->toString(array('path', 'query', 'fragment'));
	  } else{
		$url = $this->url;
	  }

	  if($user->id == 0)	
	  {
	  ?> 
	      <div id="logindiv" class="opg-margin-top" style="<?php echo $logindiv; ?>">
		  <strong><?php echo JText::_('COM_VIRTUEMART_ORDER_CONNECT_FORM') ?></strong>
		  <div id="loginerror" class="opg-width-1-1" style="display:none;">
		  </div>
		 
            <div class="first-row opg-width-1-1">
                <div class="username  opg-width-small-1-1 opg-margin-small-top" id="com-form-login-username">
                    <input id="modlgn-username" class="opg-width-1-1" type="text" name="username" size="18" alt="<?php echo JText::_('COM_VIRTUEMART_USERNAME'); ?>" value="" placeholder="<?php echo JText::_('COM_VIRTUEMART_USERNAME'); ?>" />
                </div>

                <div class="password opg-width-large-1-1 opg-width-small-1-1 opg-margin-small-top" id="com-form-login-password">
	
                    <input id="modlgn-passwd" type="password" name="password" class="opg-width-1-1" size="18" alt="<?php echo JText::_('COM_VIRTUEMART_PASSWORD'); ?>" value="" placeholder="<?php echo JText::_('COM_VIRTUEMART_PASSWORD'); ?>" />
	
                </div>

                <div class="login opg-width-large-1-1 opg-width-small-1-1 opg-margin-small-top" id="com-form-login-remember">
				 <a class="opg-button opg-button-primary opg-width-1-1" href="javascript:void(0);" onclick="ajaxlogin()"><?php echo JText::_('COM_VIRTUEMART_LOGIN') ?></a>

                </div>
                <div class="clear"></div>
            </div>
            <input type="hidden" id="loginempty" value="<?php echo JText::_("PLG_SYSTEM_VMUIKIT_ONEPAGE_LOGIN_EMPTY"); ?>" /> 
            <input type="hidden" id="loginerrors" value="<?php echo JText::_("PLG_SYSTEM_VMUIKIT_ONEPAGE_LOGIN_ERROR"); ?>" />
            <input type="hidden" name="task" value="user.login" />
            <input type="hidden" name="option" value="<?php echo $comUserOption ?>" />
            <input type="hidden" name="return" value="<?php echo base64_encode($url) ?>" id="returnurl" />
           

		  </div>
	   <?php
	  }
     ?>

  <div id="old_payments" style="<?php echo $logindis; ?>">
    <?php if ( VmConfig::get('show_tax')) { ?>
    <div><?php echo "<span  class='priceColor2 opg-hidden' id='payment_tax'>".$this->currencyDisplay->createPriceDiv('paymentTax','', $this->cart->pricesUnformatted['paymentTax'],false)."</span>"; ?> </div>
    <?php } ?>
    <div id="payment" class="opg-hidden">
      <?php  echo $this->currencyDisplay->createPriceDiv('salesPricePayment','', $this->cart->pricesUnformatted['salesPricePayment'],false); ?>
    </div>
<div class="billto-shipto">

   <?php  
   if($user->id == 0) 
   { 
   ?>
      <div class="opg-width-1-1 opg-margin-bottom" >
	  <?php
	  
      if(VmConfig::get('oncheckout_show_register') == 0)
	  {
    
	  }
	  else if (VmConfig::get('oncheckout_only_registered') == 0)
	   {
   	   ?>
	   <div class="opg-button-group opg-width-1-1" data-opg-button-radio="">
		  <a id="guestchekcout" class="opg-button opg-width-1-2 opg-button-primary" onClick="changecheckout(1)" href="javascript:void(0);"><i id="guesticon" class="opg-icon-check opg-margin-small-right"></i>Guest</a>
		  <a id="regcheckout"  class="opg-button opg-width-1-2" onClick="changecheckout(2)" href="javascript:void(0);"><i id="regicon" class="opg-margin-small-right"></i>Register</a> 
      </div>
 	   <?php
	   }
	   else
	   {
	   ?>
		  <a id="regcheckout"  class="opg-button opg-button-primary"  href="javascript:void(0);"><i id="regicon" class="opg-icon-check opg-margin-small-right"></i>Register</a> 
 	</div>
	<?php
	    } 
	}
	?>
    <div class="opg-width-1-1"> 
	
	   <?php  
	   if($user->id == 0) 
	   { 
	   
	         
	  		 if (VmConfig::get('oncheckout_only_registered') == 0)
	  		 {
	   	 	?>
			<strong id="guesttitle" class="opg-h4 opg-margin-top opg-margin-bottom"><?php echo JText::_('PLG_SYSTEM_VMUIKIT_ONEPAGE_GUEST_CHECKOUT') ?></strong>
			<strong id="regtitle" class="opg-h4 opg-margin-top opg-margin-bottom" style="display:none"><?php echo JText::_('PLG_SYSTEM_VMUIKIT_ONEPAGE_REG_CHECKOUT') ?></strong> 
	   	 	<?php
	   		 }
			 else if(VmConfig::get('oncheckout_show_register') == 0)
			 {
			 ?>
			    <strong id="regtitle" class="opg-h4" style=""><?php echo JText::_('PLG_SYSTEM_VMUIKIT_ONEPAGE_GUEST_CHECKOUT') ?></strong>
			 <?php
			 }
			 else
			 {
			 $regchecked = 'checked="checked"';
		    ?>
			    <strong id="regtitle" class="opg-h4" style=""><?php echo JText::_('PLG_SYSTEM_VMUIKIT_ONEPAGE_REG_CHECKOUT') ?></strong>
	   <?php }
	   }
	   else
	   {
	   ?>

	   <?php
	   }
	   ?>
    
	<label class="opg-text-small opg-hidden" > 
    <input class="inputbox opg-hidden" type="checkbox" <?php echo $regchecked; ?> name="register" id="register" value="1" onclick="toggle_register(this.checked);" />
	<?php echo JText::_('COM_VIRTUEMART_USER_FORM_EDIT_BILLTO_LBL'); ?>&nbsp;<?php echo JText::_('COM_VIRTUEMART_REGISTER'); ?>
	</label>
  
    <?php
	if (VmConfig::get('oncheckout_only_registered') == 1)
	{
	  if($user->id == 0) 
	  {
	    $disvar = "";
	  }
	  else
	  {
	    $disvar = "display:none;";
	  }
	}
	else
	{
	  $disvar = "display:none;";
	}

		$userFields=array('agreed','name','username','password','password2');
		echo '<div id="div_billto">';
		echo '<table class="adminform opg-table"  id="table_user" style="'.$disvar.' ">' . "\n";

		foreach($this->helper->BTaddress["fields"] as $_field) {
		
			if(!in_array($_field['name'],$userFields)) {
				continue;
			}
			if($_field['name']=='agreed') {
				continue;
			}
	    	echo '		<tr>' . "\n";
		    echo '			<td class="key">' . "\n";
			if($_field['type'] == "select")
	        {	
		      echo '				<label class="' . $_field['name'] . '" for="' . $_field['name'] . '_field">' . "\n";
		      echo '					' . $_field['title'] . ($_field['required'] ? ' *' : '') . "\n";
		      echo '				</label>';
			}
			else
			{
			 $_field['formcode']=str_replace('<input','<input placeholder="'.$_field['title'].'"' ,$_field['formcode']);
			  $_field['formcode']=str_replace('size="30"','' ,$_field['formcode']);
			}
		    echo '				' . $_field['formcode'] . "\n";
		    echo '			</td>' . "\n";
		    echo '		</tr>' . "\n";
		}
		echo '<tr><td><hr /></td></tr>';
		echo '	</table>' . "\n";
		echo '	<table class="adminform opg-table" id="table_billto" style="margin:0;">' . "\n";

		foreach($this->helper->BTaddress["fields"] as $_field) {
         
		 if($_field['formcode'] != "")
		 {
		  
		    if($_field['name']=='customer_note') {
	          continue;
			}
			if($_field['name']=='virtuemart_country_id') {
	          continue;
			}
		
			if(in_array($_field['name'],$userFields)) {
				continue;
			}
			
			
			echo '		<tr>' . "\n";
		    echo '			<td class="key">' . "\n";
			if($_field['type'] == "select")
	        {	
		    echo '				<label class="' . $_field['name'] . '" for="' . $_field['name'] . '_field">' . "\n";
		    echo '					' . $_field['title'] . ($_field['required'] ? ' *' : '') . "\n";
		    echo '				</label>';
			}
			else
			{
			 $_field['formcode']=str_replace('<input','<input placeholder="'.$_field['title'].'"' ,$_field['formcode']);
			 $_field['formcode']=str_replace('size="30"','' ,$_field['formcode']);
			}

		    if($_field['name']=='zip') {
		    	$_field['formcode']=str_replace('input','input onchange="javascript:update_form();"',$_field['formcode']);
		    } 

			else if($_field['name']=='virtuemart_country_id') {
			/*
		    	$_field['formcode']=str_replace('<select','<select onchange="javascript:update_form();"',$_field['formcode']);
				$_field['formcode']=str_replace('vm-chzn-select','',$_field['formcode']);
				*/
				
		    } else if($_field['name']=='virtuemart_state_id') {
			
		    	$_field['formcode']=str_replace('<select','<select onchange="javascript:update_form();"',$_field['formcode']);
				if($_field['required'])
				{
				  $_field['formcode']=str_replace('vm-chzn-select','required',$_field['formcode']);
				}
				else
				{
				   $_field['formcode']=str_replace('vm-chzn-select','',$_field['formcode']);
				} 
				
		    }
			else if($_field['name']=='title') {
				$_field['formcode']=str_replace('vm-chzn-select','',$_field['formcode']);
		    }
			
		    echo '				' . $_field['formcode'] . "\n";
		    echo '			</td>' . "\n";
		    echo '		</tr>' . "\n";
	      }
		}
	    echo '	</table>' . "\n";
	    echo '</div>';
		?>
  </div>
  <div class="opg-width-1-1 opg-margin-top" id="div_shipto"> 
    <div class="output-shipto">
	
		  
     <div class="opg-width-1-1">
		 <a id="shiptobutton" class="opg-button opg-width-1-1" href="#shiptopopup" data-opg-modal><i id="shiptoicon" style="display:none;" class="opg-icon opg-icon-check opg-margin-right"></i><?php echo JText::_('PLG_SYSTEM_VMUIKIT_CHANGE_SHIP_ADDRESS'); ?></a>
	 </div>
	
	
	<div id="shiptopopup" class="opg-modal"><!-- Shipto Modal Started -->
	 <div class="opg-modal-dialog"><!-- Shipto Modal Started -->
		<a class="opg-modal-close opg-close"></a>
    	   <div class="opg-modal-header"><strong><?php echo JText::_('PLG_SYSTEM_VMUIKIT_CHANGE_SHIP_ADDRESS_HEADING'); ?></strong></div>
      <label class="opg-text-small opg-hidden">
	  <?php 
	    $samebt = "";
		if($this->cart->STsameAsBT == 0)
		{
			$samebt = '';
			$shiptodisplay = "";
			
		}
	    else if($params->get('check_shipto_address') == 1)
		{
			$samebt = 'checked="checked"';
		}
		else
		{
		   $samebt = '';
		   $shiptodisplay = "";
		}
	  ?> 
      <input class="inputbox" class="opg-hidden" type="checkbox" name="STsameAsBT" checked="checked" id="STsameAsBT" value="1" onclick="set_st(this);"/>
	  
	  <?php
		if(!empty($this->cart->STaddress['fields'])){
			if(!class_exists('VmHtml'))require(JPATH_VM_ADMINISTRATOR.DS.'helpers'.DS.'html.php');
				echo JText::_('COM_VIRTUEMART_USER_FORM_ST_SAME_AS_BT');
		?>
		</label>
      <?php
		}
 		?>

    <?php if(!isset($this->cart->lists['current_id'])) $this->cart->lists['current_id'] = 0; ?>
    <?php
		echo '	<table class="adminform  opg-table" id="table_shipto" style="'.$shiptodisplay.'">' . "\n";
		

		foreach($this->helper->STaddress["fields"] as $_field) {
		  echo '		<tr>' . "\n";
	      echo '			<td class="key">' . "\n";
	     if($_field['type'] == "select")
	      {		
		    echo '				<label class="' . $_field['name'] . '" for="' . $_field['name'] . '_field">' . "\n";
		    echo '					' . $_field['title'] . ($_field['required'] ? ' *' : '') . "\n";
		    echo '				</label>';
		  }
		  else
		  {
		    $_field['formcode']=str_replace('<input','<input placeholder="'.$_field['title'].'"' ,$_field['formcode']);
		  }
		 
		  
		
    if($_field['name']=='shipto_zip') {
      	  $_field['formcode']=str_replace('input','input onchange="javascript:update_form();"',$_field['formcode']);

    } 
	else if($_field['name']=='customer_note') {
	 
	}
	else if($_field['name']=='shipto_virtuemart_country_id') {
		    	$_field['formcode']=str_replace('<select','<select onchange="javascript:update_form();add_countries();"',$_field['formcode']);
		    	$_field['formcode']=str_replace('class="virtuemart_country_id','class="shipto_virtuemart_country_id',$_field['formcode']);
				$_field['formcode']=str_replace('vm-chzn-select','',$_field['formcode']);

    } else if($_field['name']=='shipto_virtuemart_state_id') {

    	$_field['formcode']=str_replace('id="virtuemart_state_id"','id="shipto_virtuemart_state_id"',$_field['formcode']);
		    	$_field['formcode']=str_replace('<select','<select onchange="javascript:update_form();"',$_field['formcode']);
				if($_field['required'])
				{
				  $_field['formcode']=str_replace('vm-chzn-select','required',$_field['formcode']);
				}
				else
				{
				   $_field['formcode']=str_replace('vm-chzn-select','',$_field['formcode']);
				} 
	    }
    echo '				' . $_field['formcode'] . "\n";

    echo '			</td>' . "\n";
	echo ' </tr>';


 
}

    echo '</tr>	</table>' . "\n";

		?>
	  <div class="opg-modal-footer">
	  	 <a class="opg-button opg-button-primary" href="Javascript:void(0);" onclick="validateshipto();"><?php echo JText::_("PLG_SYSTEM_VMUIKIT_ONEPAGE_SUBMIT"); ?></a>
		 <a id="shiptoclose" class="opg-modal-close opg-button"><?php echo JText::_("PLG_SYSTEM_VMUIKIT_ONEPAGE_CANCEL"); ?></a>
		 
		 <a id="shiptoclose" onclick="removeshipto();" class="opg-modal-close opg-margin-left opg-button opg-button-danger"><?php echo JText::_("PLG_SYSTEM_VMUIKIT_ONEPAGE_REMOVE_SHIPTO"); ?></a>
	  </div>
    </div> <!-- Shipto Modal ended -->
</div><!-- Shipto Modal ended -->
		
  </div>
  <div class="clear"></div>
</div>
</div>