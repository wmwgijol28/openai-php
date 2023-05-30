<?php

declare(strict_types=1);

namespace OpenAI\Responses\FineTunes;

use OpenAI\Contracts\ResponseContract;
use OpenAI\Responses\Concerns\ArrayAccessible;

/**
 * @implements ResponseContract<array{id: string, object: string, created_at: int, bytes: int, filename: string, purpose: string, status: string, status_details: array<array-key, mixed>|string|null}>
 */
final class RetrieveResponseFile implements ResponseContract
{
    /**
     * @use ArrayAccessible<array{id: string, object: string, created_at: int, bytes: int, filename: string, purpose: string, status: string, status_details: array<array-key, mixed>|string|null}>
     */
    use ArrayAccessible;

    public $statusDetails;
    public $status;
    public $purpose;
    public $filename;
    public $createdAt;
    public $bytes;
    public $object;
    public $id;

    /**
     * @param  array<array-key, mixed>|null  $statusDetails
     */
    private function __construct(string $id, string $object, int $bytes, int $createdAt, string $filename, string $purpose, string $status, $statusDetails)
    {
        $this->id = $id;
        $this->object = $object;
        $this->bytes = $bytes;
        $this->createdAt = $createdAt;
        $this->filename = $filename;
        $this->purpose = $purpose;
        $this->status = $status;
        $this->statusDetails = $statusDetails;
    }

    /**
     * Acts as static factory, and returns a new Response instance.
     *
     * @param  array{id: string, object: string, created_at: int, bytes: int, filename: string, purpose: string, status: string, status_details: array<array-key, mixed>|string|null}  $attributes
     */
    public static function from(array $attributes): self
    {
        return new self(
            $attributes['id'],
            $attributes['object'],
            $attributes['bytes'],
            $attributes['created_at'],
            $attributes['filename'],
            $attributes['purpose'],
            $attributes['status'],
            $attributes['status_details'],
        );
    }

    /**
     * {@inheritDoc}
     */
    public function toArray(): array
    {
        return [
            'id' => $this->id,
            'object' => $this->object,
            'bytes' => $this->bytes,
            'created_at' => $this->createdAt,
            'filename' => $this->filename,
            'purpose' => $this->purpose,
            'status' => $this->status,
            'status_details' => $this->statusDetails,
        ];
    }
}
