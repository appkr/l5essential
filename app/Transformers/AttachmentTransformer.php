<?php

namespace App\Transformers;

use App\Attachment;
use Appkr\Api\TransformerAbstract;

class AttachmentTransformer extends TransformerAbstract
{
    /**
     * Transform single resource.
     *
     * @param  \App\Attachment $attachment
     * @return  array
     */
    public function transform(Attachment $attachment)
    {
        return [
            'id'      => optimus((int) $attachment->id),
            'name'    => $attachment->name,
            'created' => $attachment->created_at->toIso8601String(),
            'link'    => [
                'rel'  => 'self',
                'href' => url(sprintf('http://%s:8000/attachments/%s', env('APP_DOMAIN'), $attachment->name)),
            ],
        ];
    }
}
