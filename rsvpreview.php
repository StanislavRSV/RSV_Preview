<?php
/**
* @version 			SEBLOD 3.x More
* @package			SEBLOD (App Builder & CCK) // SEBLOD nano (Form Builder)
* @url				https://www.seblod.com
* @editor			Octopoos - www.octopoos.com
* @copyright		Copyright (C) 2009 - 2018 SEBLOD. All Rights Reserved.
* @license 			GNU General Public License version 2 or later; see _LICENSE.php
**/

defined( '_JEXEC' ) or die;

// Plugin
class plgCCK_FieldRsvpreview extends JCckPluginField
{
	protected static $type		=	'rsvpreview';
	protected static $path;
	protected $autoloadLanguage = true;
	
	// -------- -------- -------- -------- -------- -------- -------- -------- // Construct
	
	// onCCK_FieldConstruct
	public function onCCK_FieldConstruct( $type, &$data = array() )
	{
		if ( self::$type != $type ) {
			return;
		}
		parent::g_onCCK_FieldConstruct( $data );
	}
	
	// onCCK_FieldConstruct_TypeForm
	public static function onCCK_FieldConstruct_TypeForm( &$field, $style, $data = array(), &$config = array() )
	{
		$data['computation']	=	null;
		$data['live']			=	null;
		$data['validation']		=	null;

		if ( !isset( $config['construction']['variation'][self::$type] ) ) {
			$data['variation']	=	array(
										'hidden'=>JHtml::_( 'select.option', 'hidden', JText::_( 'COM_CCK_HIDDEN' ) ),
										'value'=>JHtml::_( 'select.option', 'value', JText::_( 'COM_CCK_VALUE' ) ),
										'100'=>JHtml::_( 'select.option', '<OPTGROUP>', JText::_( 'COM_CCK_FORM' ) ),
										''=>JHtml::_( 'select.option', '', JText::_( 'COM_CCK_DEFAULT' ) ),
										'disabled'=>JHtml::_( 'select.option', 'disabled', JText::_( 'COM_CCK_FORM_DISABLED2' ) ),
										'101'=>JHtml::_( 'select.option', '</OPTGROUP>', '' ),
										'102'=>JHtml::_( 'select.option', '<OPTGROUP>', JText::_( 'COM_CCK_TOOLBAR' ) ),
										'toolbar_button'=>JHtml::_( 'select.option', 'toolbar_button', JText::_( 'COM_CCK_TOOLBAR_BUTTON' ) ),
										'103'=>JHtml::_( 'select.option', '</OPTGROUP>', '' )
									);
			$config['construction']['variation'][self::$type]	=	$data['variation'];
		} else {
			$data['variation']									=	$config['construction']['variation'][self::$type];
		}
		parent::onCCK_FieldConstruct_TypeForm( $field, $style, $data, $config );
	}
	
	// -------- -------- -------- -------- -------- -------- -------- -------- // Prepare
	
	// onCCK_FieldPrepareContent
	public function onCCK_FieldPrepareContent( &$field, $value = '', &$config = array() )
	{
		if ( self::$type != $field->type ) {
			return;
		}
		parent::g_onCCK_FieldPrepareContent( $field, $config );
		
		// Set
		$field->value	=	$value;
	}
	
	// onCCK_FieldPrepareForm
	public function onCCK_FieldPrepareForm( &$field, $value = '', &$config = array(), $inherit = array(), $return = false )
	{
		if ( self::$type != $field->type ) {
			return;
		}
		self::$path	=	parent::g_getPath( self::$type.'/' );
		parent::g_onCCK_FieldPrepareForm( $field, $config );
		
		
		// Init
		if ( count( $inherit ) ) {
			$id		=	( isset( $inherit['id'] ) && $inherit['id'] != '' ) ? $inherit['id'] : $field->name;
			$name	=	( isset( $inherit['name'] ) && $inherit['name'] != '' ) ? $inherit['name'] : $field->name;
		} else {
			$id		=	$field->name;
			$name	=	$field->name;
		}
	
	
		// Prepare
		$options2		=	JCckDev::fromJSON( $field->options2 );
		$value			=	$field->label;
		$field->label	=	'';		
		
		$class			=	( $field->css ? ' '.$field->css : '' );	
		$attr			=	( $field->attributes ? ' '.$field->attributes : '' );
			
		if ( $field->bool6 == 3 ) {
			$label		=	'<span class="icon-'.$options2['icon'].'"></span>';
			$attr		.=	' title="'.$value.'"';
		} elseif ( $field->bool6 == 2 ) {
			$label		=	$value."\n".'<span class="icon-'.$options2['icon'].'"></span>';
		} elseif ( $field->bool6 == 1 ) {
			$label		=	'<span class="icon-'.$options2['icon'].'"></span>'."\n".$value;
		}

		// my code
		$app 			= 	JFactory::getApplication();
		// location
		$cont_type		= 	$config['type'];		
		if($cont_type) {
			$my_query 	= 	'SELECT storage_location FROM #__cck_core_types WHERE name LIKE "'.$cont_type.'"';
		}		
		
		try {			
			$cont_loc 	= 	JCckDatabase::LoadResult($my_query);	
		}						
		catch (Exception $e) {
			$msg 		= 	$e->getMessage().' Query: '.$my_query;					
			$app->enqueueMessage($msg,'error');
		} 

		// article
		if($cont_loc === 'joomla_article') {		
			$art_id = $config['pk'];
			if($art_id){
				$cat_id = JCckDatabase::LoadResult('SELECT catid FROM #__content  WHERE id='.$art_id);
			}			
			
			if ($field->bool4 == 1 and $app->isClient('site')) {
				// modal window
				$attr 	.= 	' uk-toggle';	
				$prev_class_mod = $options2['classmodal'];
				$mylink = 	'index.php?option=com_content&view=article&tmpl=component&id='.$art_id.'&catid='.$cat_id;
				$link  	= 	JRoute::link('site', $mylink);					
				$mytmpl = 	'<a href="#modal-article"'.$attr.' class="'.$class.'">'.$label.'</a>';			
				$mytmpl	.= 	'<div id="modal-article" class="'.$prev_class_mod.'" uk-modal>';			
				$mytmpl .= 	'<div class="uk-modal-dialog uk-width-auto uk-margin-auto-vertical">';			
				$mytmpl .= 	'<button class="uk-modal-close-full uk-close-large" type="button" uk-close></button>';			
				$mytmpl .= 	'<iframe src="'.$link.'"  width="1800" uk-height-viewport frameborder="0" uk-responsive uk-video></iframe>';		
				$mytmpl .= 	'</div></div>';					
				$form 	= 	$mytmpl;				
			} else {
				// new browser tab
				require_once (JPATH_SITE . '/components/com_content/helpers/route.php');
				$mylink = 	ContentHelperRoute::getArticleRoute($art_id, $cat_id);
				$link  	= 	JRoute::link('site', $mylink);					
				$form 	=	'<a href="'.$link.'" target="_blank" '.$attr.' class="'.$class.'">'.$label.'</a>';
			}
		// category (blog)
		} elseif($cont_loc === 'joomla_category') {			
			
			$cat_id = $config['pk'];			
			if ($field->bool4 == 1 and $app->isClient('site')) {
				// modal window
				$attr 	.= ' uk-toggle';	
				$prev_class_mod = $options2['classmodal'];
				$mylink	=	'index.php?option=com_content&view=category&tmpl=component&layout=blog&id='.$cat_id;			
				$link  	= 	JRoute::link('site', $mylink);				
				$mytmpl = 	'<a href="#modal-article"'.$attr.' class="'.$class.'">'.$label.'</a>';			
				$mytmpl .= 	'<div id="modal-article" class="'.$prev_class_mod.'" uk-modal>';			
				$mytmpl .= 	'<div class="uk-modal-dialog uk-width-auto uk-margin-auto-vertical">';			
				$mytmpl .= 	'<button class="uk-modal-close-full uk-close-large" type="button" uk-close></button>';			
				$mytmpl .= 	'<iframe src="'.$link.'"  width="1800" uk-height-viewport frameborder="0" uk-responsive uk-video></iframe>';		
				$mytmpl .= 	'</div></div>';			
				$form 	= 	$mytmpl;				
			} else {
				// new browser tab
				require_once (JPATH_SITE . '/components/com_content/helpers/route.php');
				$mylink = 	ContentHelperRoute::getCategoryRoute($cat_id);
				$link  	= 	JRoute::link('site', $mylink);				
				$form 	=	'<a href="'.$link.'" target="_blank" '.$attr.' class="'.$class.'">'.$label.'</a>';
			}	
		// free	
		} else {
			
			$form = '';
		}		
		
		// Set
		if ( ! $field->variation ) {
			$field->form	=	$form;
			if ( $field->script ) {
				parent::g_addScriptDeclaration( $field->script );
			}
		} else {
			parent::g_getDisplayVariation( $field, $field->variation, $value, $value, $form, $id, $name, '<input', '', '', $config );
		}		
		$field->value	=	$value;		
		
		// Return
		if ( $return === true ) {
			return $field;
		}
	}
	
	// onCCK_FieldPrepareSearch
	public function onCCK_FieldPrepareSearch( &$field, $value = '', &$config = array(), $inherit = array(), $return = false )
	{
		if ( self::$type != $field->type ) {
			return;
		}
		
		// Prepare
		//self::onCCK_FieldPrepareForm( $field, $value, $config, $inherit, $return );
		
		// Return
		if ( $return === true ) {
			return $field;
		}
	}
	
	// onCCK_FieldPrepareStore
	public function onCCK_FieldPrepareStore( &$field, $value = '', &$config = array(), $inherit = array(), $return = false )
	{
		if ( self::$type != $field->type ) {
			return;
		}
		
		// Init
		if ( count( $inherit ) ) {
			$name	=	( isset( $inherit['name'] ) && $inherit['name'] != '' ) ? $inherit['name'] : $field->name;
		} else {
			$name	=	$field->name;
		}
		
		// Validate
		parent::g_onCCK_FieldPrepareStore_Validation( $field, $name, $value, $config );
		
		// Set or Return
		if ( $return === true ) {
			return $value;
		}
		$field->value	=	$value;
		parent::g_onCCK_FieldPrepareStore( $field, $name, $value, $config );
	}
	
	// -------- -------- -------- -------- -------- -------- -------- -------- // Render
	
	// onCCK_FieldRenderContent
	public static function onCCK_FieldRenderContent( $field, &$config = array() )
	{
		return parent::g_onCCK_FieldRenderContent( $field );
	}
	
	// onCCK_FieldRenderForm
	public static function onCCK_FieldRenderForm( $field, &$config = array() )
	{
		return parent::g_onCCK_FieldRenderForm( $field );
	}
	
	// -------- -------- -------- -------- -------- -------- -------- -------- // Special Events
	
	// onCCK_FieldBeforeRenderContent
	public static function onCCK_FieldBeforeRenderContent( $process, &$fields, &$storages, &$config = array() )
	{
	}
	
	// onCCK_FieldBeforeRenderForm
	public static function onCCK_FieldBeforeRenderForm( $process, &$fields, &$storages, &$config = array() )
	{
	}
	
	// onCCK_FieldBeforeStore
	public static function onCCK_FieldBeforeStore( $process, &$fields, &$storages, &$config = array() )
	{
	}
	
	// onCCK_FieldAfterStore
	public static function onCCK_FieldAfterStore( $process, &$fields, &$storages, &$config = array() )
	{
	}
	
	// -------- -------- -------- -------- -------- -------- -------- -------- // Stuff & Script
	
	//
}
?>