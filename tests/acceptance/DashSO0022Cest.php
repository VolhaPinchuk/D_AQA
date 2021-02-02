<?php

use Codeception\Util\ActionSequence;
use Codeception\Util\Locator;
use Codeception\Util\Shared\Asserts;
use Helper\Acceptance;

class DashSO0022Cest
{
    protected $helper = null;

    public function _before(DashAcceptanceTester $I, Acceptance $acceptanceHelper)
    {
        $this->helper = $acceptanceHelper;
    }

    //calendar period test
    public function calendarperiodtest(DashAcceptanceTester $I)
    {
        $I->amOnPage('/');

        //login as show
        $I->login('show', 'show');

        //open Show Ones page
        $I->showOnesPage();
        $I->loader();

        //open Ones tab
        $I->onesTab();

        //select show
        $show = $I->selectShow();
        $I->loader();

        //select calendar period
        $I->waitForElementVisible('.form-control.reportrange-text', 20);
        $I->click('.form-control.reportrange-text');
        $I->waitForElementVisible('.calendars-container');
        $I->click('//*[contains(@class, "left")]//tr[3]/td[1]');
        $I->click('//*[contains(@class, "right")]//tr[6]/td[7]');

        //grab dates info
        $startDate=$I->grabTextFrom('//*[contains(@class, "left")]//tr[3]/td[1]');
        $startMonthAndYear=$I->grabTextFrom('//*[contains(@class, "left")]//*[@class="month"]');
        $startMonth=substr($startMonthAndYear, 0, -5);
        $endDate=$I->grabTextFrom('//*[contains(@class, "right")]//tr[6]/td[7]');
        if ($endDate<7){
            $I->click('//*[contains(@class, "right")]//*[contains(@class, "next")]');
            $endMonthAndYear=$I->grabTextFrom('//*[contains(@class, "right")]//*[@class="month"]');
            $endMonth=substr($endMonthAndYear, 0, -5);
        }
        else {
            $endMonthAndYear = $I->grabTextFrom('//*[contains(@class, "right")]//*[@class="month"]');
            $endMonth = substr($endMonthAndYear, 0, -5);
        }

        $I->click(Locator::contains('button', 'Confirm'));
        $I->click(Locator::contains('button', 'Apply'));
        $I->loader();

        //grab months from the table
        $elementsMonth = $this->helper->findElements('.header__month-name');
        $elementsMonthNumber=count($elementsMonth);
        $firstMonth=$I->grabTextFrom('(//*[@class="header__month-name"])[1]');
        $lastMonthLocator='(//*[@class="header__month-name"])[' . $elementsMonthNumber . ']';
        $lastMonth=$I->grabTextFrom($lastMonthLocator);

        //assert correct months are shown in the table
        $I->assertEquals($startMonth, $firstMonth, 'Another month is shown as first');
        $I->assertEquals($endMonth, $lastMonth, 'Another month is shown as last');

        //grab dates from the table
        $firstDate=$I->grabTextFrom('(//*[@class="header__weekView__info"])[2]');

        $ButtonLocator1 = '(//*[@class="header__month-row"]//*[contains(@class, "header__month__icon_days")])[' . $elementsMonthNumber . ']';
        $I->click($ButtonLocator1);
        $I->loader();
        $ButtonLocator2 = '((//*[@class="header__month-row"])[' . $elementsMonthNumber . ']//*[@class="header__month__icon"])[2]';
        $I->click($ButtonLocator2);
        $I->loader();

        $elementsDate = $this->helper->findElements('(//*[contains(@class, "background_day")])[1]//*[@class="header__dayView"]');
        $elementsDateNumber = count($elementsDate);
        $i = $elementsDateNumber*2;
        $elementsDateLocator = '((//*[contains(@class, "background_day")])[1]//*[@class="header__dayView__info"])[' . $i . ']';
        $lastDate = $I->grabTextFrom($elementsDateLocator);

        //assert correct dates are shown in the table
        $I->assertEquals($startDate, $firstDate, 'Another date is shown as first');
        $I->assertEquals($endDate, $lastDate, 'Another date is shown as last');
    }
}
