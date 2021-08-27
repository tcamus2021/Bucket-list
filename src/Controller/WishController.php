<?php

namespace App\Controller;

use App\Entity\Category;
use App\Entity\Wish;
use App\Form\AddWishFormType;
use App\Form\UpdateWishFormType;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class WishController extends AbstractController
{
    private $em;

    public function  __construct(EntityManagerInterface $em){
        $this->em = $em;
    }

    /**
     * @Route("/wishlist", name="wish")
     */
    public function list(): Response
    {
        $repository = $this->getDoctrine()->getRepository(Wish::class);
        $wishes = $repository->findBy(['isPublished' => 'true'], ['dateCreated' => 'DESC']);
        $repository = $this->getDoctrine()->getRepository(Category::class);
        $categories = $repository->findAll();
        return $this->render('wish/index.html.twig', [
            'titre' => 'Wishlist',
            'wishes' => $wishes,
            'categories' => $categories,
        ]);
    }

    /**
     * @Route("/wishlist/{id}", name="wishFilter")
     */
    public function listFilter(Category $category): Response
    {
        $repository = $this->getDoctrine()->getRepository(Wish::class);
        $wishes = $repository->findBy(['isPublished' => 'true', 'category' => $category->getId()], ['dateCreated' => 'DESC']);
        $repository = $this->getDoctrine()->getRepository(Category::class);
        $categories = $repository->findAll();
        return $this->render('wish/index.html.twig', [
            'titre' => 'Wishlist',
            'wishes' => $wishes,
            'categories' => $categories,
        ]);
    }

    /**
     * @Route("/wish/{id}", name="details")
     */
    public function details(Wish $wish): Response
    {
        return $this->render('wish/details.html.twig', [
            'titre' => 'Wishlist',
            'wish' => $wish
        ]);
    }

    /**
     * @Route("/addwish", name="addwish")
     */
    public function ajout(Request $request): Response
    {
        $wish = new Wish();
        $addwishform = $this->createForm(AddWishFormType::class, $wish);
        $addwishform->handleRequest($request);
        if($addwishform->isSubmitted() && $addwishform->isValid()) {
            $wish->setAuthor($this->getUser()->getUserIdentifier());
            $wish->setDateCreated(new \DateTime('now'));
            $wish->setIsPublished(true);
            $this->em->persist($wish);
            $this->em->flush();
            return $this->redirectToRoute("wish");
        }
        return $this->render('wish/addwish.html.twig', [
            'titre' => 'Add wish',
            'form' => $addwishform->createView(),
        ]);
    }

    /**
     * @Route("/wish/delete/{id}", name="deletewish")
     */
    public function supression(Wish $wish): Response
    {
        $this->em->remove($wish);
        $this->em->flush();
        return $this->redirectToRoute('wish');
    }

    /**
     * @Route("/wishupdate/{id}", name="updatewish")
     */
    public function update(Wish $wish, Request $request): Response
    {
        if($this->getUser()->getUserIdentifier() == $wish->getAuthor()) {
            $addwishform = $this->createForm(UpdateWishFormType::class, $wish);
            $addwishform->handleRequest($request);
            if ($addwishform->isSubmitted() && $addwishform->isValid()) {
                $this->em->persist($wish);
                $this->em->flush();
                return $this->redirectToRoute("wish");
            }
            return $this->render('wish/update.html.twig', [
                'titre' => 'Add wish',
                'form' => $addwishform->createView(),
            ]);
        }
        else{
            return $this->redirectToRoute('wish');
        }
    }

}
