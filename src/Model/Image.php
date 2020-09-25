<?php


namespace Observatby\Mir24Quiz\Model;


class Image
{
    private string $src;

    /**
     * Image constructor.
     * @param string $src
     */
    public function __construct(string $src)
    {
        $this->src = $src;
    }
}
