<?php
namespace App\Libraries;

class MenuHelper
{
    public static function getMenuData($request)
    {
        $menu_list = $_SESSION['menu_list'] ?? [];
        $uriPath = "/" . $request->getUri()->getPath();

        // 정확히 일치하는 메뉴 우선 검색
        $matchingMenu = array_filter($menu_list, function ($menu) use ($uriPath) {
            return isset($menu["url_path"]) && $menu["url_path"] === $uriPath;
        });

        // 정확히 일치하는 메뉴가 없으면 시작 문자열 일치로 재검색
        if (empty($matchingMenu)) {
            $matchingMenu = array_filter($menu_list, function ($menu) use ($uriPath) {
                return isset($menu["url_path"]) &&
                    !empty($menu["url_path"]) &&
                    strpos($uriPath, $menu["url_path"]) === 0;
            });
        }

        $parNmMenu = "";
        $nmMenu = "";

        if (!empty($matchingMenu)  ) {
            $firstMatch = reset($matchingMenu);
            $parNmMenu = $firstMatch["par_nm_menu"];
            $nmMenu = $firstMatch["nm_menu"];

            return [
                'title' => $nmMenu,
                'nav' => [$nmMenu => '', $parNmMenu => ''],
                'menu1' => $request->getGet('m1'),
                'menu2' => $request->getGet('m2')
            ];
        } else if( strpos($uriPath, "/teventmain/event_manage_create/") == 0)
        {
            return [
                'title' => $nmMenu,
                'nav' => [$nmMenu => '', $parNmMenu => ''],
                'menu1' => $request->getGet('m1'),
                'menu2' => $request->getGet('m2')
            ];
        }
        else{
            // 로그 기록
            error_log("[권한 차단] 접근 경로: {$uriPath}, 사용자: " . ($_SESSION['mem_info']['MEM_ID'] ?? '게스트'));

            // 차단 후 리디렉션
            $reUrl = "/tchrmain/dashboard";
            echo "<script>location.href='" . $reUrl . "';</script>";
            exit();
        }
    }

    public static function checkMenuAccessOrBlock($request)
    {
        $menu_list = $_SESSION['menu_list'] ?? [];
        $uriPath = "/" . $request->getUri()->getPath();

        // 현재 URI의 앞부분이 메뉴 path와 일치하는 항목을 허용
        $matchingMenu = array_filter($menu_list, function ($menu) use ($uriPath) {
            return isset($menu["url_path"]) && !empty($menu["url_path"]) && strpos($uriPath, $menu["url_path"]) === 0;
        });

        if (empty($matchingMenu)) {
            // 로그 기록 (선택)
            error_log("[권한 차단] 접근 경로: {$uriPath}, 사용자: " . ($_SESSION['mem_info']['MEM_ID'] ?? '게스트'));

            // 접근 차단 처리
            header("Location: /error/unauthorized"); // 또는 /main 으로 이동
            exit;
        }

        return true;
    }
}
