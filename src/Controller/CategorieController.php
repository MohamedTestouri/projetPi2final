<?php

namespace App\Controller;

use App\Entity\CategorieFilm;
use App\Entity\Film;
use App\Form\CategorieType;
use App\Repository\CategorieFilmRepository;
use Ob\HighchartsBundle\Highcharts\Highchart;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Serializer\Normalizer\DateTimeNormalizer;
use Symfony\Component\Serializer\Normalizer\NormalizerInterface;
use Symfony\Component\Serializer\Normalizer\ObjectNormalizer;
use Symfony\Component\Serializer\Serializer;

class CategorieController extends AbstractController
{
    /**
     * @Route("/categorie/api/show", name="api_show_categorie")
     */
    public function showAll(NormalizerInterface $Normalizer){
        $test = $this->getDoctrine()->getRepository(CategorieFilm::class)->findAll();
        $serializer = new Serializer([new DateTimeNormalizer(), new ObjectNormalizer()]);
        $formatted = $Normalizer->normalize($test, 'json',['groups' =>'categorie:read']);
        return new JsonResponse($formatted);
    }
    /**
     * @Route("/categorie", name="categorie")
     */
    public function index(): Response
    {
        return $this->render('categorie/index.html.twig', [
            'controller_name' => 'CategorieController',
        ]);
    }

    /**
     * @param CategorieFilmRepository $repository
     * @return Response
     * @Route ("affichageCategorie",name="affichageCategorie")
     */
    public function afficherCategorie(CategorieFilmRepository $repository){
        $categorie=$repository->findAll();
        return $this->render('categorie/afficherCategorieFront.html.twig', [
            'categorie'=>$categorie
        ]);
    }

    /**
     * @param Request $request
     * @return \Symfony\Component\HttpFoundation\RedirectResponse|Response
     * @Route ("ajoutCategorieBack",name="ajoutCategorieBack")
     */
    public function ajoutCategorie(Request $request){
        $categorie= new CategorieFilm();
        $form=$this->createForm(CategorieType::class,$categorie);
        $form->add('Ajouter',SubmitType::class,[
            'attr'=>[
                'class'=>"btn btn-primary"
            ]
        ]);
        $form->handleRequest($request);
        if($form->isSubmitted() && $form->isValid()){
            $em=$this->getDoctrine()->getManager();
            $em->persist($categorie);
            $em->flush();
            return $this->redirectToRoute('affichageCategorieBack');
        }
        return $this->render('categorie/ajoutCategorie.html.twig',[
            'form'=>$form->createView()
        ]);
    }

    /**
     * @param CategorieFilmRepository $repository
     * @return Response
     * @Route("affichageCategorieBack",name="affichageCategorieBack")
     */
    public function affichageCategorieBack(CategorieFilmRepository $repository){
        $categorie=$repository->findAll();

        $ob = new Highchart();
        $ob->chart->renderTo('linechart');
        $ob->title->text('statistique des offre ');
        $ob->plotOptions->pie(array(
            'allowPointSelect'  => true,
            'cursor'    => 'pointer',
            'dataLabels'    => array('enabled' => false),
            'showInLegend'  => true
        ));
        $stat=$repository->stat();
        $data =array();
        foreach ($stat as $values)
        {
            $a =array($values['type'],intval($values['nb']));
            array_push($data,$a);
        }

        $ob->series(array(array('type' => 'pie','name' => 'Browser share', 'data' => $data)));

        return $this->render('categorie/afficherCategorieBack.html.twig',['categorie'=>$categorie,'chart'=>$ob]);
    }

    /**
     * @param $id
     * @param CategorieFilmRepository $repository
     * @return \Symfony\Component\HttpFoundation\RedirectResponse
     * @Route ("supprimerCategorieBack/{id}",name="supprimerCategorieBack")
     */
    public function supprimerCategorie($id,CategorieFilmRepository $repository){
        $categorie=$repository->find($id);
        $em=$this->getDoctrine()->getManager();
        $em->remove($categorie);
        $em->flush();
        return $this->redirectToRoute('affichageCategorieBack');
    }

    /**
     * @param $id
     * @param CategorieFilmRepository $repository
     * @param Request $request
     * @return \Symfony\Component\HttpFoundation\RedirectResponse|Response
     * @Route ("modifierCategorieBack/{id}",name="modifierCategorieBack")
     */
    public function modifierCategorie($id,CategorieFilmRepository $repository,Request $request){
        $categorie=$repository->find($id);
        $form=$this->createForm(CategorieType::class,$categorie);
        $form->add('Modifier',SubmitType::class,[
            'attr'=>[
                'class'=>"btn btn-primary"
            ]
        ]);
        $form->handleRequest($request);
        if($form->isSubmitted() && $form->isValid()){
            $em=$this->getDoctrine()->getManager();
            $em->flush();
            return $this->redirectToRoute('affichageCategorieBack');
        }
        return $this->render('categorie/modifierCategorie.html.twig',[
            'form'=>$form->createView()
        ]);
    }

    /**
     * @param CategorieFilmRepository $repository
     * @return Response
     * @Route ("tripParNom",name="tripParNom")
     */
    public function triParNom(CategorieFilmRepository $repository){
        $categorie=$repository->tri();

        $ob = new Highchart();
        $ob->chart->renderTo('linechart');
        $ob->title->text('statistique des offre ');
        $ob->plotOptions->pie(array(
            'allowPointSelect'  => true,
            'cursor'    => 'pointer',
            'dataLabels'    => array('enabled' => false),
            'showInLegend'  => true
        ));
        $stat=$repository->stat();
        $data =array();
        foreach ($stat as $values)
        {
            $a =array($values['type'],intval($values['nb']));
            array_push($data,$a);
        }

        $ob->series(array(array('type' => 'pie','name' => 'Browser share', 'data' => $data)));

        return $this->render('categorie/afficherCategorieBack.html.twig',['categorie'=>$categorie,'chart'=>$ob]);
    }
}
