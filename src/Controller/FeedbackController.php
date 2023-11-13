<?php

namespace App\Controller;

use App\Entity\Feedback;
use App\Form\FeedbackType;
use App\Service\ProductService;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class FeedbackController extends AbstractController
{
    private $productService;

    public function __construct(ProductService $productService)
    {
        $this->productService = $productService;
    }

    #[Route('/products', name: 'product_list')]
    public function productList(): Response
    {
        $products = $this->productService->getProducts();

        return $this->render('feedback/product_list.html.twig', [
            'products' => $products,
        ]);
    }

    #[Route('/feedback/{productId}', name: 'feedback', defaults: ['productId' => null])]
    public function feedback(Request $request, ?int $productId): Response
    {
        $products = $this->productService->getProducts();
        $feedback = new Feedback();
        $form = $this->createForm(FeedbackType::class, $feedback);
        $form->handleRequest($request);
    
        if ($productId !== null) {
            $product = $this->productService->getProductById($productId);
    
            if ($form->isSubmitted() && $form->isValid()) {
                return $this->render('feedback/result.html.twig', [
                    'feedback' => $feedback,
                    'product' => $product,
                ]);
            } 
        }
    
        return $this->render('feedback/form.html.twig', [
            'form' => $form->createView(),
            'products' => $products,
        ]);
    }
}   