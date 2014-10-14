<?php

class model_note extends FeiModel
{
	var $pk = "id";
	var $table = "note";

	public function view($data)
	{
		$id  = intval($data['id']);
		$con = array(
			'userid' => $_SESSION['Fei_Userid'],
			'id'     => $id
		);
		if ($info = $this->find($con)) {
			return $info;
		} else {
			return FALSE;
		}
	}

	public function add($data)
	{
		$row = array(
			'userid'   => $_SESSION['Fei_Userid'],
			'title'    => $data['note_title'],
			'content'  => $data['note_content'],
			'keywords' => $data['note_keywords']
		);
		return $this->create($row);
	}

	public function edit($data)
	{
		$con = array(
			'userid' => $_SESSION['Fei_Userid'],
			'id'     => $data['note_id']
		);
		$row = array(
			'title'    => $data['note_title'],
			'content'  => $data['note_content'],
			'keywords' => $data['note_keywords']
		);
		return $this->update($con, $row);
	}

	public function del($data)
	{
		$con = array(
			'userid' => $_SESSION['Fei_Userid'],
			'id'     => $data['id']
		);
		return $this->delete($con);
	}
}