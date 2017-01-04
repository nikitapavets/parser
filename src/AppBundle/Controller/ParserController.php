<?php
namespace AppBundle\Controller;

use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Symfony\Component\HttpFoundation\Response;

class ParserController
{
	/**
	 * @Route("/parser")
	 */
	public function parserAction()
	{
		return new Response('Hello!');
	}
}