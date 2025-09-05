<?php

use App\Models\ApiKeyModel;

if (!function_exists('generateBranchApiKey')) {
    /**
     * 지점 등록/수정 시 API 키 자동 생성
     * 
     * @param string $compCd 회사 코드
     * @param string $bcoffCd 지점 코드  
     * @param string $bcoffNm 지점명
     * @param string $createdBy 생성자
     * @return string 생성된 API 키
     */
    function generateBranchApiKey($compCd, $bcoffCd, $bcoffNm, $createdBy = null)
    {
        $apiKeyModel = new ApiKeyModel();
        return $apiKeyModel->generateBranchApiKey($compCd, $bcoffCd, $bcoffNm, $createdBy);
    }
}

if (!function_exists('regenerateBranchApiKey')) {
    /**
     * 지점 API 키 재생성
     * 
     * @param string $compCd 회사 코드
     * @param string $bcoffCd 지점 코드
     * @param string $updatedBy 수정자
     * @return string 새로운 API 키
     */
    function regenerateBranchApiKey($compCd, $bcoffCd, $updatedBy = null)
    {
        $apiKeyModel = new ApiKeyModel();
        return $apiKeyModel->regenerateApiKey($compCd, $bcoffCd, $updatedBy);
    }
}

if (!function_exists('getBranchApiKey')) {
    /**
     * 지점의 현재 API 키 조회
     * 
     * @param string $compCd 회사 코드
     * @param string $bcoffCd 지점 코드
     * @return string|null API 키
     */
    function getBranchApiKey($compCd, $bcoffCd)
    {
        $apiKeyModel = new ApiKeyModel();
        $keyInfo = $apiKeyModel->where('comp_cd', $compCd)
                               ->where('bcoff_cd', $bcoffCd)
                               ->where('is_active', 1)
                               ->first();
        
        return $keyInfo ? $keyInfo['api_key'] : null;
    }
}