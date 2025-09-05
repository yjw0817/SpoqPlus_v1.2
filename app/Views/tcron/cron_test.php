<?php
$sDef = SpoqDef();
?>
<section class="content">
    <div class="container-fluid">
        <div class="row">
            <div class="col-md-6">
                <div class="panel panel-inverse">
                    <div class="panel-heading">
                        <h3 class="panel-title">크론 작업 테스트</h3>
                    </div>
                    <div class="panel-body">
                        <div class="row">
                            <div class="col-md-12">
                                <table class="table table-bordered">
                                    <thead>
                                        <tr>
                                            <th>크론 작업</th>
                                            <th>설명</th>
                                            <th>실행</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        <tr>
                                            <td>운동 종료일 처리</td>
                                            <td>운동 종료일이 된 회원을 종료 처리 (23:30)</td>
                                            <td>
                                                <form action="/tcron/run_cron" method="post" style="display:inline;">
                                                    <input type="hidden" name="cron_type" value="exr_e_date">
                                                    <button type="submit" class="btn btn-primary btn-sm">실행</button>
                                                </form>
                                            </td>
                                        </tr>
                                        <tr>
                                            <td>운동 시작일 처리</td>
                                            <td>운동 시작일이 된 회원을 이용중으로 처리 (00:10)</td>
                                            <td>
                                                <form action="/tcron/run_cron" method="post" style="display:inline;">
                                                    <input type="hidden" name="cron_type" value="exr_s_date">
                                                    <button type="submit" class="btn btn-primary btn-sm">실행</button>
                                                </form>
                                            </td>
                                        </tr>
                                        <tr>
                                            <td>추천상품 종료 처리</td>
                                            <td>이용안한 추천상품 상태를 종료 처리 (23:20)</td>
                                            <td>
                                                <form action="/tcron/run_cron" method="post" style="display:inline;">
                                                    <input type="hidden" name="cron_type" value="send_end">
                                                    <button type="submit" class="btn btn-primary btn-sm">실행</button>
                                                </form>
                                            </td>
                                        </tr>
                                        <tr>
                                            <td>휴회 세부적용 종료</td>
                                            <td>휴회 세부적용 상품 종료 처리 (23:40)</td>
                                            <td>
                                                <form action="/tcron/run_cron" method="post" style="display:inline;">
                                                    <input type="hidden" name="cron_type" value="domcy_hist_end">
                                                    <button type="submit" class="btn btn-primary btn-sm">실행</button>
                                                </form>
                                            </td>
                                        </tr>
                                        <tr>
                                            <td>휴회 신청 종료</td>
                                            <td>휴회 신청 리스트 종료 처리 (23:50)</td>
                                            <td>
                                                <form action="/tcron/run_cron" method="post" style="display:inline;">
                                                    <input type="hidden" name="cron_type" value="domcy_cron_end">
                                                    <button type="submit" class="btn btn-primary btn-sm">실행</button>
                                                </form>
                                            </td>
                                        </tr>
                                        <tr>
                                            <td>휴회 실행</td>
                                            <td>휴회 실행 처리 (00:20)</td>
                                            <td>
                                                <form action="/tcron/run_cron" method="post" style="display:inline;">
                                                    <input type="hidden" name="cron_type" value="domcy_run">
                                                    <button type="submit" class="btn btn-primary btn-sm">실행</button>
                                                </form>
                                            </td>
                                        </tr>
                                    </tbody>
                                </table>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            
            <div class="col-md-6">
                <div class="panel panel-inverse">
                    <div class="panel-heading">
                        <h3 class="panel-title">크론 실행 기록</h3>
                    </div>
                    <div class="panel-body">
                        <div class="row">
                            <div class="col-md-12">
                                <table class="table table-bordered">
                                    <thead>
                                        <tr>
                                            <th>실행 시간</th>
                                            <th>크론 종류</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        <?php foreach($cron_list as $cron): ?>
                                        <tr>
                                            <td><?php echo $cron['cron_cre_datetm']; ?></td>
                                            <td><?php echo $cron['cron_knd']; ?></td>
                                        </tr>
                                        <?php endforeach; ?>
                                    </tbody>
                                </table>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</section> 