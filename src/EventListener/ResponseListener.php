<?php

namespace App\EventListener;

use App\Dto\Output\ResponseDto;
use App\Util\ArrayUtil;
use Symfony\Component\EventDispatcher\Attribute\AsEventListener;
use Symfony\Component\HttpKernel\Event\ResponseEvent;
use Symfony\Component\HttpKernel\KernelEvents;
use Symfony\Component\Serializer\SerializerInterface;

final class ResponseListener
{
    public function __construct(
        private readonly SerializerInterface $serializer,
        private readonly ArrayUtil $arrayUtil
    ) {
    }

    #[AsEventListener(event: KernelEvents::RESPONSE)]
    public function onKernelResponse(ResponseEvent $responseEvent): void
    {
        $response = $responseEvent->getResponse();

        if (!json_validate($response->getContent())) {
            return;
        }

        $responseContent = json_decode($response->getContent(), true);

        $responseContentArr = (gettype($responseContent) === 'array')
            ? $responseContent
            : [$responseContent];

        $content = $response->isSuccessful() ?
            new ResponseDto($responseContentArr, $response->getStatusCode()) :
            new ResponseDto(
                code: $response->getStatusCode(),
                errors: $this->arrayUtil->uniq($responseContentArr)
            );

        $response->setContent($this->serializer->serialize($content, 'json'));
    }
}
