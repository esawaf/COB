<?php

namespace app\models;

class User extends \yii\db\ActiveRecord implements \yii\web\IdentityInterface
{
    public $id;
    public $username;
    public $password;
    public $type;
    public $active;
    public $confirmed;
    public $organization_id;
    public $selected_location;
    public $role;
    public $insurance_id;


    public $authKey;
    public $accessToken;
	
	public static function tableName()
    {
        return 'login';
    }
	private static $user;
    //private static $users = [
    //    '100' => [
    //        'id' => '100',
    //        'username' => 'admin',
    //        'password' => 'admin',
    //        'authKey' => 'test100key',
    //        'accessToken' => '100-token',
    //    ],
    //    '101' => [
    //        'id' => '101',
    //        'username' => 'demo',
    //        'password' => 'demo',
    //        'authKey' => 'test101key',
    //        'accessToken' => '101-token',
    //    ],
    //];


    /**
     * {@inheritdoc}
     */
    public static function findIdentity($id)
    {
        $userObj = Login::findOne($id);
        $user = new User();
        $user->id = $id;
        $user->username = $userObj["username"];
        $user->password = $userObj["password"];
        $user->type = $userObj["type"];
        $user->active = $userObj["active"];
        $user->confirmed = $userObj["confirmed"];
        $user->organization_id = $userObj["organization_id"];
        $user->selected_location = $userObj["selected_location"];
        $user->role = $userObj["type"];
        $user->insurance_id = $userObj["insurance_id"];
        return $user;
    }
	//
    ///**
    // * {@inheritdoc}
    // */
    public static function findIdentityByAccessToken($token, $type = null)
    {
        //foreach (self::$users as $user) {
        //    if ($user['accessToken'] === $token) {
        //        return new static($user);
        //    }
        //}
	
        //return null;
    }

    /**
     * Finds user by username
     *
     * @param string $username
     * @return static|null
     */
    public function findUser($username,$password)
    {
		$userObj = Login::find()->where(array("username"=>$username,"password"=>sha1($password)))->all();
		
		if(count($userObj)==1){
                    $user = $userObj[0];
                    $this->username=$user["username"];
                    $this->type = $user["type"];
                    $this->id=$user['id'];
                    $this->organization_id = $user["organization_id"];
                    $this->selected_location = $user["selected_location"];
                    $this->role = $user["type"];
                    $this->insurance_id = $user["insurance_id"];
                    self::$user['username'] = $user['username'];
                    self::$user['password'] = $user['password'];
                    self::$user['authKey'] = 'test100key';
                    self::$user['accessToken'] = '100-token';
                    self::$user['organization_id'] = $user["organization_id"];
                    self::$user['selected_location'] = $user["selected_location"];
                    self::$user['role'] = $user["type"];
                    self::$user['insurance_id'] = $user["insurance_id"];
                    return $this;
		}else{
                    return null;
		}
    }

    /**
     * {@inheritdoc}
     */
    public function getId()
    {
        return $this->id;
    }

    /**
     * {@inheritdoc}
     */
    public function getAuthKey()
    {
        return $this->authKey;
    }
	//
    ///**
    // * {@inheritdoc}
    // */
    public function validateAuthKey($authKey)
    {
        return $this->authKey === $authKey;
    }
	//
    ///**
    // * Validates password
    // *
    // * @param string $password password to validate
    // * @return bool if password provided is valid for current user
    // */
    public function validatePassword($password)
    {
        return $this->password === $password;
    }
}
