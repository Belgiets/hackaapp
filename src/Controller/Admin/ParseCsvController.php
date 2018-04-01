<?php

namespace App\Controller\Admin;


use App\Serializer\PersonNameConverter;
use Symfony\Component\Form\Extension\Core\Type\FileType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\Serializer\Serializer;
use Symfony\Component\Serializer\Encoder\CsvEncoder;
use Symfony\Component\Serializer\Normalizer\ObjectNormalizer;
use App\Entity\Person;

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
     * @return \Symfony\Component\HttpFoundation\RedirectResponse|\Symfony\Component\HttpFoundation\Response
     *
     * @Route("/parse", name="parse_csv")
     */
    public function parseCsv(Request $request)
    {
        $form = $this->createFormBuilder()
            ->add('csvFile', FileType::class)
            ->add('parse', SubmitType::class, ['label' => 'Parse'])
            ->getForm();
        ;

        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $file = $form['csvFile']->getData();

            $encoders = [new CsvEncoder()];
            $nameConverter = new PersonNameConverter();
            $normalizers = [new ObjectNormalizer(null, $nameConverter)];
            $serializer = new Serializer($normalizers, $encoders);

            $rows = $serializer->decode(file_get_contents($file), 'csv');

            foreach ($rows as $row) {
                $serializer->denormalize($row, Person::class);
            }
//            $test = $serializer->deserialize($data, Person::class, 'csv');
//            $test = $serializer->denormalize($file, Person::class, 'csv');

            // ... perform some action, such as saving the task to the database
            // for example, if Task is a Doctrine entity, save it!
            // $entityManager = $this->getDoctrine()->getManager();
            // $entityManager->persist($task);
            // $entityManager->flush();
        }

        return $this->render('parse_csv/parseCsv.html.twig', [
            'form' => $form->createView(),
        ]);
    }
}
