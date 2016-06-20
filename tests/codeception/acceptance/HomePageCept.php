<?php 
$I = new AcceptanceTester($scenario);
$I->wantTo('Verify Home Page');
$I->amOnPage('/web');
$I->see('All Users', 'h1');