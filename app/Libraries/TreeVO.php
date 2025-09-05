<?php
namespace App\Libraries;

use App\Libraries\TreeStateVO;

class TreeVO {
    private string $id;
    private string $parent;
    private string $parentText;
    private string $text;
    private string $type;
    private string $cd_company;
    private $state;

    public function __construct() {
        $this->id = "";
        $this->parent = "";
        $this->parentText = "";
        $this->text = "";
        $this->type = "";
        $this->cd_company = "";
        $this->state = new TreeStateVO(); // 초기에는 null로 설정
    }

    // Getter 및 Setter
    public function getId(): string {
        return $this->id;
    }

    public function setId(string $id): void {
        $this->id = $id;
    }

    public function getParent(): string {
        return $this->parent;
    }

    public function setParent(string $parent): void {
        $this->parent = $parent;
    }

    public function getParentText(): string {
        return $this->parentText;
    }

    public function setParentText(string $parentText): void {
        $this->parentText = $parentText;
    }

    public function getText(): string {
        return $this->text;
    }

    public function setText(string $text): void {
        $this->text = $text;
    }

    public function getType(): string {
        return $this->type;
    }

    public function setType(string $type): void {
        $this->type = $type;
    }

    public function getcd_company(): string {
        return $this->cd_company;
    }

    public function setcd_company(string $cd_company): void {
        $this->cd_company = $cd_company;
    }

    public function getState(): TreeStateVO {
        return $this->state;
    }

    public function setState(TreeStateVO $state): void {
        $this->state = $state;
    }
}
?>
