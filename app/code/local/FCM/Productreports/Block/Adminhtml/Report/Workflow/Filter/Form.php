<?php
class FCM_Productreports_Block_Adminhtml_Report_Workflow_Filter_Form extends FCM_Productreports_Block_Adminhtml_Report_Filter_Form
{
    /**
     * Add fieldset with general report fields
     *
     * @return Mage_Adminhtml_Block_Report_Filter_Form
     */
    protected function _prepareForm()
    {
        $actionUrl = $this->getUrl('*/*/workflow');
        $form = new Varien_Data_Form(
            array('id' => 'filter_form', 'action' => $actionUrl, 'method' => 'get')
        );
        $htmlIdPrefix = 'product_workflow_';
        $form->setHtmlIdPrefix($htmlIdPrefix);
        $fieldset = $form->addFieldset('base_fieldset', array('legend'=>Mage::helper('productreports')->__('Filter')));

        $dateFormatIso = Mage::app()->getLocale()->getDateFormat(Mage_Core_Model_Locale::FORMAT_TYPE_SHORT);

        $fieldset->addField('store_ids', 'hidden', array(
            'name'  => 'store_ids'
        ));

        $element = $fieldset->addField('product_category', 'select', array(
            'name' => 'product_category',
            'values' => Mage::helper('productreports')->getProductCategories(),
            'label' => Mage::helper('productreports')->__('Category'),
            'title' => Mage::helper('productreports')->__('Category'),
			'onchange'  => 'reloadSubCategories(this)'
        ));
		
		$element->setAfterElementHtml("<script type=\"text/javascript\">
			function reloadSubCategories(selectElement){
				var reloadurl = '". $this->getUrl('*/*/reloadcategories')."ctgid/' + selectElement.value;
				
				new Ajax.Request(reloadurl, {
					method: 'get',
					onLoading: function (transport) {
						$('". $htmlIdPrefix ."product_sub_category').update('Loading...');
					},
					onComplete: function(transport) {
						$('". $htmlIdPrefix ."product_sub_category').update(transport.responseText);
					}
				});
			}
		</script>");

        $fieldset->addField('product_sub_category', 'select', array(
            'name' => 'product_sub_category',
            'label' => Mage::helper('productreports')->__('Sub Category'),
            'title' => Mage::helper('productreports')->__('Sub Category'),
			'options'   => array(
                '' => Mage::helper('productreports')->__('Select')
            ),
        )); 
		
		$fieldset->addField('product_visibility', 'select', array(
            'name'      => 'product_visibility',
            'options'   => array(
				'0' => Mage::helper('productreports')->__('All'),
                '1' => Mage::helper('productreports')->__('Yes'),
                '2' => Mage::helper('productreports')->__('No')
            ),
            'label'     => Mage::helper('productreports')->__('Visible'),
            'title'     => Mage::helper('productreports')->__('Visible')
        ));
		
		$fieldset->addField('product_name', 'text', array(
            'name'      => 'product_name',
            'label'     => Mage::helper('productreports')->__('Product Name'),
        ));
		
		$fieldset->addField('product_stock', 'select', array(
            'name'      => 'product_stock',
            'options'   => array(
                '0' => Mage::helper('productreports')->__('All'),
                '1' => Mage::helper('productreports')->__('Yes'),
                '2' => Mage::helper('productreports')->__('No')
            ),
            'label'     => Mage::helper('productreports')->__('Stock'),
            'title'     => Mage::helper('productreports')->__('Stock')
        ));
		
		$fieldset->addField('product_sku', 'text', array(
            'name'      => 'product_sku',
            'label'     => Mage::helper('productreports')->__('Product SKU'),
        ));

        $fieldset->addField('product_type', 'select', array(
            'name' => 'product_type',
            'label' => Mage::helper('productreports')->__('Product Type'),
            'title' => Mage::helper('productreports')->__('Product Type'),
			'options'   => array(
                '' => Mage::helper('productreports')->__('Both'),
				'simple' => Mage::helper('productreports')->__('Simple'),
				'configurable' => Mage::helper('productreports')->__('Configurable'),
            ),
        ));

        $form->setUseContainer(true);
        $this->setForm($form);

        return parent::_prepareForm();
    }
}
