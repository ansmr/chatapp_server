<?php
class GroupsController extends AppController {
  public $helpers = array('Html', 'Form');

  public function index() {
    $this->set('groups', $this->User->find('all'));
  }
  // ユーザの登録
  public function add( $group_name="",$user_id="",$term="" )
  {
    // Viewへのレンダーを無効
    $this->autoRender = false;
    header("Content-Type: application/json; charset=utf-8");
    // Ajax以外の通信の場合エラー
    //if(!$this->request->is('ajax')) {
    //  throw new BadRequestException();
    //}
    if(!(empty($group_name) && empty($user_id)))
    {
      if(isset( $term ))
      {
        switch ($term){
            case 'd3':
                $limit_time =  date("Y-m-d 23:59:59",strtotime("+3 day"));
                break;
            case 'w1':
                $limit_time =  date("Y-m-d 23:59:59",strtotime("+7 day"));
                break;
            case 'm1':
                $limit_time =  date("Y-m-d 23:59:59",strtotime("+1 month"));
                break;
            default:
                $limit_time = date('Y-n-j 23:59:59', strtotime('+3 day'));
                break;
        }
      }else{
        $limit_time = date('Y-n-j 23:59:59', strtotime('+7 day'));
      }
      $this->loadModel('UserGroup');
      $uniqid = substr(md5( uniqid(mt_rand(), true) ),0,20);
      $data = array('Group' => 
                array('group_name'        =>  $group_name,
                      'firebase_group_id' =>  $uniqid,
                      'limit_time'        =>  $limit_time
                )
              );
      $fields = array('group_name','firebase_group_id','limit_time');
      $this->Group->save($data, false, $fields);
      $status = true;

      // グループと作成ユーザの紐付けを行う
      $data = array('UserGroup' =>
                array('user_id'  =>  $user_id,
                      'group_id' =>  $uniqid
                )
              );
      $fields = array('user_id','group_id');
      $this->UserGroup->save($data, false, $fields);
      $result = array(
            'group_id' => $uniqid
      );
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


  // ユーザの情報の更新（ニックネーム）
  public function upd( $group_id="", $group_name=""){
    // Viewへのレンダーを無効
    $this->autoRender = false;
    header("Content-Type: application/json; charset=utf-8");
    // Ajax以外の通信の場合エラー
    //if(!$this->request->is('ajax')) {
    //  throw new BadRequestException();
    //}
    if(!(empty($group_id) && empty($group_name))){
      $params = array(
                  'conditions' => array('uniqid' => $group_id),
                  'fields'     => 'id'
                );
      $result = $this->Group->find('first',$params);
      if(empty($result)){
        $status = false;
        $result = null;
        $error = array(
            'code' => 404,
            'message' => 'データが見つかりません' 
        );
      }else{
        // 更新する内容を設定
        $data = array('Group' => array('id' => $result['Group']['id'], 'group_name' => $group_name));
         
        // 更新する項目（フィールド指定）
        $fields = array('group_name');
         
        // 更新
        $this->Group->save($data, false, $fields);
        $status = true;
        $result = null;
      }
      
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
}
