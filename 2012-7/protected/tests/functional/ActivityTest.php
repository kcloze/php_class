<?php

class ActivityTest extends WebTestCase
{
	public $fixtures=array(
		'activitys'=>'Activity',
	);

	public function testShow()
	{
		$this->open('?r=activity/view&id=1');
	}

	public function testCreate()
	{
		$this->open('?r=activity/create');
	}

	public function testUpdate()
	{
		$this->open('?r=activity/update&id=1');
	}

	public function testDelete()
	{
		$this->open('?r=activity/view&id=1');
	}

	public function testList()
	{
		$this->open('?r=activity/index');
	}

	public function testAdmin()
	{
		$this->open('?r=activity/admin');
	}
}
