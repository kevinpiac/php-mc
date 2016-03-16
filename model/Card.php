<?php

class Card extends Model
{
	public $table = 'cards';

	public function hello()
	{
		echo("Hello World");
		echo("<br>");
	}
}