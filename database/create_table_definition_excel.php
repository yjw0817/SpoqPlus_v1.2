<?php
/**
 * 로그 분석 시스템 테이블 정의서 Excel 생성기
 * 실행: php create_table_definition_excel.php
 */

// HTML 테이블 형식으로 출력 (Excel에서 열 수 있음)
$output = '<!DOCTYPE html>
<html>
<head>
    <meta charset="UTF-8">
    <style>
        table { border-collapse: collapse; margin-bottom: 30px; }
        th, td { border: 1px solid #000; padding: 5px; text-align: center; }
        th { background-color: #f0f0f0; font-weight: bold; }
        .table-title { font-size: 18px; font-weight: bold; margin: 20px 0 10px 0; }
    </style>
</head>
<body>';

// 테이블 정의
$tables = [
    [
        'name' => 'log_analysis',
        'korean_name' => '로그 분석',
        'columns' => [
            ['id', '아이디', '고유번호', 'INT(11)', 'YES', '', 'NO', 'AUTO_INCREMENT', ''],
            ['log_date', '로그 발생 시간', '로그 발생 시간', 'DATETIME', '', '', 'NO', '', ''],
            ['error_level', '오류 레벨', '오류 레벨 (CRITICAL, ERROR, WARNING, INFO)', 'VARCHAR(20)', '', '', 'NO', '', ''],
            ['error_message', '오류 메시지', '오류 메시지', 'TEXT', '', '', 'NO', '', ''],
            ['file_path', '파일 경로', '오류 발생 파일 경로', 'VARCHAR(500)', '', '', 'YES', '', 'NULL'],
            ['line_number', '라인 번호', '오류 발생 라인 번호', 'INT', '', '', 'YES', '', 'NULL'],
            ['stack_trace', '스택 트레이스', '스택 트레이스', 'TEXT', '', '', 'YES', '', 'NULL'],
            ['user_id', '사용자 아이디', '사용자 ID', 'VARCHAR(50)', '', '', 'YES', '', 'NULL'],
            ['user_name', '사용자 이름', '사용자 이름', 'VARCHAR(100)', '', '', 'YES', '', 'NULL'],
            ['company_cd', '회사 코드', '회사 코드', 'VARCHAR(20)', '', '', 'YES', '', 'NULL'],
            ['branch_cd', '지점 코드', '지점 코드', 'VARCHAR(20)', '', '', 'YES', '', 'NULL'],
            ['request_url', '요청 URL', '요청 URL', 'VARCHAR(500)', '', '', 'YES', '', 'NULL'],
            ['request_method', '요청 메서드', '요청 메서드 (GET, POST 등)', 'VARCHAR(10)', '', '', 'YES', '', 'NULL'],
            ['request_params', '요청 파라미터', '요청 파라미터', 'TEXT', '', '', 'YES', '', 'NULL'],
            ['session_data', '세션 데이터', '세션 데이터', 'TEXT', '', '', 'YES', '', 'NULL'],
            ['user_agent', '사용자 에이전트', '사용자 에이전트', 'VARCHAR(500)', '', '', 'YES', '', 'NULL'],
            ['ip_address', 'IP 주소', 'IP 주소', 'VARCHAR(50)', '', '', 'YES', '', 'NULL'],
            ['error_hash', '오류 해시', '오류 고유 해시 (중복 체크용)', 'VARCHAR(64)', '', '', 'YES', '', 'NULL'],
            ['occurrence_count', '발생 횟수', '발생 횟수', 'INT', '', '', 'NO', '', '1'],
            ['first_occurred', '최초 발생 시간', '최초 발생 시간', 'DATETIME', '', '', 'YES', '', 'NULL'],
            ['last_occurred', '최근 발생 시간', '최근 발생 시간', 'DATETIME', '', '', 'YES', '', 'NULL'],
            ['is_resolved', '해결 여부', '해결 여부', 'BOOLEAN', '', '', 'NO', '', 'FALSE'],
            ['resolved_by', '해결한 사용자', '해결한 사용자', 'VARCHAR(50)', '', '', 'YES', '', 'NULL'],
            ['resolved_at', '해결 시간', '해결 시간', 'DATETIME', '', '', 'YES', '', 'NULL'],
            ['fix_applied', '적용된 수정 사항', '적용된 수정 사항', 'TEXT', '', '', 'YES', '', 'NULL'],
            ['created_at', '생성일시', '생성일시', 'TIMESTAMP', '', '', 'YES', '', 'CURRENT_TIMESTAMP'],
            ['updated_at', '수정일시', '수정일시', 'TIMESTAMP', '', '', 'YES', '', 'CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP'],
        ]
    ],
    [
        'name' => 'error_fix_history',
        'korean_name' => '오류 수정 이력',
        'columns' => [
            ['id', '아이디', '고유번호', 'INT(11)', 'YES', '', 'NO', 'AUTO_INCREMENT', ''],
            ['error_id', '오류 아이디', '관련 오류 ID', 'INT', '', 'YES', 'NO', '', ''],
            ['fixed_by', '수정한 사용자', '수정한 사용자', 'VARCHAR(50)', '', '', 'NO', '', ''],
            ['fixed_at', '수정 시간', '수정 시간', 'DATETIME', '', '', 'NO', '', ''],
            ['file_path', '파일 경로', '수정한 파일 경로', 'VARCHAR(500)', '', '', 'NO', '', ''],
            ['original_code', '원본 코드', '원본 코드', 'TEXT', '', '', 'YES', '', 'NULL'],
            ['fixed_code', '수정된 코드', '수정된 코드', 'TEXT', '', '', 'YES', '', 'NULL'],
            ['fix_description', '수정 설명', '수정 설명', 'TEXT', '', '', 'YES', '', 'NULL'],
            ['fix_type', '수정 유형', '수정 유형 (AI, TEMPLATE, MANUAL)', 'VARCHAR(50)', '', '', 'YES', '', 'NULL'],
            ['created_at', '생성일시', '생성일시', 'TIMESTAMP', '', '', 'YES', '', 'CURRENT_TIMESTAMP'],
        ]
    ],
    [
        'name' => 'log_statistics_summary',
        'korean_name' => '로그 통계 요약',
        'columns' => [
            ['id', '아이디', '고유번호', 'INT(11)', 'YES', '', 'NO', 'AUTO_INCREMENT', ''],
            ['date', '날짜', '통계 날짜', 'DATE', '', '', 'NO', '', ''],
            ['error_level', '오류 레벨', '오류 레벨', 'VARCHAR(20)', '', '', 'NO', '', ''],
            ['count', '발생 횟수', '발생 횟수', 'INT', '', '', 'NO', '', '0'],
            ['unique_errors', '고유 오류 수', '고유 오류 수', 'INT', '', '', 'NO', '', '0'],
            ['affected_users', '영향받은 사용자 수', '영향받은 사용자 수', 'INT', '', '', 'NO', '', '0'],
            ['resolved_count', '해결된 오류 수', '해결된 오류 수', 'INT', '', '', 'NO', '', '0'],
            ['created_at', '생성일시', '생성일시', 'TIMESTAMP', '', '', 'YES', '', 'CURRENT_TIMESTAMP'],
        ]
    ],
    [
        'name' => 'log_alert_settings',
        'korean_name' => '로그 알림 설정',
        'columns' => [
            ['id', '아이디', '고유번호', 'INT(11)', 'YES', '', 'NO', 'AUTO_INCREMENT', ''],
            ['alert_name', '알림 이름', '알림 이름', 'VARCHAR(100)', '', '', 'NO', '', ''],
            ['error_level', '오류 레벨', '모니터링할 오류 레벨', 'VARCHAR(20)', '', '', 'NO', '', ''],
            ['threshold_count', '임계값', '임계값 (이상 발생시 알림)', 'INT', '', '', 'NO', '', '1'],
            ['time_window', '시간 윈도우', '시간 윈도우 (분)', 'INT', '', '', 'NO', '', '60'],
            ['alert_email', '알림 이메일', '알림 이메일', 'VARCHAR(255)', '', '', 'YES', '', 'NULL'],
            ['alert_sms', '알림 SMS', '알림 SMS 번호', 'VARCHAR(20)', '', '', 'YES', '', 'NULL'],
            ['is_active', '활성화 여부', '활성화 여부', 'BOOLEAN', '', '', 'NO', '', 'TRUE'],
            ['created_by', '생성자', '생성자', 'VARCHAR(50)', '', '', 'YES', '', 'NULL'],
            ['created_at', '생성일시', '생성일시', 'TIMESTAMP', '', '', 'YES', '', 'CURRENT_TIMESTAMP'],
            ['updated_at', '수정일시', '수정일시', 'TIMESTAMP', '', '', 'YES', '', 'CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP'],
        ]
    ]
];

// 각 테이블 출력
foreach ($tables as $table) {
    $output .= '<div class="table-title">' . $table['korean_name'] . ' (' . $table['name'] . ')</div>';
    $output .= '<table>';
    $output .= '<tr>
        <th>테이블(한글명)</th>
        <th>테이블(영문명)</th>
        <th>컬럼(한글명)</th>
        <th>컬럼(영문명)</th>
        <th>컬럼설명</th>
        <th>타입</th>
        <th>PK</th>
        <th>FK</th>
        <th>NULL</th>
        <th>자동생성</th>
        <th>Default</th>
    </tr>';
    
    foreach ($table['columns'] as $column) {
        $output .= '<tr>';
        $output .= '<td>' . $table['korean_name'] . '</td>';
        $output .= '<td>' . $table['name'] . '</td>';
        $output .= '<td>' . $column[1] . '</td>';
        $output .= '<td>' . $column[0] . '</td>';
        $output .= '<td>' . $column[2] . '</td>';
        $output .= '<td>' . $column[3] . '</td>';
        $output .= '<td>' . $column[4] . '</td>';
        $output .= '<td>' . $column[5] . '</td>';
        $output .= '<td>' . $column[6] . '</td>';
        $output .= '<td>' . $column[7] . '</td>';
        $output .= '<td>' . $column[8] . '</td>';
        $output .= '</tr>';
    }
    
    $output .= '</table>';
}

$output .= '</body></html>';

// 파일 저장
file_put_contents('로그분석시스템_테이블정의서.html', $output);
echo "파일이 생성되었습니다: 로그분석시스템_테이블정의서.html\n";
echo "이 파일을 Excel에서 열면 테이블 형식으로 볼 수 있습니다.\n";