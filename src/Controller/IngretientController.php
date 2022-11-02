<?php

namespace App\Controller;

use App\Entity\Ingredient;
use App\Form\IngredientType;
use App\Repository\IngredientRepository;
use Doctrine\ORM\EntityManagerInterface;
use Knp\Component\Pager\PaginatorInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class IngretientController extends AbstractController
{
	/**
	 * This function display all ingretients
	 * @param IngredientRepository $ingredientRepository
	 * @param PaginatorInterface $paginator
	 * @param Request $request
	 * @return Response
	 */
    #[Route('/ingretient', name: 'ingretient.index', methods: ['GET'])]
    public function index(IngredientRepository $ingredientRepository, PaginatorInterface $paginator, Request $request): Response
    {
		$ingretients = $paginator->paginate(
			$ingredientRepository->findAll(),
			$request->query->getInt('page', 1),
			10
		);
		//$ingretient = $ingredientRepository->findAll();
        return $this->render('pages/ingretient/index.html.twig', [
				'ingretients' => $ingretients
        ]);
    }

	#[Route('/ingretient/nouveau', name:  'ingretient.new', methods: ['GET', 'POST'])]
	public function new(
		Request $request,
		EntityManagerInterface $manager
	): Response
	{
		$ingredient = new Ingredient();
		$form = $this->createForm(IngredientType::class, $ingredient);

		$form->handleRequest($request);
		if($form->isSubmitted() && $form->isValid()){
			$ingredient = $form->getData();
			$manager->persist($ingredient);
			$manager->flush();
			$this->addFlash(
				'success',
				'Votre ingredient a été crée avec succès !'
			);
		    return $this->redirectToRoute('ingretient.index');
		}
		return $this->render('pages/ingretient/new.html.twig', [
			'form' => $form->createView()
		]);
	}

	#[Route('/ingretient/edition/{id}', name: 'ingredient.edit', methods: ['GET', 'POST'])]
	public function edit(
		Ingredient $ingredient,
		Request $request,
		EntityManagerInterface $manager
	) : Response
	{
		//$ingredient = $ingredientRepository->findOneBy(["id" => $id]);
		$form = $this->createForm(type: IngredientType::class, data: $ingredient);
		$form->handleRequest($request);
		if($form->isSubmitted() && $form->isValid()){
			$ingredient = $form->getData();
			$manager->persist($ingredient);
			$manager->flush();
			$this->addFlash(
				'success',
				'Votre ingredient a été modifié avec succès !'
			);
			return $this->redirectToRoute('ingretient.index');
		}
		return $this->render('pages/ingretient/edit.html.twig', [
			'form'=>$form->createView()
		]);
	}


	#[Route('/ingredient/suppression/{id}', name: 'ingredient.delete', methods: ['GET'])]
	public function delete(
		EntityManagerInterface $manager,
		Ingredient $ingredient
	): Response
	{
		if(!$ingredient){
			$this->addFlash(
				'success',
				'L\'ingredient en question n\'a pas été trouvé !'
			);
			return $this->redirectToRoute('ingredient.index');
		}else{
			$manager->remove($ingredient);
			$manager->flush();
			$this->addFlash(
				'success',
				'Votre ingredient a été supprimé avec succès !'
			);
			return $this->redirectToRoute('ingretient.index');
		}
	}
}
