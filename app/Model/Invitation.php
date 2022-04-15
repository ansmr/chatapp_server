<?php

class Invitation extends AppModel
{

	public function getInvitationCode($groupId)
	{
		$result['Invitation']['invitation_cd'] = "";
		$params = array(
			'conditions' => array(
				'group_id' => $groupId
			),
			'fields' => 'invitation_cd'
		);
		$result = $this->find('first', $params);
		if (count($result) != 1) {
			$invitationCd = substr(uniqid(rand()), 0, 10);
			$data = array(
				'group_id' => $groupId,
				'invitation_cd' => $invitationCd
			);
			$this->save($data, false);
			$result['Invitation']['invitation_cd'] = $invitationCd;
		}
		return $result['Invitation']['invitation_cd'];
	}

	public function isInvitationCode($invitaionId)
	{
		$params = array(
			'conditions' => array(
				'invitation_cd' => $invitaionId
			),
			'fields' => 'group_id'
		);

		return $this->find('first', $params);
	}
}
