<?php

declare(strict_types=1);

namespace App\Api\ListUsers;

use App\Domain\Dto\RequestListDto;
use App\Domain\Models\User;
use App\Domain\Serializer\SerializerEnum;
use App\Domain\Serializer\SerializerFactory;
use App\Infrastructure\Framework\AbstractController;
use App\Usecase\Services\ListUsersService;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpKernel\Attribute\MapQueryString;
use Symfony\Component\Routing\Attribute\Route;

final class Action extends AbstractController
{
    public function __construct(
        private readonly ListUsersService $listUsersService,
    ) {}

    #[Route(path: '/api/v1/list-users', name: 'api_v1_list_users', methods: ['GET'])]
    public function __invoke(#[MapQueryString()] Request $dto): JsonResponse
    {
        $serializer = SerializerFactory::create(SerializerEnum::JSON_SERIALIZER);

        $listCourse = ($this->listUsersService)(
            dto: new RequestListDto(
                page: $dto->page,
                limit: $dto->limit,
            )
        );

        $items = [];

        /** @var User $item */
        foreach ($listCourse->items as $item) {
            $items[] = $serializer->normalize($item);
        }

        return $this->success([
            'items' => $items,
            'pagination' => $listCourse->pagination,
        ]);
    }
}
