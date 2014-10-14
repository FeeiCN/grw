<?php

class money extends Grw
{
	function __construct()
	{
		parent::__construct();
		$this->__initial('money');
	}

	function review()
	{
		$money   = FeiClass('model_money');
		$bank    = FeiClass('model_money_bank');
		$record  = FeiClass('model_money_bank_record');
		$deposit = FeiClass('model_money_deposit');
		$assets  = FeiClass('model_money_assets');
		$action  = $this->FeiArgs('action', NULL, 'POST');
		$data    = $this->FeiArgs(NULL, NULL, 'POST');
		switch ($action) {
			case 'add_bank':
				$this->__check_istrue($bank->add($data));
			case 'banks':
				$banks   = $bank->banks();
				$hrecord = NULL;
				for ($i = 0; $i < count($banks); $i++) {
					$type    = $banks[$i]['type'] == 1 ? "储蓄卡" : "信用卡";
					$typemin = $banks[$i]['type'] == 1 ? "储" : "信";
					$bankid  = $banks[$i]['id'];
					//获取此银行操作记录
					$brecord = $record->get_one($banks[$i]['id']);
					if (count($brecord) == 0) {
						$hrecord = "<li><span>暂无操作记录</span>";
					} else {
						for ($j = 0; $j < count($brecord); $j++) {
							$ctime = date('m-d', $brecord[$j]['ctime']);
							switch ($brecord[$j]['cz']) {
								case '0':
									$cz = '取款';
									break;
								case '1':
									$cz = '存款';
									break;
								case '2':
									$cz = '转账';
									break;
								default:
									$cz = '错误';
									break;
							}
							$hrecord .= "<li class=\"tooltip\" title=\"" . $brecord[$j]['address'] . "\">
                                            <i class=\"time\">
                                                " . $ctime . "
                                            </i>
                                            <span>
                                                " . $cz . $brecord[$j]['sum'] . "元
                                            </span>
                                        </li>";
						}
					}
					$html .= "
                    <div class=\"pricing\">
                        <div class=\"title tooltip\" title=\"" . $type . "\">
                            " . $banks[$i]['title'] . "(" . $typemin . ")" . "
                        </div>
                        <div class=\"price\">
                            <div class=\"left\">
                                <strong>
                                    ￥" . $banks[$i]['reserve'] . "
                                </strong>
                                <small>
                                    余额(单位：元)
                                </small>
                            </div>
                            <div class=\"right\">
                                <a class=\"button small icon-refresh tooltip\" title=\"查看消费记录\"></a>
                                <a onclick=\"Money.edit_bank(" . $banks[$i]['id'] . ")\" class=\"button small red icon-pencil tooltip\" title=\"编辑\"></a>
                            </div>
                        </div>
                        <ul class=\"info\">
                            " . $hrecord . "
                            <li style=\"text-align:center;\">
                                <button href=\"javascript:void(0);\" onclick=\"Money.add_record(" . $banks[$i]['id'] . ")\" class=\"button grey block\">
                                    添加一笔
                                </button>
                            </li>
                        </ul>
                    </div>";
				}
				echo $html;
				exit;
			case 'bank_info':
				$this->__check_istrue($info = $bank->info($data), $info);
			case 'edit_bank':
				$this->__check_istrue($bank->edit($data));
				break;
			case 'add_record':
				$this->__check_istrue($record->record($data));
				break;
			case 'deposit':
				/**
				 * Money deposit
				 * @param Boolen cz 1:out 0:in
				 * @return html
				 */
				$cz     = intval($data['cz']);
				$result = $deposit->deposits($cz);
				if (count($result) == 0 && $cz == 0) {
					echo "<div class=\"alert note\"> <span class=\"icon\"></span><span class=\"close\">x</span> 没有借钱出去 :-) </div>";
				} else if (count($result) == 0 && $cz == 1) {
					echo "<div class=\"alert note\"> <span class=\"icon\"></span><span class=\"close\">x</span> 没有借他人钱 :-) </div>";
				} else {
					for ($i = 0; $i < count($result); $i++) {
						$result[$i]['stime'] = date('y-m-d', strtotime($result[$i]['stime']));
						#Cover period to year/mouth/day
						if ($result[$i]['period'] == 0) {
							$result[$i]['day'] = '未讲明';
						} else {
							if (($yu[$i] = $result[$i]['period'] % 30) != 0) {
								$result[$i]['day'] = floor($result[$i]['period'] / 30) . '个月零' . $yu[$i] . '天';
							} else {
								$result[$i]['day'] = floor($result[$i]['period'] / 30) . '个月';
							}
						}
						$html .= "<tr>
                                    <td>
                                        " . $result[$i]['user'] . "
                                    </td>
                                    <td>
                                        <span class=\"badge blue\">
                                            ￥" . $result[$i]['sum'] . "
                                        </span>
                                    </td>
                                    <td>
                                        " . $result[$i]['stime'] . "
                                    </td>
                                    <td class=\"center\">
                                        <span class=\"badge red dark\">
                                            " . $result[$i]['day'] . "
                                        </span>
                                    </td>
                                    <td class=\"center\">
                                        <span class=\"badge blue light tooltip\" title=" . $result[$i]['remark'] . ">备注</span>
                                    </td>
                                    <td class=\"center\">
                                        <a href=\"#\" class=\"button small grey tooltip\" title=\"Edit\">
                                            <i class=\"icon-pencil\"></i>
                                        </a>
                                        <a href=\"#\" class=\"button small grey tooltip\" title=\"Remove\">
                                            <i class=\"icon-remove\"></i>
                                        </a>
                                    </td>
                                </tr>";
					}
					echo $html;
				}
				exit;
			case 'add_deposit':
				$this->__check_istrue($deposit->add($data));
				break;
			default: #计算总额
				#借出总额
				$this->jiechu = $deposit->sum();
				#现有存款
				$this->cunkuan = $bank->sum();
				#固定资产
				$this->asset_num = $assets->sum();
				break;
		}
	}

	function __format_day($day)
	{
		$date = array(
			'year'  => 365,
			'month' => 30
		);
		for ($i = 0; $i < 12; $i++) {
			if ($day < 30 * $i) {
				$day = $day - $i * 30;
				return $i . 'month - ' . $day . '天';
			}
		}
	}

	function deposit()
	{

	}

	function bank()
	{

	}

	function assets()
	{
		$assets = FeiClass('model_money_assets');
		$action = $this->FeiArgs('action', NULL, 'POST');
		switch ($action) {
			case 'getAll':
				$this->__check_istrue($asset = $assets->getAll(), $asset);
				break;
			case 'add':
				$this->__check_istrue($assets->add());
				break;
			case 'del':
				$this->__check_istrue($assets->del());
				break;
			default:
		}
	}
}