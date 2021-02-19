<?php

use Codeception\Util\ActionSequence;
use Codeception\Util\Locator;
use Codeception\Util\Shared\Asserts;
use Helper\Acceptance;

class DashAcceptanceTester extends AcceptanceTester
{
    protected $showForTests = 'AQA-TEST';
    protected $scenarioForTests = 'MASTER';
    /**
     * @var Acceptance
     */
    protected $helper = null;

    public function login($username, $password){
        $this->fillField('#UserName',$username);
        $this->fillField('#Password', $password);
        $this->click('Log in');
    }

    public function loader(){
        try{
            $this->waitForElementVisible('.request-progress-bar__wrapper.wave-loader', 5);
            $this->waitForElementNotVisible('.request-progress-bar__wrapper.wave-loader', 120);
        }
        catch (\Exception $exception){
            return false;
        }
    }

    public function showOnesPage(){
        $this->waitForElementClickable(Locator::contains('span', 'Show Ones'), 60);
        $this->wait(1);
        $this->click(Locator::contains('span', 'Show Ones'));
    }

    public function onesTab(){
        $atr = $this->grabAttributeFrom('//*[@class="VTab__header"]//*[contains(text(),"Ones")]', 'class');
        if (strpos($atr, 'active') !== true) {
            $this->waitForElementClickable('//*[@class="VTab__header"]//*[contains(text(),"Ones")]', 60);
            $this->click('//*[@class="VTab__header"]//*[contains(text(),"Ones")]');
        }
    }

    public function manageShowsPage(){
        $this->waitForElementClickable(Locator::contains('span', 'Manage Shows'), 60);
        $this->wait(1);
        $this->click(Locator::contains('span', 'Manage Shows'));
    }

    public function selectShow(){
        $this->waitForElementClickable('(//*[@class="show-ones__header-select"])[1]', 60);
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

    public function logout(){
        $this->click('//*[@class="user-name"]');
        $this->click(Locator::contains('span', 'Log out'));
    }

    public function dlDeptOnesPage(){
        $this->waitForElementClickable('//*[text()="DL Dept. Ones"]', 60);
        $this->wait(1);
        $this->click('//*[text()="DL Dept. Ones"]');
    }

    public function addpositionpopup(){
        $this->click('.item__info__department-add-icon');
        $this->waitForElementVisible('#AddPositionPopup', 20);
    }

    public function addoneposition($a, $b){
        $this->click('(//*[@id="AddPositionPopup"]//*[contains(@class,"ui-checkbox")])[' . $a . ']');
        $this->waitForElementVisible('(//input[contains(@id,"VInput")])[' . $b . ']');
        $this->fillField('(//input[contains(@id,"VInput")])[' . $b . ']', '1');
    }

    public function confirmaddposition(){
        $this->click('//*[@id="AddPositionPopup"]//button[contains(text(), "Add")]');
    }

    public function numberofendposition(){
        $i = 2;
        $position = '//*[@id="app"]//div[2]/div[2]/div[2]/div/div[2]/div[2]/div/div[' . $i . ']';
        $atr = $this->grabAttributeFrom($position, 'class');
        while (strpos($atr, 'item_artist') !== false):
            $i++;
            $position = '//*[@id="app"]//div[2]/div[2]/div[2]/div/div[2]/div[2]/div/div[' . $i . ']';
            $atr = $this->grabAttributeFrom($position, 'class');
        endwhile;
        return array ($i, $position);
    }

    public function columnsnumber(Acceptance $acceptanceHelper){
        $this->helper = $acceptanceHelper;
        $column = '(//*[contains(@class, "item_artist")])[1]//*[contains(@class, "row__cell")]';
        $columns = $this->helper->findElements($column);
        $columnsNumber = count($columns);
        return $columnsNumber;
    }

    public function addrandomgreymark($i, $columnsNumber){
        $x = $i - 1;
        $addOnesPopup = null;
        while ($addOnesPopup !== false):
            $a = rand(1, $x);
            $b = rand(9, $columnsNumber);
            $ones = '((//*[contains(@class, "item_artist")])[' . $a . ']//*[contains(@class, "row__cell")])[' . $b . ']';
            $this->waitForElementVisible($ones);
            $this->click($ones);
            $this->waitForElementVisible('//*[@id="BookPopup"]');
            $this->click('//*[@id="BookPopup"]//button[contains(text(), "Confirm")]');
            $addOnesPopup = $this->elementIsHere('//*[contains(@class, "toast-message")]');
        endwhile;
        return array ($a, $b);
    }

    public function save(){
        $this->waitForElementClickable(Locator::contains('button', 'File'), 20);
        $this->click(Locator::contains('button', 'File'));
        $this->waitForElementClickable(Locator::contains('span', 'Save'), 20);
        $this->click(Locator::contains('span', 'Save'));
    }

    public function showonespublish(){
        $this->waitForElementClickable(Locator::contains('button', 'File'), 20);
        $this->click(Locator::contains('button', 'File'));
        $this->waitForElementClickable(Locator::contains('a', 'Publish'), 20);
        $this->click(Locator::contains('a', 'Publish'));
        $this->click('((//*[contains(@class, "publishPopup__body__tabs-group")])[1]//*[contains(@class, "ui-checkbox_default")])[1]');
        $this->click('((//*[contains(@class, "publishPopup__body__tabs-group")])[2]//*[contains(@class, "ui-checkbox_default")])[1]');
        $this->click('//*[@class="publishPopup"]//button[contains(text(), "Send")]');
    }

    public function deptpublish(){
        $this->waitForElementClickable(Locator::contains('button', 'File'));
        $this->click(Locator::contains('button', 'File'));
        $this->waitForElementClickable(Locator::contains('a', 'Publish'));
        $this->click(Locator::contains('a', 'Publish'));
    }

    public function selectcalendarperiod(){
        $this->waitForElementVisible('.form-control.reportrange-text', 20);
        $this->click('.form-control.reportrange-text');
        $this->waitForElementVisible('.calendars-container', 20);
        $this->click('//*[contains(@class, "left")]//*[contains(@class, "next")]');
        $this->click('//*[contains(@class, "left")]//*[contains(@class, "next")]');
        $this->click('//*[contains(@class, "left")]//tr[3]/td[1]');
        $this->click('//*[contains(@class, "right")]//tr[6]/td[7]');
    }

    public function addpositionindept(){
        $this->waitForElementClickable('.fa-user-plus', 20);
        $this->click('.fa-user-plus');
        //select type = CH
        $this->waitForElementVisible('(//*[@id="vueAddPositionsPopup"]//*[contains(@type,"button")])[2]', 20);
        $this->click('(//*[@id="vueAddPositionsPopup"]//*[contains(@type,"button")])[2]');
        $this->waitForElementClickable('//li[contains(@value, "CH")]', 20);
        $this->click('//li[contains(@value, "CH")]');
        //select Seniority = Lead
        $this->waitForElementClickable('(//*[@id="vueAddPositionsPopup"]//*[contains(@type,"button")])[3]', 20);
        $this->click('(//*[@id="vueAddPositionsPopup"]//*[contains(@type,"button")])[3]');
        $this->waitForElementVisible('(//li[contains(@value, "Lead")])[2]', 20);
        $this->click('(//li[contains(@value, "Lead")])[2]');
        //set salary
        $this->waitForElementClickable('(//*[@id="vueAddPositionsPopup"]//input[contains(@type,"text")])[3]', 20);
        $sel_val = $this->grabValueFrom('(//*[@id="vueAddPositionsPopup"]//input[contains(@type,"text")])[3]');
        echo('Salary is: ' . $sel_val . "\n");
        if ($sel_val <= 0) {
            $this->fillField('(//*[@id="vueAddPositionsPopup"]//input[contains(@type,"text")])[3]', '17000');
        }
        //select start date
        $this->waitForElementClickable('(//*[@id="vueAddPositionsPopup"]//input[contains(@type,"text")])[4]', 20);
        $this->click('(//*[@id="vueAddPositionsPopup"]//input[contains(@type,"text")])[4]');
        $this->waitForElementVisible('.today', 20);
        $this->click('.today');
        //select end date
        $this->waitForElementClickable('(//*[@id="vueAddPositionsPopup"]//input[contains(@type,"text")])[5]', 20);
        $this->click('(//*[@id="vueAddPositionsPopup"]//input[contains(@type,"text")])[5]');
        $this->waitForElementVisible('//tr[5]/td[7]', 20);
        $this->click('//tr[5]/td[7]');
        //Add
        $this->waitForElementClickable('//*[@id="vueAddPositionsPopup"]//button[contains(text(),"Add")]', 20);
        $this->click('//*[@id="vueAddPositionsPopup"]//button[contains(text(),"Add")]');
    }

    public function clearcart(){
        $this->deptpublish();
        try{
            $this->elementIsHere('//*[@id="publishPopup"]');
            $this->waitForElementClickable('//*[@id="publishPopup"]//button[contains(text(), "Yes, Publish")]');
            $this->click('//*[@id="publishPopup"]//button[contains(text(), "Yes, Publish")]');
        }
        catch (\Exception $exception) {
            $this->waitForElementClickable('//*[@id="publishWarningPopup"]//button[contains(text(), "Yes, Publish")]');
            $this->click('//*[@id="publishWarningPopup"]//button[contains(text(), "Yes, Publish")]');
            $this->loader();
            $popup = $this->elementIsHere('//*[@id="publishOnesPrioritiesPopup"]');
            if ($popup === true) {
                $this->waitForElementClickable('//*[@id="publishOnesPrioritiesPopup"]//button[contains(text(), "Yes, Publish")]');
                $this->click('//*[@id="publishOnesPrioritiesPopup"]//button[contains(text(), "Yes, Publish")]');
            }
        }
        $this->loader();
        $this->waitForElementNotVisible('//*[contains(@class, "toast-message")]');
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

    public function assertGreaterOrEquals($a, $b, $text){
        if($a < $b){
            $this->fail($text);
        }
    }

    public function assertGreaterThan($a, $b, $text){
        if($a <= $b){
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