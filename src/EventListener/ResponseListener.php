<?php

namespace App\EventListener;

use App\Dto\Output\ResponseDto;
use Symfony\Component\EventDispatcher\Attribute\AsEventListener;
use Symfony\Component\HttpKernel\Event\ResponseEvent;
use Symfony\Component\Serializer\SerializerInterface;

#[AsEventListener(event: 'kernel.response')]
final class ResponseListener
{
    public function __construct(private SerializerInterface $serializer)
    {
    }

    public function onKernelResponse(ResponseEvent $responseEvent)
    {
        $response = $responseEvent->getResponse();
        $responseContent = $response->getContent();

        $responseContentArr = json_decode($responseContent, true);
        $content = new ResponseDto($responseContentArr, $response->getStatusCode());

        if (!$response->isSuccessful()) {
            $content->setMessage(null);
            $content->setError($response->getContent());
        }

        $response->setContent($this->serializer->serialize($content, 'json'));
    }
}
