<?php
namespace AppBundle\Controller;

use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Ddeboer\DataImport\Reader\CsvReader;
use AppBundle\Entity\Product;

class ParserController extends Controller
{
	/**
	 * @Route("/parser", name="parser")
	 */
	public function parserAction()
	{

		/**
		 * @var Product $product
		 */

		$rows = 1001;
		$parser = $this->get('app.parser');
		$parser->setParameters($this->getParameter('kernel.root_dir').'/documents/stock.csv');
		$reader = $parser->readFile();
		foreach ($reader as $row) {
			print_r($row);
		}

		$repository = $this->getDoctrine()->getRepository('AppBundle:Product');
		$products = $repository->findAll();

		foreach ($products as $product) {
			echo $product->getProductName();
		}


		return $this->render('parser/parsing.html.twig', array(
			'rows' => $rows,
		));
	}
}