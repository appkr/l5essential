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
    private $client;

    /**
     * @var \Exception
     */
    private $primitive;

    /**
     * @param \Exception $e
     * @param string     $webhook
     * @param array      $settings
     */
    public function __construct(\Exception $e, $webhook = '', $settings = [])
    {
        $this->primitive = $e;
        $webhook = $webhook ?: env('SLACK_WEBHOOK');
        $this->createClient($webhook, $settings);
    }

    /**
     * Send slack report.
     *
     * @return mixed
     */
    public function send()
    {
        return $this->client->createMessage()->attach($this->buildPayload())->send();
    }

    /**
     * Build Slack Attachment array based on given Exception object.
     *
     * @see https://api.slack.com/docs/attachments
     *
     * @return array
     */
    protected function buildPayload()
    {
        return new Attachment([
            'fallback' => 'Error Report',
            'text'     => $this->primitive->getMessage() ?: "Something broken :(",
            'color'    => 'danger',
            'fields'   => [
                new AttachmentField([
                    'title' => 'localtime',
                    'value' => Carbon::now('Asia/Seoul')->toDateTimeString(),
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
                        ),
                ]),
                new AttachmentField([
                    'title' => 'description',
                    'value' => sprintf(
                        '%s in %s line %d',
                        get_class($this->primitive),
                        pathinfo($this->primitive->getFile(), PATHINFO_BASENAME),
                        $this->primitive->getLine()
                    ),
                ]),
                new AttachmentField([
                    'title' => 'trace',
                    'value' => $this->primitive->getTraceAsString(),
                ]),
            ],
        ]);
    }

    /**
     * Factory - Create HTTP API Client for Slack
     *
     * @param array $overrides
     * @return \Maknz\Slack\Client
     */
    protected function createClient($webhook, $overrides = [])
    {
        $settings = array_merge([
            'channel'                 => '#l5essential',
            'username'                => 'aws-demo',
            'link_names'              => true,
            'unfurl_links'            => true,
            'markdown_in_attachments' => ['title', 'text', 'fields'],
        ], $overrides);

        return $this->client = new Client($webhook, $settings);
    }
}