<?php

class Card extends Model
{
	public $table = 'cards';
    public $debug = true;

	public function hello()
	{
		echo("Hello World");
		echo("<br>");
	}
}