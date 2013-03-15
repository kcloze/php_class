<?php

class ActivityPageTest extends WebTestCase
{
	public $fixtures=array(
		'activityPages'=>'ActivityPage',
	);

	public function testShow()
	{
		$this->open('?r=activityPage/view&id=1');
	}

	public function testCreate()
	{
		$this->open('?r=activityPage/create');
	}

	public function testUpdate()
	{
		$this->open('?r=activityPage/update&id=1');
	}

	public function testDelete()
	{
		$this->open('?r=activityPage/view&id=1');
	}

	public function testList()
	{
		$this->open('?r=activityPage/index');
	}

	public function testAdmin()
	{
		$this->open('?r=activityPage/admin');
	}
}
