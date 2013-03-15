<?php

class AdminTest extends WebTestCase
{
	public $fixtures=array(
		'admins'=>'Admin',
	);

	public function testShow()
	{
		$this->open('?r=admin/view&id=1');
	}

	public function testCreate()
	{
		$this->open('?r=admin/create');
	}

	public function testUpdate()
	{
		$this->open('?r=admin/update&id=1');
	}

	public function testDelete()
	{
		$this->open('?r=admin/view&id=1');
	}

	public function testList()
	{
		$this->open('?r=admin/index');
	}

	public function testAdmin()
	{
		$this->open('?r=admin/admin');
	}
}
