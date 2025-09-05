<?php
namespace App\Libraries;

class RoleMenuVO {
    private int $seq_role;
    private string $cd_menu;
    private string $cd_company;
    private string $nm_company;

    public function __construct() {
        $this->seq_role = -1;
        $this->cd_menu = "";
        $this->cd_company = "";
        $this->nm_company = "";
    }

    // Getter 및 Setter
    public function getseq_role(): int {
        return $this->seq_role;
    }

    public function setseq_role(int $seq_role): void {
        $this->seq_role = $seq_role;
    }

    public function getcd_menu(): string {
        return $this->cd_menu;
    }

    public function setcd_menu(string $cd_menu): void {
        $this->cd_menu = $cd_menu;
    }

    public function getcd_company(): string {
        return $this->cd_company;
    }

    public function setcd_company(string $cd_company): void {
        $this->cd_company = $cd_company;
    }

    public function getnm_company(): string {
        return $this->nm_company;
    }

    public function setnm_company(string $nm_company): void {
        $this->nm_company = $nm_company;
    }
}
?>
