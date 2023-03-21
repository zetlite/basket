<?php

namespace L\DataManager;

use Bitrix\Main\Entity\{DataManager, DateField, IntegerField, StringField};

class BasketPropertyTable extends DataManager
{
	public static function getTableName(): string
    {
		return "l_sale_basket_property";
	}
	
	public static function getMap(): array
    {
		return [
			new IntegerField('ID', [
				'primary' => true,
				'autocomplete' => true,
			]),
			new IntegerField('BASKET_ID', [
				'required' => true,
			]),
			new StringField('NAME'),
			new StringField('VALUE'),
		];
	}
}