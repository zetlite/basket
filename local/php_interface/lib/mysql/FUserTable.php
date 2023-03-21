<?php

namespace L\DataManager;

use Bitrix\Main\Entity\{
	DataManager,
	DatetimeField,
	IntegerField,
	StringField,
	Event
};

class FUserTable extends DataManager
{
	/**
	 * Returns DB table name for entity.
	 *
	 * @return string
	 */
	public static function getTableName(): string
    {
		return 'l_sale_fuser';
	}
	
	/**
	 * Returns entity map definition.
	 *
	 * @return array
	 */
	public static function getMap(): array
    {
		global $DB;
		
		return [
			'ID' => [
				'data_type' => 'integer',
				'primary' => true,
				'autocomplete' => true,
			],
			'DATE_INSERT' => [
				'data_type' => 'datetime',
			],
			'DATE_INS' => [
				'data_type' => 'datetime',
				'expression' => [
					$DB->DatetimeToDateFunction('%s'), 'DATE_INSERT',
				],
			],
			'DATE_UPDATE' => [
				'data_type' => 'datetime',
			],
			'DATE_UPD' => [
				'data_type' => 'datetime',
				'expression' => [
					$DB->DatetimeToDateFunction('%s'), 'DATE_UPDATE',
				],
			],
			new IntegerField(
				"USER_ID"
			),
			
			'USER' => [
				'data_type' => 'Bitrix\Main\User',
				'reference' => ['=this.USER_ID' => 'ref.ID'],
			],
			
			new StringField(
				'CODE',
				[
					'size' => 32,
				]),
		];
	}
}