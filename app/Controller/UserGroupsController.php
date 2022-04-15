<?php

class UserGroupsController extends AppController
{

	public $helpers = array(
		'Html',
		'Form'
	);
	// 参照可能リストの取得
	public function userList($group_id = "")
	{
		// Viewへのレンダーを無効
		$this->autoRender = false;
		if (! empty($group_id)) {
			$params = array(
				'conditions' => array(
					'UserGroup.group_id' => $group_id
				),
				'fields' => array(
					'UserGroup.user_id',
					'User.user_name'
				),
				'joins' => array(
					array(
						'type' => 'INNER',
						'table' => 'users',
						'alias' => 'User',
						'conditions' => 'User.uniqid = UserGroup.user_id'
					)
				)
			);
			$result = $this->UserGroup->find('all', $params);
			$status = true;
			$result = $result;
			$error = null;
		}else{
			$status = false;
			$result = null;
			$error = array(
				'code' => 400,
				'message' => 'パラメータが不足しています。'
			);
		}
		return json_encode(compact('status', 'result', 'error'));
	}
	// 参照可能リストの取得
	public function lists($user_id = "")
	{
		// Viewへのレンダーを無効
		$this->autoRender = false;
		header("Content-Type: application/json; charset=utf-8");
		// Ajax以外の通信の場合エラー
		// if(!$this->request->is('ajax')) {
		//  throw new BadRequestException();
		// }
		if (! empty($user_id)) {
			$params = array(
				'conditions' => array(
					'UserGroup.user_id' => $user_id,
					'Group.limit_time >=' => date('Y-m-d', strtotime('now'))
				),
				'fields' => array(
					'UserGroup.group_id',
					'Group.group_name',
					"DATE_FORMAT(Group.limit_time,  '%Y-%m-%d' ) as 'limit_time'"
				),
				'joins' => array(
					array(
						'type' => 'INNER',
						'table' => 'groups',
						'alias' => 'Group',
						'conditions' => 'Group.firebase_group_id = UserGroup.group_id'
					)
				)
			);
			$row = $this->UserGroup->find('all', $params);
			foreach ($row as $value) {
				$result[] = array(
					"UserGroup" => array(
						'group_id' => $value['UserGroup']['group_id']
					),
					"Group" => array(
						'group_name'=> $value['Group']['group_name'],
						'limit_time'=> $value['0']['limit_time']
					),
				);
			}
			$status = true;
			$error = null;
		} else {
			$status = false;
			$result = null;
			$error = array(
				'code' => 400,
				'message' => 'パラメータが不足しています。'
			);
		}
		return json_encode(compact('status', 'result', 'error'));
	}

	// 参照可能リストの取得
	public function del($group_id = "", $user_id = "")
	{
		// Viewへのレンダーを無効
		$this->autoRender = false;
		header("Content-Type: application/json; charset=utf-8");
		// Ajax以外の通信の場合エラー
		// if(!$this->request->is('ajax')) {
		// throw new BadRequestException();
		// }

		if (! (empty($group_id) && empty($user_id))) {
			$param = array(
				'group_id' => $group_id,
				'user_id' => $user_id
			);
			$this->UserGroup
			->deleteAll($param);
			$status = true;
			$result = null;
			$error = null;
		} else {
			$status = false;
			$result = null;
			$error = array(
				'code' => 501,
				'message' => 'データが存在しません'
			);
		}
		return json_encode(compact('status', 'result', 'error'));
	}
	// グループに参加
	public function joining($group_id = "", $user_id = "")
	{
		$status = true;
		$error = array();
		// Viewへのレンダーを無効
		$this->autoRender = false;
		header("Content-Type: application/json; charset=utf-8");
		// Ajax以外の通信の場合エラー
		// if(!$this->request->is('ajax')) {
		// throw new BadRequestException();
		// }

		if (! (empty($group_id) && empty($user_id))) {
			$this->loadModel('Group');

			$params = array(
				'conditions' => array(
					'user_id' => $user_id,
					'group_id' => $group_id
				)
			);
			$result = $this->UserGroup->find('count', $params);
			$this->set('result', $result);
			if ($result != 0) {
				$status = false;
				$result = null;
				$error = array(
					'code' => 500,
					'message' => 'データが存在しません'
				);
				return json_encode(compact('status', 'result', 'error'));
			} else {
				$params = array(
					'conditions' => array(
						'firebase_group_id' => $group_id
					)
				);
				$result = $this->Group->find('count', $params);
				if (! $result == 0) {
					// グループと作成ユーザの紐付けを行う
					$data = array(
						'UserGroup' => array(
							'user_id' => $user_id,
							'group_id' => $group_id
						)
					);
					$this->UserGroup->save($data, false, $fields);
					$status = true;
					$result = null;
					$error = null;
					return json_encode(compact('status', 'result', 'error'));
				}
				$status = false;
				$result = null;
				$error = array(
					'code' => 501,
					'message' => 'データが存在しません'
				);
				return json_encode(compact('status', 'result', 'error'));
			}
		} else {
			$status = false;
			$result = null;
			$error = array(
				'code' => 400,
				'message' => 'パラメータが不足しています。'
			);
		}
		return json_encode(compact('status', 'result', 'error'));
	}

	public function joinRoom($groupId,$userId){
		$status = true;
		$error = array();
		header('Access-Control-Allow-Origin: *');
		// Viewへのレンダーを無効
		$this->autoRender = false;
		header("Content-Type: application/json; charset=utf-8");
		$data = array(
			'group_id' => $groupId,
			'user_id' => $userId
		);
		$this->UserGroup->save($data, false);
		$result = true;
		return json_encode(compact('status', 'result', 'error'));
	}

	// グループに参加
	public function joinjoin()
	{
		header('Access-Control-Allow-Origin: *');
		// Viewへのレンダーを無効
		$this->autoRender = false;
		header("Content-Type: application/json; charset=utf-8");
		// Ajax以外の通信の場合エラー
		if (! $this->request->is('ajax')) {
			throw new BadRequestException();
		}

		if (isset($_POST['group_id']) && isset($_POST['user_id'])) {
			$user_id = $_POST['group_id'];
			$group_id = $_POST['user_id'];

			for ($i = 0; $i < count($user_id); $i ++) {

				$this->loadModel('Group');

				$params = array(
					'conditions' => array(
						'user_id' => $user_id[i],
						'group_id' => $group_id[i]
					)
				);
				$result = $this->UserGroup->find('count', $params);
				$this->set('result', $result);
				if ($result != 0) {
					$status[i] = false;
					$result[i] = null;
					$error[i] = array(
						'code' => 500,
						'message' => 'データが存在しません'
					);
				} else {
					$params = array(
						'conditions' => array(
							'firebase_group_id' => $group_id[i]
						)
					);
					$result = $this->Group->find('count', $params);
					if (! $result == 0) {
						// グループと作成ユーザの紐付けを行う
						$data = array(
							'UserGroup' => array(
								'user_id' => $user_id[i],
								'group_id' => $group_id[i]
							)
						);
						$this->UserGroup->save($data, false, $fields);
						$status[i] = true;
						$result[i] = null;
						$error[i] = null;
					}
					$status[i] = false;
					$result[i] = null;
					$error[i] = array(
						'code' => 501,
						'message' => 'データが存在しません'
					);
				}
				return json_encode(compact('status', 'result', 'error'));
			}
		} else {
			$status = false;
			$result = null;
			$error = array(
				'code' => 400,
				'message' => 'パラメータが不足しています。'
			);
		}
		return json_encode(compact('status', 'result', 'error'));
	}
}
