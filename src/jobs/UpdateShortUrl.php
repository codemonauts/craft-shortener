<?php

namespace codemonauts\shortener\jobs;

use codemonauts\shortener\elements\ShortUrl;
use codemonauts\shortener\Shortener;
use craft\queue\BaseJob;

class UpdateShortUrl extends BaseJob
{
    public array $entryIds = [];

    public function execute($queue): void
    {
        $shortener = Shortener::getInstance()->shortUrl;
        $total = count($this->entryIds);
        $counter = 0;

        foreach ($this->entryIds as $i => $entryId) {
            $counter++;
            $this->setProgress($queue, ($counter / $total), 'Step ' . $counter . ' of ' . $total);

            $shortUrls = ShortUrl::find()
                ->elementId($entryId)
                ->all();

            foreach ($shortUrls as $shortUrl) {
                $shortener->update($shortUrl);
            }
        }
    }

    protected function defaultDescription(): string
    {
        return 'Update Short URL';
    }
}
