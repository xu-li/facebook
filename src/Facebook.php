<?php
namespace Xu\Facebook;

use Xu\Facebook\Contracts\ICellRenderer;
use Xu\Facebook\Traits\HeaderFooterRendererAware;
use Xu\Facebook\Traits\Griddable;
use Xu\Facebook\Traits\Chapterable;
use Xu\Facebook\Traits\Debugger;

/**
 * Facebook class
 */
class Facebook extends \FPDF implements ICellRenderer
{
    use HeaderFooterRendererAware, Griddable, Chapterable;
    
    /**
     * {@inheritdoc}
     */
    public function __construct($orientation = 'P', $unit = 'mm', $size = 'A4')
    {
        parent::__construct($orientation, $unit, $size);

        // set renderers
        $this->setCellRenderer($this);
    }

    /**
     * Add users
     *
     * @param array $users
     */
    public function addUsers($users)
    {
        if (count($users) === 0) {
            return ;
        }

        // support [[user1, user2], [user3, user4]] format
        if (is_array($users[0])) {
            foreach ($users as $chapter) {
                $this->addUsers($chapter);
            }

            return ;
        }

        $this->addChapter(array_chunk($users, $this->getCols() * $this->getRows()));
    }

    /**
     * Render it
     *
     * @return FPDF
     */
    public function render()
    {
        if (empty($this->chapters)) {
            return ;
        }

        foreach ($this->chapters as $i => $pages) {
            foreach ($pages as $users) {
                $this->renderGrid();
            }
        }

        return $this;
    }

    /**
     * {@inheritdoc}
     */
    public function renderCell(\FPDF $pdf, Cell $cell)
    {
        $user = $this->getUserAtCell($cell);

        if (!$user) {
            return ;
        }

        // draw border
        $this->renderCellBorder($cell);

        // draw image
        $this->renderUserImage($user, $cell);

        // draw name
        $this->renderUserName($user, $cell);

        // draw description
        $this->renderUserDescription($user, $cell);
    }

    /**
     * Render user's image
     *
     * @param  User $user
     * @param  Cell $cell
     */
    protected function renderUserImage(User $user, Cell $cell)
    {
        // draw image
        if (!$user->image || !file_exists($user->image)) {
            return ;
        }

        list($width, $height) = $cell->getSizeWithoutPaddings();
        
        $box_width  = $user->image_size[0] == 0 ? $width : $user->image_size[0];
        $box_height = $user->image_size[1];
        $box_ratio  = $box_width / $box_height;

        // file size
        $info = @getimagesize($user->image);

        if (!$info) {
            return ;
        }

        $epsilon  = 0.00001;
        $offset_x = 0;
        $offset_y = 0;

        // scale image to fit into box
        $image_ratio = $info[0] / $info[1];
        if (abs($box_ratio - $image_ratio) > $epsilon) {
            if ($box_ratio > $image_ratio) {
                $offset_x  = ($box_width - $box_height * $image_ratio) / 2;
                $box_width = $box_height * $image_ratio;
            } else {
                $offset_y  = ($box_height - $box_width / $image_ratio) / 2;
                $box_height = $box_width / $image_ratio;
            }
        }

        $this->Image(
            $user->image,
            $cell->x + $cell->paddings[3] + ($width - $box_width) / 2 + $offset_x,
            $cell->y + $cell->paddings[0] + $offset_y,
            $box_width,
            $box_height
        );
    }

    /**
     * Render user's name
     *
     * @param  User $user
     * @param  Cell $cell
     */
    protected function renderUserName(User $user, Cell $cell)
    {
        // draw name
        if (!$user->name) {
            return ;
        }

        // save text style
        $this->saveTextState();

        // font
        if ($user->name_font) {
            if (is_string($user->name_font)) {
                $this->SetFont($user->name_font);
            } else {
                $this->SetFont($user->name_font['family'], $user->name_font['style'], $user->name_font['size']);
            }
        }

        // color
        if ($user->name_color) {
            $this->SetTextColor($user->name_color[0], $user->name_color[1], $user->name_color[2]);
        }

        // sizes
        list($width, $height) = $cell->getSizeWithoutPaddings();
        $cell_height_left     = $height - $user->image_size[1] - $user->desc_size[1];
        $name_width           = $user->name_size[0] == 0 ? $width : $user->name_size[0];
        $name_height          = $user->name_size[1];

        $this->SetXY(
            $cell->x + $cell->paddings[3] + ($width - $name_width) / 2,
            $cell->y + $cell->paddings[0] + $user->image_size[1] + ($cell_height_left - $name_height) / 2
        );

        // draw it
        $this->Cell($name_width, $name_height, $user->name, 0, 0, 'C');

        // restore text style
        $this->restoreTextState();
    }

    /**
     * Render user's description
     *
     * @param  User $user
     * @param  Cell $cell
     */
    protected function renderUserDescription(User $user, Cell $cell)
    {
        // draw description
        if (!$user->desc) {
            return ;
        }

        // save text style
        $this->saveTextState();

        // font
        if ($user->desc_font) {
            if (is_string($user->desc_font)) {
                $this->SetFont($user->desc_font);
            } else {
                $this->SetFont($user->desc_font['family'], $user->desc_font['style'], $user->desc_font['size']);
            }
        }

        // color
        if ($user->desc_color) {
            $this->SetTextColor($user->desc_color[0], $user->desc_color[1], $user->desc_color[2]);
        }

        list($width, $height) = $cell->getSizeWithoutPaddings();
        
        // description width
        $desc_width = $user->desc_size[0] == 0 ? $width : $user->desc_size[0];

        // calculate height
        $lines       = $this->GetStringLines($user->desc, $desc_width);
        $line_height = $user->desc_size[1] / $lines;

        // Set y from bottom
        $this->SetXY(
            $cell->x + $cell->paddings[3] + ($width - $desc_width) / 2,
            $cell->y + $cell->h - $cell->paddings[2] - $user->desc_size[1]
        );

        // draw it
        $this->MultiCell($desc_width, $line_height, $user->desc, 0, 'C');

        // restore text style
        $this->restoreTextState();
    }

    /**
     * Get user at cell
     *
     * @param  Cell $cell
     * @return User
     */
    protected function getUserAtCell(Cell $cell)
    {
        $page = $cell->page;

        foreach ($this->chapters as $chapter => $pages) {
            $num_pages = count($pages);

            if ($num_pages <= $page) {
                $page -= $num_pages;
            } else {
                $index = $cell->col + $cell->row * $this->getCols();
                return isset($pages[$page][$index]) ? $pages[$page][$index] : null;
            }
        }

        return null;
    }
}
