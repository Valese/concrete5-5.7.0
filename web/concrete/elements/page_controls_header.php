<?
defined('C5_EXECUTE') or die("Access Denied.");
$valt = Loader::helper('validation/token');
$token = '&' . $valt->getParameter();
$html = Loader::helper('html');
$dh = Loader::helper('concrete/dashboard');

if (isset($cp)) {
	if ($cp->canViewToolbar()) { 

?>

<style type="text/css">html {margin-top: 49px !important;} </style>

<script type="text/javascript">
<?
$valt = Loader::helper('validation/token');
print "var CCM_SECURITY_TOKEN = '" . $valt->generate() . "';";
?>
</script>

<?
$dh = Loader::helper('concrete/dashboard');
$v = View::getInstance();

if (!$dh->inDashboard()) {

	$v->requireAsset('core/app');
	
	$editMode = $c->isEditMode();
	$tools = REL_DIR_FILES_TOOLS_REQUIRED;
	if ($c->isEditMode()) {
		$startEditMode = 'new Concrete.EditMode();';
	}
	if ($cp->canEditPageContents() && $_REQUEST['ctask'] == 'check-out-first') {
		$pagetype = $c->getPageTypeObject();
		if (is_object($pagetype) && $pagetype->doesPageTypeLaunchInComposer()) {
			$launchPageComposer = "$('a[data-launch-panel=page]').toggleClass('ccm-launch-panel-active'); ConcretePanelManager.getByIdentifier('page').show();";
		}
	}
	$panelDashboard = URL::to('/ccm/system/panels/dashboard');
	$panelPage = URL::to('/ccm/system/panels/page');
	$panelSitemap = URL::to('/ccm/system/panels/sitemap');
	$panelAdd = URL::to('/ccm/system/panels/add');
	$panelCheckIn = URL::to('/ccm/system/panels/page/check_in');

	$js = <<<EOL
<script type="text/javascript" src="{$tools}/i18n_js"></script>
<script type="text/javascript">$(function() {
	$('html').addClass('ccm-toolbar-visible');
	ConcretePanelManager.register({'identifier': 'dashboard', 'position': 'right', url: '{$panelDashboard}'});
	ConcretePanelManager.register({'identifier': 'page', url: '{$panelPage}'});
	ConcretePanelManager.register({'identifier': 'sitemap', 'position': 'right', url: '{$panelSitemap}'});
	ConcretePanelManager.register({'identifier': 'add-block', 'translucent': false, 'position': 'left', url: '{$panelAdd}'});
	ConcretePanelManager.register({'identifier': 'check-in', 'position': 'left', url: '{$panelCheckIn}'});
	ConcreteToolbar.start();
	{$startEditMode}
	{$launchPageComposer}
});
</script>

EOL;

	$v->addFooterItem($js);

	if (ENABLE_PROGRESSIVE_PAGE_REINDEX && Config::get('DO_PAGE_REINDEX_CHECK')) {
		$v->addFooterItem('<script type="text/javascript">$(function() { ccm_doPageReindexing(); });</script>');
	}
	$cih = Loader::helper('concrete/ui');
	if (Localization::activeLanguage() != 'en') {
		$v->addFooterItem($html->javascript('i18n/ui.datepicker-' . Localization::activeLanguage() . '.js'));
		$v->addFooterItem('<script type="text/javascript">$(function() { jQuery.datepicker.setDefaults({dateFormat: \'yy-mm-dd\'}); });</script>');
	}
	if (!Config::get('SEEN_INTRODUCTION')) {
		$v->addHeaderItem('<script type="text/javascript">$(function() { ccm_showAppIntroduction(); });</script>');
		Config::save('SEEN_INTRODUCTION', 1);
	}
}

	}
	
}