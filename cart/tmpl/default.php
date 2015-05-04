<?php

/**

*
 
* Layout for the shopping cart

*

* @package	VirtueMart

* @subpackage Cart

* @author Max Milbers

*

* @link http://www.virtuemart.net

* @copyright Copyright (c) 2004 - 2010 VirtueMart Team. All rights reserved.

* @license http://www.gnu.org/copyleft/gpl.html GNU/GPL, see LICENSE.php

* VirtueMart is free software. This version may have been modified pursuant

* to the GNU General Public License, and as distributed it includes or

* is derivative of works licensed under the GNU General Public License or

* other free or open source software licenses.

* @version $Id: cart.php 2551 2010-09-30 18:52:40Z milbo $

*/


// Check to ensure this file is included in Joomla!

defined('_JEXEC') or die('Restricted access');

JHTML::script('facebox.js', 'components/com_virtuemart/assets/js/', false);
JHTML::stylesheet('facebox.css', 'components/com_virtuemart/assets/css/', false);

JHTML::script('plugins/system/onepage_generic/onepage_generic.js');
JHTML::stylesheet ( 'plugins/system/onepage_generic/onepage_generic.css');


$taskRoute = "";

vmJsApi::jPrice();

require_once dirname(__FILE__).DS.'helper.php';

$this->helper=new CartHelper();
$this->helper->assignValues();
$plugin=JPluginHelper::getPlugin('system','onepage_generic');
$params=new JRegistry($plugin->params);
if($params->get("buttoncolour") != "")
{
  ?>
  <style type="text/css">
  .opg-button-primary
  {
    background:<?php echo $params->get("buttoncolour"); ?> !important;
  }
  
  </style>
  <?php
}


JFactory::getLanguage()->load('plg_system_onepage_generic',JPATH_ADMINISTRATOR);

// required for all JS there

$userFieldsModel = VmModel::getModel('userfields');



JHtml::_('behavior.formvalidation');

$document = JFactory::getDocument();
$document->addScriptDeclaration("

	jQuery(document).ready(function($) {

		$('div#full-tos').hide();

		$('span.terms-of-service').click( function(){

			//$.facebox({ span: '#full-tos' });

			$.facebox( { div: '#full-tos' }, 'my-groovy-style');

		});

	});

");


$document->addStyleDeclaration('#facebox .content {display: block !important; height: 480px !important; overflow: auto; width: 560px !important; }');

  $document->addScriptDeclaration("
      //<![CDATA[ 
      window.CARTPAGE = 'yes';
      //]]>
      ");


?>


<script type="text/javascript">

window.lastvalue = 1;

jQuery(document).ready(function($) {
  Virtuemart.product($("div.product"));
});
var preloader_visible=false;

function add_countries() {

	new Request.JSON({

		'url':'index.php?option=com_virtuemart&view=state&format=json&virtuemart_country_id='+document.id('shipto_virtuemart_country_id').value,

		'async':false,

		'noCache':true,

		'onSuccess':function(json,text) {

			document.id('shipto_virtuemart_state_id').options.length=1;

			if(document.id('shipto_virtuemart_state_id').getElements('optgroup')[0]) {

				document.id('shipto_virtuemart_state_id').getElements('optgroup')[0].destroy();

			}

			var states=json[+document.id('shipto_virtuemart_country_id').value];

			if(states.length) {

				var optgroup=new Element('optgroup',{

					'label':document.id('shipto_virtuemart_country_id').options[document.id('shipto_virtuemart_country_id').selectedIndex].text

				});

				document.id('shipto_virtuemart_state_id').grab(optgroup);

				

				

				states.each(function(item) {

					optgroup.grab(new Element('option',{

						'value':item.virtuemart_state_id,

						'text':item.state_name

					}));

				});

			}

		}

	}).send();

}


<?php

if($params->get('preloader',0)==1) {

	?>

	window.addEvent('domready',function() {

		var preloader=new Element('div',{

			'id':'preloader'

		});

		document.getElementsByTagName('body')[0].appendChild(preloader);

		var img=new Element('img',{

			'src':'<?php echo JFactory::getUri()->base(); ?>plugins/system/vmuikit_onepage/images/loader.gif',

			'id':'preloader_img'

		});

		preloader.grab(img);

		preloader.setStyle('display','none');

	});	

	<?php

}



?>
jQuery(document).ready(function(){

jQuery('#shiptopopup').on({

    'show.uk.modal': function()
	{
	    document.id('STsameAsBT').checked = false;
    },
    'hide.uk.modal': function(){
	   value = validateshipto("yes");
       
	   if(value == true)
	   {
	      
	   }
	   else
	   {
	     jQuery("#shiptoicon").hide();
	     jQuery("#shiptobutton").removeClass("opg-button-primary");
	     document.id('STsameAsBT').checked=true;
	   }
    }
});

});

function validatecomment()
{
  if(jQuery("#commentpopup #customer_note_field").hasClass("required"))
  {
  
     comval = jQuery("#commentpopup #customer_note_field").val();
	 if(comval == "")
	 {
	    jQuery("#commentpopup #customer_note_field").addClass("opg-form-danger");
		jQuery("#commenticon").hide();
		jQuery("#commentbutton").removeClass("opg-button-primary");
	 }
	 else
	 {
	 	 jQuery("#commenticon").show();
		 jQuery("#commentbutton").addClass("opg-button-primary");
	     jQuery("#commentpopup #customer_note_field").removeClass("opg-form-danger");
	     update_form();   
    	 jQuery("#commentclose").click();
	 }

  }
  else
  {
  	comval = jQuery("#commentpopup #customer_note_field").val();
	 if(comval == "")
	 { 
		 jQuery("#commenticon").hide();
		 jQuery("#commentbutton").removeClass("opg-button-primary");
		 update_form();   
	     jQuery("#commentclose").click();
	 }
	 else
	 {	
		 jQuery("#commenticon").show();
		 jQuery("#commentbutton").addClass("opg-button-primary");
	     jQuery("#commentpopup #customer_note_field").removeClass("opg-form-danger");
    	 update_form();   
	     jQuery("#commentclose").click();
	 }
  }
}

function removeshipto()
{
	document.id('table_shipto').getElements('input').each(function(el) 
	    {
			  elementid = el.id;
			  jQuery("#"+elementid).val("");
		});
     document.id('STsameAsBT').checked = true;
	 update_form();   
     jQuery("#shiptoclose").click();
}

function validateshipto(returnval)
{
	var valid=true;
	if(document.id('STsameAsBT').checked==true)
	{
	   jQuery("#shiptoicon").hide();
       jQuery("#shiptobutton").removeClass("opg-button-primary");
	  
	}
	else
	{
		var validator=new JFormValidator();
		document.id('table_shipto').getElements('input').each(function(el) {
			var cval=validator.validate(el);;
			elementid = el.id;
			if(cval == false)
			{
			  jQuery("#"+elementid).addClass("opg-form-danger");
			}
			else
			{
			  jQuery("#"+elementid).removeClass("opg-form-danger");
			}
			valid=valid && cval;

		});
		
		 country_ele2 = document.id('shipto_virtuemart_country_id');
		 if(country_ele2 != null)
		 {
	    	 var cval3 =validator.validate(country_ele2);
			 if(cval3 == false)
			 {
				  jQuery("#shipto_virtuemart_country_id").addClass("opg-form-danger");
		 	 }
			 else
			 {
			  jQuery("#shipto_virtuemart_country_id").removeClass("opg-form-danger");
		  	 }
			 valid=valid && cval3;
		 }
		 state_ele2 = document.id('shipto_virtuemart_state_id');
		 if(state_ele2 != null)
		 {
	    	 var cval4=validator.validate(state_ele2);
			 if(cval4 == false)	
			 {
				  jQuery("#shipto_virtuemart_state_id").addClass("opg-form-danger");
		 	 }	
			 else
			 {
				  jQuery("#shipto_virtuemart_state_id").removeClass("opg-form-danger");
		  	 }
			 valid=valid && cval4;
		 }
	}
	if(returnval == "yes")
	{
	   return valid;
	}
	if(!valid) 
	{
	     jQuery("#shiptoicon").hide();
	     jQuery("#shiptobutton").removeClass("opg-button-primary");
		 return false;
	}
	else
	{
	   jQuery("#shiptoicon").show();
	   jQuery("#shiptobutton").addClass("opg-button-primary");
	   update_form();   
	   jQuery("#shiptoclose").click();
	}
}

function create_preloader() {

	<?php
	
	if($params->get('preloader',0)==1) {

		?>

		document.id('preloader').setStyle('display','');

		document.id('preloader').setStyle('height',Math.max( document.body.scrollHeight, document.body.offsetHeight,document.documentElement.clientHeight, document.documentElement.scrollHeight, document.documentElement.offsetHeight ));

		document.id('preloader_img').position('center');

		preloader_visible=true;

		<?php

	}

	?>

}

window.addEvent('domready',function() {

    if(document.id('zip_field') && document.id('zip_field').value=='1') {

	document.id('zip_field').setProperty('value',"");

    }

    var shipments_checked=false;

    if(document.id('shipments')) {

        for(var i=0;i<document.id('shipments').getElements('input').length;i++) {

            if(document.id('shipments').getElements('input')[i].checked==true) {

                shipments_checked=true;

                break;

            }   

        }

			if(shipments_checked == false)
			{
				 if(document.id('shipments').getElements('input').length > 1)
				  {
					  document.id('shipments').getElements('input')[0].checked=true;
					  update_form();
				  }
			}
    }



    update_form(false);

//document.id('system-message-container').setAttribute('style','display:none');
jQuery(".opg-alert").hide();
jQuery("#system-message-container").hide();
});



function remove_preloader() {

	<?php

	if($params->get('preloader',0)==1) {

		?>

		document.id('preloader').setStyle('display','none');

	 	<?php

	}

	?>

}



<?php if(!empty($this->cart->STaddress['fields'])){ ?>

function set_st(item) {

	if(item.checked) {

		

	} else {

		document.id('table_shipto').style.display='';

	}

	update_form();

} 

<?php }; ?>



function toggle_register(state) {

	if(state) {

		document.id('table_user').setStyle('display','');

	} else {

		document.id('table_user').setStyle('display','none');

	}

}
function changecheckout(val)
{

 if(val == 1)
  {
    jQuery("#regtitle").slideUp();
	jQuery("#guesttitle").slideDown();

	jQuery("#guestchekcout").addClass("opg-button-primary");
	jQuery("#regcheckout").removeClass("opg-button-primary");
	jQuery("#regicon").removeClass("opg-icon-check");
	jQuery("#guesticon").addClass("opg-icon-check");
	if(window.lastvalue == 2)
	{
	   jQuery("#register").click();
	   window.lastvalue = 1;
	}
    
  }
  if(val == 2)
  {
     jQuery("#regtitle").slideDown();
	 jQuery("#guesttitle").slideUp();
	 
	 jQuery("#guestchekcout").removeClass("opg-button-primary");
	 jQuery("#regcheckout").addClass("opg-button-primary");
	 jQuery("#regicon").addClass("opg-icon-check");
	 jQuery("#guesticon").removeClass("opg-icon-check");
	 if(window.lastvalue == 1)
	 {
	   jQuery("#register").click();
	   window.lastvalue = 2;
	 }
  }
}

function changemode(val)
{
  if(val == 1)
  {
    jQuery("#logindiv").slideDown();
	jQuery("#loginbtn").addClass("opg-button-primary");
	jQuery("#regbtn").removeClass("opg-button-primary");
	jQuery("#old_payments").slideUp();
	jQuery("#other-things").slideUp();
    
  }
  if(val == 2)
  {
     jQuery("#logindiv").slideUp();
	 jQuery("#loginbtn").removeClass("opg-button-primary");
	 jQuery("#regbtn").addClass("opg-button-primary");
	 jQuery("#old_payments").slideDown();
	 jQuery("#other-things").slideDown();
	 
	 
  }
}



function set_coupon() {

	create_preloader();

	new Request.JSON({

		'url':'index.php?type=onepage&opc_task=set_coupon',

		'noCache':true,

		'method':'post',

		'data':'coupon='+document.id('coupon_code').value,

		'onSuccess':function(json,text) {

			var cart=json;

			if(json.error) {

				alert(json.message);


			}

			if (json.success) {

				alert(json.message);

			}

			


			remove_preloader();

			update_form();

		}

	}).send();

}

function strip_tags(str, allow) {
  // making sure the allow arg is a string containing only tags in lowercase (<a><b><c>)
  allow = (((allow || "") + "").toLowerCase().match(/<[a-z][a-z0-9]*>/g) || []).join('');

  var tags = /<\/?([a-z][a-z0-9]*)\b[^>]*>/gi;
  var commentsAndPhpTags = /<!--[\s\S]*?-->|<\?(?:php)?[\s\S]*?\?>/gi;
  return str.replace(commentsAndPhpTags, '').replace(tags, function ($0, $1) {
    return allow.indexOf('<' + $1.toLowerCase() + '>') > -1 ? $0 : '';
  });
}
function ajaxlogin()
{
 jQuery("#modlgn-username").removeClass("opg-form-danger");
 jQuery("#modlgn-passwd").removeClass("opg-form-danger");
 usernameval = document.getElementById("modlgn-username").value;
 passwordval = document.getElementById("modlgn-passwd").value;
 returnurlval = document.getElementById("returnurl").value;
 loginempty = document.getElementById("loginempty").value; 
 loginerror = document.getElementById("loginerrors").value; 

 if(usernameval == "" || passwordval == "")
 {
   if(usernameval == "")
   {
     jQuery("#modlgn-username").addClass("opg-form-danger");
   }
   if(passwordval == "")
   {
     jQuery("#modlgn-passwd").addClass("opg-form-danger");
   }
    var r = '<div class="opg-alert opg-alert-danger" data-opg-alert><a href="" class="opg-alert-close opg-close"></a><p>' + loginempty + "</p></div>";
	jQuery("#loginerror").show();
	jQuery("#loginerror").html(r);
 }
  else
  {
     jQuery("#loginerror").hide();
     var url= vmSiteurl+"index.php?type=onepage";
	 url += "&opc_task=login&username=" + encodeURIComponent(usernameval) + "&passwd=" + encodeURIComponent(passwordval) + "&return=" + encodeURIComponent(returnurlval); 
	 
	  jQuery.ajax({
        	type: "POST",
	        cache: false,
    	    url: url,
	       }).done(
			function (data, textStatus) 
			{
			  if(data == "error")
			  {
			     jQuery("#modlgn-username").addClass("opg-form-danger");
				 jQuery("#modlgn-passwd").addClass("opg-form-danger");
				 var r = '<div class="opg-alert opg-alert-danger" data-opg-alert><a href="" class="opg-alert-close opg-close"></a><p>' + loginerror + "</p></div>";
				 jQuery("#loginerror").show();
				 jQuery("#loginerror").html(r);
				 
			  }
			  else
			  {
			    window.location.reload();
			  }
		    });
		 		
  }
}


function update_form(task,id,payment) {

	var did=id;

	if(task=='update_product') {

		if(document.id('quantity_'+id).value<=0) {

			return alert('<?php echo Jtext::_('PLG_VM2_ONEPAGECHECKOUT_NEGATIVE'); ?>');

		}

	}

	var update_address=true;

        if(task==false) {

            update_address=false;

        }

	create_preloader();

	var url= vmSiteurl+"index.php?type=onepage";

	if(!task) {

		var task='update_form';

	}
	if(task  == "custom")
	{
	  var task='update_form';
	  custask  = "yes";
	}
	else if(task == "updatesproduct")
	{ 
	  var task='update_form';
	  cusupdate = "yes";
	}
	else
	{
	  custask =  "";
	  cusupdate = "";
	}

	url+='&opc_task='+task;

	if(id) {

		url+='&id='+id;

	}

	if(task=='update_product') {

		url+='&quantity='+document.id('quantity_'+id).value;

	}
	if(update_address==false) {

            url+='&update_address=false';

        }





	if (typeof window._klarnaCheckout != 'undefined') {

		window._klarnaCheckout(function (api) {

        	api.suspend();

    	});

	};

	new Request.JSON({

		'url':url,

		'method':'post',

		'noCache':true,

		'data':document.id('checkoutForm').toQueryString(),

		'onSuccess':function(json,text) {

			if(json.error) {

				remove_preloader();

				alert(json.message);

			} else {
			
			   jQuery("#customerror").hide();
			   
			   if(cusupdate=='yes') 
			   {

				   updatemsg = "<?php echo JText::_('COM_VIRTUEMART_PRODUCT_UPDATED_SUCCESSFULLY'); ?>";
				   var r = '<div class="opg-alert opg-alert-success" data-opg-alert><a href="" class="opg-alert-close opg-close"></a><p>' + updatemsg + "</p></div>";
				   
				   jQuery("#customerror").html("");
				   
				   jQuery("#customerror").show();
				   
				   jQuery("#customerror").html(r);
				}

				Virtuemart.productUpdate(jQuery('.vmCartModule'));

				if(task=='remove_product') {
				
				   deletemsg = "<?php echo JText::_('COM_VIRTUEMART_PRODUCT_REMOVED_SUCCESSFULLY'); ?>";
				   var r = '<div class="opg-alert opg-alert-warning" data-opg-alert><a href="" class="opg-alert-close opg-close"></a><p>' + deletemsg + "</p></div>";
				   
				   jQuery("#customerror").html("");
				   
				   jQuery("#customerror").show();
				   
				   jQuery("#customerror").html(r);
				   

					document.id('product_row_'+did).destroy();

					mod=jQuery(".vmCartModule");

					jQuery.getJSON(vmSiteurl+"index.php?option=com_virtuemart&nosef=1&view=cart&task=viewJS&format=json"+vmLang,

						function(datas, textStatus) {

							if (datas.totalProduct >0) {

								mod.find(".vm_cart_products").html("");

								jQuery.each(datas.products, function(key, val) {

									jQuery("#hiddencontainer .container").clone().appendTo(".vmCartModule .vm_cart_products");

									jQuery.each(val, function(key, val) {

										if (jQuery("#hiddencontainer .container ."+key)) mod.find(".vm_cart_products ."+key+":last").html(val) ;

									});

								});

								mod.find(".total").html(datas.billTotal);

								mod.find(".show_cart").html(datas.cart_show);

							} else {
							
							    window.location.reload();

								mod.find(".vm_cart_products").html("");

								mod.find(".total").html(datas.billTotal);

							}

							mod.find(".total_products").html(datas.totalProductTxt);

						}

					);

				}

				

     for(var id in json.price.products) {
	 

                    <?php if ( VmConfig::get('show_tax')) { ?>

                    if ( document.id('subtotal_tax_amount_'+id)) {

                        document.id('subtotal_tax_amount_'+id).set('text',json.price.products[id].subtotal_tax_amount);

                    }

                    <?php } ?>

                    if ( document.id('subtotal_discount_'+id) ) {

                        document.id('subtotal_discount_'+id).set('text',json.price.products[id].subtotal_discount);

                    }

                    if ( document.id('subtotal_with_tax_'+id) ) {

                        document.id('subtotal_with_tax_'+id).set('html',json.price.products[id].subtotal_with_tax);

                    }

                }

				

				<?php if ( VmConfig::get('show_tax')) { ?>

					//document.id('tax_amount').set('text',json.price.taxAmount);

				<?php } ?>

				//document.id('discount_amount').set('text',json.price.discountAmount);
				
				if(json.price.salesPrice != "")
				{
				    jQuery("sales_pricedivfull").show();
					document.id('sales_price').set('text',json.price.salesPrice);
			    }
				else
				{
				   jQuery("sales_pricedivfull").hide();
				}

				

				<?php if ( VmConfig::get('show_tax')) { ?>

					document.id('shipment_tax').set('text',json.price.shipmentTax);

				<?php } ?>
				
				if(json.price.salesPriceShipment != "")
				{
				    jQuery("#shipmentdivfull").show();
					document.id('shipment').set('text',json.price.salesPriceShipment);
			    }
				else
				{
				   jQuery("#shipmentdivfull").hide();
				}

				

				<?php if ( VmConfig::get('show_tax')) { ?>

					document.id('payment_tax').set('text',json.price.paymentTax);

				<?php } ?>

				document.id('payment').set('text',json.price.salesPricePayment);

				

				<?php if ( VmConfig::get('show_tax')) { ?>
				
				   if(json.price.billTaxAmount != "")
				   {
				    jQuery("#total_taxdivfull").show();
					document.id('total_tax').set('text',json.price.billTaxAmount);
				   }
				   else
				   {
				     jQuery("#total_taxdivfull").hide();
				   }

				<?php } ?>

				<?php if(!empty($this->cart->pricesUnformatted['billDiscountAmount'])) { ?>
				
				if(json.price.billDiscountAmount != "")
				{
				    jQuery("#total_amountdivfull").show();
					document.id('total_amount').set('text',json.price.billDiscountAmount);
			    }
				else
				{
				   jQuery("#total_amountdivfull").hide();
				}
				 
				<?php } ?>
				
				if(json.price.billTotal != "")
				{
				    jQuery("#bill_totalamountdivfull").show();
					jQuery("#bottom_total").show();
					document.id('bill_total').set('text',json.price.billTotal);
					document.id('carttotal').set('text',json.price.billTotal);
				}
				else
				{
				    jQuery("#bill_totalamountdivfull").hide();
					jQuery("#bottom_total").hide();
				}
				

				document.id('shipments').empty();

				var shipments="";
				
				
				if(json.shipments.length == 0)
				{
				     jQuery("#shipment_fulldiv").html("");
					 newhtml = '<p id="shipmentnill" class="opg-text-warning"></p>';
					 jQuery("#shipment_fulldiv").html(newhtml);
					 
				     country_ele = document.id('virtuemart_country_id');
				     if(country_ele != null)
					 {
					    var validator=new JFormValidator();
					     var cval2 =validator.validate(country_ele);
						 if(cval2 == false)
						 {
							  shipmentnil  = "<?php echo JText::_("PLG_SYSTEM_VMUIKIT_CHOOSE_COUNTRY"); ?>";
							  jQuery("#shipmentnill").html("");
							  jQuery("#shipmentnill").html(shipmentnil); 
 		 				 } 
						 else
						 {
							  shipmentnil  = "<?php echo vmInfo('COM_VIRTUEMART_NO_SHIPPING_METHODS_CONFIGURED', ''); ?>";
							  jQuery("#shipmentnill").html("");
							  jQuery("#shipmentnill").html(shipmentnil);
					  	 }
					 }
					 else
					 {
						  shipmentnil  = "<?php echo vmInfo('COM_VIRTUEMART_NO_SHIPPING_METHODS_CONFIGURED', ''); ?>";
						  jQuery("#shipmentnill").html("");
						  jQuery("#shipmentnill").html(shipmentnil);
					 }
				}
				else
				{
					 jQuery("#shipment_fulldiv").html("");
					 newhtml = '<table class="opg-table opg-table-striped" id="shipmenttable"><tr id="shipmentrow"><td id="shipmentdetails"></td></tr></table>';
					 jQuery("#shipment_fulldiv").html(newhtml);
				}
				
				

				if(json.shipments)
				{
				 
				    shipments+= '<ul class="opg-list" id="shipment_ul">';

				    for(var i=0;i<json.shipments.length;i++) {
					
					   inputstr = json.shipments[i].toString();
					   var n = inputstr.search("checked"); 

					   if(n > 0)
					   {
					     var activeclasss = "liselected";
					   }
					   else
					   {
					     var activeclasss = "";
					   }
					   
					   if(activeclasss != "")
					   {
						  texxt = json.shipments[i];
						  tmptxt = strip_tags(texxt, '<span><img>');
						  tmptxt = tmptxt.replace('</span><span', '</span><br /><span');
						  tmptxt = tmptxt.replace('vmshipment_description', 'vmshipment_description opg-text-small');
						  tmptxt = tmptxt.replace('vmshipment_cost', 'vmshipment_cost opg-text-small');
						  document.id('shipmentdetails').set('html', tmptxt);
						  
						  if(json.shipments.length > 1)
						  {
						    if(document.getElementById("shipchange") == null)
							{
							     jQuery("#shipchangediv").remove();
							     temptext = "";
							  	 temptext =  '<td id="shipchangediv" class="opg-width-1-4">';
						         temptext += '<a class="opg-button opg-button-primary" href="#shipmentdiv" data-opg-modal>';
								 temptext += '<?php echo  JText::_("PLG_SYSTEM_VMUIKIT_ONEPAGE_CHNAGE"); ?>';
								 temptext += '</a></td>';
								 jQuery("#shipmentrow").append(temptext);
						    }
						  }
						  else
						  {
						    jQuery("#shipchangediv").remove();
						  }
					   } 
					    texxts = "";
						texxts = json.shipments[i];
						texxts = strip_tags(texxts, '<span><img><input>');
						texxts = texxts.replace('</span><span', '</span><br /><span');
						texxts = texxts.replace('vmshipment_description', 'vmpayment_description opg-text-small');
						texxts = texxts.replace('vmshipment_cost', 'vmpayment_cost opg-text-small');
					
                        shipments+='<li class="'+activeclasss+'">';
				    	//shipments+=json.shipments[i].toString().replace('input','input onclick="javascript:update_form();"');
						shipments+='<label class="opg-width-1-1">'+texxts+'</label>';
						shipments+='<hr class="opg-margin-small-bottom opg-margin-small-top" /></li>';

				    }
					shipments+='</ul>';
					
					if(custask  == "yes")
					{
					 update_form();
					}
					document.id('shipments').set('html','');
					jQuery("#shipmentclose").click();
				    document.id('shipments').set('html',shipments);
				}
				
				
			
				
			var shipmentchecked=false;
			if(document.id('shipments')) 
			{
				for(var i=0;i<document.id('shipments').getElements('input').length;i++) 
				{
					if(document.id('shipments').getElements('input')[i].checked==true) 
					{
						shipmentchecked=true;
						break;
	    		    }	
			    }
			}
			if(shipmentchecked == false)
			{
			
				 if(document.id('shipments').getElements('input').length > 1)
				  {
				     autoshipid = document.getElementById("auto_shipmentid").value;
					 if(autoshipid > 0)
					 {
					    jQuery("#shipments #shipment_id_"+autoshipid).attr('checked', true);
						update_form();

					 }
					 else
					 {
					  document.id('shipments').getElements('input')[0].checked=true;
					  update_form();
					 }
				  }
				  else  if(document.id('shipments').getElements('input').length > 0)
				  {
				      document.id('shipments').getElements('input')[0].checked=true;
					  update_form();
				  }
				  
			}
			
			jQuery("#couponpricediv").hide();

				<?php if ( VmConfig::get('coupons_enable')) { ?>
				

					var ctext = json.price.couponCode;

					if (json.price.couponDescr != '') {

						ctext += ' (' + json.price.couponDescr + ')';

					}

					if (json.price.salesPriceCoupon) {

						document.id('coupon_code_txt').set('html', ctext);
						 jQuery("#couponpricediv").show();

					} else {

						document.id('coupon_code_txt').set('text', '');
						jQuery("#couponpricediv").hide();

					}

					

					<?php if ( VmConfig::get('show_tax')) { ?>

						if(json.price.couponTax) {

							document.id('coupon_tax').set('text',json.price.couponTax);

						} else {

							document.id('coupon_tax').set('text','');

						}

					<?php } ?>

						if(json.price.salesPriceCoupon) {

							document.id('coupon_price').set('text',json.price.salesPriceCoupon);

						} else {

							document.id('coupon_price').set('text','');

						}

				<?php }  ?>

					if(task=='update_product') {
						update_form("updatesproduct");
					}

				document.id('paymentsdiv').empty();
				
				
				if(json.paymentsnew.length == 0)
				{
				     jQuery("#payment_fulldiv").html("");
					 newhtml = '<p id="paymentnill" class="opg-text-warning"></p>';
					 jQuery("#payment_fulldiv").html(newhtml);
					 
				     country_ele = document.id('virtuemart_country_id');
				     if(country_ele != null)
					 {
					    var validator=new JFormValidator();
					     var cval2 =validator.validate(country_ele);
						 if(cval2 == false)
						 {
							  paymentnil  = "<?php echo JText::_("PLG_SYSTEM_VMUIKIT_CHOOSE_COUNTRY"); ?>";
							  jQuery("#paymentnill").html("");
							  jQuery("#paymentnill").html(paymentnil); 
 		 				 } 
						 else
						 {
							  paymentnil  = "<?php echo vmInfo('COM_VIRTUEMART_NO_PAYMENT_METHODS_CONFIGURED', ''); ?>";
							  jQuery("#paymentnill").html("");
							  jQuery("#paymentnill").html(paymentnil);
					  	 }
					 }
					 else
					 {
					 
					     paymentnil  = "<?php echo vmInfo('COM_VIRTUEMART_NO_PAYMENT_METHODS_CONFIGURED', ''); ?>";
						 jQuery("#paymentnill").html("");
						 jQuery("#paymentnill").html(paymentnil);
					 }
				}
				else
				{
					 jQuery("#payment_fulldiv").html("");
					 newhtml = '<table class="opg-table opg-table-striped" id="paymentable"><tr id="paymentrow"><td id="paymentdetails"></td></tr></table>';
					 jQuery("#payment_fulldiv").html(newhtml);
				}
				

				var payments="";

				if(json.paymentsnew) 
				{
				    payments+= '<ul class="opg-list" id="payment_ul">';
				    for(var i=0;i<json.paymentsnew.length;i++) 
					{
					      
						   inputstr = json.payments[i].toString();
						    var s = inputstr.search("klarna-checkout-container"); 
						   if(s > 0)
						   {
						      jQuery("#klarna-checkout-container").appendTo("#klarnadiv");
						   }
						   var n = inputstr.search("checked"); 
						   if(n > 0)
						   {
						      var activeclasss = "liselected";
					   	   }
					   	   else
					       {
					   		  var activeclasss = "";
					       }
						   if(activeclasss != "")
						   {
						      texxt = json.paymentsnew[i];
							  tmptxt = strip_tags(texxt, '<span><img><div>');
							  tmptxt = tmptxt.replace('</span><span', '</span><br /><span');
							  tmptxt = tmptxt.replace('vmpayment_description', 'vmpayment_description opg-text-small');
							  tmptxt = tmptxt.replace('vmpayment_cost', 'vmpayment_cost opg-text-small');
							  document.id('paymentdetails').set('html', tmptxt);
								  
						 	  if(json.paymentsnew.length > 1)
							  {	
							     if(document.getElementById("shipchange") == null)
							 	 {
								     jQuery("#paychangediv").remove();
							 	     temptext = "";
								  	 temptext =  '<td id="paychangediv" class="opg-width-1-4">';
							         temptext += '<a class="opg-button opg-button-primary" href="#paymentdiv" data-opg-modal>';
									 temptext += '<?php echo  JText::_("PLG_SYSTEM_VMUIKIT_ONEPAGE_CHNAGE"); ?>';
									 temptext += '</a></td>';
									 jQuery("#paymentrow").append(temptext);
							     }
						   	 }
						  	 else
						   	 {
						   		 jQuery("#paychangediv").remove();
						  	 }
						   } 
						    
						   texxts = "";
						   texxts = json.paymentsnew[i];
						   tmptxts = strip_tags(texxts, '<span><img><input>');
						   tmptxts = tmptxts.replace('</span><span', '</span><br /><span');
						   tmptxts = tmptxts.replace('vmpayment_description', 'vmpayment_description opg-text-small');
						   tmptxts = tmptxts.replace('vmpayment_cost', 'vmpayment_cost opg-text-small');
						   payments+='<li class="'+activeclasss+'">';
						   payments+='<label class="opg-width-1-1">'+tmptxts+'</label>';
						   payments+="<hr class='opg-margin-small-bottom opg-margin-small-top' /></li>";
						  //payments+=json.payments[i].toString().replace(/type="radio"/g,'type="radio" onclick="javascript:update_form(\'update_form\', 0, true);"')+'<br />';
			         }
					payments += "</ul>";
					
					if(custask  == "yes")
					{ 
					 update_form();
					}
					jQuery("#paymentclose").click();
				    document.id('paymentsdiv').set('html',payments);
			   }		
			   
			  
  

				

				jQuery("#paymentsdiv").find("script").each(function (idx, val) { jQuery.globalEval(val.text); });

};

				remove_preloader();

		
			paymentchecked  = false;
			if(document.id('paymentsdiv')) 
			{
		      for(var i=0;i<document.id('paymentsdiv').getElements('input').length;i++) 
			   {
				if(document.id('paymentsdiv').getElements('input')[i].checked==true)
				 {  
				   val_id = document.id('paymentsdiv').getElements('input')[i].value;
				   jQuery("#payments #payment_id_"+val_id).attr('checked', true);
				   document.id('paymentsdiv').getElements('input')[i].checked=true;
				   paymentchecked=true;
			 	   break;
			     }
			   }
			}
			
			
			if(paymentchecked == false)
			{
			  if(document.id('paymentsdiv').getElements('input').length > 1)
			  {
			     autopayid = document.getElementById("auto_paymentid").value;
				 if(autopayid > 0)
				 {
				   jQuery("#payments #payment_id_"+autopayid).attr('checked', true);
				   jQuery("#paymentsdiv #payment_id_"+autopayid).attr('checked', true);
				   checkpaymentval();
				 }
				 else
				 {
				   val_id = document.id('paymentsdiv').getElements('input')[0].value;
				   jQuery("#payments #payment_id_"+val_id).attr('checked', true);
				   document.id('paymentsdiv').getElements('input')[0].checked=true;
				   checkpaymentval();
				 }
			  }
			  else if(document.id('paymentsdiv').getElements('input').length > 0)
			  {
			       val_id = document.id('paymentsdiv').getElements('input')[0].value;
				   jQuery("#payments #payment_id_"+val_id).attr('checked', true);
				   document.id('paymentsdiv').getElements('input')[0].checked=true;
				   checkpaymentval();
			  }
			}
			
			
			if(document.getElementById('klarna_checkout_onepage') != null)
			{
			
			   
	            klarnapaymentid = document.getElementById('klarna_checkout_onepage').value;
				if(klarnapaymentid == selectedpaymentid)
				{ 
				 <?php
				 foreach($this->helper->BTaddress["fields"] as $_field) 
				   {
				      if($_field['name']=='customer_note') 
				 	 {
			     ?>
				     document.getElementById("extracommentss").style.display = "block";
        	     <?php
				     }
				   }
				 ?>

				   if(document.getElementById("klarna-checkout-iframe") == null)
					{
					
					  document.location.reload(); 
					}
					//document.location.reload();
				 }
				 else
				 {
				   <?php
					foreach($this->helper->BTaddress["fields"] as $_field) 
					  {
					     if($_field['name']=='customer_note') 
						 {
		    	       ?>
					      document.getElementById("extracommentss").style.display = "none";
				      <?php
			    	     }
				      }
				   ?>
	   	         }
			}
			else
			{
			   <?php
					foreach($this->helper->BTaddress["fields"] as $_field) 
					  {
					     if($_field['name']=='customer_note') 
						 {
		    	       ?>
					      document.getElementById("extracommentss").style.display = "none";
				      <?php
			    	     }
				      }
				   ?>
			}
 
// Klarna checkout code

(function($){

 

	var klarna_id = $('#klarna_checkout_onepage').val();

	if (klarna_id != null) {

		if ($("#paymentsdiv input[name='virtuemart_paymentmethod_id']:checked").val() == klarna_id) {
			<?php
			foreach($this->helper->BTaddress["fields"] as $_field) 
			  {
			     if($_field['name']=='customer_note') 
				 {
		    ?>
			
		
		     document.getElementById("extracommentss").style.display = "block";
			 <?php
			     }
			 }
			 ?>
			$("#klarnadiv").slideDown();
			$("#klarna-checkout-container").slideDown();
			$('#otherpay_buttons').slideUp();
			$('div.billto-shipto').slideUp();

			$('div#other-things').slideUp();

		} else {
			$("#klarnadiv").slideUp();
			$("#klarna-checkout-container").slideUp();
			$('#otherpay_buttons').slideDown();
			$('div.billto-shipto').slideDown();
			$('div#other-things').slideDown();

		};
	}
	else
	{
	   jQuery("#klarnadiv").hide();
	}

})(jQuery);

	if (typeof window._klarnaCheckout != 'undefined') {

		window._klarnaCheckout(function (api) {

        	api.resume();

    	});

	};

// EOF Klarna Checkout

		}

	}).send();

}



function submit_order() {	

	<?php
	if(VmConfig::get('agree_to_tos_onorder')) {
	?>
  	  if(document.id('squaredOne').checked==false) 
	  { 
			return alert('<?php echo JText::_('COM_VIRTUEMART_CART_PLEASE_ACCEPT_TOS'); ?>');
	  }
	<?php
	}
	$showextraterms = $params->get('show_extraterms',0);	
	if($showextraterms)
	{
	?>
	  if(document.id('privacy_checkbox').checked==false) 
	  { 
			return alert('<?php echo JText::_('PLG_VMUIKITONEPAGE_PRIVACY_POLICY_ERROR'); ?>');
	  }
	<?php
	}
	?>

	var shipments_checked=false;

	var payments_checked=false;

	if(document.id('shipments')) {

		for(var i=0;i<document.id('shipments').getElements('input').length;i++) {

			if(document.id('shipments').getElements('input')[i].checked==true) {

				shipments_checked=true;

				break;

			}	

		}

		if(shipments_checked==false) {

			return alert('<?php echo JText::_('COM_VIRTUEMART_CART_SELECT_SHIPMENT'); ?>');

		}

	}


	if(document.id('paymentsdiv')) {

		for(var i=0;i<document.id('paymentsdiv').getElements('input').length;i++) {

			if(document.id('paymentsdiv').getElements('input')[i].checked==true) {

				payments_checked=true;

				break;

			}

		}

		if(payments_checked==false) {

			return alert('<?php echo JText::_('COM_VIRTUEMART_CART_SELECT_PAYMENT'); ?>');

		}

	}
    jQuery( "#fullerrordiv" ).html("");
	

	var register_state=true;

	if(document.id('register') && document.id('register').checked==true) {
	
	   checkvalidation();

		register_state=false;

		new Request.JSON({

			'url':'index.php?type=onepage&opc_task=register',

			'method':'post',

			'async':false,

			'noCache':true,

			'data':document.id('div_billto').toQueryString()+'&address_type=BT&<?php echo JSession::getFormToken(); ?>=1',

			'onSuccess':function(json,text) {

				if(json.error && json.error==1) {
				
				   erromsg = '<div data-opg-alert="" class="opg-alert"><a href="#" class="opg-alert-close opg-close"></a><p>'+json.message+'</p></div>';
					
					if(json.message != "")
					{
					  jQuery( "#fullerrordiv" ).html(erromsg);
					} 
					
					 
                     //document.getElementById('system-message-container').innerHTML = erromsg;
					 //document.getElementById('system-message-container').style.display="block";
					//alert(json.message);

				} else {

					register_state=true;

				}

			},

			'onFailure':function(xhr) {

				if(xhr.status==500); {

					register_state=true;

				}

			}

		}).send();

	}

	if(!register_state) {

		return;

	}

		


	var validator=new JFormValidator();

	validator.attachToForm(document.id('table_shipto'));

	var valid=true;

	document.id('table_billto').getElements('input').each(function(el) {

		var cval=validator.validate(el);;
		elementid = el.id;
		
		if(cval == false)
		{
		  jQuery("#"+elementid).addClass("opg-form-danger");
		}
		else
		{
		  jQuery("#"+elementid).removeClass("opg-form-danger");
		}
		

		valid=valid && cval;

	});
  	 country_ele = document.id('virtuemart_country_id');
	 if(country_ele != null)
	 {
	     var cval2 =validator.validate(country_ele);
		 if(cval2 == false)
		 {
			  jQuery("#virtuemart_country_id").addClass("opg-form-danger");
 		 }
		 else
		 {
			  jQuery("#virtuemart_country_id").removeClass("opg-form-danger");
	  	 }
		 valid=valid && cval2;
	 }
	 state_ele = document.id('virtuemart_state_id');
	 if(state_ele != null)
	 {
	     var cval3=validator.validate(state_ele);
		 if(cval3 == false)
		 {
			  jQuery("#virtuemart_state_id").addClass("opg-form-danger");
	 	 }
		 else
		 {
			  jQuery("#virtuemart_state_id").removeClass("opg-form-danger");
	  	 }
		 valid=valid && cval3;
	}


	if(!valid) {

		window.location.hash ='cart_top';

		return;

	}

	

			

<?php if(!empty($this->cart->STaddress['fields'])){ ?>

	if(document.id('STsameAsBT').checked==true) {
	

		var ship_to=document.id('table_shipto').getElements('input');

		var bill_to=document.id('table_billto');

		

		ship_to.each(function(item) {

			var name=item.get('id').replace('shipto_','');

			if(bill_to.getElementById(name)) {

				item.set('value',bill_to.getElementById(name).get('value'));

			}

		});
		
  	 if(country_ele != null)
	  {
		document.id('table_shipto').getElementById('shipto_virtuemart_country_id').set('value',document.getElementById('virtuemart_country_id').value);
      }

	} else {

<?php }; ?>

		var validator=new JFormValidator();

		validator.attachToForm(document.id('table_billto'));

		var valid=true;

		document.id('table_shipto').getElements('input').each(function(el) {

			var cval=validator.validate(el);;
			elementid = el.id;
			
			if(cval == false)
			{
			  jQuery("#"+elementid).addClass("opg-form-danger");
			}
			else
			{
			  jQuery("#"+elementid).removeClass("opg-form-danger");
			}

			valid=valid && cval;

		});
		
		
	 country_ele2 = document.id('shipto_virtuemart_country_id');
	 if(country_ele2 != null)
	 {
	     var cval3 =validator.validate(country_ele2);
		 if(cval3 == false)
		 {
			  jQuery("#shipto_virtuemart_country_id").addClass("opg-form-danger");
	 	 }
		 else
		 {
		  jQuery("#shipto_virtuemart_country_id").removeClass("opg-form-danger");
	  	 }
		 valid=valid && cval3;
	 }
	 state_ele2 = document.id('shipto_virtuemart_state_id');
	 if(state_ele2 != null)
	 {
	     var cval4=validator.validate(state_ele2);
		 if(cval4 == false)
		 {
			  jQuery("#shipto_virtuemart_state_id").addClass("opg-form-danger");
	 	 }	
		 else
		 {
			  jQuery("#shipto_virtuemart_state_id").removeClass("opg-form-danger");
	  	 }
		 valid=valid && cval4;
	 }
		

		if(!valid) {

			window.location.hash='cart_top';

			return;

		}

<?php if(!empty($this->cart->STaddress['fields'])){ ?>

	}

<?php }; ?>

			

		

	new Request.JSON({

		'url':'index.php?type=onepage&opc_task=set_checkout',

		'method':'post',

		'data':document.id('checkoutForm').toQueryString(),

		'async':false,

		'noCache':true,

		'onSuccess':function(json,text) {

			// Fucky IE adds to task 'update' for some unexpected cause

			document.checkoutForm.task.value='confirm';

			//alert(document.checkoutForm.task.value);

			document.checkoutForm.submit();

		}	

	}).send();

}



jQuery(document).ready(function($){

update_form();

});


</script>
<?php 
$jsvalidation = "[";
foreach($this->helper->BTaddress["fields"] as $field) 
{
  if($field['required'])
  {
     $fieldname = $field['name']."_field";
	 $jsvalidation .= '"'.$fieldname.'", ';
  }
}
$jsvalidation = substr($jsvalidation, 0, -2);
$jsvalidation  .= "]";
$jsarray = "fieldsarr = ".$jsvalidation.";";
?>
<script>
<?php echo $jsarray; ?>
function checkvalidation()
{
  for (i = 0; i < fieldsarr.length; i++) 
  { 
    elementid = fieldsarr[i];
    if(jQuery("#"+elementid).val()  == "")
	{
	 jQuery("#"+elementid).addClass("opg-form-danger");
	}
	else
	{
	    jQuery("#"+elementid).removeClass("opg-form-danger");
	}

  }
  if(jQuery("#name_field").val()  == "")
	{
	 jQuery("#name_field").addClass("opg-form-danger");
	}
	else
	{
	    jQuery("#name_field").removeClass("opg-form-danger");
	}

}
</script>
<style>
input#register
{
 float:none !important;
}
.billto-shipto{
 border:none !important;
}
</style>

	<input type="hidden" name="is_opc" id="is_opc" value="1" />

	<a name="cart_top"></a>

<?php 
if(count($this->cart->products) == 0)
{
?>
<div  class="opg-panel opg-panel-box">
		<strong><?php echo JText::_('COM_VIRTUEMART_EMPTY_CART') ?></strong>
			<?php if(!empty($this->continue_link_html)) : ?>
			<div class="opg-text-center">
				<?php 
				echo str_replace("continue_link", "opg-button opg-button-primary", $this->continue_link_html);
				?>
			</div>
			<?php endif; ?>		
	</div>	
<?php
}
else
{
?>

	
<div class="opg-width-1-1" id="fullerrordiv">
</div>
	

	<form method="post" id="checkoutForm" name="checkoutForm" action="<?php echo JRoute::_( 'index.php?option=com_virtuemart&view=cart'.$taskRoute,$this->useXHTML,$this->useSSL ); ?>" class="opg-form opg-width-1-1 ">
	


	<?php

	// This displays the pricelist MUST be done with tables, because it is also used for the emails

	echo $this->loadTemplate('pricelist');

	$user = JFactory::getUser();
	  if($user->id == 0)	
	  { 
	     $logindis = '';
	  }
	  else
	  {
	    $logindis = '';
	  }

	?>



	



<div id="other-things" style="<?php echo $logindis; ?>">

	<?php //echo shopFunctionsF::getLoginForm($this->cart,false);

	if ($this->checkout_task) $taskRoute = '&task='.$this->checkout_task;

	else $taskRoute ='';

	?>

		<?php // Leave A Comment Field ?>
		
		<?php
  foreach($this->helper->BTaddress["fields"] as $_field) 
  {
     if($_field['name']=='customer_note') 
	 {
		if($this->cart->BT['customer_note'] != "")
	 	{
		  $commenticon  = '';
		  $commentactive = 'opg-button-primary';
		}
		else
		{
		  $commenticon  = 'display:none';
		  $commentactive = '';
		}
		?>
		
	 <div class="opg-width-1-1">
		 <a id="commentbutton" class="opg-button <?php echo $commentactive; ?> opg-width-1-1" href="#commentpopup" data-opg-modal><i id="commenticon" style="<?php echo $commenticon; ?>" class="opg-icon opg-icon-check opg-margin-small-right"></i><?php echo JText::_('Add Notes and Special Requests'); ?></a>
	 </div>
	 
	 <div id="commentpopup" class="opg-modal"><!-- Comment Modal Started -->
	 <div class="opg-modal-dialog"><!-- Comment Modal Started -->
		<a class="opg-modal-close opg-close"></a>
    	   <div class="opg-modal-header"><strong><?php echo JText::_('COM_VIRTUEMART_COMMENT_CART'); ?></strong></div>
		   <div id="extracomments" class="customer-comments">
		   <?php
			   if($_field['required'])
			   {
			     $tmptext = "";
				 $tmptext = str_replace("<textarea", '<textarea onblur="javascript:update_form();" ', $_field['formcode']);
				 $tmptext = str_replace("<textarea", '<textarea class="required"', $tmptext);
				 echo $tmptext;

			   }
			   else
			   {
			    	echo str_replace("<textarea", '<textarea onblur="javascript:update_form();" ', $_field['formcode']);
			   }
			   ?>
		   </div>
		   <div class="opg-modal-footer">
	  			 <a class="opg-button opg-button-primary" href="Javascript:void(0);" onclick="validatecomment();">Submit</a>
				 <a id="commentclose" class="opg-modal-close opg-button">Cancel</a>
		   </div>
    </div> <!-- Shipto Modal ended -->
	</div><!-- Shipto Modal ended -->
	 <?php
	 }
  }
  ?>


		<?php // Leave A Comment Field END ?>







		<?php // Continue and Checkout Button ?>

		<div class="checkout-button-top">



			<?php // Terms Of Service Checkbox

			if (!class_exists('VirtueMartModelUserfields')){

				require(JPATH_VM_ADMINISTRATOR . DS . 'models' . DS . 'userfields.php');

			}



			    ?>


				
					

			    <?php
			

				if(!class_exists('VmHtml'))require(JPATH_VM_ADMINISTRATOR.DS.'helpers'.DS.'html.php');


		if(VmConfig::get('oncheckout_show_legal_info',1)){

		?>

                <section title=".squaredOne">
					    <div class="squaredOne">
						  <?php echo VmHtml::checkbox('tos',$this->cart->squaredOne,1,0,'class="terms-of-service" id="squaredOne"'); ?>
					      <label for="squaredOne"></label>
					    </div>
				 </section>

			<a class="opg-link opg-text-small" href="#full-tos" data-opg-modal><?php echo JText::_('COM_VIRTUEMART_CART_TOS_READ_AND_ACCEPTED'); ?></a>
		    <div id="full-tos" class="opg-modal">
			  <div class="opg-modal-dialog opg-text-left">
			        <a class="opg-modal-close opg-close"></a>
					<strong><?php echo JText::_('COM_VIRTUEMART_CART_TOS'); ?></strong>
				<?php echo $this->cart->vendor->vendor_terms_of_service;?>
			  </div>
			</div>


		<?php
		} 
		// VmConfig::get('oncheckout_show_legal_info',1)
		//echo '<span class="tos">'. JText::_('COM_VIRTUEMART_CART_TOS_READ_AND_ACCEPTED').'</span>';

				?>


				
			

		    <?php
		$showextraterms = $params->get('show_extraterms',0);	
		if($showextraterms)
		{
		?>
		  <div id="privcacy_div" class="opg-width-1-1 opg-margin-small">
		  <span class="comment opg-align-left"><?php echo JText::_ ('PLG_VMUIKITONEPAGE_PRIVACY_POLICY_TITLE'); ?></span>
		   <textarea id="privacy_textarea" rows="4" readonly="readonly" class="opg-width-1-1"><?php echo JText::_('PLG_VMUIKITONEPAGE_PRIVACY_POLICY_TEXT'); ?></textarea>
		   <label class="opg-margin-top" for="privacy_checkbox">
				<input type="checkbox" value="1" name="privacy_checkbox" id="privacy_checkbox" class="">
				<?php echo JText::_("PLG_VMUIKITONEPAGE_PRIVACY_POLICY_CHECKBOX"); ?>								
			</label>


		  </div>
		<?php
		}




			//echo $this->checkout_link_html;

			if (!VmConfig::get('use_as_catalog')) {
			
			   echo '<p id="bottom_total" class="opg-text-large opg-text-primary opg-text-bold opg-text-center">'.JText::_("COM_VIRTUEMART_CART_TOTAL").'&nbsp;:&nbsp;<strong class="opg-text-large opg-text-primary opg-text-bold" id="carttotal"></strong></p>';

				echo '<a class="opg-button opg-button-primary opg-button-large opg-margin-top opg-width-1-1" href="javascript:void(0);" onclick="submit_order();"><span>' . JText::_('COM_VIRTUEMART_ORDER_CONFIRM_MNU') . '</span></a>';

			}

			$text = JText::_('COM_VIRTUEMART_ORDER_CONFIRM_MNU');

			?>

		</div>

		<div class="module">

			<?php

			$modules=JModuleHelper::getModules('onepagecheckout');

			foreach($modules as $module) {

				echo JModuleHelper::renderModule($module,array('style'=>'rest'));

			}

			?>

		</div>

		<?php //vmdebug('my cart',$this->cart);// Continue and Checkout Button END ?>



		<!--<input type='hidden' name='task' value='<?php echo $this->checkout_task; ?>'/>-->

		<input type='hidden' name='task' value='confirm'/>

		<input type='hidden' name='option' value='com_virtuemart'/>
		

		<input type='hidden' name='view' value='cart'/>
	</div>
   </div>
 </div>
</div> <!-- Grid-div-end -->
</div>
</div>

</form>

<?php
}
?>