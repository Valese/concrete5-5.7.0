<?
namespace Concrete\Core\Tree\Node\Type;
use Concrete\Core\Tree\Node\Node as TreeNode;
use Loader;
class Topic extends TreeNode {

	public function getPermissionResponseClassName() {
		return '\\Concrete\\Core\\Permission\\Response\\TopicTreeNodeResponse';
	}

	public function getPermissionAssignmentClassName() {
		return '\\Concrete\\Core\\Permission\\Assignment\\TopicTreeNodeAssignment';	
	}
	public function getPermissionObjectKeyCategoryHandle() {
		return 'topic_tree_node';
	}

	public function getTreeNodeDisplayName() {
		return $this->treeNodeTopicName;
	}

	public function loadDetails() {
		$db = Loader::db();
		$row = $db->GetRow('select * from TreeTopicNodes where treeNodeID = ?', array($this->treeNodeID));
		$this->setPropertiesFromArray($row);
	}

	public function deleteDetails() {
		$db = Loader::db();
		$db->Execute('delete from TreeTopicNodes where treeNodeID = ?', array($this->treeNodeID));
	}

	public function getTreeNodeJSON() {
		$obj = parent::getTreeNodeJSON();
		if (is_object($obj)) {
			return $obj;
		}
	}

	public function duplicate($parent = false) {
		$node = $this::add($this->treeNodeTopicName, $parent);
		$this->duplicateChildren($node);
		return $node;
	}

	public function setTreeNodeTopicName($treeNodeTopicName) {
		$db = Loader::db();
		$db->Replace('TreeTopicNodes', array('treeNodeID' => $this->getTreeNodeID(), 'treeNodeTopicName' => $treeNodeTopicName), array('treeNodeID'), true);
		$this->treeNodeTopicName = $treeNodeTopicName;
	}

	public static function add($treeNodeTopicName, $parent = false) {
		$db = Loader::db();
		$node = parent::add($parent);
		$node->setTreeNodeTopicName($treeNodeTopicName);
		return $node;
	}

}