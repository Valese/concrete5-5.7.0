<? defined('C5_EXECUTE') or die("Access Denied."); ?>

<?
if (isset($entry)) { ?>

	<form method="post" enctype="multipart/form-data" action="<?=$this->action('save')?>" id="ccm-dashboard-composer-form">
	
	<h1><span><?=ucfirst($action)?> <?=$ct->getCollectionTypeName()?></span></h1>
	<div class="ccm-dashboard-inner" id="ccm-dashboard-composer">
	<div id="composer-save-status"></div>
	<h2><?=t("Basic Information")?></h2>
	<ol>
		<li>
		<strong><?=$form->label('cName', t('Name'))?></strong><br/>
		<?=$form->text('cName', $name)?>		
		</li>
		<li>
		<strong><?=$form->label('cDescription', t('Short Description'))?></strong><br/>
		<?=$form->textarea('cDescription', $description)?>		
		</li>
	</ol>
	
	<ol>
	<? 
	foreach($contentitems as $ci) {
		if ($ci instanceof AttributeKey) { 
			$ak = $ci;
			if (is_object($entry)) {
				$value = $entry->getAttributeValueObject($ak);
			}
			?>
			<li><strong><?=$ak->render('label');?></strong><br/>
			<?=$ak->render('form', $value, true)?>	
			</li>
		
		<? } else { 
			$b = $ci; 
			$b = $entry->getComposerBlockInstance($b);
			?>
		
		<li>
		<?
		$bv = new BlockView();
		$bv->render($b, 'composer');
		?>
		
		</li>
		
		<?
		} ?>
	<? }  ?>
	</ol>
	
	
		<?
		$v = $entry->getVersionObject();
		
		?>
		
		<?=Loader::helper('concrete/interface')->submit(t('Save'), 'save', 'left')?>
		<?=Loader::helper('concrete/interface')->submit(t('Discard'), 'discard', 'left', 'ccm-composer-hide-on-approved')?>
		<?=Loader::helper('concrete/interface')->button_js(t('Preview'), 'javascript:ccm_composerLaunchPreview()', 'left', 'ccm-composer-hide-on-approved')?>
		<? if ($entry->isComposerDraft()) { ?>
			<?=Loader::helper('concrete/interface')->submit(t('Publish Page'), 'publish')?>
		<? } else { ?>
			<?=Loader::helper('concrete/interface')->submit(t('Publish Changes'), 'publish')?>
		<? } ?>
		
		<?=$form->hidden('entryID', $entry->getCollectionID())?>
		<input type="hidden" name="cPublishParentID" value="0" />
		<?=$form->hidden('autosave', 0)?>
		<?=Loader::helper('validation/token')->output('composer')?>
		<div class="ccm-spacer">&nbsp;</div>
		
	</div>
	</form>

	<script type="text/javascript">
	var ccm_composerAutoSaveInterval = false;
	
	ccm_composerDoAutoSave = function() {
		$('input[name=autosave]').val('1');
		try {
			tinyMCE.triggerSave(true, true);
		} catch(e) { }
		
		$('#ccm-dashboard-composer-form').ajaxSubmit({
			'dataType': 'json',
			'success': function(r) {
				ccm_composerLastSaveTime = new Date();
				$("#composer-save-status").html('<?=t("Page saved at ")?>' + r.time);
				$(".ccm-composer-hide-on-approved").show();
			}
		});
		$('input[name=autosave]').val('0');
	}
	
	ccm_composerLaunchPreview = function() {
		<? $t = PageTheme::getSiteTheme(); ?>
		ccm_previewInternalTheme(<?=$entry->getCollectionID()?>, <?=$t->getThemeID()?>, '<?=addslashes(str_replace(array("\r","\n","\n"),'',$t->getThemeName()))?>');
	}
	
	ccm_composerSelectParentPageAndSubmit = function(cID) {
	 	$("input[name=cPublishParentID]").val(cID);
	 	$("input[name=ccm-submit-publish]").click();
	}
		
		
	ccm_composerEditBlock = function(cID, bID, arHandle, w, h) {
		if(!w) w=550;
		if(!h) h=380; 
		var editBlockURL = '<?=REL_DIR_FILES_TOOLS_REQUIRED ?>/edit_block_popup';
		$.fn.dialog.open({
			title: ccmi18n.editBlock,
			href: editBlockURL+'?cID='+cID+'&bID='+bID+'&arHandle=' + encodeURIComponent(arHandle) + '&btask=edit',
			width: w,
			modal: false,
			height: h
		});		
	}
	
	$(function() {
		<? if (is_object($v) && $v->isApproved()) { ?>
			$(".ccm-composer-hide-on-approved").hide();
		<? } ?>
		
		var ccm_composerAutoSaveIntervalTimeout = 7000;
		var ccm_composerIsPublishClicked = false;
		
		$("#ccm-submit-discard").click(function() {
			return (confirm('<?=t("Discard this draft?")?>'));
		});
		
		$("#ccm-submit-publish").click(function() {
			ccm_composerIsPublishClicked = true;
		});
		
		<? if ($entry->isComposerDraft()) { ?>
			$("#ccm-dashboard-composer-form").submit(function() {
				if ($("input[name=cPublishParentID]").val() > 0) {
					return true;
				}
				if (ccm_composerIsPublishClicked) {
					ccm_composerIsPublishClicked = false;			
	
					<? if ($ct->getCollectionTypeComposerPublishMethod() == 'PAGE_TYPE' || $ct->getCollectionTypeComposerPublishMethod() == 'CHOOSE') { ?>
						jQuery.fn.dialog.open({
							title: '<?=t("Publish Page")?>',
							href: CCM_TOOLS_PATH + '/composer_target?cID=<?=$entry->getCollectionID()?>',
							width: '550',
							modal: false,
							height: '400'
						});
						return false;
					<? } else if ($ct->getCollectionTypeComposerPublishMethod() == 'PARENT') { ?>
						return true;				
					<? } else { ?>
						return false;
					<? } ?>
				}
			});
		<? } ?>
		ccm_composerAutoSaveInterval = setInterval(function() {
			ccm_composerDoAutoSave();
		}, 
		ccm_composerAutoSaveIntervalTimeout);
		
	});
	</script>
	
	
<? } else { ?>

	<h1><span><?=t('Composer')?></span></h1>
	<div class="ccm-dashboard-inner" id="ccm-dashboard-composer">


	<? if (count($ctArray) > 0) { ?>
	<h2><?=t('What type of page would you like to write?')?></h2>
	<ul>
	<? foreach($ctArray as $ct) { ?>
		<li><a href="<?=$this->url('/dashboard/composer/write', $ct->getCollectionTypeID())?>"><?=$ct->getCollectionTypeName()?></a></li>
	<? } ?>
	</ul>
	<? } else { ?>
		<p><?=t('You have not setup any page types for Composer.')?></p>
	<? } ?>

	</div>
	
<? } ?>
