<?php
namespace Xu\Facebook\Traits;

trait Chapterable
{
    protected $chapters = [];

    /**
     * Add a new chapter with pages
     *
     * @param  array $pages
     * @return FPDF
     */
    public function addChapter($pages)
    {
        $this->chapters[] = $pages;
    }

    /**
     * Get current chapter
     *
     * @return int
     */
    public function getCurrentChapter()
    {
        $chapter = 0;
        $page    = $this->PageNo();

        foreach ($this->chapters as $chapter => $pages) {
            $num_pages = count($pages);

            if ($num_pages < $page) {
                $page -= $num_pages;
            } else {
                return $chapter + 1;
            }
        }

        return $chapter + 1;
    }
}
