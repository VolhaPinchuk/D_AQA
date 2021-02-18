<?php

use Codeception\Util\ActionSequence;
use Codeception\Util\Locator;
use Codeception\Util\Shared\Asserts;
use Helper\Acceptance;

class DashSO00121Cest
{
    protected $helper = null;

    public function _before(DashAcceptanceTester $I, Acceptance $acceptanceHelper)
    {
        $this->helper = $acceptanceHelper;
    }

    //mark ones for Lead as grey
    public function markonesgreylead(DashAcceptanceTester $I)
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

        $total = $I->grabTextFrom('(//*[@class="item__info__department-seniority-split"])[1]');
        $k = 0;
        $l = null;
        $m = null;
        while ($m != '/'):
            $m = $total[$k];
            $l = $l . $m;
            $k++;
        endwhile;
        $l = intval($l);
        $n1 = substr($total, $k);

        //find number of the end position in the first department
        list ($i, $position) = $I->numberofendposition();

        //open Add position popup
        $I->addpositionpopup();

        //add position Lead
        $I->addoneposition(1, 1);
        $I->confirmaddposition();

        //find columns number
        $columnsNumber = $I->columnsnumber($acceptanceHelper);

        //add random grey mark
        list ($a, $b) = $I->addrandomgreymark($i, $columnsNumber);

        $totalAfterAddMark = $I->grabTextFrom('(//*[@class="item__info__department-seniority-split"])[1]');

        //assert total did not change after add mark
        $I->assertEquals($total, $totalAfterAddMark, 'Total changed after add mark');

        //save mark
        $I->save();
        $I->loader();

        //check mark was saved
        $totalAfterSave = $I->grabTextFrom('(//*[@class="item__info__department-seniority-split"])[1]');
        $k = 0;
        $p = null;
        $m = null;
        while ($m != '/'):
            $m = $totalAfterSave[$k];
            $p = $p . $m;
            $k++;
        endwhile;
        $p = intval($p)-1;
        $n2 = substr($totalAfterSave, $k);

        //assert total changed and the difference is equal 1
        $I->assertNotEquals($total, $totalAfterSave, 'Total was not changed');
        $I->assertEquals($l, $p, 'The difference is not equal 1');
        $I->assertEquals($n1, $n2, 'Other part of total was not changed');
    }
}
