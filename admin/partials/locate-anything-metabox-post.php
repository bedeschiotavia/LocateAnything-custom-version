<?php wp_nonce_field ('I961JpJQTj0crLKH0mGB', 'locate_anything_class_nonce' );
if($post_type=="user") $post_params=Locate_Anything_Admin::getUserMetas($object->ID);
else  $post_params=Locate_Anything_Admin::getPostMetas($object->ID);
 ?>
<div id="locate-anything-wrapper-post">
<h2 class="nav-tab-wrapper">
    <a  data-pane="1" class="active nav-tab"><?php _e("Geo localização","locate-anything")?></a>
    <a  class="nav-tab" data-pane="2"><?php _e("Marcador","locate-anything")?></a>
    <a  class="nav-tab" data-pane="4"><?php _e("Tooltip","locate-anything")?></a>
   <a  class="nav-tab" data-pane="3"><?php _e("Campos Adicionais","locate-anything")?></a>

</h2>	
<div id="locate-anything-map-settings-page-1" class="locate-anything-map-option-pane locate-anything-map-settings-list-ul" style="width:auto" >
<table>
<tr><td><h2><?php _e("Geo settings","locate-anything")?></h2></td></tr> 

				<tr>
				<td><?php _e("Localidade","locate-anything")?></td>
				<td><input type="text"	name="locate-anything-street" value="<?php echo  $post_params['locate-anything-street'];?>"></td>
			
				</tr>
				<tr>
				<td><?php _e("Numero","locate-anything")?></td>
				<td><input type="text" name="locate-anything-streetnumber" value="<?php echo $post_params['locate-anything-streetnumber'];?>"></td>
				</tr>
				<tr>
				<td><?php _e("Concelho","locate-anything")?></td>
				<td><input type="text" name="locate-anything-city" value="<?php echo $post_params['locate-anything-city'];?>"></td>
				</tr>
				<tr>
				<td><?php _e("Código Postal","locate-anything")?></td>
				<td><input type="text" name="locate-anything-zip" value="<?php echo $post_params['locate-anything-zip'];?>"></td>
				</tr>
				<tr>
				<td><?php _e("Distrito","locate-anything")?></td>
				<td><input type="text" name="locate-anything-state" value="<?php echo $post_params['locate-anything-state'];?>"></td>
				</tr>
				<tr>
				<td><?php _e("País","locate-anything")?></td>
				<td><input type="text" name="locate-anything-country" value="<?php echo $post_params['locate-anything-country'];?>"></td>
				</tr>
				<tr>
				<tr>
				<td><?php _e("PDF","locate-anything")?></td>
				<td>
					<?php 
							//ACF field 'upload_pdf_file'
							$upload_pdf_file = get_field('upload_pdf_file'); 
							if ($upload_pdf_file) {
									$pdf_info_url = esc_url($upload_pdf_file['url']);
									$pdf_title = $upload_pdf_file['title'];
									$pdf_link ='<a href="' . $pdf_info_url . '" target="_blank">' . esc_html($upload_pdf_file['title']) . '</a>';
									echo $pdf_link;
									$post_params['locate-anything-pdf_info'] = $pdf_info_url;
							} else {
									echo __('Nenhum PDF Adicionado', 'locate-anything');
									$post_params['locate-anything-pdf_info'] = '';
							}
					?>
				</td>
				<td><input type="hidden" name="locate-anything-pdf_info" value="<?php echo "<a href='{$post_params['locate-anything-pdf_info']}'class='pdf-link' target='_blank'>{$pdf_title}</a>"?>"></td>
				<?php
						//ACF field 'intervention'
						$intervention_type = get_field('intervention');
						
						if($intervention_type == 'Interrupção de Abastecimento'){
							$post_params['locate-anything-type'] = $intervention_type;
							?>
							<td><input type="hidden" name="locate-anything-type" value="<?php echo "<span class='type-interrupcao-abastecimento'>{$post_params['locate-anything-type']}</span>"?>"></td><?php
						}

						if($intervention_type == 'Interrupção de Circulação'){
							$post_params['locate-anything-type'] = $intervention_type;
							?>
							<td><input type="hidden" name="locate-anything-type" value="<?php echo "<span class='type-interrupcao-circulacao'>{$post_params['locate-anything-type']}</span>"?>"></td><?php
						}

						if($intervention_type == 'Obras'){
							$post_params['locate-anything-type'] = $intervention_type;
							?>
							<td><input type="hidden" name="locate-anything-type" value="<?php echo "<span class='type-obras'>{$post_params['locate-anything-type']}</span>"?>"></td><?php
						}
				?>
				
				
				<?php
				//ACF field 'time_period'
						$time_period = get_field('time_period');
						$post_params['locate-anything-date'] = $time_period;
				?>
				<td><input type="hidden" name="locate-anything-date" value="<?php echo $post_params['locate-anything-date']?>"></td>
				</tr>
				</table>
<br>
				<input class="button-admin" type="button" onclick="GetLocation()" value="Geolocalize esta morada" />
				<br><br><?php _e("Latitude","locate-anything")?> <input type="text" name="locate-anything-lat" value="<?php echo   $post_params['locate-anything-lat'];?>">
				<?php _e("Longitude","locate-anything")?> <input type="text" name="locate-anything-lon" value="<?php echo    $post_params['locate-anything-lon'];?>">
</div>
							
			      
  
     <table id="locate-anything-map-settings-page-4" class="locate-anything-map-option-pane locate-anything-map-settings-list-ul" style='width:auto;display:none'>
           <tr><td><h2><?php _e("Escolha modelo da tooltip de acordo com o tipo de intervenção","locate-anything")?></h2></td></tr>  
           <tr>
           <td><b><?php _e("Modelos","locate-anything")?> </b>:</td>
           <td><select name="locate-anything-tooltip-preset" id="locate-anything-tooltip-preset"><?php 
           $u=Locate_Anything_Admin::getDefaultTemplates();
					 
/* tooltip presets *///Added - 29/02
$tooltip_presets = array(
	(object)array(
			"class" => '',
			"name" => __('Selecione modelo', "locate-anything"),
			"template" => ''
	),
	(object)array(
			"class" => 'abastecimento',
			"name" => __('Abastecimento', "locate-anything"),
			"template" => '<h5>|type|</h5><hr><p>Localidade: |street|, |streetnum| - |city| </p><p>Período da Intervenção: |date|</p><hr><span>|pdf_info|</span>'
	),
	(object)array(
			"class" => 'obras',
			"name" => __('Obras', "locate-anything"),
			"template" => '<h5>|type|</h5><hr><p>Localidade: |street|, |streetnum| - |city| </p><p>Período da Intervenção: |date|</p><hr><span>|pdf_info|</span>'
	),
	(object)array(
			"class" => 'circulacao',
			"name" => 'Circulação',
			"template" => '<h5>|type|</h5><hr><p>Localidade: |street|, |streetnum| - |city| </p><p>Período da Intervenção: |date|</p><hr><span>|pdf_info|</span>'
	)
);
 $tooltip_presets=apply_filters("locate_anything_tooltip_presets",$tooltip_presets);
 $selectedPreset=$post_params["locate-anything-tooltip-preset"];
 foreach ($tooltip_presets as  $preset) {
 	if($selectedPreset===$preset->class) $say="selected";else $say='';
 	echo '<option '.$say.' value="'.$preset->class.'" data-template="'.$preset->template.'">'.$preset->name.'</option>';
 }?>
</select></td>
           </tr>
           <!-- <tr id="nice-tooltips-settings">
<td><?php _e("Nice Tooltips settings","locate-anything")?> : &nbsp;<input type="button" data-target="nice-tooltips-settings" class="locate-anything-help"></td><td><?php _e("Main image max-height","locate-anything")?> : <input type="text" value="<?php echo $post_params["locate-anything-nice-tooltips-img-height"]?>" name="locate-anything-nice-tooltips-img-height">
</td></tr> -->
           <tr>
           <td id="customtemplate" width="40%">
           	<div id="locate-anything-marker-html-template">
				<b><?php _e("Custom HTML template","locate-anything");?></b>&nbsp;<input type="button" data-target="customtemplate" class="locate-anything-help">
				<div class="LA_custom_template_editor">
				<textarea name="locate-anything-marker-html-template" id="marker-html-template" style="width: 70%; height: 20em"><?php echo $post_params['locate-anything-marker-html-template'];?></textarea>
				</div>
			</div>
			</td>
			<td id="addifields">
			<div class="LA_additional_fields_notice">
				<b><?php _e("Campos disponíveis","locate-anything")?></b>&nbsp;<input type="button" data-target="addifields" class="locate-anything-help">
				<p><?php _e("Here is a list of the additional fields available for display in the template. To use them just copy/paste the corresponding tag in the template editor","locate-anything")?></p>
				<?php Locate_Anything_Admin::displayAdditionalFieldNotice($post_type)?>
			
			</div>
           </td>
           </tr>
           </table>
  
   
           <table id="locate-anything-map-settings-page-2" class="locate-anything-map-option-pane locate-anything-map-settings-list-ul" style='display:none'>
           <tr><td><h2><?php _e("Escolha o ícone do Marcador","locate-anything")?></h2></td></tr>  
           <tr>
           <td width="40%">               	
               	<input type="radio" name="locate-anything-marker-type" value="standard" <?php if ($post_params["locate-anything-marker-type"]=="standard" || $post_params["locate-anything-marker-type"]==false ) echo 'checked' ?>> <b><?php _e("Ícones","locate-anything")?></b> :  
			</td>
			<td>
			<select style="width: 50% !important" name="locate-anything-custom-marker" id="locate-anything-custom-marker">
				 <option value=""><?php _e("Use default marker","locate-anything")?></option>
				 <?php foreach (Locate_Anything_Assets::getMarkers() as $marker){?>
				 	<option value="<?php echo $marker->id?>" <?php if(esc_attr($post_params["locate-anything-custom-marker"])==$marker->id) echo "selected"?>><?php echo $marker->url?></option>	 		
		<?php }?>  
			</select>
		</td></tr>


			</table>		

<table id="locate-anything-map-settings-page-3" class="locate-anything-map-option-pane locate-anything-map-settings-list-ul"  style='display:none' >
<tr><td><h2><?php _e("Additional fields","locate-anything")?></h2></td></tr>  
<tr><td>
			<div id="locate-anything-additional_fields">			
			<?php Locate_Anything_Admin::displayAdditionalFields($object);?>			  
			</div>   
			</td></tr>    
    </table>
</div>

<script type="text/javascript">
jQuery(document).ready(function(){
	/* help texts */
	<?php include plugin_dir_path(__FILE__)."locate-anything-help.php";?>
			/* initializes marker selector */ 
			initialize_marker_selector("locate-anything-custom-marker");		
				/* initializes the media uploader*/
				initialize_media_uploader();

				jQuery("#locate-anything-tooltip-preset").change(function(e){locate_anything_select_preset(e)});

			});

function locate_anything_select_preset(e){
  if(confirm("<?php _e('Quer alterar o modelo atual da tooltip?','locate-anything')?>"))jQuery("#marker-html-template").val(jQuery('#locate-anything-tooltip-preset :selected').attr("data-template"));
}
</script>       