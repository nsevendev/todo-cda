<?php

declare(strict_types=1);

namespace Tocda\Infrastructure\ApiResponse;

use Tocda\Infrastructure\ApiResponse\Component\ApiResponseData;
use Tocda\Infrastructure\ApiResponse\Component\ApiResponseLink;
use Tocda\Infrastructure\ApiResponse\Component\ApiResponseMessage;
use Tocda\Infrastructure\ApiResponse\Component\ApiResponseMeta;
use Tocda\Infrastructure\ApiResponse\Exception\Error\ListError;

final readonly class ApiResponse
{
    public function __construct(
        private int $responseStatusCode,
        private ApiResponseMessage $apiResponseMessage,
        private ApiResponseData $apiResponseData,
        private ListError $listError,
        private ApiResponseLink $apiResponseLink = new ApiResponseLink(),
        private ApiResponseMeta $apiResponseMeta = new ApiResponseMeta(),
    ) {}

    /**
     * @return array<string, mixed>
     */
    public function toArray(): array
    {
        return [
            'statusCode' => $this->responseStatusCode,
            'message' => $this->apiResponseMessage->message,
            'data' => $this->apiResponseData->data,
            'errors' => $this->listError->toArray(),
            'meta' => $this->apiResponseMeta->listMetaData(),
            'links' => $this->apiResponseLink->listLinks(),
        ];
    }
}
