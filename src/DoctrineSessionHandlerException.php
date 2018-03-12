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
// Time:     17:31
// Project:  lib-doctrinesessionhandler
//
declare(strict_types = 1);
namespace CodeInc\DoctrineSessionHandler;
use Throwable;


/**
 * Class DoctrineSessionHandlerException
 *
 * @package CodeInc\DoctrineSessionHandler
 * @author Joan Fabrégat <joan@codeinc.fr>
 */
class DoctrineSessionHandlerException extends \Exception
{
	/**
	 * @var DoctrineSessionHandler
	 */
	private $sessionHandler;

	/**
	 * DoctrineSessionHandlerException constructor.
	 *
	 * @param string $message
	 * @param DoctrineSessionHandler $sessionHandler
	 * @param int|null $code
	 * @param null|Throwable $previous
	 */
	public function __construct(string $message, DoctrineSessionHandler $sessionHandler,
		?int $code = null, ?Throwable $previous = null)
	{
		$this->sessionHandler = $sessionHandler;
		parent::__construct($message, $code, $previous);
	}

	/**
	 * @return DoctrineSessionHandler
	 */
	public function getSessionHandler():DoctrineSessionHandler
	{
		return $this->sessionHandler;
	}
}