<?php

namespace Environment\Basis;


use BusinessLogic\Basis\Content;
use Exception;
use Slim\Http\Request;
use Slim\Http\Response;

class Presentation implements IPresentation
{
    /** @var Content $content */
    private $content = null;
    /** @var Response $response */
    private $response = null;
    /** @var Request $request */
    private $request = null;

    public function __construct(Request $request, Response $response, Content $content)
    {
        $this->setContent($content)
            ->setResponse($response)
            ->setRequest($request);
    }

    private function setRequest(Request $request): self
    {
        $this->request = $request;
        return $this;
    }

    private function setContent(Content $content): self
    {
        $this->content = $content;
        static::getContent();

        return $this;
    }

    protected function getContent(): Content
    {
        return $this->content;
    }

    public function getResponse(): Response
    {
        return $this->response;
    }

    protected function setResponse(Response $response): self
    {
        $this->response = $response;
        return $this;
    }

    /**
     * @return Response
     * @throws Exception
     */
    public function process(): Response
    {
        throw new Exception('Method process() Not Implemented');
    }

    protected function isSuccess(): bool
    {
        return $this->getContent()->isSuccess();
    }

    protected function shouldAttach(): bool
    {
        $request = $this->getRequest();
        $shouldAttach = $request->isGet() || $request->isPost();

        return $shouldAttach;
    }

    protected function getRequest(): Request
    {
        return $this->request;
    }
}
