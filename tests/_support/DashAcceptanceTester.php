<?php

use Codeception\Util\ActionSequence;
use Codeception\Util\Locator;
use Codeception\Util\Shared\Asserts;
use Helper\Acceptance;

class DashAcceptanceTester extends AcceptanceTester
{
    protected $showForTests = 'A-LIB';
    protected $scenarioForTests = 'MASTER';

    public function login($username, $password){
        $this->fillField('#UserName',$username);
        $this->fillField('#Password', $password);
        $this->click('Log in');
    }

    public function loader(){
        $this->waitForElementVisible('.request-progress-bar__wrapper.wave-loader', 20);
        $this->waitForElementNotVisible('.request-progress-bar__wrapper.wave-loader', 40);
    }

    public function showOnesPage(){
        $this->waitForElementClickable(Locator::contains('span', 'Show Ones'), 20);
        $this->wait(1);
        $this->click(Locator::contains('span', 'Show Ones'));
    }

    public function onesTab(){
        $this->click('//*[@class="VTab__header"]//*[contains(text(),"Ones")]');
    }

    public function selectShow(){
        $this->waitForElementClickable('(//*[@class="show-ones__header-select"])[1]', 40);
        $this->click('(//*[@class="show-ones__header-select"])[1]');
        $show = $this->showForTests;
        $this->click($show);
        return $show;
    }

    public function selectScenario(){
        $this->click('(//*[@class="show-ones__header-select"])[2]');
        $scenario = $this->scenarioForTests;
        $this->click($scenario);
        return $scenario;
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
        if ($a == $b) {
            $this->fail($text);
        }
    }

    public function elementIsHere($locator){
        try {
            $this->seeElement($locator);
        }
        catch (\Exception $exception){
            return false;
        }
        return true;
    }
}