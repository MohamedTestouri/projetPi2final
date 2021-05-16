<?php

namespace App\Controller;

use App\Entity\CategorieFilm;
use App\Entity\Film;
use App\Form\FilmType;
use App\Repository\CategorieFilmRepository;
use App\Repository\FilmRepository;
use Knp\Component\Pager\PaginatorInterface;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Form\Extension\Core\Type\DateTimeType;
use Symfony\Component\Form\Extension\Core\Type\DateType;
use Symfony\Component\Form\Extension\Core\Type\FileType;
use Symfony\Component\Form\Extension\Core\Type\IntegerType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\HttpFoundation\File\Exception\FileException;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Serializer\Encoder\JsonEncoder;
use Symfony\Component\Serializer\Normalizer\DateTimeNormalizer;
use Symfony\Component\Serializer\Normalizer\NormalizerInterface;
use Symfony\Component\Serializer\Normalizer\ObjectNormalizer;
use Symfony\Component\Serializer\Serializer;


class FilmController extends AbstractController
{
    /**
     * @Route("/film/api/show", name="api_show_film")
     */
    public function showAll(NormalizerInterface $Normalizer){
        $test = $this->getDoctrine()->getRepository(Film::class)->findAll();
        $serializer = new Serializer([new DateTimeNormalizer(), new ObjectNormalizer()]);
        $formatted = $Normalizer->normalize($test, 'json',['groups' =>'film:read']);
        return new JsonResponse($formatted);
    }
    /**
     * @Route("/film/api/show/category", name="api_show_film_category")
     */
    public function showByCategory(NormalizerInterface $Normalizer, Request $request){
        $test = $this->getDoctrine()->getRepository(Film::class)->getByCategory($request->get('idCategory'));
        $serializer = new Serializer([new DateTimeNormalizer(), new ObjectNormalizer()]);
        $formatted = $Normalizer->normalize($test, 'json',['groups' =>'film:read']);
        return new JsonResponse($formatted);
    }
    /**
     * @Route("/film", name="film")
     */
    public function index(): Response
    {
        return $this->render('backtemplate.html.twig', [
            'controller_name' => 'FilmController',
        ]);
    }

    /**
     * @param $id
     * @param FilmRepository $repository
     * @param CategorieFilmRepository $repository1
     * @return Response
     * @Route ("affichageFilm/{id}",name="affichageFilm")
     */
    public function afficherFilm($id,FilmRepository $repository,CategorieFilmRepository $repository1,PaginatorInterface $paginator,Request $request){
        $categorie=$repository1->findAll();
        $categ=$repository1->find($id);
        $films=$repository->findBy(['categorie'=>$categ]);
        $pagination=$paginator->paginate($films,
            $request->query->get('page',1),2
        );
        return $this->render('film/affichageFilmFront.html.twig',['films'=>$pagination,'categorie'=>$categorie]);

    }

    /**
     * @param FilmRepository $repository
     * @param $id
     * @return Response
     * @Route ("detailFilm/{id}",name="detailFilm")
     */
    public function detailFilm(FilmRepository $repository,CategorieFilmRepository $repository1,$id){
        $categorie=$repository1->findAll();
        $film=$repository->find($id);
        return $this->render('film/detailFilmFront.html.twig',['film'=>$film,'categorie'=>$categorie]);
    }

    /**
     * @param Request $request
     * @return Response
     * @Route ("ajouterFilm",name="ajouterFilm")
     */
    public function ajouterFilm(Request $request){
        $film=new Film();
        $form=$this->createForm(FilmType::class,$film);
        $form->add('Ajouter',SubmitType::class,[
            'attr'=>[
                'class'=>"btn btn-primary"
            ]
        ]);
        $form->handleRequest($request);
        if($form->isSubmitted() && $form->isValid()){
            $file = $form->get('image')->getData();
            $fileName=md5(uniqid()).'.'.$file->guessExtension();
            try{
                $file->move(
                    $this->getParameter('images_directory'),
                    $fileName
                );
            }catch (FileException $e){

            }
            $film->setImage($fileName);
            $em=$this->getDoctrine()->getManager();
            $em->persist($film);
            $em->flush();
            $this->addFlash('success', 'film ajouté avec succès ');
            return $this->redirectToRoute('affichageFilmBack',['id'=>$film->getCategorie()->getId()]);
        }
        return $this->render('film/ajoutFilmBack.html.twig',['form'=>$form->createView()]);
    }

    /**
     * @param FilmRepository $repository
     * @param CategorieFilmRepository $repository1
     * @param $id
     * @return Response
     * @Route ("affichageFilmBack/{id}",name="affichageFilmBack")
     */
    public function afficherFilmBack(FilmRepository $repository,CategorieFilmRepository $repository1,$id){
        $categorie=$repository1->find($id);
        $film=$repository->findBy(['categorie'=>$categorie]);
        return $this->render('film/affichageFilmBack.html.twig',['film'=>$film]);
    }

    /**
     * @param FilmRepository $repository
     * @param $id
     * @return \Symfony\Component\HttpFoundation\RedirectResponse
     * @Route ("suppressionFilmBack/{id}",name="suppressionFilmBack")
     */
    public function supprimerFilm(FilmRepository $repository,$id){
        $film=$repository->find($id);
        $idCategorie=$film->getCategorie()->getId();
        $em=$this->getDoctrine()->getManager();
        $em->remove($film);
        $em->flush();
        $this->addFlash('danger', 'film supprimé ');
        return $this->redirectToRoute('affichageFilmBack',['id'=>$idCategorie]);
    }

    /**
     * @param $id
     * @param FilmRepository $repository
     * @param Request $request
     * @return \Symfony\Component\HttpFoundation\RedirectResponse|Response
     * @Route ("modificationFilmBack/{id}",name="modificationFilmBack")
     */
    public function modifierFilm($id,FilmRepository $repository,Request $request){
        $film=$repository->find($id);
        $form=$this->createFormBuilder($film)
            ->add('titre',TextType::class,[
                'attr'=>[
                    'class'=>"form-control",
                    'placeholder'=>"saisir le titre"
                ]
            ])
            ->add('description',TextareaType::class,[
                'attr'=>[
                    'class'=>"form-control",
                    'placeholder'=>"saisir la description"
                ]
            ])
            ->add('duree',DateTimeType::class,[
                'attr'=>[
                    'class'=>'flatpickr flatpickr-input form-control'
                ]
            ])
            ->add('datesortie',DateTimeType::class,[
                'attr'=>[
                    'date_widget' => 'single_text',
                    'class'=>"form-control"
                ]
            ])
            ->add('note',IntegerType::class,[
                'attr'=>[
                    'class'=>"form-control",
                    'placeholder'=>"saisir note (d'après source)"
                ]
            ])
            ->add('realisepar',TextType::class,[
                'attr'=>[
                    'class'=>"form-control",
                    'placeholder'=>"saisir le nom du réalisateur"
                ]
            ])
            ->add('categorie',EntityType::class,[
                'attr'=>[
                    'class'=>"form-control department-name select2input"],
                'class' =>CategorieFilm::class ,
                'choice_label' =>'type'
            ])

            ->getForm();
        $form->add('Modifier',SubmitType::class,[
            'attr'=>[
                'class'=>"btn btn-primary"
            ]
        ]);
        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid()){
            $em=$this->getDoctrine()->getManager();
            $em->flush();
            $this->addFlash('success', 'Film modifié avec succès ');
            return $this->redirectToRoute('affichageFilmBack',['id'=>$film->getCategorie()->getId()]);
        }
        return $this->render('film/modifierFilmBack.html.twig',['form'=>$form->createView()]);
    }

    /**
     * @param FilmRepository $repository
     * @return Response
     * @Route ("affichageListeFilm",name="affichageListeFilm")
     */
    public function afficherListeFilm(FilmRepository $repository,CategorieFilmRepository $repository1){
        $categorie=$repository1->findAll();
        $films=$repository->findAll();
        return $this->render('film/affichageListeFilmBack.html.twig',['film'=>$films,'categorie'=>$categorie]);
    }

    /**
     * @param FilmRepository $repository
     * @Route("triParTitre",name="triParTitre")
     */
    public function triParTitre(FilmRepository $repository){
        $films=$repository->triParTitre();
        return $this->render('film/affichageListeFilmBack.html.twig',['film'=>$films]);
    }
    /**
     * @param FilmRepository $repository
     * @Route("triParDate",name="triParDate")
     */
    public function triParDate(FilmRepository $repository){
        $films=$repository->triParDate();
        return $this->render('film/affichageListeFilmBack.html.twig',['film'=>$films]);
    }
    /**
     * @param FilmRepository $repository
     * @Route("triParNote",name="triParNote")
     */
    public function triParNote(FilmRepository $repository){
        $films=$repository->triParNote();
        return $this->render('film/affichageListeFilmBack.html.twig',['film'=>$films]);
    }


    /**
     * @param FilmRepository $repository
     * @Route("triParTitreF",name="triParTitreF")
     */
    public function triParTitreF(FilmRepository $repository,CategorieFilmRepository $repository1){
        $categorie=$repository1->findAll();
        $films=$repository->triParTitre();
        return $this->render('film/affichageFilmFront.html.twig',['films'=>$films,'categorie'=>$categorie]);
    }
    /**
     * @param FilmRepository $repository
     * @Route("triParDateF",name="triParDateF")
     */
    public function triParDateF(FilmRepository $repository,CategorieFilmRepository $repository1){
        $categorie=$repository1->findAll();
        $films=$repository->triParDate();
        return $this->render('film/affichageFilmFront.html.twig',['films'=>$films,'categorie'=>$categorie]);
    }
    /**
     * @param FilmRepository $repository
     * @Route("triParNoteF",name="triParNoteF")
     */
    public function triParNoteF(FilmRepository $repository,CategorieFilmRepository $repository1){
        $categorie=$repository1->findAll();
        $films=$repository->triParNote();
        return $this->render('film/affichageFilmFront.html.twig',['films'=>$films,'categorie'=>$categorie]);
    }

    /**
     * @param FilmRepository $repository
     * @return Response
     * @Route("affichageListeFilmFront",name="affichageListeFilmFront")
     */
    public function afficherListeFilmFront(FilmRepository $repository,CategorieFilmRepository $repository1,PaginatorInterface $paginator,Request $request){
        $categorie=$repository1->findAll();
        $films=$repository->findAll();
        $pagination=$paginator->paginate($films,
            $request->query->get('page',1),2
        );
        return $this->render('film/affichageListeFilmFront.html.twig',['films'=>$pagination,'categorie'=>$categorie]);
    }

}

