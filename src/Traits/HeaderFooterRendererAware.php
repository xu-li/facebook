<?php
namespace Xu\Facebook\Traits;

use Xu\Facebook\Contracts\IHeaderFooterRenderer;

trait HeaderFooterRendererAware
{
    // The header footer renderer
    protected $header_footer_renderer;

    /**
     * Set renderer
     *
     * @param  IHeaderFooterRenderer $renderer
     * @return FPDF
     */
    public function setHeaderFooterRenderer(IHeaderFooterRenderer $renderer)
    {
        $this->header_footer_renderer = $renderer;

        return $this;
    }

    /**
     * {@inheritdoc}
     */
    function Header()
    {
        if ($this->header_footer_renderer) {
            $x = $this->lMargin;
            $y = 0;
            $w = $this->GetPageWidth() - $this->lMargin - $this->rMargin;
            $h = $this->tMargin;

            $this->header_footer_renderer->renderHeader($this, $x, $y, $w, $h);
        } else {
            parent::Header();
        }
    }

    /**
     * {@inheritdoc}
     */
    function Footer()
    {
        if ($this->header_footer_renderer) {
            $x = $this->lMargin;
            $y = $this->GetPageHeight() - $this->bMargin;
            $w = $this->GetPageWidth() - $this->lMargin - $this->rMargin;
            $h = $this->bMargin;

            $this->header_footer_renderer->renderFooter($this, $x, $y, $w, $h);
        } else {
            parent::Footer();
        }
    }
}
