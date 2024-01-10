<?php

namespace App\Controller;

use App\Entity\Feedback;
use App\Entity\Product;
use App\Form\FeedbackType;
use App\Repository\FeedbackRepository;
use App\Repository\ProductRepository;
use App\Service\ProductService;
use Doctrine\ORM\EntityManager;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class FeedbackController extends AbstractController
{
    private ProductService $productService;
    private FeedbackRepository $feedbackRepository;
    private ProductRepository $productRepository;

    public function __construct(
        ProductService $productService,
        FeedbackRepository $feedbackRepository,
        ProductRepository $productRepository)
    {
        $this->productService = $productService;
        $this->feedbackRepository = $feedbackRepository;
        $this->productRepository = $productRepository;
    }

    #[Route('/', name: 'index')]
    public function index(): Response
    {
        return $this->render('feedback/index.html.twig');
    }

    #[Route('/products', name: 'product_list')]
    public function productList(): Response
    {
        $products = $this->productService->getProducts();

        return $this->render('feedback/product_list.html.twig', [
            'products' => $products,
        ]);
    }

    #[Route('/feedback/{productId}', name: 'feedback', requirements: ['productId' => '\d+'], defaults: ['productId' => null])]
    public function create(Request $request, ?int $productId): Response
    {
        $products = $this->productRepository->findAll();
        $feedback = new Feedback();

        if ($productId !== null) {
            $product = $this->productRepository->find($productId);

            if ($product) {
                $feedback->setProduct($product);

                $form = $this->createForm(FeedbackType::class, $feedback);
                $form->handleRequest($request);

                if ($form->isSubmitted() && $form->isValid()) {
                    $this->feedbackRepository->save($feedback);

                    return $this->render('feedback/result.html.twig', [
                        'feedback' => $feedback,
                        'product' => $product,
                    ]);
                }
            } else {
                return $this->redirectToRoute('product_list');
            }
        }

        return $this->render('feedback/form.html.twig', [
            'form' => $form->createView(),
            'products' => $products,
        ]);
    }

    #[Route('/feedbacks', name: 'feedback_list')]
    public function feedbackList(): Response
    {
        $feedbacks = $this->feedbackRepository->findAll();

        $feedbackData = [];
        foreach ($feedbacks as $feedback) {
            $productName = $feedback->getProduct() ? $feedback->getProduct()->getName() : 'N/A';

            $feedbackData[] = [
                'id' => $feedback->getId(),
                'productName' => $productName,
                'clientName' => $feedback->getNomClient(),
                'email' => $feedback->getEmailClient(),
                'note' => $feedback->getNoteProduit(),
                'comment' => $feedback->getCommentaires(),
                'submissionDate' => $feedback->getDateSoumission()->format('Y-m-d H:i:s'),
            ];
        }

        return $this->render('feedback/feedback_list.html.twig', [
            'feedbackData' => $feedbackData,
        ]);
    }

    #[Route('/feedback/{id}/delete', name: 'feedback_delete', methods: ['POST'])]
    public function delete(Feedback $feedback): Response
    {
        $feedbackRepository = $this->feedbackRepository;
        $feedbackRepository->delete($feedback);

        return $this->redirectToRoute('feedback_list');
    }

    #[Route('/feedback/{id}/edit', name: 'feedback_edit', methods: ['GET', 'POST'])]
    public function edit(Request $request, Feedback $feedback): Response
    {
        $form = $this->createForm(FeedbackType::class, $feedback);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $this->feedbackRepository->save($feedback);

            return $this->redirectToRoute('feedback_list');
        }

        return $this->render('feedback/edit.html.twig', [
            'feedback' => $feedback,
            'form' => $form->createView(),
        ]);
    }

}   