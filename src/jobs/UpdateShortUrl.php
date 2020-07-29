<?php

namespace codemonauts\shortener\jobs;

use codemonauts\shortener\elements\ShortUrl;
use codemonauts\shortener\Shortener;
use craft\queue\BaseJob;

class UpdateShortUrl extends BaseJob
{
    public $entryIds = [];

    public function execute($queue)
    {
        $shortener = Shortener::getInstance()->shortUrl;
        $total = count($this->entryIds);
        $counter = 0;

        foreach ($this->entryIds as $entryId) {
            $counter++;
            $this->setProgress($queue, ($counter / $total), 'Step ' . $counter . ' of ' . $total);

            $shortUrls = ShortUrl::find()
                ->elementId($entryId)
                ->all();

            foreach ($shortUrls as $shortUrl) {
                $shortener->update($shortUrl);
            }
        }

        return true;
    }

    protected function defaultDescription(): string
    {
        return 'Update Short URL';
    }
}
