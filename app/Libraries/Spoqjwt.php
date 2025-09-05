<?php
namespace App\Libraries;

class SpoQjwt {
    
    protected $alg;
    public function __construct(){
        //사용할 알고리즘
        $this->alg = "sha256";
    }
    
    public function hashing(array $data){
        // 토큰의 헤더
        $header = json_encode(array(
            "alg"=>$this->alg,
            "typ"=>"JWT",
        ));
        $payload = json_encode($data);
        $signature = hash($this->alg, $header.$payload);
        return base64_encode($header.".".$payload.".".$signature);
    }
    
    public function dehashing($token){
        // 토큰 만들때의 구분자 . 으로 나누기
        $parted = explode(".", base64_decode($token));
        
        $signature = $parted[2];
        
        // 위에서 토큰 만들때와 같은 방식으로 시그니처 만들고 비교
        if(hash($this->alg, $parted[0].$parted[1]) == $signature){
            //  echo "good";
        }else{
            //echo("잘못된 signature 입니다");
        }
        $payload = json_decode($parted[1],true);
        return $payload;
    }
}