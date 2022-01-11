<?php

declare(strict_types=1);

namespace App\Controller;

use App\Builder\BuildingStrategy\ImageStrategy;
use App\Builder\BuildingStrategy\VideoStrategy;
use App\Builder\LinkBuilder;
use App\Model\Link;
use Doctrine\ORM\EntityManagerInterface;
use JMS\Serializer\SerializerInterface;
use Knp\Component\Pager\PaginatorInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Method;
use Symfony\Component\Validator\Validator\ValidatorInterface;
use OpenApi\Annotations as OA;

class LinkController extends AbstractController
{
    private SerializerInterface $serializer;
    private LinkBuilder $linkBuilder;
    private ImageStrategy $imageStrategy;
    private VideoStrategy $videoStrategy;
    private EntityManagerInterface $entityManager;
    private ValidatorInterface $validator;
    private PaginatorInterface $paginator;

    /**
     * @param SerializerInterface $serializer
     * @param LinkBuilder $linkBuilder
     * @param ImageStrategy $imageStrategy
     * @param VideoStrategy $videoStrategy
     * @param EntityManagerInterface $entityManager
     * @param ValidatorInterface $validator
     */
    public function __construct(
        SerializerInterface    $serializer,
        LinkBuilder            $linkBuilder,
        ImageStrategy          $imageStrategy,
        VideoStrategy          $videoStrategy,
        EntityManagerInterface $entityManager,
        ValidatorInterface     $validator,
        PaginatorInterface     $paginator
    )
    {
        $this->serializer = $serializer;
        $this->linkBuilder = $linkBuilder;
        $this->imageStrategy = $imageStrategy;
        $this->videoStrategy = $videoStrategy;
        $this->entityManager = $entityManager;
        $this->validator = $validator;
        $this->paginator = $paginator;
    }

    /**
     * @Route("/api/v1/link", name="link_create")
     * @Method({"POST"})
     * @OA\Response(
     *     response=201,
     *     description="Returns the link created",
     * )
     */
    public function create(Request $request): JsonResponse
    {
        $content = $request->getContent();

        $link = $this->serializer->deserialize($content, 'App\Model\Link', 'json');

        $errors = $this->validator->validate($link);

        if (count($errors)) {
            foreach ($errors as $error) {
                $error = $this->serializer
                    ->serialize(
                        [
                            'failure' => $error,
                            'status' => Response::HTTP_BAD_REQUEST,
                        ],
                        'json'
                    );

                return new JsonResponse($error, Response::HTTP_BAD_REQUEST, [], true);
            }
        }

        try {
            $link = $this->linkBuilder->build($link, [$this->imageStrategy, $this->videoStrategy]);

            $this->entityManager->persist($link);
            $this->entityManager->flush();

            $data = $this->serializer->serialize(
                [
                    'success' => 'link is created',
                    'link' => $link,
                    'status' => Response::HTTP_CREATED,
                ],
                'json'
            );

            return new JsonResponse($data, Response::HTTP_CREATED, [], true);
        } catch (\Throwable $e) {
            $error = $this->serializer
                ->serialize(
                    [
                        'failure' => $e->getMessage(),
                        'status' => Response::HTTP_BAD_REQUEST,
                    ],
                    'json'
                );

            return new JsonResponse($error, Response::HTTP_BAD_REQUEST, [], true);
        }
    }

    /**
     * @Route("/api/v1/links", name="link_list")
     * @Method({"GET"})
     * @OA\Response(
     *     response=302,
     *     description="Returns a list of links paginated",
     * )
     */
    public function list(Request $request): JsonResponse
    {
        $query = $this->entityManager->createQuery("SELECT l FROM App\Model\Link l");

        $links = $this->paginator->paginate(
            $query,
            $request->query->getInt('page', 1), /*page number*/
            10 /*limit per page*/
        );

        $data = $this->serializer->serialize($links, 'json');

        return new JsonResponse($data, Response::HTTP_FOUND, [], true);
    }

    /**
     * @Route("/api/v1/links/{id}",name="link_delete",requirements = {"id"="\d+"})
     * @Method({"DELETE"})
     * @OA\Response(
     *     response=200
     * )
     */
    public function delete(Request $request): JsonResponse
    {
        $id = $request->get('id');

        $link = $this->entityManager->getRepository(Link::class)->find($id);

        if (null === $link) {
            $data = $this->serializer
                ->serialize(
                    [
                        'failure' => 'the link is not found.',
                        'status' => Response::HTTP_NOT_FOUND,
                    ],
                    'json'
                );

            return new JsonResponse($data, Response::HTTP_NOT_FOUND, [], true);
        }

        $this->entityManager->remove($link);
        $this->entityManager->flush();

        $data = $this->serializer
            ->serialize(
                [
                    'success' => 'link is deleted successfully.',
                    'status' => Response::HTTP_OK,
                ],
                'json'
            );

        return new JsonResponse($data, Response::HTTP_OK, [], true);
    }
}