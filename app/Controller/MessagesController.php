<?php

class MessagesController extends AppController
{

	public $helpers = array(
		'Html',
		'Form'
	);

	public function room($group_id = "")
	{
		$errorFlg = false;
		$errorMessage = "";
		$result = array();
		if ($group_id != "") {
			try {
				$params = array(
					'conditions' => array(
						'Message.group_id' => $group_id
					),
					'fields' => array(
						'Message.id',
						'Message.user_id',
						'Message.group_id',
						'Message.message',
						'Message.created',
						'User.user_name'
					),
					'joins' => array(
						array(
							'type' => 'INNER',
							'table' => 'users',
							'alias' => 'User',
							'conditions' => 'User.uniqid = Message.user_id'
						)
					)
				);
				$result = $this->Message->find('all', $params);
			} catch (Exception $e) {
				$errorFlg = true;
				$errorMessage = "メッセージ情報取得に失敗しました。{$e}";
			}
		} else {
			$errorFlg = true;
			$errorMessage = "パラメータエラー";
		}
		$this->set("groupId", $group_id);
		$this->set("messageList", $result);
		$this->set("error", $errorFlg);
		$this->set("errorMessage", $errorMessage);
	}

	public function add( $group_id = "")
	{
		$result = null;
		// Viewへのレンダーを無効
		//$this->autoRender = false;
		//header("Content-Type: application/json; charset=utf-8");
		if (isset($this->request->data['message'])) {
			$message = $this->request->data['message'];
			$user_id = $this->request->data['sendUser'];
			$data = array(
				'user_id' => $user_id,
				'group_id' => $group_id,
				'message' => $message
			);
			$this->Message->save($data);
			$result = $this->Message->find('all');
			$status = true;
			$error = null;
		}else{
			$status = false;
			$error = null;
		}
		return json_encode(compact('status', 'result', 'error'));
	}

	public function lists($groupId="",$showNo=0)
	{		// Viewへのレンダーを無効
		$this->autoRender = false;
		header("Content-Type: application/json; charset=utf-8");

		if(isset($groupId)){
			$params = array(
				'conditions' => array(
					'Message.group_id' => $groupId,
					'Message.id >' => $showNo
				),
				'fields' => array(
					'Message.id',
					'Message.user_id',
					'Message.group_id',
					'Message.message',
					'Message.created',
					'User.user_name'
				),
				'joins' => array(
					array(
						'type' => 'INNER',
						'table' => 'users',
						'alias' => 'User',
						'conditions' => 'User.uniqid = Message.user_id'
					)
				)
			);
			$result = $this->Message->find('all', $params);
			$status = true;
			$error = null;
			return json_encode(compact('status', 'result', 'error'));
		}
	}
}