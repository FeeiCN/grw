<?php

/**
 * $param int $status Todo
 *                   Todos
 *                      0(未办)
 *                        1(已办)
 *                        2(删除)
 *                  Maybe
 *                          3(将来或许)
 *                  Fixed
 *                      4(固定事项)
 *                          5(暂停)
 *                    Record
 *                        6
 **/
class model_todo extends FeiModel
{
	var $pk = "doid";
	var $table = "todo";

	/* =========== Todo  ======================== */
	public function add($data)
	{
		$row = array(
			'userid'   => $_SESSION['Fei_Userid'],
			'name'     => $data['name'],
			'remark'   => $data['remark'],
			'startime' => $data['startime'],
			'endtime'  => $data['endtime'],
			'level'    => $data['level'],
			'repeats'  => $data['repeats'],
			'tags'     => $data['tags']
		);
		return $this->create($row);
	}

	/**
	 * @todo tags no
	 * @param $data
	 * @return bool|mixed
	 */
	public function add_enter($data)
	{
		$row = array(
			'userid' => $_SESSION['Fei_Userid'],
			'name'   => $data['value'],
			'tags'   => 1,
			'status' => 6 #Record
		);
		return $this->create($row);
	}

	public function edit($data)
	{
		$con = array(
			'doid'   => $data['doid'],
			'userid' => $_SESSION['Fei_Userid']
		);
		$row = array(
			'name'     => $data['name'],
			'remark'   => $data['remark'],
			'startime' => $data['startime'],
			'endtime'  => $data['endtime'],
			'level'    => $data['level'],
			'repeats'  => $data['repeats'],
			'tags'     => $data['tags']
		);
		if ($this->find($con)) {
			return $this->update($con, $row);
		} else {
			return FALSE;
		}
	}

	public function completed($data)
	{
		$con = array(
			'doid'   => $data['doid'],
			'userid' => $_SESSION['Fei_Userid']
		);
		if ($result = $this->find($con)) {
			if ($result['status'] == 0 || $result['status'] == 3) { //Maybe come from today or maybe
				$row = array(
					'status' => 1
				);
				return $this->update($con, $row);
			} else {
				return FALSE;
			}
		} else {
			return FALSE;
		}
	}

	public function cancel_completed($data)
	{
		$con = array(
			'doid'   => $data['doid'],
			'userid' => $_SESSION['Fei_Userid']
		);
		if ($result = $this->find($con)) {
			if ($result['status'] == 1) {
				$row = array(
					'status' => 0
				);
				return $this->update($con, $row);
			} else {
				return FALSE;
			}
		} else {
			return FALSE;
		}
	}

	public function cancel_trash($data)
	{
		$con = array(
			'doid'   => $data['doid'],
			'userid' => $_SESSION['Fei_Userid']
		);
		if ($result = $this->find($con)) {
			if ($result['status'] == 2) {
				$row = array(
					'status' => 0
				);
				return $this->update($con, $row);
			} else {
				return FALSE;
			}
		} else {
			return FALSE;
		}
	}

	public function get($data)
	{
		$con = array(
			'doid'   => $data['doid'],
			'userid' => $_SESSION['Fei_Userid']
		);
		return $this->find($con);
	}

	public function getAll($data, $status)
	{
		if ($status == 'index') { //控制面板
			$con = array(
				'status' => 0,
				'userid' => $_SESSION['Fei_Userid']
			);
			return $this->findAll($con, 'endtime ASC', NULL, 6);
		} else if ($status == 'completed') {
			$con = array(
				'status' => 1,
				'userid' => $_SESSION['Fei_Userid']
			);
			return $this->findAll($con, 'doid DESC', NULL, 6);
		} else if ($status == 'trash') {
			$con = array(
				'status' => 2,
				'userid' => $_SESSION['Fei_Userid']
			);
			return $this->findAll($con, 'doid DESC', NULL, 6);
		} else if ($status == 'todo') {
			$con = array(
				'status' => 0,
				'userid' => $_SESSION['Fei_Userid'],
				'tags'   => $data['tagid']
			);
			return $this->findAll($con, 'endtime ASC');
		}

	}

	public function del($data)
	{
		$con = array(
			'doid'   => $data['doid'],
			'userid' => $_SESSION['Fei_Userid']
		);
		if ($result = $this->find($con)) {
			if ($result['status'] == 0 || $result['status'] == 1) {
				$row = array(
					'status' => 2
				);
				return $this->update($con, $row);
			}
		} else {
			return FALSE;
		}
	}

	public function maybe($data)
	{
		$con = array(
			'doid'   => $data['doid'],
			'userid' => $_SESSION['Fei_Userid']
		);
		if ($this->find($con)) {
			$row = array(
				'status' => 3
			);
			return $this->update($con, $row);
		} else {
			return FALSE;
		}
	}

	public function find_tags_by_todoid($todoid)
	{
		$con  = array(
			'doid'   => $todoid,
			'userid' => $_SESSION['Fei_Userid']
		);
		$tags = $this->find($con, NULL, 'tags');
		return $tags['tags'];
	}

	/* =========== Maybe ======================== */

	public function maybes($data)
	{
		$con = array(
			'status' => 3,
			'userid' => $_SESSION['Fei_Userid'],
			'tags'   => $data['tagid']
		);
		return $this->findAll($con);
	}


	/* =========== Fixed ======================== */
	function fixeds()
	{
		$con = array(
			'userid' => $_SESSION['Fei_Userid'],
			'status' => 4
		);
		return $this->findAll($con);
	}

	function add_fixed($data)
	{
		$data = safe_replace($data);
		$row  = array(
			'userid'   => $_SESSION['Fei_Userid'],
			'name'     => $data['fixed_name'],
			'remark'   => $data['fixed_remark'],
			'startime' => $data['fixed_startime'],
			'repeats'  => intval($data['fixed_repeats']),
			'status'   => 4
		);
		return $this->create($row);
	}

	/**
	 * Statics todos
	 * @param  intval $status 0(todo),3(maybe)
	 * @return intval counts
	 */
	function statics($status)
	{
		if (isset($status) && !empty($status)) {
			$status = intval($status);
		} else {
			$status = 0;
		}
		$con = array(
			'userid' => $_SESSION['Fei_Userid'],
			'status' => $status
		);
		return $this->findCount($con);
	}
}