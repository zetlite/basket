<?php

namespace L\DataManager;

use Bitrix\Main\Entity\{DataManager, DatetimeField, IntegerField, StringField, Event};

class BasketTable extends DataManager
{
	public static function getTableName(): string
    {
		return "l_sale_basket";
	}
	
	public static function getMap(): array
    {
		return [
			new IntegerField('ID', [
				'primary' => true,
				'autocomplete' => true,
			]),
			new IntegerField('FUSER_ID', [
				'required' => true,
			]),
			new IntegerField('PRODUCT_ID', [
				'required' => true,
			]),
			new IntegerField('IBLOCK_ID'),
			new IntegerField('IBLOCK_SECTION_ID'),
			new IntegerField('QUANTITY'),
			
			new StringField('NAME'),
			new StringField('DETAIL_PAGE_URL'),
			new StringField('PREVIEW_PICTURE'),
			new StringField('DETAIL_PICTURE'),
			
			
			new DatetimeField('DATE_INSERT'),
			new DatetimeField('DATE_UPDATE'),
		];
	}
	
	public static function add(array $data)
	{
		$property = $data["PROPERTY"];
		
		unset($data["PROPERTY"]);
		if ($result = parent::add($data)) {
			if ($result->isSuccess()){
				foreach ($property as $k => $v) {
					BasketPropertyTable::add([
						"BASKET_ID" => $result->getId(),
						"NAME" => $k,
						"VALUE" => $v
					]);
				}
				return $result;
			}
		}
	
		return false;
	}
	
	public static function onDelete(Event $event)
	{
		parent::onDelete($event);
		
		$props = BasketPropertyTable::getList([
			'filter' => [
				"BASKET_ID" => $event->getParameter('id'),
			],
			'select' => ["*"]
		])->fetchAll();
		
		foreach ($props as $prop) {
			BasketPropertyTable::delete($prop["ID"]);
		}
	}
}