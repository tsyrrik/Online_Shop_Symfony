<?php

declare(strict_types=1);

namespace App\Infrastructure\Api\DTO;

use OpenApi\Attributes as OA;
use Symfony\Component\Serializer\Annotation\Groups;

#[OA\Schema(
    schema: 'Product',
    title: 'Product',
    description: 'Details of a product available in the online shop',
)]
class ProductDTO extends BaseDTO
{
    #[OA\Property(type: 'string', format: 'uuid', example: '550e8400-e29b-41d4-a716-446655440000')]
    #[Groups(['product:read'])]
    public string $id;

    #[OA\Property(type: 'string', example: 'Bicycle Model X')]
    #[Groups(['product:read'])]
    public string $name;

    #[OA\Property(type: 'string', nullable: true, example: 'A high-quality bicycle')]
    #[Groups(['product:read'])]
    public ?string $description;

    #[OA\Property(type: 'integer', example: 500)]
    #[Groups(['product:read'])]
    public int $cost;

    #[OA\Property(type: 'integer', example: 50)]
    #[Groups(['product:read'])]
    public int $tax;

    #[OA\Property(
        type: 'object',
        properties: [
            new OA\Property(property: 'weight', type: 'integer', example: 12000),
            new OA\Property(property: 'height', type: 'integer', example: 100),
            new OA\Property(property: 'width', type: 'integer', example: 50),
            new OA\Property(property: 'length', type: 'integer', example: 170),
        ],
    )]
    #[Groups(['product:read'])]
    public array $measurements;

    public function __construct(
        string $id,
        string $name,
        ?string $description,
        int $cost,
        int $tax,
        array $measurements,
    ) {
        $this->id = $id;
        $this->name = $name;
        $this->description = $description;
        $this->cost = $cost;
        $this->tax = $tax;
        $this->measurements = $measurements;
    }
}
