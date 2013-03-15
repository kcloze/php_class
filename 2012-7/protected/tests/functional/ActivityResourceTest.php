<?php

class ActivityResourceTest extends WebTestCase
{
	public $fixtures=array(
		'activityResources'=>'ActivityResource',
	);

	public function testShow()
	{
		$this->open('?r=activityResource/view&id=1');
	}

	public function testCreate()
	{
		$this->open('?r=activityResource/create');
	}

	public function testUpdate()
	{
		$this->open('?r=activityResource/update&id=1');
	}

	public function testDelete()
	{
		$this->open('?r=activityResource/view&id=1');
	}

	public function testList()
	{
		$this->open('?r=activityResource/index');
	}

	public function testAdmin()
	{
		$this->open('?r=activityResource/admin');
	}
}
