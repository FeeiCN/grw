<?php

class news extends Grw
{
	function __construct()
	{
		parent::__construct();
		$this->__initial('news');
	}

	function index()
	{
		if (isset($_POST['action']) && $_POST['action'] == 'get_rss_content') {
			$rss = FeiClass(FeiRss);
			$rss = $rss->loadRss("http://news.qq.com/newsgn/rss_newsgn.xml");
			echo 'Title: ', $rss->title;
			echo 'Description: ', $rss->description;
			echo 'Link: ', $rss->link;

			foreach ($rss->item as $item) {
				$str .= "<tr>
                        <td>
                            " . $item->title . "
                        </td>
                        <td>
                            " . $item->description . "
                        </td>
                        <td>
                            " . date('Y-m-d H:i:s', $item->timestamp) . "
                        </td>
                        <td class=\"center\">
                            <a href=\"" . $item->link . "\" class=\"button small grey tooltip\" data-gravity=s title=\"Edit\">
                                <i class=\"icon-pencil\"></i>
                            </a>
                        </td>
                        <td class=\"center\">
                            " . $item->{'content:encoded'} . "
                        </td>
                    </tr>";
			}
			echo $str;
			exit;
		} elseif (isset($_POST['action']) && $_POST['action'] == 'add_rss') {
			$rss_source = FeiClass(model_rss);
			$conditions = array(
				'userid' => $_SESSION['Fei_Userid'],
				'name'   => $this->FeiArgs('name'),
				'url'    => $this->FeiArgs('url')
			);
			$this->__check_istrue($rss_source->create($conditions));
		}
		$rss = FeiClass(FeiRss);
		$rss->load("http://news.qq.com/newsgn/rss_newsgn.xml");
		$this->rss_lists = $rss->getItems();
		//dump($this->rss_lists);
		$rss_source       = FeiClass(model_rss);
		$r_con            = array(
			'userid' => $_SESSION['Fei_Userid']
		);
		$this->rss_source = $rss_source->findAll($r_con);
	}
}