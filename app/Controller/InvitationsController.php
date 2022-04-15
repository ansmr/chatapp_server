<?php

class InvitationsController extends AppController
{

	public $helpers = array(
		'Html',
		'Form'
	);

	public function invitationCode($groupId = "")
	{
		$status = true;
		$error = array();
		// Viewへのレンダーを無効
		$this->autoRender = false;
		$status = true;
		$error = array();
		if (! empty($groupId)) {
			$result = $this->Invitation->getInvitationCode($groupId);
		} else {
			$status = false;
			$result = null;
			$error = array(
				'code' => "400",
				'message' => '不正リクエスト'
			);
		}
		return json_encode(compact('status', 'result', 'error'));
	}

	public function joinRoom($invitaionId = "",$userId ="")
	{
		$status = true;
		$error = array();
		// Viewへのレンダーを無効
		$this->autoRender = false;
		if (! empty($invitaionId)) {
			$result = $this->Invitation->isInvitationCode($invitaionId);
			if (count($result) > 0) {
				$this->redirect(array(
					'controller' => 'UserGroups',
					'action' => 'joinRoom',
					 $result['Invitation']['group_id'],
					 $userId
				));
			} else {
				$status = false;
				$result = null;
				$error = array(
					'code' => "401",
					'message' => '入力した値が不正です。'
				);
			}
		} else {
			$status = false;
			$result = null;
			$error = array(
				'code' => "400",
				'message' => '不正リクエスト'
			);
		}
		return json_encode(compact('status', 'result', 'error'));
	}
}
