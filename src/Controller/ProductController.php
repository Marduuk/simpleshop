<?php
declare(strict_types=1);

namespace App\Controller;

use App\Entity\Product;
use App\Form\ProductType;
use App\Repository\ProductRepository;
use App\Service\ExceptionService;
use App\Service\MailerService;
use App\Service\ProductService;
use Doctrine\ORM\EntityManagerInterface;
use Knp\Component\Pager\PaginatorInterface;
use Symfony\Component\Form\FormFactoryInterface;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;

/**
 * Class ProductController
 * @package App\Controller
 */
class ProductController
{
    /** @var FormFactoryInterface */
    private $formFactory;

    /** @var EntityManagerInterface */
    private $entityManager;

    /** @var PaginatorInterface */
    private $paginator;

    /** @var ProductRepository */
    private $repository;

    /** @var ExceptionService */
    private $exceptionService;

    /** @var MailerService */
    private $mailserService;

    /** @var ProductService */
    private $productService;

    /**
     * ProductController constructor.
     * @param FormFactoryInterface $formFactory
     * @param EntityManagerInterface $entityManager
     * @param PaginatorInterface $paginator
     * @param ProductRepository $repository
     * @param MailerService $mailserService
     * @param ExceptionService $exceptionService
     * @param ProductService $productService
     */
    public function __construct(FormFactoryInterface $formFactory,
                                EntityManagerInterface $entityManager,
                                PaginatorInterface $paginator,
                                ProductRepository $repository,
                                MailerService $mailserService,
                                ExceptionService $exceptionService,
                                ProductService $productService)
    {
        $this->formFactory = $formFactory;
        $this->entityManager = $entityManager;
        $this->paginator = $paginator;
        $this->repository = $repository;
        $this->mailserService = $mailserService;
        $this->productService = $productService;
        $this->exceptionService = $exceptionService;
    }

    /**
     * @Route("/product/{page}", methods={"GET"}) PAGE ID
     * @param int $page
     * @return JsonResponse
     */
    public function getProducts(int $page = 1): JsonResponse
    {
        $products = $this->repository->findAll();

        /** @var Product[] $products */
        $products = $this->paginator->paginate($products, $page, 10,
            [
                'defaultSortFieldName' => 'created_at',
                'defaultSortDirection' => 'desc'
            ]
        );
        return new JsonResponse(
            [
                'message' => 'Successfully fetched products',
                'success' => true,
                'products' => $this->productService->getProductsForOutput($products)
            ],
            200);
    }

    /**
     * @Route("/admin/product", methods={"POST"})
     * @param Request $request
     * @return JsonResponse
     */
    public function save(Request $request)
    {
        $product = new Product();
        $form = $this->formFactory->createNamed('product', ProductType::class, $product);
        $form->submit($request->request->all());

        if ($form->isValid()) {
            $this->entityManager->persist($product);
            $this->entityManager->flush();

            $this->mailserService->sendNewProductNotification();

            $response = new JsonResponse(
                [
                    'message' => 'Successfully added new product',
                    'success' => true,
                    'product' => $this->productService->getProductForOutput($product)
                ],
                201);
        } else {
            $errors = $this->exceptionService->getFormErrors($form);
            $response = new JsonResponse(
                [
                    'message' => $errors,
                    'success' => false
                ],
                400);
        }
        return $response;
    }
}
