<?php
namespace App\Libraries;

class TreeStateVO {
    public bool $opened;
    public bool $selected;

    public function __construct() {
        $this->opened = true;
        $this->selected = false;
    }

    // Getter 및 Setter (선택 사항)
    public function isOpened(): bool {
        return $this->opened;
    }

    public function setOpened(bool $opened): void {
        $this->opened = $opened;
    }

    public function isSelected(): bool {
        return $this->selected;
    }

    public function setSelected(bool $selected): void {
        $this->selected = $selected;
    }
}

?>
