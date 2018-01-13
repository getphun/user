<?php
/**
 * user service
 * @package user
 * @version 0.0.1
 * @upgrade true
 */

namespace User\Service;
use UserEmail\Model\UserEmail as UEmail;
use UserPhone\Model\UserPhone as UPhone;

class User {

    private $_user;
    private $_cookie_name = 'phun-user';
    private $_cookie_expr = (60*60*24*7);   // one week
    private $_sess;
    
    public function __construct(){
        $dis = &\Phun::$dispatcher;
        
        $hash = $dis->req->getCookie($this->_cookie_name);
        if(!$hash)
            return;
        
        $session = \User\Model\UserSession::get(['hash'=>$hash], false);
        if(!$session)
            return false;
        
        $expiration = strtotime($session->expired);
        if($expiration < time())
            return false;
        
        // Increase session expiration if it's almost expired
        $perten_expiration = $expiration - time();
        if($perten_expiration < (60*60*24)){
            \User\Model\UserSession::remove(['hash' => $session->hash]);
            return $this->loginById($session->user);
        }
        
        $user = \User\Model\User::get(['id' => $session->user], false);
        
        if(!$user || !$user->status)
            return false;
        
        $this->_sess = $session;
        $this->_user = $user;
    }
    
    private function _createSession($user_id){
        $session = [
            'user' => $user_id,
            'hash' => $this->genPassword(time().'-'.$user_id.'.'.uniqid()),
            'expired' => date('Y-m-d H:i:s', (time()+$this->_cookie_expr))
        ];
        
        $session['id'] = \User\Model\UserSession::create($session);
        $this->_sess = (object)$session;
        
        \Phun::$dispatcher->res->addCookie($this->_cookie_name, $session['hash'], $this->_cookie_expr);
        
        return true;
    }
    
    public function genPassword($password){
        return password_hash($password, PASSWORD_DEFAULT);
    }
    
    public function isLogin(){
        return (bool)$this->_user;
    }
    
    public function loginById($user_id, $session=true){
        $user = \User\Model\User::get($user_id, false);
        if(!$user || !$user->status)
            return false;
        
        $this->_user = $user;
        
        return $session ? $this->_createSession($user->id) : true;
    }
    
    public function loginByCred($name, $password){
        $dis = &\Phun::$dispatcher;
        
        $loginBy = $dis->config->user['loginBy'];
        
        $name = strtolower($name);
        $user = null;
        
        // check if login by email
        if(module_exists('user-email') && $loginBy['email'] && filter_var($name, FILTER_VALIDATE_EMAIL)){
            $user_email = UEmail::get(['address' => $name], false);
            if($user_email)
                $user = \User\Model\User::get(['id' => $user_email->user], false);
        }
        
        // check if login by phone number
        if(module_exists('user-phone') && $loginBy['phone'] && preg_match('!^\+([0-9- ]+)[0-9]$!', $name)){
            $phone_number = preg_replace('![^0-9+]!', '', $name);
            $user_phone = UPhone::get(['number' => $phone_number], false);
            if($user_phone)
                $user = \User\Model\User::get(['id' => $user_phone->user], false);
        }
        
        if(!$user && $loginBy['name'])
            $user = \User\Model\User::get(['name' => $name], false);
        
        if(!$user)
            return false;
        
        if(!$user->status)
            return false;
        
        if(!$this->testPassword($password, $user->password))
            return false;
        
        return $this->_createSession($user->id);
    }
    
    public function logout(){
        if(!$this->_sess)
            return true;
        \Phun::$dispatcher->res->addCookie($this->_cookie_name, '', -1000);
        \User\Model\UserSession::remove(['hash' => $this->_sess->hash]);
        
        $this->_user = null;
        $this->_sess = null;
    }
    
    public function testPassword($plain, $hashed){
        return password_verify($plain, $hashed);
    }
    
    public function __get($name){
        if(!$this->isLogin())
            return null;
        $result = $this->_user->$name ?? null;
        if(!is_null($result))
            return $result;
        
        if($name === 'object')
            return $this->_user;
        if($name === 'login')
            return $this->isLogin();
        if($name === 'session')
            return $this->_sess;
            
        // check if requested data is provide by user_property module
        if(module_exists('user-property'))
            return \UserProperty\Library\User::fetch($name);
        return null;
    }
}