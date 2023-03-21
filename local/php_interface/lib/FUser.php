<?php

namespace L;

use Bitrix\Main;
use Bitrix\Main\Type\RandomSequence;
use \Bitrix\Main\Web\Cookie;
use \Bitrix\Main\Application;
use L\DataManager\FUserTable;


class FUser
{
	const CODE_LENGTH = 10;
	
	public static function getId()
	{
		
		return self::getByCode();
	}
	
	/**
	 * Return fuser code.
	 *
	 * @return int
	 */
	public static function getCurrent(): int
    {
		return self::getByCode();
	}
	
	public static function getByCode()
	{
		
		$res = FUserTable::getList([
			'filter' => [
				'CODE' => $_COOKIE["BITRIX_SM_USER_SALE_CODE"],
			],
			'select' => [
				'ID',
			],
			'order' => ['ID' => "DESC"],
		]);
		if ($fuserData = $res->fetch()) {
			self::setCodeInCookie($_COOKIE["BITRIX_SM_USER_SALE_CODE"]);
			return (int)$fuserData['ID'];
		} else {
			$r = static::createFUser();
			if ($r->isSuccess()) {
				return $r->getId();
			}
		}
		
		return false;
	}
	
	protected static function createFUser()
	{
		$code = self::generateCode();
		$fields = [
			'DATE_INSERT' => new Main\Type\DateTime(),
			'DATE_UPDATE' => new Main\Type\DateTime(),
			'USER_ID' => 0,
			'CODE' => $code,
		];
		
		self::setCodeInCookie($code);
		
		return FUserTable::add($fields);
	}
	
	protected static function generateCode(): string
    {
		do {
			$code = '';
			
			while (strlen($code) < self::CODE_LENGTH) {
				$rs = new RandomSequence(time() . (random_int(1, 10000) + strlen($code) * random_int(1, 10000)) . __FILE__);
				$code .= preg_replace('/[^a-z0-9]/', '', $rs->randString(12));
			}
			
			$code = substr($code, 0, self::CODE_LENGTH);
			
			$result = FUserTable::getList([
				'filter' => ['=CODE' => $code],
				'limit' => 1,
				'select' => ['ID'],
			]);
		} while ($result->fetch());
		
		return $code;
	}
	
	public static function setCodeInCookie($code): void
    {
		$context = Application::getInstance()->getContext();
		$cookie = new Cookie("USER_SALE_CODE", $code, time() + 86400 * 90);
		$cookie->setDomain($context->getServer()->getHttpHost());
		$cookie->setPath("/");
		$cookie->setSecure(false);
		$cookie->setHttpOnly(false);
		
		$context->getResponse()->addCookie($cookie);
		$context->getResponse()->flush("");
	}
}