<?php

namespace L;

use L\DataManager\{BasketTable, BasketPropertyTable};

use Bitrix\Main\Type\DateTime;

class Basket
{
    /**
     * @var array|mixed
     */
    private mixed $data;

    public function __construct($data = [])
	{
		if (!empty($data)) {
			$this->data = $data;
		}
	}
	
	public function add()
	{
		if (empty($this->data["ID"])) {
			return false;
		}
		
		if ($id = $this->getIdByProduct($this->data["ID"])) {
			return $this->update($id, [
				"QUANTITY" => $this->data["QUANTITY"],
			]);
		}
		
		$property = $this->getProperty($this->data);
		
		$date = new DateTime();
		$res = BasketTable::add([
			'FUSER_ID' => FUser::getCurrent(),
			'PRODUCT_ID' => $this->data["ID"],
			'IBLOCK_ID' => $this->data["IBLOCK_ID"],
			'IBLOCK_SECTION_ID' => $this->data["IBLOCK_SECTION_ID"],
			'NAME' => $this->data["NAME"],
			'DETAIL_PAGE_URL' => $this->data["DETAIL_PAGE_URL"],
			'PREVIEW_PICTURE' => $this->data["PREVIEW_PICTURE"],
			'DETAIL_PICTURE' => $this->data["DETAIL_PICTURE"],
			'DATE_INSERT' => $date,
			'DATE_UPDATE' => $date,
			'QUANTITY' => $this->data["QUANTITY"] ?: 1,
			'PROPERTY' => $property,
		]);
		
		if ($res->isSuccess()) {
			return $res->getId();
		}
		
		return false;
	}
	
	public function update($id, $data)
	{
		return BasketTable::update(
			$id,
			$data
		);
	}
	
	public function getIdByProduct($id)
	{
		$dbResult = BasketTable::getList([
			'filter' => [
				'ID' => $id,
				'FUSER_ID' => FUser::getCurrent(),
			],
			'select' => ["ID"],
		]);
		
		if ($res = $dbResult->fetch()) {
			return $res["ID"];
		}
		
		return false;
	}
	
	public static function getList(): array
    {
		
		$dbResults = BasketTable::getList([
			'filter' => [
				'FUSER_ID' => FUser::getCurrent(),
			],
			'select' => ["*"],
		]);
		
		$items = [];
		
		while ($basketItem = $dbResults->fetch()) {
			$items[] = $basketItem;
		}
		
		return $items;
	}
	
	public static function getListProperty($filter = []): array
    {
		
		$dbResult = BasketPropertyTable::getList([
			'filter' => $filter,
			'select' => ["*"],
		]);
		$prop = [];
		while ($res = $dbResult->fetch()) {
			$prop[$res["BASKET_ID"]][$res["NAME"]] = $res["VALUE"];
		}
		
		return $prop;
	}
	
	public static function getProperty($array): array
    {
		$prop = [];
		foreach ($array as $k => $v) {
			if (str_contains($k, "PROPERTY")) {
				$prop[$k] = $v;
			}
		}
		
		return $prop;
	}
	
	public static function deleteAll(): void
    {
		$items = self::getList();
		
		foreach ($items as $item) {
			BasketTable::delete($item["ID"]);
		}
	}
	
	public static function delete($filter): void
    {
		$dbResult = BasketTable::getList([
			'filter' => $filter,
			'select' => ["ID"],
		]);
		
		if ($arRes = $dbResult->fetch()) {
			BasketTable::delete($arRes["ID"]);
		}
	}
}