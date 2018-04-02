<?php

namespace App\Controller\Admin;


use App\Service\CsvParser;
use Symfony\Component\Form\Extension\Core\Type\FileType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;

/**
 * Class ParseCsvController
 * @package App\Controller
 *
 * @Route("/admin/csv")
 */
class ParseCsvController extends Controller
{
    /**
     * @param Request $request
     * @param CsvParser $parser
     * @return \Symfony\Component\HttpFoundation\Response
     *
     * @Route("/parse", name="parse_csv")
     */
    public function parseCsv(Request $request, CsvParser $parser)
    {
        $form = $this->createFormBuilder()
            ->add('csvFile', FileType::class)
            ->add('parse', SubmitType::class, ['label' => 'Parse'])
            ->getForm();
        ;

        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $result = $parser->parse($form['csvFile']->getData());
        }

        return $this->render('parse_csv/parseCsv.html.twig', [
            'result' => empty($result) ? '' : $result,
            'form' => $form->createView(),
        ]);
    }
}
