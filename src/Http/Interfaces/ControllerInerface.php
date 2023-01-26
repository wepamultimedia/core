<?php

namespace Wepa\Core\Http\Interfaces;

interface ControllerInerface
{
	public function create();
	
	public function store();
	
	public function edit();
	
	public function update();
	
	public function destroy();
}