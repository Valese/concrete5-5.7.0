<?
namespace Concrete\Core\Workflow\Request;
use Workflow;
use Loader;
use Page;
use \Concrete\Core\Workflow\Description as WorkflowDescription;
use Permissions;
use PermissionKey;
use \Concrete\Core\Workflow\Progress\Progress as WorkflowProgress;
use CollectionVersion;
use Events;
use \Concrete\Core\Workflow\Progress\Action\Action as WorkflowProgressAction;
use \Concrete\Core\Workflow\Progress\Response as WorkflowProgressResponse;

class ApproveStackRequest extends PageRequest {

	public function approve(WorkflowProgress $wp) {
		$s = Stack::getByID($this->getRequestedPageID());
		$v = CollectionVersion::get($s, $this->cvID);
		$v->approve(false);
		if ($s->getStackName() != $v->getVersionName()) {
			// The stack name has changed so we need to
			// update that for the stack object as well.
			$s->update(array('stackName' => $v->getVersionName()));
		}

		$ev = new \Concrete\Core\Page\Collection\Version\Event($s);
		$ev->setCollectionVersionObject($v);
		Events::dispatch('on_page_version_submit_approve', $ev);

		$wpr = new WorkflowProgressResponse();
		$wpr->setWorkflowProgressResponseURL(BASE_URL . DIR_REL . '/' . DISPATCHER_FILENAME . '?cID=' . $s->getCollectionID());
		return $wpr;
	}

	
}