<?php
//
// +---------------------------------------------------------------------+
// | CODE INC. SOURCE CODE                                               |
// +---------------------------------------------------------------------+
// | Copyright (c) 2017 - Code Inc. SAS - All Rights Reserved.           |
// | Visit https://www.codeinc.fr for more information about licensing.  |
// +---------------------------------------------------------------------+
// | NOTICE:  All information contained herein is, and remains the       |
// | property of Code Inc. SAS. The intellectual and technical concepts  |
// | contained herein are proprietary to Code Inc. SAS are protected by  |
// | trade secret or copyright law. Dissemination of this information or |
// | reproduction of this material  is strictly forbidden unless prior   |
// | written permission is obtained from Code Inc. SAS.                  |
// +---------------------------------------------------------------------+
//
// Author:   Joan Fabrégat <joan@codeinc.fr>
// Date:     08/03/2018
// Time:     17:04
// Project:  lib-doctrinesessionhandler
//
declare(strict_types = 1);
namespace CodeInc\DoctrineSessionHandler;
use CodeInc\DoctrineSessionHandler\SessionData\SessionDataEntityInterface;
use Doctrine\ORM\EntityManager;

/**
 * Class DoctrineSessionHandler
 *
 * @author Joan Fabrégat <joan@codeinc.fr>
 */
class DoctrineSessionHandler implements \SessionHandlerInterface {
	/**
	 * @var EntityManager
	 */
	private $entityManager;

	/**
	 * @var string
	 */
	private $sessionDataEntityClass;

	/**
	 * DoctrineSessionHandler constructor.
	 *
	 * @param EntityManager $entityManager
	 * @param string $sessionDataEntityClass
	 * @throws DoctrineSessionHandlerException
	 */
	public function __construct(EntityManager $entityManager, string $sessionDataEntityClass)
	{
		if (!is_subclass_of($sessionDataEntityClass, SessionDataEntityInterface::class)) {
			throw new DoctrineSessionHandlerException(
				sprintf("The class %s is anot a valid session data entity. The session data entity "
					."must implement %s.",
					$sessionDataEntityClass, SessionDataEntityInterface::class),
				$this
			);
		}
		$this->sessionDataEntityClass = $sessionDataEntityClass;
		$this->entityManager = $entityManager;
	}

	/**
	 * @inheritdoc
	 */
	public function open($save_path, $name):void { return; }

	/**
	 * @inheritdoc
	 * @return bool|void
	 */
	public function close():void { return; }

	/**
	 * @inheritdoc
	 * @throws \Exception
	 */
	public function gc($maxlifetime):bool
	{
		$dateTime = new \DateTime("now");
		$dateTime->sub(new \DateInterval("PT".(int)$maxlifetime."S"));

		$this->entityManager->createQueryBuilder()
			->delete($this->sessionDataEntityClass, "s")
			->where("s.lastHit < :t")
			->setParameter(":t", $dateTime)
			->getQuery()->execute();

		return true;
	}

	/**
	 * @inheritdoc
	 * @throws \Doctrine\ORM\ORMException
	 * @throws \Doctrine\ORM\OptimisticLockException
	 * @throws \Doctrine\ORM\TransactionRequiredException
	 */
	public function read($sessionId):string
	{
		if ($sessionData = $this->entityManager->find($this->sessionDataEntityClass, (string)$sessionId)) {
			/** @var SessionDataEntityInterface $sessionData */
			return $sessionData->getData();
		}
		return serialize([]);
	}

	/**
	 * @inheritdoc
	 * @param string $sessionId
	 * @param string $data
	 * @throws \Doctrine\ORM\ORMException
	 * @throws \Doctrine\ORM\OptimisticLockException
	 * @throws \Doctrine\ORM\TransactionRequiredException
	 */
	public function write($sessionId, $data):void
	{
		/** @var SessionDataEntityInterface $session */
		if ($session = $this->entityManager->find($this->sessionDataEntityClass, (string)$sessionId)) {
			$session->setData($data);
		}
		else {
			$session = new ($this->sessionDataEntityClass);
			$session->setId($sessionId);
			$session->setData($data);
		}
		$this->entityManager->persist($session);
	}

	/**
	 * @inheritdoc
	 * @throws \Doctrine\ORM\ORMException
	 * @throws \Doctrine\ORM\OptimisticLockException
	 * @throws \Doctrine\ORM\TransactionRequiredException
	 */
	public function destroy($sessionId):void
	{
		if ($session = $this->entityManager->find($this->sessionDataEntityClass, (string)$sessionId)) {
			/** @var SessionDataEntityInterface $session */
			$this->entityManager->remove($session);
		}
	}
}