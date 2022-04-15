<?php

class UsersController extends AppController
{

	public $helpers = array(
		'Html',
		'Form'
	);

	public function index()
	{
		throw new NotFoundException();
	}

	public function app()
	{
		$params = array(
			'fields' => array(
				'id',
				'uniqid',
				'user_name',
				'created',
				'modified'
			)
		);
		$result = $this->User->find('all', $params);
		$this->set('userLists', $result);
	}

	public function lists()
	{
		// Viewへのレンダーを無効
		$this->autoRender = false;
		header("Content-Type: application/json; charset=utf-8");
		// Ajax以外の通信の場合エラー
		// if(!$this->request->is('ajax')) {
		// throw new BadRequestException();
		// }
		$params = array(
			'fields' => array(
				'uniqid',
				'user_name'
			)
		);
		$result = $this->User->find('all', $params);
		$status = true;
		$result = $result;
		$error = null;
		return json_encode(compact('status', 'result', 'error'));
	}
	// ユーザの登録
	public function add($user_name = "")
	{
		// Viewへのレンダーを無効
		$this->autoRender = false;
		header("Access-Control-Allow-Origin: *");
		header("Content-Type: application/json; charset=utf-8");
		// Ajax以外の通信の場合エラー
		// if(!$this->request->is('ajax')) {
		// throw new BadRequestException();
		// }
		if (! empty($user_name)) {
			$uniqid = md5(uniqid(mt_rand(), true));
			$data = array(
				'User' => array(
					'user_name' => $user_name,
					'uniqid' => $uniqid
				)
			);
			$fields = array(
				'user_name',
				'uniqid'
			);
			$this->User->save($data, false, $fields);
			$status = true;
			$result = array(
				'id' => $uniqid
			);
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
	// ユーザの情報の更新（ニックネーム）
	public function upd($user_id = "", $user_name = "")
	{
		// Viewへのレンダーを無効
		$this->autoRender = false;
		header("Content-Type: application/json; charset=utf-8");
		// Ajax以外の通信の場合エラー
		// if(!$this->request->is('ajax')) {
		// throw new BadRequestException();
		// }
		if (! (empty($user_id) && empty($user_name))) {
			$params = array(
				'conditions' => array(
					'uniqid' => $user_id
				),
				'fields' => 'id'
			);
			$result = $this->User->find('first', $params);
			if (empty($result)) {
				$status = false;
				$result = null;
				$error = array(
					'code' => 404,
					'message' => 'データが見つかりません'
				);
			} else {
				// 更新する内容を設定
				$data = array(
					'User' => array(
						'id' => $result['User']['id'],
						'user_name' => $user_name
					)
				);

				// 更新する項目（フィールド指定）
				$fields = array(
					'user_name'
				);

				// 更新
				$this->User->save($data, false, $fields);
				$status = true;
				$result = null;
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
