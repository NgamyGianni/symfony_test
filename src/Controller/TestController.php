<?php

namespace App\Controller;

use App\Entity\Episode;
use App\Entity\User;
use App\Form\ArticleFormType;
use App\Form\SignUpType;
use Doctrine\Persistence\ManagerRegistry;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;
use Doctrine\Persistence\ObjectManager;
use Symfony\Component\Security\Http\Authentication\AuthenticationUtils;

class TestController extends AbstractController
{
    
    /**
     * @Route("/Article/num/{id}", name="ArticleByid")
     */
    public function index(ManagerRegistry $doctrine, int $id): Response
    {
        $article = $doctrine->getRepository(Episode :: class)->find($id);

        if (!$article) {
            throw $this->createNotFoundException(
                'No product found for id '."1"
            );
        }

        return $this->render('test/article.html.twig', [
            'controller_name' => 'TestController',
            'article' => $article
        ]);
    }

    /**
     * @Route("/", name="home")
     */
    public function home(ManagerRegistry $doctrine): Response
    {
        $articles = $doctrine->getRepository(Episode :: class)->findAll();
        $actes = array();
        for($i=0; $i < sizeof($articles); $i++){
            if(!in_array($articles[$i]->getActe(), $actes))  array_push($actes, $articles[$i]->getActe());
        }
        asort($actes);
        if (!$articles) {
            throw $this->createNotFoundException(
                'No product found for id '."1"
            );
        }
        return $this->render('test/index.html.twig', [
            'controller_name' => 'TestController',
            'articles' => $articles,
            'actes' => $actes,
        ]);
    }

    /**
     * @Route("/Article/new", name="newArticle", methods={"GET", "POST"})
     */
    public function newArticle(Request $request): Response
    {
        $article = new Episode();
        $form = $this->createForm(ArticleFormType::class, $article);
        $form->handleRequest($request);

        if ($form->isSubmitted()) {
            if($form->isValid()){
                $entityManager = $this->getDoctrine()->getManager();
                $entityManager->persist($article);
                $entityManager->flush();

                $this->addFlash('success', 'Le nouveau modèle d\'article a été ajouté');

                return $this->redirectToRoute('home');
            }else{
                $this->addFlash('error', 'Le formulaire est incorrect');
            }
        }
        return $this->render('test/form.html.twig', [
            'form' => $form->createView(),
        ]);
    }

     /**
     * @Route("/Article/{id}/modify", name="modifyArticle", methods={"GET", "POST"})
     */
    public function editArticle(Request $request, Episode $episode): Response
    {
        $form = $this->createForm(ArticleFormType::class, $episode);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $this->getDoctrine()->getManager()->flush();

            $this->addFlash('success', 'Le modèle d\'article a été modifié');

            return $this->redirectToRoute('home');
        }
        if ($form->isSubmitted()) {
            $this->addFlash('error', 'Le formulaire est incorrect');
        }

        return $this->render('test/form.html.twig', [
            'modele_article' => $episode,
            'form' => $form->createView(),
            'mode' => 'edit',
        ]);
    }

    /**
     * @Route("/Article/{id}/delete", name="deleteArticle", methods={"GET", "DELETE"})
     */
    public function deleteArticle(Episode $episode): Response
    {
        $entityManager = $this->getDoctrine()->getManager();
        $entityManager->remove($episode);
        $entityManager->flush();
        $this->addFlash('success', 'Le modèle d\'article a été supprimé');
        
        return $this->redirectToRoute('home');
    }

    /**
     * @Route("/User/new", name="newUser", methods={"GET", "POST"})
     */
    public function newUser(Request $request, UserPasswordHasherInterface $encoder): Response
    {
        $user = new User();
        $form = $this->createForm(SignUpType::class, $user);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $plaintextPassword = $user->getPassword();
            $hashedPassword = $encoder->hashPassword(
                $user,
                $plaintextPassword
            );
            $user->setPassword($hashedPassword);
            $entityManager = $this->getDoctrine()->getManager();
            $entityManager->persist($user);
            $entityManager->flush();

            $this->addFlash('success', 'Le nouveau modèle d\'article a été ajouté');
            return $this->redirectToRoute('home');
        }
        return $this->render('test/signup.html.twig', [
            'form' => $form->createView(),
        ]);
    }

    /**
     * @Route("/User/login", name="userLogin"), methods={"GET", "POST"})
     */
    public function login(AuthenticationUtils $auth): Response
    {
        return $this->render('test/signin.html.twig', [
        ]);
    }
}