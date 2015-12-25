<?php

namespace App\Reporters;

use Carbon\Carbon;
use Maknz\Slack\Attachment;
use Maknz\Slack\AttachmentField;
use Maknz\Slack\Client;
use Request;
use Route;

class ErrorReport
{
    /**
     * @var \Maknz\Slack\Client
     */
    protected $client;

    public function __construct()
    {
        $this->createClient();
    }

    /**
     * Send slack report.
     *
     * @param \Exception $e
     * @return mixed
     */
    public function send(\Exception $e)
    {
        return $this->client->createMessage()->attach($this->buildPayload($e))->send();
    }

    /**
     * Build Slack Attachment array based on given Exception object.
     * @see https://api.slack.com/docs/attachments
     *
     * @param \Exception $e
     * @return array
     */
    protected function buildPayload(\Exception $e)
    {
        return new Attachment([
            'fallback' => 'Error Report',
            'text' => $e->getMessage() ?: "Something broken :(",
            'color' => 'danger',
            'fields' => [
                new AttachmentField([
                    'title' => 'localtime',
                    'value' => Carbon::now('Asia/Seoul')->toDateTimeString()
                ]),
                new AttachmentField([
                    'title' => 'username',
                    'value' => (auth()->check() ? auth()->user()->email : 'Unknown')
                        . sprintf(' (%s)', Request::ip()),
                ]),
                new AttachmentField([
                    'title' => 'route',
                    'value' => Route::currentRouteName()
                        . sprintf(
                            ' (%s %s)',
                            Request::method(),
                            Request::fullUrl()
                        ) ,
                ]),
                new AttachmentField([
                    'title' => 'description',
                    'value' => sprintf(
                        '%s in %s line %d',
                        get_class($e),
                        pathinfo($e->getFile(), PATHINFO_BASENAME),
                        $e->getLine()
                    ),
                ]),
                new AttachmentField([
                    'title' => 'trace',
                    'value' => $e->getTraceAsString()
                ]),
            ]
        ]);
    }

    /**
     * Factory - Create HTTP API Client for Slack
     */
    protected function createClient()
    {
        $settings = [
            'channel'      => '#l5essential',
            'username'     => 'aws-demo',
            'link_names'   => true,
            'unfurl_links' => true,
            'markdown_in_attachments' => ['title', 'text', 'fields', 'value']
        ];

        return $this->client = new Client(
            env('SLACK_WEBHOOK'),
            $settings
        );
    }
}