<?php

use Codeception\Util\ActionSequence;

class DashAcceptanceTester extends AcceptanceTester
{
    public function login($username, $password){
        $this->fillField('#UserName',$username);
        $this->fillField('#Password', $password);
        $this->click('Log in');
    }

    public function assertContains($a, $b, $text){
        if(strpos($b,$a)===false){
            $this->fail($text);
        }
    }

    public function assertEquals($a, $b, $text){
        if($a != $b){
            $this->fail($text);
        }
    }

    public function assertNotEquals($a, $b, $text){
        if($a == $b){
            $this->fail($text);
        }
    }
}