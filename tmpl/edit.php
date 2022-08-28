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
JCckDev::forceStorage();
$options2	=	JCckDev::fromJSON( $this->item->options2 );
$fieldClassMod =	JCckDev::getEmpty( array( 'name'=>'core_options', 'type'=>'text', 'label'=>'FIELD_RSVPREVIEW_FIELDCLASSMODNAME',
										  'storage'=>'dev', 'storage_field'=>'json[options2][classmodal]', 'size'=>'40') );
JCckDev::get( $fieldClassMod, htmlspecialchars( @$options2['classmodal'] ), $config );

?>

<div class="seblod">
	<?php echo JCckDev::renderLegend( JText::_( 'COM_CCK_CONSTRUCTION' ), JText::_( 'PLG_CCK_FIELD_'.$this->item->type.'_DESC' ) ); ?>
    <ul class="adminformlist adminformlist-2cols">
        <?php
        echo JCckDev::renderForm( 'core_label', $this->item->label, $config );		
		echo '<li><label>'.JText::_( 'COM_CCK_LABEL_ICON' ).'</label>'
		 .	 JCckDev::getForm( 'core_dev_select', $this->item->bool6, $config, array( 'label'=>'Label Icon', 'defaultvalue'=>'0', 'selectlabel'=>'',
		 																			  'options'=>'Hide=0||Show=optgroup||Prepend=1||Append=2||Replace=3', 'storage_field'=>'bool6' ) )
		 .	 JCckDev::getForm( 'core_icons', @$options2['icon'], $config, array( 'css'=>'max-width-150' ) )
		 .	 '</li>';
	
		echo JCckDev::renderForm( 'core_bool4', $this->item->bool4, $config, array( 'label'=>'Select Mode', 'options'=>'No=0||Yes=1' ) );
		?>		
		 <li class="w50">
			 <div id="modal-txt1">
				 <p>
				 <b>No</b> - open in a new browser tab. <b>Yes</b> - open in a modal window (use <a href="https://getuikit.com/docs/modal" target="_blank">framework Uikit 3</a>, if installed)
				 </p>
			 </div>		 
		 </li>	
		 <li class="w50">
			<div id="modal-class">
				<label><?php echo JText::_( $fieldClassMod->label ); ?></label>
				<?php echo $fieldClassMod->form; ?>
			</div>
        </li>	
		<li class="w50">
			 <div id="modal-txt2">
				 <p>
				 <b>uk-modal-full</b> - full screen modal window, <b>uk-modal-container</b> - modal window in a container
				 </p>
			 </div>		 
		 </li>	
		 
		<?php 
		echo JCckDev::renderSpacer( JText::_( 'COM_CCK_STORAGE' ), JText::_( 'COM_CCK_STORAGE_DESC' ) );
		echo JCckDev::getForm( 'core_storage', $this->item->storage, $config );
        ?>
    </ul>
</div>

<script type="text/javascript">
// при загрузке страницы
window.onload = function() {
  var val4 = document.getElementById('bool4').value;   
  if (val4==0) {
	document.querySelector("#modal-txt1").style.display='none';
	document.querySelector("#modal-class").style.display='none';
	document.querySelector("#modal-txt2").style.display='none';
  } else {
	 document.querySelector("#modal-txt1").style.display='block';
	 document.querySelector("#modal-class").style.display='block';
	 document.querySelector("#modal-txt2").style.display='block';
  } 
}

// изменение при клике
document.getElementById("bool4").addEventListener("click", changed_b4);
function changed_b4(){  
  var val4 = document.getElementById('bool4').value;   
  if (val4==0) {
	document.querySelector("#modal-txt1").style.display='none';
	document.querySelector("#modal-class").style.display='none';
	document.querySelector("#modal-txt2").style.display='none';
  } else {
	document.querySelector("#modal-txt1").style.display='block';
	document.querySelector("#modal-class").style.display='block';
	document.querySelector("#modal-txt2").style.display='block';
  }
}
</script>
