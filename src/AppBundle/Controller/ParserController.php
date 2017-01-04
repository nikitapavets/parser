<?php
namespace AppBundle\Controller;

use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;

class ParserController extends Controller
{
	/**
	 * @Route("/parser", name="parser")
	 */
	public function parserAction()
	{
		$rows = 1001;

		return $this->render('parser/parsing.html.twig', array(
			'rows' => $rows,
		));
	}
}