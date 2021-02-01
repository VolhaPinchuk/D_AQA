<?php

use Codeception\Util\ActionSequence;
use Codeception\Util\Locator;
use Codeception\Util\Shared\Asserts;
use Helper\Acceptance;

class DashAcceptanceTester extends AcceptanceTester
{
    protected $showForTests = 'A-LIB';
    protected $scenarioForTests = '123';

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
        $this->waitForElementVisible(Locator::contains('span', 'Show Ones'), 20);
        $this->wait(1);
        $this->click(Locator::contains('span', 'Show Ones'));
    }

    public function onesTab(){
        $this->click('//*[@class="VTab__header"]//*[contains(text(),"Ones")]');
    }

    public function selectShow(){
        $this->waitForElementVisible('.show-ones__header-select:nth-child(2)');
        $this->click('.show-ones__header-select:nth-child(2)');
        $show = $this->showForTests;
        $this->click($show);
        return $show;
    }

    public function selectScenario(){
        $this->click('.show-ones__header-select:nth-child(4)');
        $scenario = $this->scenarioForTests;
        $I->click($scenario);
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

    public function assertNotEquals($a, $b, $text)
    {
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