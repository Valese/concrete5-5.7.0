<?
namespace Concrete\Core\User\PrivateMessage;
use \Concrete\Core\Foundation\Collection\Database\DatabaseItemList;
use Loader;
class PrivateMessageList extends DatabaseItemList {
	
	protected $itemsPerPage = 10;
	protected $mailbox;
	
	public function filterByMailbox($mailbox) {
		$this->filter('msgMailboxID', $mailbox->getMailboxID());
		$this->filter('uID', $mailbox->getMailboxUserID());
		$this->mailbox = $mailbox;
	}
	
	function __construct() {
		$this->setQuery("select UserPrivateMessagesTo.msgID from UserPrivateMessagesTo inner join UserPrivateMessages on UserPrivateMessagesTo.msgID = UserPrivateMessages.msgID");
		$this->sortBy('msgDateCreated', 'desc');
	}
	
	public function get($itemsToGet = 0, $offset = 0) {
		$r = parent::get($itemsToGet, $offset);
		foreach($r as $row) {
			$messages[] = UserPrivateMessage::getByID($row['msgID'], $this->mailbox);
		}
		return $messages;
	}
	
}