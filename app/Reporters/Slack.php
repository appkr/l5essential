<?php

namespace App\Reporters;

class Slack
{
    /**
     * @var \Monolog\Logger
     */
    protected $logger;

    public function __construct()
    {
        $this->createLogger();
    }

    /**
     * Send slack report.
     *
     * @param \Exception $e
     * @return mixed
     */
    public function send(\Exception $e)
    {
        return $this->logger->error(
            $e->getMessage() ?: "Something broken :(",
            $this->buildPayload($e)
        );
    }

    /**
     * Build payload array based on given Exception object.
     *
     * @param \Exception $e
     * @return array
     */
    protected function buildPayload(\Exception $e)
    {
        return [
            'username'  => auth()->check() ? auth()->user()->email : 'Unknown',
            'route'     => \Route::currentRouteName(),
            'localtime' => \Carbon\Carbon::now('Asia/Seoul')->toDateTimeString(),
            'exception' => [
                'class'   => get_class($e),
                'file'    => $e->getFile(),
                'line'    => $e->getLine(),
                'message' => $e->getMessage(),
                'code'    => $e->getCode(),
                'trace'   => $e->getTraceAsString(),
                'ip'      => \Request::ip(),
                'method'  => \Request::method(),
                'url'     => \Request::fullUrl(),
                'content' => \Request::instance()->getContent()
                    ?: json_encode(\Request::all()),
                'headers' => \Request::header(),
            ],
        ];
    }

    /**
     * Factory - Create Monolog instance which has a Slack handler.
     */
    protected function createLogger()
    {
        $logger = \Log::getMonolog();

        $logger->pushHandler(
            (new \Monolog\Handler\SlackHandler(env('SLACK_TOKEN'), '#l5essential', 'aws-demo'))
                ->setLevel(\Monolog\Logger::ERROR)
        );

        return $this->logger = $logger;
    }
}